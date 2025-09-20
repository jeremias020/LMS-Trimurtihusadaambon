<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialDownload;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:guru');
    }

    /**
     * Display a listing of materials.
     */
    public function index(): View
    {
        $materials = Material::withCount('downloads')
            ->with('subject')
            ->where('guru_id', Auth::id())
            ->latest()
            ->paginate(12);

        $totalSize = Material::where('guru_id', Auth::id())->sum('file_size');
        
        // Get subjects for filter dropdown
        $subjects = Subject::where('is_active', true)->get();

        return view('guru.materials.index', compact('materials', 'totalSize', 'subjects'));
    }

    /**
     * Show the form for creating a new material.
     */
    public function create(): View
    {
        $subjects = Subject::where('is_active', true)->get();
        $categories = [
            'Teori' => 'Teori',
            'Praktik' => 'Praktik', 
            'Tugas' => 'Tugas',
            'Ujian' => 'Ujian',
            'Referensi' => 'Referensi'
        ];

        return view('guru.materials.create', compact('subjects', 'categories'));
    }

    /**
     * Store a newly created material.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'category' => 'required|in:Teori,Praktik,Tugas,Ujian,Referensi',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,mp4,avi,mov,jpg,jpeg,png|max:51200',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
        ], [
            'file.mimes' => 'Format file harus: pdf, doc, docx, ppt, pptx, xls, xlsx, txt, zip, rar, mp4, avi, mov, jpg, jpeg, png',
            'file.max' => 'Ukuran file maksimal 50MB',
            'file.required' => 'File materi wajib diupload',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form');
        }

        try {
            $material = new Material();
            $material->guru_id = Auth::id();
            $material->judul = $request->judul;
            $material->subject_id = $request->subject_id;
            $material->category = $request->category;
            $material->description = $request->description;
            $material->is_published = $request->has('is_published');

            if ($request->hasFile('file')) {
                $fileData = $this->handleFileUpload($request->file('file'));
                $material->fill($fileData);
            }

            $material->save();

            Log::info('Material created', [
                'material_id' => $material->id,
                'guru_id' => Auth::id(),
                'judul' => $material->judul,
                'ip' => $request->ip()
            ]);

            return redirect()->route('guru.materials.index')
                ->with('success', 'Materi berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Material creation failed: ' . $e->getMessage(), [
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified material.
     */
    public function show(Material $material): View
    {
        if ($material->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $downloads = MaterialDownload::with('siswa')
            ->where('material_id', $material->id)
            ->orderBy('downloaded_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_downloads' => $material->downloads_count,
            'last_week_downloads' => MaterialDownload::where('material_id', $material->id)
                ->where('downloaded_at', '>=', now()->subWeek())
                ->count(),
            'unique_downloaders' => MaterialDownload::where('material_id', $material->id)
                ->distinct('siswa_id')
                ->count('siswa_id'),
        ];

        return view('guru.materials.show', compact('material', 'downloads', 'stats'));
    }

    /**
     * Show the form for editing the material.
     */
    public function edit(Material $material): View
    {
        if ($material->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $subjects = Subject::where('is_active', true)->get();
        $categories = [
            'Teori' => 'Teori',
            'Praktik' => 'Praktik', 
            'Tugas' => 'Tugas',
            'Ujian' => 'Ujian',
            'Referensi' => 'Referensi'
        ];

        return view('guru.materials.edit', compact('material', 'subjects', 'categories'));
    }

    /**
     * Update the specified material.
     */
    public function update(Request $request, Material $material): RedirectResponse
    {
        if ($material->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'category' => 'required|in:Teori,Praktik,Tugas,Ujian,Referensi',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,mp4,avi,mov,jpg,jpeg,png|max:51200',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $material->judul = $request->judul;
            $material->subject_id = $request->subject_id;
            $material->category = $request->category;
            $material->description = $request->description;
            $material->is_published = $request->has('is_published');

            if ($request->hasFile('file')) {
                $fileData = $this->handleFileUpload($request->file('file'), $material->file);
                $material->fill($fileData);
            }

            $material->save();

            Log::info('Material updated', [
                'material_id' => $material->id,
                'guru_id' => Auth::id(),
                'judul' => $material->judul,
                'ip' => $request->ip()
            ]);

            return redirect()->route('guru.materials.index')
                ->with('success', 'Materi berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Material update failed: ' . $e->getMessage(), [
                'material_id' => $material->id,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified material.
     */
    public function destroy(Material $material): RedirectResponse
    {
        if ($material->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        try {
            if ($material->file) {
                Storage::disk('public')->delete('materials/' . $material->file);
            }

            MaterialDownload::where('material_id', $material->id)->delete();

            $material->delete();

            Log::info('Material deleted', [
                'material_id' => $material->id,
                'guru_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            return redirect()->route('guru.materials.index')
                ->with('success', 'Materi berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Material deletion failed: ' . $e->getMessage(), [
                'material_id' => $material->id,
                'guru_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Publish the material.
     */
    public function publish(Material $material): RedirectResponse
    {
        if ($material->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $material->update(['is_published' => true]);

        Log::info('Material published', [
            'material_id' => $material->id,
            'guru_id' => Auth::id(),
            'ip' => request()->ip()
        ]);

        return back()->with('success', 'Materi berhasil diterbitkan!');
    }

    /**
     * Unpublish the material.
     */
    public function unpublish(Material $material): RedirectResponse
    {
        if ($material->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $material->update(['is_published' => false]);

        Log::info('Material unpublished', [
            'material_id' => $material->id,
            'guru_id' => Auth::id(),
            'ip' => request()->ip()
        ]);

        return back()->with('success', 'Materi berhasil disembunyikan!');
    }

    /**
     * Toggle publish status of material.
     */
    public function togglePublish(Material $material): RedirectResponse
    {
        if ($material->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }
        
        $material->is_published = !$material->is_published;
        $material->save();
        
        $status = $material->is_published ? 'diterbitkan' : 'disembunyikan';
        
        Log::info('Material publish status toggled', [
            'material_id' => $material->id,
            'guru_id' => Auth::id(),
            'new_status' => $material->is_published,
            'ip' => request()->ip()
        ]);
        
        return back()->with('success', "Materi berhasil {$status}!");
    }

    /**
     * Download the material (for preview/testing by teacher).
     */
    public function download(Material $material)
    {
        if ($material->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        if (!$material->file) {
            return back()->with('error', 'File materi tidak ditemukan.');
        }

        $filePath = storage_path('app/public/materials/' . $material->file);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File materi tidak ditemukan di server.');
        }

        // Log download — sebagai guru
        try {
            MaterialDownload::create([
                'material_id' => $material->id,
                'siswa_id' => Auth::id(), // Use guru's ID instead of null
                'downloaded_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // If duplicate entry (guru already downloaded), just update the timestamp
            MaterialDownload::where('material_id', $material->id)
                ->where('siswa_id', Auth::id())
                ->update(['downloaded_at' => now()]);
        }

        DB::table('materials')
            ->where('id', $material->id)
            ->increment('downloads_count');

        $downloadName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $material->judul) . '.' . $material->file_type;

        return response()->download($filePath, $downloadName);
    }

    /**
     * Bulk delete materials.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => [
                'required',
                Rule::exists('materials', 'id')->where('guru_id', Auth::id())
            ],
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Data tidak valid');
        }

        try {
            $materials = Material::where('guru_id', Auth::id())
                ->whereIn('id', $request->ids)
                ->get();

            foreach ($materials as $material) {
                if ($material->file) {
                    Storage::disk('public')->delete('materials/' . $material->file);
                }
                MaterialDownload::where('material_id', $material->id)->delete();
                $material->delete();
            }

            Log::info('Bulk materials deleted', [
                'material_ids' => $request->ids,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('success', count($request->ids) . ' materi berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Bulk material deletion failed: ' . $e->getMessage(), [
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem');
        }
    }

    /**
     * Bulk publish materials.
     */
    public function bulkPublish(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => [
                'required',
                Rule::exists('materials', 'id')->where('guru_id', Auth::id())
            ],
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Data tidak valid');
        }

        try {
            $count = Material::where('guru_id', Auth::id())
                ->whereIn('id', $request->ids)
                ->update(['is_published' => true]);

            Log::info('Bulk materials published', [
                'material_ids' => $request->ids,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('success', "$count materi berhasil diterbitkan");

        } catch (\Exception $e) {
            Log::error('Bulk material publish failed: ' . $e->getMessage(), [
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem');
        }
    }

    /**
     * Bulk unpublish materials.
     */
    public function bulkUnpublish(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => [
                'required',
                Rule::exists('materials', 'id')->where('guru_id', Auth::id())
            ],
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Data tidak valid');
        }

        try {
            $count = Material::where('guru_id', Auth::id())
                ->whereIn('id', $request->ids)
                ->update(['is_published' => false]);

            Log::info('Bulk materials unpublished', [
                'material_ids' => $request->ids,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('success', "$count materi berhasil disembunyikan");

        } catch (\Exception $e) {
            Log::error('Bulk material unpublish failed: ' . $e->getMessage(), [
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem');
        }
    }

    /**
     * Handle file upload and cleanup.
     */
    private function handleFileUpload($file, $oldFilename = null)
    {
        if ($oldFilename) {
            Storage::disk('public')->delete('materials/' . $oldFilename);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $originalName) . '.' . $extension;
        $path = $file->storeAs('materials', $filename, 'public');

        return [
            'file' => $filename,
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'file_type' => $extension,
            'mime_type' => $file->getMimeType(),
        ];
    }
}