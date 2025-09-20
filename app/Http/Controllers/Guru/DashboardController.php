<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Practical;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:guru');
    }

    /**
     * Display the guru dashboard.
     */
    public function index(): View
    {
        $guruId = Auth::id();
        $today = Carbon::today();
        $weekStart = $today->copy()->startOfWeek();
        $weekEnd = $today->copy()->endOfWeek();

        // Stats utama
        $stats = [
            'total_materials' => Material::where('guru_id', $guruId)->count(),
            'total_assignments' => Assignment::where('guru_id', $guruId)->count(),
            'total_practicals' => Practical::where('guru_id', $guruId)->count(),
            'total_students' => DB::table('users')->where('role', 'siswa')->count(), // Total siswa
            'pending_grading' => AssignmentSubmission::join('assignments', 'assignment_submissions.assignment_id', '=', 'assignments.id')
                ->where('assignments.guru_id', $guruId)
                ->whereNull('assignment_submissions.score')
                ->count(),
            'pending_submissions' => AssignmentSubmission::join('assignments', 'assignment_submissions.assignment_id', '=', 'assignments.id')
                ->where('assignments.guru_id', $guruId)
                ->whereNull('assignment_submissions.score')
                ->count(),
            'today_attendance' => Attendance::where('recorded_by', $guruId)
                ->whereDate('tanggal', $today)
                ->count(),
            'week_attendance' => Attendance::where('recorded_by', $guruId)
                ->whereBetween('tanggal', [$weekStart, $weekEnd])
                ->count(),
        ];

        // Data terbaru
        $recentMaterials = Material::where('guru_id', $guruId)
            ->withCount('downloads')
            ->latest()
            ->take(5)
            ->get();

        $recentAssignments = Assignment::withCount([
            'submissions',
            'submissions as ungraded_count' => function($query) {
                $query->whereNull('score');
            }
        ])
        ->where('guru_id', $guruId)
        ->latest()
        ->take(5)
        ->get();

        $recentPracticals = Practical::withCount('scores')
            ->where('guru_id', $guruId)
            ->latest()
            ->take(5)
            ->get();

        // Submission yang perlu dinilai
        $pendingSubmissions = AssignmentSubmission::whereHas('assignment', function($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->whereNull('score')
            ->with(['assignment', 'siswa'])
            ->latest()
            ->take(8)
            ->get();

        // Grafik absensi mingguan — hanya untuk absensi yang direkam guru ini
        $weeklyAttendance = Attendance::select(
                DB::raw('DATE(tanggal) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as present')
            )
            ->where('recorded_by', $guruId)
            ->whereBetween('tanggal', [$weekStart, $weekEnd])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top materials
        $topMaterials = Material::where('guru_id', $guruId)
            ->withCount('downloads')
            ->orderBy('downloads_count', 'desc')
            ->take(5)
            ->get();

        // Log access (opsional)
        Log::info('Guru dashboard accessed', [
            'guru_id' => $guruId,
            'ip' => request()->ip()
        ]);

        return view('guru.dashboard', compact(
            'stats',
            'recentMaterials',
            'recentAssignments',
            'recentPracticals',
            'pendingSubmissions',
            'weeklyAttendance',
            'topMaterials',
            'today',
            'weekStart',
            'weekEnd'
        ))->with([
            'recentActivities' => collect(), // Empty collection for now
            'recentSubmissions' => $pendingSubmissions, // Use pending submissions as recent submissions
            'upcomingDeadlines' => Assignment::where('guru_id', $guruId)
                ->where('deadline', '>', now())
                ->where('deadline', '<=', now()->addWeeks(2))
                ->orderBy('deadline')
                ->take(6)
                ->get()
        ]);
    }

    /**
     * Get quick stats for dashboard (AJAX).
     */
    public function getQuickStats(): JsonResponse
    {
        $guruId = Auth::id();
        $today = Carbon::today();

        $stats = [
            'today_materials' => Material::where('guru_id', $guruId)
                ->whereDate('created_at', $today)
                ->count(),
            'today_assignments' => Assignment::where('guru_id', $guruId)
                ->whereDate('created_at', $today)
                ->count(),
            'today_practicals' => Practical::where('guru_id', $guruId)
                ->whereDate('created_at', $today)
                ->count(),
            'attendance_rate' => Attendance::where('recorded_by', $guruId)
                ->whereDate('tanggal', $today)
                ->selectRaw('ROUND((SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) * 100.0 / COUNT(*)), 2) as rate')
                ->first()?->rate ?? 0,
        ];

        return response()->json($stats);
    }
}