<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialView;
use App\Models\MaterialDownload;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MaterialTrackingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Track material view
     */
    public function trackView(Request $request, $materialId): JsonResponse
    {
        try {
            $material = Material::findOrFail($materialId);
            
            // Check if student has access to this material
            $student = \App\Models\Siswa::where('user_id', Auth::id())->first();
            if (!$this->canAccessMaterial($material, $student)) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }

            // Record or update view
            $materialView = MaterialView::updateOrCreate([
                'material_id' => $materialId,
                'siswa_id' => Auth::id(),
                'view_date' => now()->toDateString(),
            ], [
                'view_count' => \Illuminate\Support\Facades\DB::raw('view_count + 1'),
                'last_viewed_at' => now(),
            ]);

            // Increment material views count
            $material->increment('views_count');

            return response()->json([
                'success' => true,
                'message' => 'View tracked successfully',
                'total_views' => $material->views_count,
                'student_views_today' => $materialView->view_count
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to track material view', [
                'material_id' => $materialId,
                'siswa_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to track view'], 500);
        }
    }

    /**
     * Track material download
     */
    public function trackDownload(Request $request, $materialId): JsonResponse
    {
        try {
            $material = Material::findOrFail($materialId);
            
            // Check if student has access to this material
            $student = \App\Models\Siswa::where('user_id', Auth::id())->first();
            if (!$this->canAccessMaterial($material, $student)) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }

            // Record download
            $download = MaterialDownload::create([
                'material_id' => $materialId,
                'siswa_id' => Auth::id(),
                'downloaded_at' => now(),
            ]);

            // Increment material downloads count
            $material->increment('downloads_count');

            return response()->json([
                'success' => true,
                'message' => 'Download tracked successfully',
                'total_downloads' => $material->downloads_count,
                'download_id' => $download->id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to track material download', [
                'material_id' => $materialId,
                'siswa_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to track download'], 500);
        }
    }

    /**
     * Get student's material statistics
     */
    public function getStudentStats(): JsonResponse
    {
        try {
            $siswaId = Auth::id();
            
            // Get view statistics
            $viewStats = MaterialView::where('siswa_id', $siswaId)
                ->with('material')
                ->get()
                ->groupBy(function($item) {
                    return $item->material->id;
                });

            // Get download statistics
            $downloadStats = MaterialDownload::where('siswa_id', $siswaId)
                ->with('material')
                ->get()
                ->groupBy(function($item) {
                    return $item->material->id;
                });

            // Calculate totals
            $totalViews = $viewStats->sum(function($group) {
                return $group->sum('view_count');
            });

            $totalDownloads = $downloadStats->count();
            $uniqueMaterialsViewed = $viewStats->count();
            $uniqueMaterialsDownloaded = $downloadStats->count();

            // Get recent activity
            $recentViews = MaterialView::where('siswa_id', $siswaId)
                ->with('material')
                ->orderBy('last_viewed_at', 'desc')
                ->limit(5)
                ->get();

            $recentDownloads = MaterialDownload::where('siswa_id', $siswaId)
                ->with('material')
                ->orderBy('downloaded_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_views' => $totalViews,
                    'total_downloads' => $totalDownloads,
                    'unique_materials_viewed' => $uniqueMaterialsViewed,
                    'unique_materials_downloaded' => $uniqueMaterialsDownloaded,
                    'recent_views' => $recentViews,
                    'recent_downloads' => $recentDownloads
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get student stats', [
                'siswa_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to get statistics'], 500);
        }
    }

    /**
     * Check if student can access material
     */
    private function canAccessMaterial(Material $material, $student): bool
    {
        if (!$student) {
            return false;
        }

        // Check if material is published
        if (!$material->published_at) {
            return false;
        }

        // Check if material is for student's class or for all classes
        // $student bisa berupa User atau Siswa — ambil kelas_id dengan aman
        $kelasId = null;
        if ($student instanceof \App\Models\Siswa) {
            $kelasId = $student->kelas_id;
        } elseif ($student instanceof \App\Models\User) {
            $kelasId = $student->siswa?->kelas_id;
        }

        if ($kelasId) {
            return $material->kelas_id === $kelasId || $material->kelas_id === null;
        }

        // If student has no class, only show materials without specific class
        return $material->kelas_id === null;
    }
}
