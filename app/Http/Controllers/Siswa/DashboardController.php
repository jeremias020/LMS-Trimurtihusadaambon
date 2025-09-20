<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Practical;
use App\Models\Attendance;
use App\Models\PracticalScore;
use App\Models\AssignmentSubmission;
use App\Models\MaterialDownload;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the student dashboard.
     */
    public function index(): View
    {
        $siswa = Auth::user(); // ✅ Dapatkan model lengkap
        $siswaId = $siswa->id;
        
        // Load siswa relation with kelas to get kelas_id
        $siswa->load('siswa.kelas');
        $kelasId = $siswa->kelas_id; // Using the accessor we created

        $stats = [
            'available_materials' => Material::where('is_published', true)
                ->where(function($query) use ($kelasId) {
                    $query->where('kelas_id', $kelasId)
                          ->orWhereNull('kelas_id'); // Include materials without specific class
                })
                ->count(),
            'active_assignments' => Assignment::where('is_published', true)
                ->where(function($query) use ($kelasId) {
                    $query->where('kelas_id', $kelasId)
                          ->orWhereNull('kelas_id'); // Include assignments without specific class
                })
                ->where('deadline', '>', now())
                ->count(),
            'pending_assignments' => $this->getPendingAssignmentsCount($siswaId, $kelasId), // ✅ Use kelas_id
            'submitted_assignments' => AssignmentSubmission::where('siswa_id', $siswaId)->count(),
            'practicals_count' => Practical::where('is_published', true)
                ->where(function($query) use ($kelasId) {
                    $query->where('kelas_id', $kelasId)
                          ->orWhereNull('kelas_id'); // Include practicals without specific class
                })
                ->count(),
            'attendance_rate' => $this->calculateAttendanceRate($siswaId),
            'downloaded_materials' => MaterialDownload::where('siswa_id', $siswaId)->count(),
        ];

        $upcomingAssignments = Assignment::with(['submissions' => function($query) use ($siswaId) {
            $query->where('siswa_id', $siswaId);
        }])
        ->where('is_published', true)
        ->where(function($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId)
                  ->orWhereNull('kelas_id'); // Include assignments without specific class
        })
        ->where('deadline', '>', now())
        ->orderBy('deadline', 'asc')
        ->take(5)
        ->get();

        $recentMaterials = Material::with('guru')
            ->where('is_published', true)
            ->where(function($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->orWhereNull('kelas_id'); // Include materials without specific class
            })
            ->latest()
            ->take(5)
            ->get();

        $recentScores = PracticalScore::with(['practical', 'criteria'])
            ->where('siswa_id', $siswaId)
            ->latest()
            ->take(5)
            ->get();

        $overdueAssignments = Assignment::where('is_published', true)
            ->where(function($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->orWhereNull('kelas_id'); // Include assignments without specific class
            })
            ->where('deadline', '<', now())
            ->whereDoesntHave('submissions', function($query) use ($siswaId) {
                $query->where('siswa_id', $siswaId);
            })
            ->count();

        $todayAttendance = Attendance::where('siswa_id', $siswaId)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        $notifications = $this->getNotifications($siswaId, $kelasId); // ✅ Use kelas_id

        // Variables for view compatibility
        $newMaterialsCount = Material::where('is_published', true)
            ->where(function($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->orWhereNull('kelas_id');
            })
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
            
        $pendingAssignmentsCount = $stats['pending_assignments'];
        $upcomingPracticalsCount = $stats['practicals_count'];
        $attendancePercentage = $stats['attendance_rate'];

        return view('siswa.dashboard', compact(
            'stats',
            'upcomingAssignments',
            'recentMaterials',
            'recentScores',
            'overdueAssignments',
            'todayAttendance',
            'notifications',
            'newMaterialsCount',
            'pendingAssignmentsCount',
            'upcomingPracticalsCount',
            'attendancePercentage'
        ));
    }

    protected function getPendingAssignmentsCount($siswaId, $kelasId)
    {
        return Assignment::leftJoin('assignment_submissions', function($join) use ($siswaId) {
                $join->on('assignments.id', '=', 'assignment_submissions.assignment_id')
                     ->where('assignment_submissions.siswa_id', '=', $siswaId);
            })
            ->where('assignments.is_published', true)
            ->where(function($query) use ($kelasId) {
                $query->where('assignments.kelas_id', $kelasId)
                      ->orWhereNull('assignments.kelas_id'); // Include assignments without specific class
            })
            ->where('assignments.deadline', '>', now())
            ->whereNull('assignment_submissions.id')
            ->count();
    }

    protected function calculateAttendanceRate($siswaId)
    {
        $month = now()->month;
        $year = now()->year;

        $presentDays = Attendance::where('siswa_id', $siswaId)
            ->where('status', 'hadir')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->count();

        $workingDays = $this->getWorkingDays($month, $year);

        return $workingDays > 0 ? round(($presentDays / $workingDays) * 100) : 0;
    }

    protected function getWorkingDays($month, $year)
    {
        $startDate = Carbon::create($year, $month, 1);
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $workingDays = 0;
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            if (!$currentDate->isWeekend()) {
                $workingDays++;
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }

    protected function getNotifications($siswaId, $kelasId)
    {
        $notifications = [];

        $urgentAssignments = Assignment::where('is_published', true)
            ->where(function($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->orWhereNull('kelas_id'); // Include assignments without specific class
            })
            ->where('deadline', '>', now())
            ->where('deadline', '<=', now()->addDays(2))
            ->whereDoesntHave('submissions', function($query) use ($siswaId) {
                $query->where('siswa_id', $siswaId);
            })
            ->count();

        if ($urgentAssignments > 0) {
            $notifications[] = [
                'type' => 'warning',
                'message' => "Anda memiliki $urgentAssignments tugas yang mendekati deadline!",
                'link' => route('siswa.assignments.index')
            ];
        }

        $todayAttendance = Attendance::where('siswa_id', $siswaId)
            ->whereDate('tanggal', Carbon::today())
            ->exists();

        if (!$todayAttendance && !Carbon::now()->isWeekend()) {
            $notifications[] = [
                'type' => 'info',
                'message' => 'Belum ada catatan absensi hari ini. Pastikan Anda sudah absen!',
                'link' => route('siswa.attendance.index')
            ];
        }

        return $notifications;
    }

    /**
     * Get chart data for dashboard.
     */
    public function getChartData(): JsonResponse
    {
        $siswaId = Auth::id();

        $attendanceData = Attendance::selectRaw('DATE(tanggal) as date, COUNT(*) as count, status')
            ->where('siswa_id', $siswaId)
            ->where('tanggal', '>=', Carbon::now()->subDays(30))
            ->groupBy('date', 'status')
            ->get();

        $scoreData = AssignmentSubmission::selectRaw('DATE(graded_at) as date, AVG(score) as average_score')
            ->where('siswa_id', $siswaId)
            ->whereNotNull('score')
            ->where('graded_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->get();

        return response()->json([
            'attendance' => $attendanceData,
            'scores' => $scoreData
        ]);
    }
}
