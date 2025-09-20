<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Practical;
use App\Models\Attendance;
use App\Models\PracticalScore;
use App\Models\AssignmentSubmission;
use App\Models\MaterialDownload;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:guru');
    }

    /**
     * Display the reports dashboard.
     */
    public function index(): View
    {
        $guruId = Auth::id();
        $startDate = request('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = request('end_date', Carbon::now()->format('Y-m-d'));

        $stats = [
            'total_materials' => Material::where('guru_id', $guruId)->count(),
            'total_assignments' => Assignment::where('guru_id', $guruId)->count(),
            'total_practicals' => Practical::where('guru_id', $guruId)->count(),
            'total_attendance' => Attendance::where('recorded_by', $guruId)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->count(),

            'graded_assignments' => AssignmentSubmission::whereHas('assignment', function($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })->whereNotNull('score')->count(),

            'pending_assignments' => AssignmentSubmission::whereHas('assignment', function($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })->whereNull('score')->count(),

            'materials_downloads' => MaterialDownload::whereHas('material', function($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })->whereBetween('downloaded_at', [$startDate, $endDate])->count(),

            'average_practical_score' => PracticalScore::whereHas('practical', function($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })->avg('score') ?? 0,
        ];

        // Chart data for dashboard
        $monthlyData = $this->getMonthlyReportData($guruId);

        // Determine which view to use based on the route
        $viewName = request()->route()->getName() === 'guru.reports.index' 
            ? 'guru.reports.index' 
            : 'guru.laporan.index';
            
        return view($viewName, compact('stats', 'startDate', 'endDate', 'monthlyData'));
    }

    private function getMonthlyReportData($guruId)
    {
        $startDate = Carbon::now()->subMonths(6)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $monthlyData = [
            'labels' => [],
            'materials' => [],
            'assignments' => [],
            'practicals' => [],
            'attendance' => []
        ];

        $current = $startDate->copy();
        while ($current <= $endDate) {
            $monthLabel = $current->format('M Y');
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();

            $monthlyData['labels'][] = $monthLabel;
            $monthlyData['materials'][] = Material::where('guru_id', $guruId)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();

            $monthlyData['assignments'][] = Assignment::where('guru_id', $guruId)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();

            $monthlyData['practicals'][] = Practical::where('guru_id', $guruId)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();

            $monthlyData['attendance'][] = Attendance::where('recorded_by', $guruId)
                ->whereBetween('tanggal', [
                    $monthStart->format('Y-m-d'),
                    $monthEnd->format('Y-m-d')
                ])->count();

            $current->addMonth();
        }

        return $monthlyData;
    }

    /**
     * Show practical reports.
     */
    public function praktik(Request $request): View
    {
        $guruId = Auth::id();

        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'kelas' => 'nullable|string',
            'skill_level' => 'nullable|in:Pemula,Menengah,Mahir',
        ]);

        $filters = [
            'start_date' => $request->start_date ?? Carbon::now()->subMonth()->format('Y-m-d'),
            'end_date' => $request->end_date ?? Carbon::now()->format('Y-m-d'),
            'kelas' => $request->kelas,
            'skill_level' => $request->skill_level,
        ];

        $query = Practical::with(['scores' => function($query) {
                $query->with(['siswa', 'criteria']);
            }])
            ->where('guru_id', $guruId)
            ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']]);

            if ($filters['kelas']) {
                $query->where('kelas_id', $filters['kelas']);
            }

        if ($filters['skill_level']) {
            $query->where('skill_level', $filters['skill_level']);
        }

        $practicals = $query->latest()->paginate(15);

        $practicalStats = [
            'total_siswa' => PracticalScore::whereHas('practical', function($query) use ($guruId, $filters) {
                $query->where('guru_id', $guruId)
                    ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']]);
                if ($filters['kelas']) {
                    $query->where('kelas_id', $filters['kelas']);
                }
            })->distinct('siswa_id')->count('siswa_id'),

            'average_score' => PracticalScore::whereHas('practical', function($query) use ($guruId, $filters) {
                $query->where('guru_id', $guruId)
                    ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']]);
                if ($filters['kelas']) {
                    $query->where('kelas_id', $filters['kelas']);
                }
            })->avg('score') ?? 0,

            'total_graded' => PracticalScore::whereHas('practical', function($query) use ($guruId, $filters) {
                $query->where('guru_id', $guruId)
                    ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']]);
                if ($filters['kelas']) {
                    $query->where('kelas_id', $filters['kelas']);
                }
            })->count(),
        ];

        $classes = \App\Models\Kelas::whereHas('students', function($query) {
                $query->where('role', 'siswa');
            })
            ->where('status', 'active')
            ->pluck('name', 'id');

        return view('guru.laporan.praktik', compact(
            'practicals',
            'practicalStats',
            'classes',
            'filters'
        ));
    }

    /**
     * Show attendance reports.
     */
    public function absensi(Request $request): View
    {
        $guruId = Auth::id();

        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'kelas' => 'nullable|string',
            'status' => 'nullable|in:hadir,izin,sakit,alpha',
        ]);

        $filters = [
            'start_date' => $request->start_date ?? Carbon::now()->subMonth()->format('Y-m-d'),
            'end_date' => $request->end_date ?? Carbon::now()->format('Y-m-d'),
            'kelas' => $request->kelas,
            'status' => $request->status,
        ];

        $query = Attendance::with('siswa')
            ->where('recorded_by', $guruId)
            ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']]);

            if ($filters['kelas']) {
                $query->whereHas('user', function($q) use ($filters) {
                    $q->where('kelas_id', $filters['kelas']);
                });
            }

        if ($filters['status']) {
            $query->where('status', $filters['status']);
        }

        $attendance = $query->orderBy('tanggal', 'desc')->paginate(20);

        $attendanceStats = Attendance::selectRaw('status, COUNT(*) as count')
            ->where('recorded_by', $guruId)
            ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']])
            ->when($filters['kelas'], function($query) use ($filters) {
                return $query->whereHas('siswa', function($q) use ($filters) {
                    $q->where('kelas_id', $filters['kelas']);
                });
            })
            ->groupBy('status')
            ->get();

        $summaryStats = [
            'total_days' => Carbon::parse($filters['start_date'])->diffInDays(Carbon::parse($filters['end_date'])) + 1,
            'total_records' => $attendance->total(),
            'present_count' => $attendanceStats->where('status', 'hadir')->first()?->count ?? 0,
            'absent_count' => $attendanceStats->where('status', 'alpha')->first()?->count ?? 0,
            'permission_count' => $attendanceStats->whereIn('status', ['izin', 'sakit'])->sum('count'),
            'attendance_rate' => $attendance->total() > 0 ?
                round(($attendanceStats->where('status', 'hadir')->first()?->count ?? 0) / $attendance->total() * 100, 2) : 0,
        ];

        $classes = \App\Models\Kelas::whereHas('students', function($query) {
                $query->where('role', 'siswa');
            })
            ->where('status', 'active')
            ->pluck('name', 'id');

        // Get subjects taught by this guru - using a more flexible approach
        try {
            $subjects = Subject::where('guru_id', $guruId)->get();
        } catch (\Exception $e) {
            // If Subject table doesn't have guru_id or other issues, create default
            $subjects = collect();
        }
        
        // If no subjects found, try alternative queries or create default
        if ($subjects->isEmpty()) {
            try {
                // Try to find subjects in other ways or just get first few subjects
                $subjects = Subject::limit(5)->get();
            } catch (\Exception $e) {
                // Final fallback - create a mock subject
                $subjects = collect([(object)['id' => 0, 'name' => 'General Subject']]);
            }
        }

        // Create attendance summary data 
        $attendanceSummary = collect();
        foreach($subjects as $subject) {
            $subjectAttendance = Attendance::where('recorded_by', $guruId)
                ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']])
                ->when($filters['kelas'], function($query) use ($filters) {
                    return $query->whereHas('siswa', function($q) use ($filters) {
                        $q->where('kelas_id', $filters['kelas']);
                    });
                })
                ->get();
                
            $summary = (object)[
                'subject_name' => $subject->name,
                'class' => $filters['kelas'] ? $classes[$filters['kelas']] ?? '-' : 'All Classes',
                'total_sessions' => $subjectAttendance->groupBy('tanggal')->count(),
                'present_count' => $subjectAttendance->where('status', 'hadir')->count(),
                'late_count' => $subjectAttendance->where('status', 'terlambat')->count(),
                'absent_count' => $subjectAttendance->where('status', 'alpha')->count(),
                'attendance_rate' => $subjectAttendance->count() > 0 ? 
                    round($subjectAttendance->where('status', 'hadir')->count() / $subjectAttendance->count() * 100, 2) : 0
            ];
            
            $attendanceSummary->push($summary);
        }

        // Get students data for the detailed table
        $studentsQuery = User::where('role', 'siswa')
            ->when($filters['kelas'], function($query) use ($filters) {
                return $query->where('kelas_id', $filters['kelas']);
            })
            ->with(['attendances' => function($query) use ($guruId, $filters) {
                $query->where('recorded_by', $guruId)
                    ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']]);
            }]);
            
        $students = $studentsQuery->paginate(20);
        
        // Map student data with attendance calculations
        $students->getCollection()->transform(function($student) use ($subjects) {
            $attendances = $student->attendances;
            $student->class = $student->kelas->name ?? '-';
            $student->subject_name = $subjects->first()->name ?? 'No Subject';
            $student->present_count = $attendances->where('status', 'hadir')->count();
            $student->late_count = $attendances->where('status', 'terlambat')->count();
            $student->absent_count = $attendances->where('status', 'alpha')->count();
            $student->excused_count = $attendances->whereIn('status', ['izin', 'sakit'])->count();
            $totalRecords = $attendances->count();
            $student->attendance_rate = $totalRecords > 0 ? 
                round($student->present_count / $totalRecords * 100, 2) : 0;
            return $student;
        });

        // Update stats to match view expectations
        $stats = [
            'present_count' => $summaryStats['present_count'],
            'late_count' => $attendanceStats->where('status', 'terlambat')->first()?->count ?? 0,
            'absent_count' => $summaryStats['absent_count'],
            'attendance_rate' => $summaryStats['attendance_rate']
        ];

        // Determine which view to use based on the route
        $viewName = request()->route()->getName() === 'guru.reports.attendance' 
            ? 'guru.reports.attendance' 
            : 'guru.laporan.absensi';
            
        return view($viewName, compact(
            'attendance',
            'attendanceStats', 
            'attendanceSummary',
            'summaryStats',
            'classes',
            'subjects',
            'students',
            'stats',
            'filters'
        ));
    }

    /**
     * Show assignment reports.
     */
    public function tugas(Request $request): View
    {
        $guruId = Auth::id();

        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:graded,pending',
        ]);

        $filters = [
            'start_date' => $request->start_date ?? Carbon::now()->subMonth()->format('Y-m-d'),
            'end_date' => $request->end_date ?? Carbon::now()->format('Y-m-d'),
            'status' => $request->status,
        ];

        $query = Assignment::withCount([
                'submissions',
                'submissions as graded_count' => function($query) {
                    $query->whereNotNull('score');
                },
                'submissions as pending_count' => function($query) {
                    $query->whereNull('score');
                }
            ])
            ->where('guru_id', $guruId)
            ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);

        $assignments = $query->latest()->paginate(15);

        $assignmentStats = [
            'total_submissions' => AssignmentSubmission::whereHas('assignment', function($query) use ($guruId, $filters) {
                $query->where('guru_id', $guruId)
                    ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
            })->count(),

            'graded_submissions' => AssignmentSubmission::whereHas('assignment', function($query) use ($guruId, $filters) {
                $query->where('guru_id', $guruId)
                    ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
            })->whereNotNull('score')->count(),

            'average_score' => AssignmentSubmission::whereHas('assignment', function($query) use ($guruId, $filters) {
                $query->where('guru_id', $guruId)
                    ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
            })->whereNotNull('score')->avg('score') ?? 0,
        ];

        return view('guru.laporan.tugas', compact(
            'assignments',
            'assignmentStats',
            'filters'
        ));
    }

    /**
     * Show material reports.
     */
    public function materi(Request $request): View
    {
        $guruId = Auth::id();

        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category' => 'nullable|string',
        ]);

        $filters = [
            'start_date' => $request->start_date ?? Carbon::now()->subMonth()->format('Y-m-d'),
            'end_date' => $request->end_date ?? Carbon::now()->format('Y-m-d'),
            'category' => $request->category,
        ];

        $query = Material::withCount('downloads')
            ->where('guru_id', $guruId)
            ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);

        if ($filters['category']) {
            $query->where('category', $filters['category']);
        }

        $materials = $query->latest()->paginate(15);

        $materialStats = [
            'total_downloads' => MaterialDownload::whereHas('material', function($query) use ($guruId, $filters) {
                $query->where('guru_id', $guruId)
                    ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
                if ($filters['category']) {
                    $query->where('category', $filters['category']);
                }
            })->whereBetween('downloaded_at', [$filters['start_date'], $filters['end_date']])->count(),

            'total_views' => Material::where('guru_id', $guruId)
                ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']])
                ->when($filters['category'], function($query) use ($filters) {
                    return $query->where('category', $filters['category']);
                })
                ->sum('views_count') ?? 0,

            'most_downloaded' => Material::where('guru_id', $guruId)
                ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']])
                ->when($filters['category'], function($query) use ($filters) {
                    return $query->where('category', $filters['category']);
                })
                ->orderBy('downloads_count', 'desc')
                ->first(),
        ];

        $categories = [
            'Anatomi' => 'Anatomi',
            'Fisiologi' => 'Fisiologi',
            'Keperawatan' => 'Keperawatan',
            'Kebidanan' => 'Kebidanan',
            'Farmasi' => 'Farmasi',
            'Gizi' => 'Gizi',
            'Umum' => 'Umum'
        ];

        return view('guru.laporan.materi', compact(
            'materials',
            'materialStats',
            'categories',
            'filters'
        ));
    }

    /**
     * Show attendance reports (English method for guru.reports.attendance route).
     */
    public function attendance(Request $request): View
    {
        return $this->absensi($request);
    }

    /**
     * Show practical reports (English method for guru.reports.practical route).
     */
    public function practical(Request $request): View
    {
        return $this->praktik($request);
    }

    /**
     * Generate report (for guru.reports.generate route).
     */
    public function generate(Request $request)
    {
        $type = $request->input('type');
        return $this->export($type, $request);
    }

    /**
     * Export report to PDF.
     */
    public function export($type, Request $request)
    {
        $guruId = Auth::id();

        $validClasses = \App\Models\Kelas::whereHas('students', function($query) {
                $query->where('role', 'siswa');
            })
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();

        $rules = [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf',
        ];

        if (!empty($validClasses)) {
            $rules['kelas'] = 'nullable|string|in:' . implode(',', $validClasses);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $filters = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'kelas' => $request->kelas,
        ];

        $filename = 'laporan-' . $type . '-' . $filters['start_date'] . '-to-' . $filters['end_date'];

        Log::info('Report exported to PDF', [
            'type' => $type,
            'guru_id' => $guruId,
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
            'kelas' => $filters['kelas'],
            'ip' => $request->ip()
        ]);

        switch ($type) {
            case 'absensi':
                return $this->exportAttendancePdf($filters, $filename, $guruId);

            case 'praktik':
                return $this->exportPracticalPdf($filters, $filename, $guruId);

            case 'tugas':
                return $this->exportAssignmentPdf($filters, $filename, $guruId);

            case 'materi':
                return $this->exportMaterialPdf($filters, $filename, $guruId);

            default:
                return redirect()->back()->with('error', 'Jenis laporan tidak valid');
        }
    }

    private function exportAttendancePdf($filters, $filename, $guruId)
    {
        $attendance = Attendance::with('siswa')
            ->where('recorded_by', $guruId)
            ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']])
            ->when($filters['kelas'], function($query) use ($filters) {
                return $query->whereHas('siswa', function($q) use ($filters) {
                    $q->where('kelas_id', $filters['kelas']);
                });
            })
            ->orderBy('tanggal', 'desc')
            ->limit(1000)
            ->get();

        $stats = Attendance::selectRaw('status, COUNT(*) as count')
            ->where('recorded_by', $guruId)
            ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']])
            ->when($filters['kelas'], function($query) use ($filters) {
                return $query->whereHas('siswa', function($q) use ($filters) {
                    $q->where('kelas_id', $filters['kelas']);
                });
            })
            ->groupBy('status')
            ->get();

        $pdf = Pdf::loadView('guru.laporan.pdf.absensi', compact('attendance', 'stats', 'filters'));
        return $pdf->download($filename . '.pdf');
    }

    private function exportPracticalPdf($filters, $filename, $guruId)
    {
        $practicals = Practical::with(['scores.siswa', 'scores.criteria'])
            ->where('guru_id', $guruId)
            ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']])
            ->when($filters['kelas'], function($query) use ($filters) {
                return $query->where('kelas_id', $filters['kelas']);
            })
            ->latest()
            ->limit(1000)
            ->get();

        $stats = [
            'total_practicals' => $practicals->count(),
            'total_scores' => PracticalScore::whereHas('practical', function($query) use ($guruId, $filters) {
                $query->where('guru_id', $guruId)
                    ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']]);
                if ($filters['kelas']) {
                    $query->where('kelas_id', $filters['kelas']);
                }
            })->count(),
            'average_score' => PracticalScore::whereHas('practical', function($query) use ($guruId, $filters) {
                $query->where('guru_id', $guruId)
                    ->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']]);
            if ($filters['kelas']) {
                $query->where('kelas_id', $filters['kelas']);
            }
            })->avg('score') ?? 0,
        ];

        $pdf = Pdf::loadView('guru.laporan.pdf.praktik', compact('practicals', 'stats', 'filters'));
        return $pdf->download($filename . '.pdf');
    }

    private function exportAssignmentPdf($filters, $filename, $guruId)
    {
        $assignments = Assignment::withCount([
                'submissions',
                'submissions as graded_count' => function($query) {
                    $query->whereNotNull('score');
                }
            ])
            ->where('guru_id', $guruId)
            ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']])
            ->latest()
            ->limit(1000)
            ->get();

        $stats = [
            'total_assignments' => $assignments->count(),
            'total_submissions' => AssignmentSubmission::whereHas('assignment', function($query) use ($guruId, $filters) {
                $query->where('guru_id', $guruId)
                    ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
            })->count(),
            'average_score' => AssignmentSubmission::whereHas('assignment', function($query) use ($guruId, $filters) {
                $query->where('guru_id', $guruId)
                    ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
            })->whereNotNull('score')->avg('score') ?? 0,
        ];

        $pdf = Pdf::loadView('guru.laporan.pdf.tugas', compact('assignments', 'stats', 'filters'));
        return $pdf->download($filename . '.pdf');
    }

    private function exportMaterialPdf($filters, $filename, $guruId)
    {
        $materials = Material::withCount('downloads')
            ->where('guru_id', $guruId)
            ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']])
            ->latest()
            ->limit(1000)
            ->get();

        $stats = [
            'total_materials' => $materials->count(),
            'total_downloads' => MaterialDownload::whereHas('material', function($query) use ($guruId, $filters) {
                $query->where('guru_id', $guruId)
                    ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
            })->whereBetween('downloaded_at', [$filters['start_date'], $filters['end_date']])->count(),
            'most_downloaded' => $materials->sortByDesc('downloads_count')->first(),
        ];

        $pdf = Pdf::loadView('guru.laporan.pdf.materi', compact('materials', 'stats', 'filters'));
        return $pdf->download($filename . '.pdf');
    }
}