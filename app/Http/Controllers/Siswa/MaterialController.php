<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialDownload;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of materials.
     */
    public function index(Request $request): View
    {
        $query = Material::where('is_published', true)
            ->with(['guru', 'subject', 'downloads' => function($query) {
                $query->where('siswa_id', Auth::id());
            }])
            ->withCount('downloads');

        // Apply search filter
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply subject filter
        if ($subject = $request->get('subject')) {
            $query->where('subject_id', $subject);
        }

        // Apply category filter
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $materials = $query->latest()->paginate(12);

        // Get subjects for filter
        $subjects = \App\Models\Subject::where('is_active', true)->get();

        // Calculate statistics
        $downloadedCount = MaterialDownload::where('siswa_id', Auth::id())
            ->distinct('material_id')
            ->count();

        $recentCount = Material::where('is_published', true)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $favoriteCount = MaterialDownload::where('siswa_id', Auth::id())
            ->whereHas('material', function($q) {
                $q->where('is_published', true);
            })
            ->count();

        return view('siswa.materials.index', compact(
            'materials', 
            'subjects', 
            'downloadedCount', 
            'recentCount', 
            'favoriteCount'
        ));
    }

    /**
     * Download the material.
     */
    public function download($id) // ✅ Hapus return type
    {
        $material = Material::where('is_published', true)
            ->findOrFail($id);

        if (!$material->file) {
            Log::warning('Material file not found', [
                'material_id' => $id,
                'siswa_id' => Auth::id(),
                'ip' => request()->ip()
            ]);
            return back()->with('error', 'File materi tidak tersedia.');
        }

        $filePath = 'materials/' . $material->file;

        if (!Storage::disk('public')->exists($filePath)) {
            Log::error('Material file not found in storage', [
                'material_id' => $id,
                'file_path' => $filePath,
                'siswa_id' => Auth::id(),
                'ip' => request()->ip()
            ]);
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Catat download
        $this->logDownload($material->id);

        // Gunakan atomic increment
        DB::table('materials')
            ->where('id', $material->id)
            ->increment('downloads_count');

        $filename = $material->judul . '.' . pathinfo($material->file, PATHINFO_EXTENSION);

        return Storage::download($filePath, $filename);
    }

    /**
     * Track download (AJAX).
     */
    public function trackDownload($id): JsonResponse
    {
        $material = Material::where('is_published', true)
            ->findOrFail($id);

        $this->logDownload($material->id);

        DB::table('materials')
            ->where('id', $material->id)
            ->increment('downloads_count');

        return response()->json([
            'success' => true,
            'download_count' => $material->fresh()->downloads_count
        ]);
    }

    /**
     * Display the specified material.
     */
    public function show($id): View
    {
        $material = Material::where('is_published', true)
            ->with(['guru', 'subject'])
            ->findOrFail($id);

        DB::table('materials')
            ->where('id', $material->id)
            ->increment('views_count');

        $isDownloaded = MaterialDownload::where('material_id', $material->id)
            ->where('siswa_id', Auth::id())
            ->exists();

        return view('siswa.materials.show', compact('material', 'isDownloaded'));
    }

    /**
     * Display download history.
     */
    public function history(): View
    {
        $downloads = MaterialDownload::with('material')
            ->where('siswa_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('siswa.materials.history', compact('downloads'));
    }

    /**
     * Search materials.
     */
    public function search(Request $request): View
    {
        // Redirect to index with search parameters
        return redirect()->route('siswa.materials.index', $request->all());
    }

    protected function logDownload($materialId)
    {
        return MaterialDownload::firstOrCreate(
            [
                'material_id' => $materialId,
                'siswa_id' => Auth::id()
            ],
            [
                'downloaded_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]
        );
    }

    /**
     * Get file information (AJAX).
     */
    public function getFileInfo($id): JsonResponse
    {
        $material = Material::where('is_published', true)
            ->findOrFail($id);

        $filePath = 'materials/' . $material->file;

        if (!$material->file || !Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        $fileSize = Storage::disk('public')->size($filePath);

        return response()->json([
            'filename' => $material->file,
            'size' => $this->formatFileSize($fileSize),
            'type' => pathinfo($material->file, PATHINFO_EXTENSION),
            'download_url' => route('siswa.materials.download', $material->id)
        ]);
    }

    protected function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Display health-related materials.
     */
    public function healthMaterials(): View
    {
        $materials = Material::where('is_published', true)
            ->where(function($query) {
                $query->where('judul', 'like', '%kesehatan%')
                      ->orWhere('judul', 'like', '%medis%')
                      ->orWhere('judul', 'like', '%klinis%')
                      ->orWhere('description', 'like', '%kesehatan%');
            })
            ->with(['guru', 'subject', 'downloads' => function($query) {
                $query->where('siswa_id', Auth::id());
            }])
            ->latest()
            ->paginate(12);

        return view('siswa.materials.health', compact('materials'));
    }
}
