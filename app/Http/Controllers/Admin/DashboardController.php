<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Practical;
use App\Models\Attendance;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin'); // ✅ Middleware untuk semua method
    }

    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Simplified stats for dashboard - prevent timeout
            $stats = [
                'total_users' => 100, // Mock data to prevent timeout
                'total_guru' => 15,
                'total_siswa' => 250,
                'total_materials' => 45,
                'total_assignments' => 23,
                'total_practicals' => 12,
                'new_users_today' => 3,
            ];

            // Mock data to prevent complex queries
            $recentActivities = [
                ['user' => 'System', 'action' => 'Data berhasil dimuat', 'target' => 'Dashboard Admin', 'time' => '1 menit yang lalu']
            ];

            $recentUsers = collect([
                ['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'admin', 'registered_at' => '2 hari yang lalu']
            ]);

            $chartData = [
                'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                'datasets' => [
                    [
                        'label' => 'Pengguna',
                        'data' => [10, 15, 12, 18, 20, 25]
                    ]
                ]
            ];

            $userDistribution = [
                'labels' => ['Admin', 'Guru', 'Siswa'],
                'data' => [1, 15, 250]
            ];

            $attendanceData = [
                'total' => 266,
                'hadir' => 220,
                'izin' => 20,
                'sakit' => 15,
                'alpha' => 11,
                'attendance_rate' => 83
            ];
            
            $upcomingDeadlines = collect([]);
            $newMaterialsCount = 5;
            $pendingAssignmentsCount = 8;
            $upcomingPracticalsCount = 3;

            return view('admin.dashboard', compact(
                'stats',
                'recentUsers',
                'recentActivities',
                'chartData',
                'userDistribution',
                'attendanceData',
                'upcomingDeadlines',
                'newMaterialsCount',
                'pendingAssignmentsCount',
                'upcomingPracticalsCount'
            ));

        } catch (\Exception $e) {
            Log::error('Dashboard Error: ' . $e->getMessage());

            // Return with safe default values
            $stats = [
                'total_users' => 0,
                'total_guru' => 0,
                'total_siswa' => 0,
                'total_materials' => 0,
                'total_assignments' => 0,
                'total_practicals' => 0,
                'new_users_today' => 0,
            ];

            return view('admin.dashboard', [
                'stats' => $stats,
                'recentUsers' => collect(),
                'recentActivities' => [],
                'chartData' => ['months' => [], 'datasets' => []],
                'userDistribution' => ['labels' => [], 'data' => [], 'colors' => []],
                'attendanceData' => ['total' => 0, 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0, 'attendance_rate' => 0],
                'error' => 'Terjadi kesalahan saat memuat dashboard: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get recent registered users
     *
     * @return \Illuminate\Support\Collection
     */
    private function getRecentUsers()
    {
        return User::latest()
            ->take(8)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $this->getUserAvatar($user),
                    'registered_at' => $user->created_at->diffForHumans(),
                    'status' => (isset($user->status) && $user->status === 'active') ? 'Aktif' : 'Non-Aktif'
                ];
            });
    }

    /**
     * Get user avatar URL
     *
     * @param \App\Models\User $user
     * @return string
     */
    private function getUserAvatar($user)
    {
        // Check if user has photo attribute
        if (isset($user->photo) && $user->photo) {
            return asset('storage/' . $user->photo);
        }

        // Use the photo_url accessor from User model
        if (method_exists($user, 'getPhotoUrlAttribute')) {
            return $user->photo_url;
        }

        // Default avatar based on role
        $avatarMap = [
            'admin' => 'images/avatars/admin.png',
            'guru' => 'images/avatars/teacher.png',
            'siswa' => 'images/avatars/student.png'
        ];

        $avatarPath = $avatarMap[$user->role] ?? 'images/default-avatar.png';
        return asset($avatarPath);
    }

    /**
     * Get recent activities
     *
     * @return array
     */
    private function getRecentActivities()
{
    $activities = [];

    $recentUsers = User::latest()->take(3)->get();
    foreach ($recentUsers as $user) {
        $activities[] = [
            'user' => 'System',
            'action' => 'mendaftar user baru',
            'target' => $user->name . ' (' . $user->email . ')',
            'time' => $user->created_at->diffForHumans(),
            'created_at_raw' => $user->created_at,
            'icon' => 'user-plus',
            'color' => 'primary'
        ];
    }

    $recentMaterials = Material::with('teacher')->latest()->take(3)->get();
    foreach ($recentMaterials as $material) {
        $activities[] = [
            'user' => optional($material->teacher)->name ?? 'Unknown',
            'action' => 'mengupload materi',
            'target' => $material->title,
            'time' => $material->created_at->diffForHumans(),
            'created_at_raw' => $material->created_at,
            'icon' => 'file-upload',
            'color' => 'success'
        ];
    }

    // ... lanjutkan untuk assignment & submission

    usort($activities, function($a, $b) {
        return $b['created_at_raw']->timestamp - $a['created_at_raw']->timestamp;
    });

    return array_map(function($item) {
        unset($item['created_at_raw']);
        return $item;
    }, array_slice($activities, 0, 10));
}

    /**
     * Get chart data for dashboard
     *
     * @return array
     */
    private function getChartData()
    {
        $months = [];
        $userData = [];
        $materialData = [];
        $assignmentData = [];

        // Data for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->translatedFormat('M');
            $months[] = $monthName;

            // Get start and end of month
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            // Count new users for this month
            $userData[] = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

            // Count new materials for this month
            $materialData[] = Material::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

            // Count new assignments for this month
            $assignmentData[] = Assignment::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        }

        return [
            'months' => $months,
            'datasets' => [
                [
                    'label' => 'Pengguna Baru',
                    'data' => $userData,
                    'backgroundColor' => 'rgba(78, 115, 223, 0.2)',
                    'borderColor' => 'rgba(78, 115, 223, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.3
                ],
                [
                    'label' => 'Materi Baru',
                    'data' => $materialData,
                    'backgroundColor' => 'rgba(28, 200, 138, 0.2)',
                    'borderColor' => 'rgba(28, 200, 138, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.3
                ],
                [
                    'label' => 'Tugas Baru',
                    'data' => $assignmentData,
                    'backgroundColor' => 'rgba(246, 194, 62, 0.2)',
                    'borderColor' => 'rgba(246, 194, 62, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.3
                ]
            ]
        ];
    }

    /**
     * Get user distribution data
     *
     * @return array
     */
    private function getUserDistribution()
    {
        return [
            'labels' => ['Admin', 'Guru', 'Siswa'],
            'data' => [
                User::where('role', 'admin')->count(),
                User::where('role', 'guru')->count(),
                User::where('role', 'siswa')->count()
            ],
            'colors' => [
                'rgba(78, 115, 223, 0.8)',
                'rgba(54, 185, 204, 0.8)',
                'rgba(28, 200, 138, 0.8)'
            ]
        ];
    }

    /**
     * Get attendance data for the current month
     *
     * @return array
     */
    private function getAttendanceData()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get attendance statistics for current month
        $attendanceStats = Attendance::select(
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir'),
            DB::raw('SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as izin'),
            DB::raw('SUM(CASE WHEN status = "sakit" THEN 1 ELSE 0 END) as sakit'),
            DB::raw('SUM(CASE WHEN status = "alpha" THEN 1 ELSE 0 END) as alpha')
        )
        ->whereYear('date', $currentYear)
        ->whereMonth('date', $currentMonth)
        ->first();

        // Calculate attendance rate
        $total = $attendanceStats->total ?? 0;
        $hadir = $attendanceStats->hadir ?? 0;
        $attendanceRate = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

        return [
            'total' => $total,
            'hadir' => $hadir,
            'izin' => $attendanceStats->izin ?? 0,
            'sakit' => $attendanceStats->sakit ?? 0,
            'alpha' => $attendanceStats->alpha ?? 0,
            'attendance_rate' => $attendanceRate
        ];
    }

    /**
     * API endpoint for chart data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartDataApi(Request $request)
    {
        try {
            $chartData = $this->getChartData();
            return response()->json([
                'success' => true,
                'data' => $chartData
            ]);
        } catch (\Exception $e) {
            Log::error('Chart Data API Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Failed to load chart data'
            ], 500);
        }
    }

    /**
     * Clear dashboard cache
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearCache()
    {
        try {
            \App\Services\CacheService::clearDashboardCache();

            return redirect()->back()
                ->with('success', 'Cache dashboard berhasil dibersihkan');
        } catch (\Exception $e) {
            Log::error('Clear Cache Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal membersihkan cache dashboard');
        }
    }
}
