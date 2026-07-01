<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Practical;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the reports overview page.
     */
    public function index(): View
    {
        try {
            $currentMonth = Carbon::now()->month;
            $currentYear  = Carbon::now()->year;

            // Attendance rate bulan ini
            $attendanceTotal = Attendance::whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)->count();
            $attendanceHadir = Attendance::whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->where('status', 'hadir')->count();
            $attendanceRate = $attendanceTotal > 0
                ? round($attendanceHadir / $attendanceTotal * 100, 1)
                : 0;

            $stats = [
                'total_users'            => User::count(),
                'total_activities'       => User::whereDate('created_at', today())->count()
                                          + Attendance::whereDate('date', today())->count(),
                'attendance_rate'        => $attendanceRate,
                'completed_assignments'  => AssignmentSubmission::count(),
            ];

        } catch (\Exception $e) {
            Log::warning('ReportController::index error: ' . $e->getMessage());
            $stats = [
                'total_users'           => 0,
                'total_activities'      => 0,
                'attendance_rate'       => 0,
                'completed_assignments' => 0,
            ];
        }

        return view('admin.reports.index', compact('stats'));
    }

    /**
     * Laporan kehadiran.
     */
    public function attendance(Request $request): View
    {
        try {
            $query = Attendance::with(['siswa', 'guru'])
                ->orderByDesc('date');

            if ($request->filled('date_from')) {
                $query->whereDate('date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('date', '<=', $request->date_to);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $attendances = $query->paginate(20)->withQueryString();

            $summary = Attendance::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

        } catch (\Exception $e) {
            Log::warning('ReportController::attendance error: ' . $e->getMessage());
            $attendances = collect();
            $summary = [];
        }

        return view('admin.reports.absensi', compact('attendances', 'summary'));
    }

    /**
     * Laporan praktikum.
     */
    public function practical(Request $request): View
    {
        try {
            $practicals = Practical::with(['guru', 'subject'])
                ->withCount('scores')
                ->orderByDesc('created_at')
                ->paginate(20)
                ->withQueryString();

        } catch (\Exception $e) {
            Log::warning('ReportController::practical error: ' . $e->getMessage());
            $practicals = collect();
        }

        return view('admin.reports.praktik', compact('practicals'));
    }
}
