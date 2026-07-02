<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\NilaiPraktik;
use App\Models\AssignmentSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $user    = Auth::user();
        $siswaId = $user->id;
        $kelasId = $user->siswa?->kelas_id;

        $practicalScores = NilaiPraktik::with(['practical.subject'])
            ->where('siswa_id', $siswaId)
            ->when($kelasId, fn($q) => $q->whereHas('practical', fn($s) => $s->where('kelas_id', $kelasId)))
            ->latest()
            ->paginate(10, ['*'], 'practical_page');

        $assignmentScores = AssignmentSubmission::with(['assignment.subject', 'assignment.guru'])
            ->where('student_id', $siswaId)
            ->whereNotNull('score')
            ->when($kelasId, fn($q) => $q->whereHas('assignment', fn($s) => $s->where('kelas_id', $kelasId)))
            ->latest()
            ->paginate(10, ['*'], 'assignment_page');

        $stats = $this->calculateStats($siswaId, $kelasId);

        return view('siswa.nilai.index', compact('practicalScores', 'assignmentScores', 'stats'));
    }

    public function practical(): View
    {
        $user    = Auth::user();
        $siswaId = $user->id;
        $kelasId = $user->siswa?->kelas_id;

        $scores = NilaiPraktik::with(['practical.subject'])
            ->where('siswa_id', $siswaId)
            ->when($kelasId, fn($q) => $q->whereHas('practical', fn($s) => $s->where('kelas_id', $kelasId)))
            ->latest()
            ->paginate(15);

        $col   = $scores->getCollection();
        $stats = [
            'average_score'  => round($col->avg('score') ?? 0, 1),
            'total_scores'   => $col->count(),
            'highest_score'  => $col->max('score') ?? 0,
            'lowest_score'   => $col->min('score') ?? 0,
        ];

        return view('siswa.nilai.practical', compact('scores', 'stats'));
    }

    public function assignment(): View
    {
        $user    = Auth::user();
        $siswaId = $user->id;
        $kelasId = $user->siswa?->kelas_id;

        $scores = AssignmentSubmission::with(['assignment.subject', 'assignment.guru'])
            ->where('student_id', $siswaId)
            ->whereNotNull('score')
            ->when($kelasId, fn($q) => $q->whereHas('assignment', fn($s) => $s->where('kelas_id', $kelasId)))
            ->latest()
            ->paginate(15);

        $col   = $scores->getCollection();
        $stats = [
            'average_score'  => round($col->avg('score') ?? 0, 1),
            'total_graded'   => $col->count(),
            'highest_score'  => $col->max('score') ?? 0,
            'lowest_score'   => $col->min('score') ?? 0,
        ];

        return view('siswa.nilai.assignment', compact('scores', 'stats'));
    }

    public function exportScores()
    {
        $user    = Auth::user();
        $siswaId = $user->id;
        $kelasId = $user->siswa?->kelas_id;

        $practicalScores = NilaiPraktik::with(['practical.subject'])
            ->where('siswa_id', $siswaId)
            ->when($kelasId, fn($q) => $q->whereHas('practical', fn($s) => $s->where('kelas_id', $kelasId)))
            ->get();

        $assignmentScores = AssignmentSubmission::with(['assignment.subject'])
            ->where('student_id', $siswaId)
            ->whereNotNull('score')
            ->when($kelasId, fn($q) => $q->whereHas('assignment', fn($s) => $s->where('kelas_id', $kelasId)))
            ->get();

        $filename = 'nilai-' . $user->id . '-' . now()->format('Ymd') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($practicalScores, $assignmentScores) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Jenis', 'Judul', 'Mata Pelajaran', 'Nilai', 'Grade', 'Tanggal']);

            foreach ($practicalScores as $s) {
                $score = (float)($s->score ?? 0);
                $grade = $score >= 90 ? 'A' : ($score >= 80 ? 'B' : ($score >= 70 ? 'C' : ($score >= 60 ? 'D' : 'E')));
                fputcsv($handle, [
                    'Praktikum', $s->practical?->title ?? '—',
                    $s->practical?->subject?->name ?? '—',
                    $score, $grade,
                    $s->graded_at ? \Carbon\Carbon::parse($s->graded_at)->format('d/m/Y') : ($s->created_at?->format('d/m/Y') ?? '—'),
                ]);
            }

            foreach ($assignmentScores as $s) {
                $score = (float)($s->score ?? 0);
                $grade = $score >= 90 ? 'A' : ($score >= 80 ? 'B' : ($score >= 70 ? 'C' : ($score >= 60 ? 'D' : 'E')));
                fputcsv($handle, [
                    'Tugas', $s->assignment?->title ?? '—',
                    $s->assignment?->subject?->nama ?? $s->assignment?->subject?->name ?? '—',
                    $score, $grade,
                    $s->updated_at?->format('d/m/Y') ?? '—',
                ]);
            }
            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    public function getChartData(): JsonResponse
    {
        $user    = Auth::user();
        $siswaId = $user->id;
        $kelasId = $user->siswa?->kelas_id;

        $practicalData = NilaiPraktik::where('siswa_id', $siswaId)
            ->when($kelasId, fn($q) => $q->whereHas('practical', fn($s) => $s->where('kelas_id', $kelasId)))
            ->selectRaw('DATE(created_at) as date, AVG(score) as average_score')
            ->groupBy('date')->orderBy('date')->get();

        $assignmentData = AssignmentSubmission::where('student_id', $siswaId)
            ->whereNotNull('score')
            ->when($kelasId, fn($q) => $q->whereHas('assignment', fn($s) => $s->where('kelas_id', $kelasId)))
            ->selectRaw('DATE(created_at) as date, AVG(score) as average_score')
            ->groupBy('date')->orderBy('date')->get();

        return response()->json([
            'practical_scores'  => $practicalData,
            'assignment_scores' => $assignmentData,
        ]);
    }

    protected function calculateStats(int $siswaId, ?int $kelasId): array
    {
        $practicals = NilaiPraktik::where('siswa_id', $siswaId)
            ->when($kelasId, fn($q) => $q->whereHas('practical', fn($s) => $s->where('kelas_id', $kelasId)))
            ->get();

        $assignments = AssignmentSubmission::where('student_id', $siswaId)
            ->whereNotNull('score')
            ->when($kelasId, fn($q) => $q->whereHas('assignment', fn($s) => $s->where('kelas_id', $kelasId)))
            ->get();

        $practicalAvg  = (float)($practicals->avg('score') ?? 0);
        $assignmentAvg = (float)($assignments->avg('score') ?? 0);
        $totalCount    = $practicals->count() + $assignments->count();
        $overallAvg    = $totalCount > 0
            ? (($practicalAvg * $practicals->count()) + ($assignmentAvg * $assignments->count())) / $totalCount
            : 0;

        return [
            'practical_avg'            => round($practicalAvg, 1),
            'assignment_avg'           => round($assignmentAvg, 1),
            'overall_avg'              => round($overallAvg, 1),
            'total_practical_scores'   => $practicals->count(),
            'total_graded_assignments' => $assignments->count(),
        ];
    }
}
