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
use App\Models\Student;
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
        $siswa = Auth::user();
        $siswaId = $siswa->id;
        
        // Load siswa relation with kelas to get kelas_id
        $siswa->load('siswa.kelas');
        $kelasId = $siswa->siswa->kelas_id ?? null;

        // Stats for dashboard
        $stats = [
            'total_materials' => Material::whereNotNull('published_at')
                ->where(function($query) use ($kelasId) {
                    $query->where('kelas_id', $kelasId)
                          ->orWhereNull('kelas_id');
                })
                ->count(),
            'completed_assignments' => AssignmentSubmission::where('siswa_id', $siswaId)
                ->whereNotNull('score')
                ->count(),
            'completed_practicals' => PracticalScore::where('siswa_id', $siswaId)
                ->whereNotNull('score')
                ->count(),
            'attendance_percentage' => $this->calculateAttendanceRate($siswaId),
            'average_score' => $this->getAverageScore($siswaId),
            'attendance_count' => Attendance::where('siswa_id', $siswaId)
                ->where('status', 'hadir')
                ->count(),
            'rank' => $this->getStudentRank($siswaId, $kelasId),
        ];

        // Recent materials
        $recentMaterials = Material::with('guru')
            ->whereNotNull('published_at')
            ->where(function($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->orWhereNull('kelas_id');
            })
            ->latest()
            ->take(5)
            ->get();

        // Upcoming deadlines
        $upcomingDeadlines = $this->getUpcomingDeadlines($siswaId, $kelasId);

        // Variables for backward compatibility
        $newMaterialsCount = $stats['total_materials'];
        $pendingAssignmentsCount = $this->getPendingAssignmentsCount($siswaId, $kelasId);
        $upcomingPracticalsCount = Practical::whereNotNull('published_at')
            ->where(function($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->orWhereNull('kelas_id');
            })
            ->count();
        $attendancePercentage = $stats['attendance_percentage'];

        return view('siswa.dashboard', compact(
            'stats',
            'recentMaterials',
            'upcomingDeadlines',
            'newMaterialsCount',
            'pendingAssignmentsCount',
            'upcomingPracticalsCount',
            'attendancePercentage'
        ));
    }

    protected function getUpcomingDeadlines($siswaId, $kelasId)
    {
        $deadlines = [];
        
        // Get upcoming assignments
        $assignments = Assignment::where(function($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->orWhereNull('kelas_id');
            })
            ->where('due_date', '>', now())
            ->whereDoesntHave('submissions', function($query) use ($siswaId) {
                $query->where('siswa_id', $siswaId);
            })
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();
            
        foreach ($assignments as $assignment) {
            $deadlines[] = (object)[
                'id' => $assignment->id,
                'assignment_id' => $assignment->id,
                'title' => $assignment->title,
                'type' => 'assignment',
                'deadline' => $assignment->deadline,
                'days_left' => now()->diffInDays($assignment->deadline, false)
            ];
        }
        
        // Get upcoming practicals
        $urgentAssignments = Assignment::where(function($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->orWhereNull('kelas_id'); // Include assignments without specific class
            })
            ->where('due_date', '>', now())
            ->whereDoesntHave('submissions', function($query) use ($siswaId) {
                $query->where('siswa_id', $siswaId);
            })
            ->orderBy('due_date', 'asc')
            ->take(3)
            ->get();
            
        $practicals = Practical::whereNotNull('published_at')
            ->where(function($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->orWhereNull('kelas_id');
            })
            ->where('date', '>', now())
            ->whereDoesntHave('scores', function($query) use ($siswaId) {
                $query->where('siswa_id', $siswaId);
            })
            ->orderBy('date', 'asc')
            ->take(5)
            ->get();
            
        foreach ($practicals as $practical) {
            $deadlines[] = (object)[
                'id' => $practical->id,
                'praktikum_id' => $practical->id,
                'title' => $practical->judul,
                'type' => 'practical',
                'deadline' => $practical->date,
                'days_left' => now()->diffInDays($practical->date, false)
            ];
        }
        
        // Sort by deadline
        usort($deadlines, function($a, $b) {
            return $a->deadline <=> $b->deadline;
        });
        
        return array_slice($deadlines, 0, 5);
    }
    
    protected function getAverageScore($siswaId)
    {
        $assignmentScores = AssignmentSubmission::where('siswa_id', $siswaId)
            ->whereNotNull('score')
            ->pluck('score');
            
        $practicalScores = PracticalScore::where('siswa_id', $siswaId)
            ->whereNotNull('score')
            ->pluck('score');
            
        $allScores = $assignmentScores->merge($practicalScores);
        
        return $allScores->isNotEmpty() ? round($allScores->avg(), 2) : 0;
    }
    
    protected function getStudentRank($siswaId, $kelasId)
    {
        if (!$kelasId) return '-';
        
        // Get all students in the same class
        $classStudents = Student::where('kelas_id', $kelasId)->pluck('id');
        
        // Calculate average scores for all students
        $studentScores = [];
        foreach ($classStudents as $studentId) {
            $avgScore = $this->getAverageScore($studentId);
            $studentScores[] = ['student_id' => $studentId, 'score' => $avgScore];
        }
        
        // Sort by score descending
        usort($studentScores, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        // Find current student's rank
        foreach ($studentScores as $index => $studentScore) {
            if ($studentScore['student_id'] == $siswaId) {
                return $index + 1;
            }
        }
        
        return '-';
    }

    protected function getPendingAssignmentsCount($siswaId, $kelasId)
    {
        return Assignment::leftJoin('assignment_submissions', function($join) use ($siswaId) {
                $join->on('assignments.id', '=', 'assignment_submissions.assignment_id')
                     ->where('assignment_submissions.siswa_id', '=', $siswaId);
            })
            ->where('assignments.deleted_at', null)
            ->where('assignments.due_date', '>', now())
            ->whereNull('assignment_submissions.id')
            ->count();
    }

    protected function calculateAttendanceRate($siswaId)
    {
        $month = now()->month;
        $year = now()->year;

        $presentDays = Attendance::where('siswa_id', $siswaId)
            ->where('status', 'hadir')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
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
            ->where('due_date', '>', now())
            ->where('due_date', '<=', now()->addDays(2))
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
            ->whereDate('date', Carbon::today())
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

        $attendanceData = Attendance::selectRaw('DATE(date) as date, COUNT(*) as count, status')
            ->where('student_id', $siswaId)
            ->where('date', '>=', Carbon::now()->subDays(30))
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        $scoreData = AssignmentSubmission::selectRaw('DATE(created_at) as date, AVG(score) as average_score')
            ->where('student_id', $siswaId)
            ->whereNotNull('score')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'attendance' => $attendanceData,
            'scores' => $scoreData
        ]);
    }
}
