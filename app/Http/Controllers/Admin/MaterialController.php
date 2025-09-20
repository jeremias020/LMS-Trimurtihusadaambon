<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialDownload;
use App\Models\Subject;
use App\Models\User;
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
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of all materials.
     */
    public function index(): View
    {
        $materials = Material::withCount('downloads')
            ->with(['subject', 'teacher'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total_materials' => Material::count(),
            'published_materials' => Material::where('is_published', true)->count(),
            'unpublished_materials' => Material::where('is_published', false)->count(),
            'total_downloads' => MaterialDownload::count(),
            'total_size' => Material::sum('file_size'),
        ];

        return view('admin.materials.index', compact('materials', 'stats'));
    }

    /**
     * Show the form for creating a new material.
     */
    public function create(): View
    {
        $subjects = Subject::where('is_active', true)->get();
        $teachers = User::where('role', 'guru')->get();
        $categories = [
            'Teori' => 'Teori',
            'Praktikum' => 'Praktikum',
            'Modul' => 'Modul',
            'Handout' => 'Handout',
            'Lembar Kerja' => 'Lembar Kerja',
            'Video' => 'Video',
            'Referensi' => 'Referensi'
        ];

        return view('admin.materials.create', compact('subjects', 'teachers', 'categories'));
    }

    /**
     * Store a newly created material.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'guru_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'category' => 'required|string|max:100',
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
            $material->guru_id = $request->guru_id;
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

            Log::info('Material created by admin', [
                'material_id' => $material->id,
                'admin_id' => Auth::id(),
                'guru_id' => $material->guru_id,
                'judul' => $material->judul,
                'ip' => $request->ip()
            ]);

            return redirect()->route('admin.materials.index')
                ->with('success', 'Materi berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Material creation failed by admin: ' . $e->getMessage(), [
                'admin_id' => Auth::id(),
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
        $downloads = MaterialDownload::with('siswa')
            ->where('material_id', $material->id)
            ->latest()
            ->paginate(15);

        $stats = [
            'total_downloads' => $material->downloads_count,
            'last_week_downloads' => MaterialDownload::where('material_id', $material->id)
                ->where('created_at', '>=', now()->subWeek())
                ->count(),
            'unique_downloaders' => MaterialDownload::where('material_id', $material->id)
                ->distinct('siswa_id')
                ->count('siswa_id'),
        ];

        return view('admin.materials.show', compact('material', 'downloads', 'stats'));
    }

    /**
     * Show the form for editing the material.
     */
    public function edit(Material $material): View
    {
        $subjects = Subject::where('is_active', true)->get();
        $teachers = User::where('role', 'guru')->get();
        $categories = [
            'Teori' => 'Teori',
            'Praktikum' => 'Praktikum',
            'Modul' => 'Modul',
            'Handout' => 'Handout',
            'Lembar Kerja' => 'Lembar Kerja',
            'Video' => 'Video',
            'Referensi' => 'Referensi'
        ];

        return view('admin.materials.edit', compact('material', 'subjects', 'teachers', 'categories'));
    }

    /**
     * Update the specified material.
     */
    public function update(Request $request, Material $material): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'guru_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'category' => 'required|string|max:100',
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
            $material->guru_id = $request->guru_id;
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

            Log::info('Material updated by admin', [
                'material_id' => $material->id,
                'admin_id' => Auth::id(),
                'guru_id' => $material->guru_id,
                'judul' => $material->judul,
                'ip' => $request->ip()
            ]);

            return redirect()->route('admin.materials.index')
                ->with('success', 'Materi berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Material update failed by admin: ' . $e->getMessage(), [
                'material_id' => $material->id,
                'admin_id' => Auth::id(),
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
        try {
            if ($material->file) {
                Storage::disk('public')->delete('materials/' . $material->file);
            }

            MaterialDownload::where('material_id', $material->id)->delete();

            $material->delete();

            Log::info('Material deleted by admin', [
                'material_id' => $material->id,
                'admin_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            return redirect()->route('admin.materials.index')
                ->with('success', 'Materi berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Material deletion failed by admin: ' . $e->getMessage(), [
                'material_id' => $material->id,
                'admin_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Toggle publish status of material.
     */
    public function togglePublish(Material $material): RedirectResponse
    {
        $material->update([
            'is_published' => !$material->is_published
        ]);

        $status = $material->is_published ? 'dipublikasikan' : 'disembunyikan';

        Log::info('Material publish status toggled by admin', [
            'material_id' => $material->id,
            'admin_id' => Auth::id(),
            'is_published' => $material->is_published,
            'ip' => request()->ip()
        ]);

        return back()->with('success', "Materi berhasil $status!");
    }

    /**
     * Bulk delete materials.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|exists:materials,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Data tidak valid'], 400);
        }

        try {
            $materials = Material::whereIn('id', $request->ids)->get();

            foreach ($materials as $material) {
                if ($material->file) {
                    Storage::disk('public')->delete('materials/' . $material->file);
                }
                MaterialDownload::where('material_id', $material->id)->delete();
                $material->delete();
            }

            Log::info('Bulk materials deleted by admin', [
                'material_ids' => $request->ids,
                'admin_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return response()->json(['success' => 'Materi berhasil dihapus massal']);

        } catch (\Exception $e) {
            Log::error('Bulk material deletion failed by admin: ' . $e->getMessage(), [
                'admin_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return response()->json(['error' => 'Terjadi kesalahan sistem'], 500);
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
