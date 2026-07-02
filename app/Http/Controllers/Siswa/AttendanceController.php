<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Rekap absensi per bulan.
     */
    public function index(Request $request): View
    {
        $user    = Auth::user();
        $siswaId = $user->id;
        $kelasId = $user->siswa?->kelas_id;

        $month = min(max(1, (int) $request->input('month', Carbon::now()->month)), 12);
        $year  = max(2000, min((int) $request->input('year', Carbon::now()->year), 2100));

        $attendances = Attendance::with(['subject'])
            ->where('siswa_id', $siswaId)
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

        $monthlyStats = $this->getMonthlyStats($siswaId, $kelasId, $month, $year);

        return view('siswa.absensi.index', compact(
            'attendances', 'monthlyStats', 'month', 'year'
        ));
    }

    /**
     * Export rekap absensi ke CSV.
     */
    public function export(Request $request)
    {
        $user    = Auth::user();
        $siswaId = $user->id;
        $kelasId = $user->siswa?->kelas_id;

        $month = min(max(1, (int) $request->input('month', Carbon::now()->month)), 12);
        $year  = max(2000, min((int) $request->input('year', Carbon::now()->year), 2100));

        $attendances = Attendance::with(['subject'])
            ->where('siswa_id', $siswaId)
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'asc')
            ->get();

        $stats    = $this->getMonthlyStats($siswaId, $kelasId, $month, $year);
        $bulan    = Carbon::createFromDate($year, $month, 1)->locale('id')->monthName;
        $filename = 'absensi-' . $user->id . '-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($attendances, $stats, $bulan, $year, $user) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Rekap Absensi - ' . $user->name]);
            fputcsv($handle, ['Periode: ' . $bulan . ' ' . $year]);
            fputcsv($handle, []);
            fputcsv($handle, ['Tanggal', 'Hari', 'Mata Pelajaran', 'Status', 'Keterangan']);

            foreach ($attendances as $a) {
                $statusLabel = match (strtolower($a->status ?? '')) {
                    'hadir', 'present' => 'Hadir',
                    'izin'             => 'Izin',
                    'sakit', 'sick'    => 'Sakit',
                    default            => 'Alpa',
                };
                fputcsv($handle, [
                    $a->date->format('d/m/Y'),
                    $a->date->locale('id')->dayName,
                    $a->subject?->name ?? $a->subject?->nama ?? '—',
                    $statusLabel,
                    $a->note ?? '—',
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Ringkasan']);
            fputcsv($handle, ['Hadir',  $stats['hadir']]);
            fputcsv($handle, ['Izin',   $stats['izin']]);
            fputcsv($handle, ['Sakit',  $stats['sakit']]);
            fputcsv($handle, ['Alpa',   $stats['alpa']]);
            fputcsv($handle, ['Total',  $stats['total']]);
            fputcsv($handle, ['Persentase', $stats['attendance_rate'] . '%']);
            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    public function show($id): View
    {
        $attendance = Attendance::with(['subject'])
            ->where('siswa_id', Auth::id())
            ->findOrFail($id);

        return view('siswa.absensi.show', compact('attendance'));
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    protected function getMonthlyStats(int $siswaId, ?int $kelasId, int $month, int $year): array
    {
        $rows = Attendance::selectRaw('status, COUNT(*) as count')
            ->where('siswa_id', $siswaId)
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->groupBy('status')
            ->get();

        $hadir = 0; $izin = 0; $sakit = 0; $alpa = 0;
        foreach ($rows as $row) {
            $st = strtolower($row->status ?? '');
            match (true) {
                in_array($st, ['hadir', 'present']) => $hadir += $row->count,
                in_array($st, ['sakit', 'sick'])    => $sakit += $row->count,
                $st === 'izin'                      => $izin  += $row->count,
                default                             => $alpa  += $row->count,
            };
        }

        $total          = $hadir + $izin + $sakit + $alpa;
        $percentage     = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
        $workingDays    = $this->countWorkingDays($month, $year);
        $attendanceRate = $workingDays > 0 ? round(($hadir / $workingDays) * 100, 1) : $percentage;

        return [
            'hadir'           => $hadir,
            'izin'            => $izin,
            'sakit'           => $sakit,
            'alpa'            => $alpa,
            'total'           => $total,
            'percentage'      => $percentage,
            'attendance_rate' => $attendanceRate,
            'present'         => $hadir,
            'absent'          => $alpa,
            'permission'      => $izin + $sakit,
            'working_days'    => $workingDays,
            'breakdown'       => collect([
                (object)['status' => 'hadir', 'count' => $hadir],
                (object)['status' => 'izin',  'count' => $izin],
                (object)['status' => 'sakit', 'count' => $sakit],
                (object)['status' => 'alpha', 'count' => $alpa],
            ]),
        ];
    }

    protected function countWorkingDays(int $month, int $year): int
    {
        $start   = Carbon::create($year, $month, 1)->startOfMonth();
        $end     = Carbon::create($year, $month, 1)->endOfMonth();
        $days    = 0;
        $current = $start->copy();
        while ($current <= $end) {
            if (!$current->isWeekend()) $days++;
            $current->addDay();
        }
        return $days;
    }
}
