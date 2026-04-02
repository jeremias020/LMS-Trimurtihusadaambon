<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PracticalScore;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of scores.
     */
    public function index(): View
    {
        $siswaId = Auth::id();
        $kelasId = Auth::user()->kelas_id ?? null; // gunakan kelas_id sesuai skema

        $practicalScores = PracticalScore::with(['practical', 'criteria'])
            ->where('siswa_id', $siswaId)
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('practical', function($sub) use ($kelasId) {
                    $sub->where('kelas_id', $kelasId);
                });
            })
            ->latest()
            ->paginate(10);

        $assignmentScores = AssignmentSubmission::with(['assignment', 'assignment.guru'])
            ->where('siswa_id', $siswaId)
            ->whereNotNull('score')
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('assignment', function($sub) use ($kelasId) {
                    $sub->where('kelas_id', $kelasId);
                });
            })
            ->latest()
            ->paginate(10);

        $stats = $this->calculateStats($siswaId, $kelasId);

        // Normalize aggregated scores structure expected by the view
        $scores = [];

        return view('siswa.nilai.index', compact('practicalScores', 'assignmentScores', 'stats', 'scores'));
    }

    /**
     * Display the specified practical score.
     */
    public function show($id): View
    {
        $siswaId = Auth::id();
        $kelasId = Auth::user()->kelas_id ?? null;

        $score = PracticalScore::with(['practical', 'criteria'])
            ->where('id', $id)
            ->where('siswa_id', $siswaId)
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('practical', function($sub) use ($kelasId) {
                    $sub->where('kelas_id', $kelasId);
                });
            })
            ->firstOrFail();

        $averageScore = PracticalScore::where('practical_id', $score->practical_id)
            ->avg('score') ?? 0;

        return view('siswa.nilai.show', compact('score', 'averageScore'));
    }

    /**
     * Display practical scores.
     */
    public function practicalScores(): View
    {
        $siswaId = Auth::id();
        $kelasId = Auth::user()->kelas_id ?? null;

        $scores = PracticalScore::with(['practical', 'criteria'])
            ->where('siswa_id', $siswaId)
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('practical', function($sub) use ($kelasId) {
                    $sub->where('kelas_id', $kelasId);
                });
            })
            ->latest()
            ->paginate(15);

        $scoreCollection = $scores->getCollection();
        $stats = [
            'average_score' => $scoreCollection->avg('score') ?? 0,
            'total_scores' => $scoreCollection->count(),
            'highest_score' => $scoreCollection->max('score') ?? 0,
            'lowest_score' => $scoreCollection->min('score') ?? 0,
        ];

        return view('siswa.nilai.practical', compact('scores', 'stats'));
    }

    /**
     * Alias for route siswa.reports.practical
     */
    public function practical(): View
    {
        return $this->practicalScores();
    }

    /**
     * Display assignment scores.
     */
    public function assignmentScores(): View
    {
        $siswaId = Auth::id();
        $kelasId = Auth::user()->kelas_id ?? null;

        $scores = AssignmentSubmission::with(['assignment', 'assignment.guru'])
            ->where('siswa_id', $siswaId)
            ->whereNotNull('score')
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('assignment', function($sub) use ($kelasId) {
                    $sub->where('kelas_id', $kelasId);
                });
            })
            ->latest()
            ->paginate(15);

        $scoreCollection = $scores->getCollection();
        $stats = [
            'average_score' => $scoreCollection->avg('score') ?? 0,
            'total_graded' => $scoreCollection->count(),
            'highest_score' => $scoreCollection->max('score') ?? 0,
            'lowest_score' => $scoreCollection->min('score') ?? 0,
        ];

        return view('siswa.nilai.assignment', compact('scores', 'stats'));
    }

    /**
     * Alias for route siswa.reports.assignment
     */
    public function assignment(): View
    {
        return $this->assignmentScores();
    }

    protected function calculateStats($siswaId, $kelasId)
    {
        $practicalScores = PracticalScore::where('siswa_id', $siswaId)
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('practical', function($sub) use ($kelasId) {
                    $sub->where('kelas_id', $kelasId);
                });
            })
            ->get();

        $assignmentScores = AssignmentSubmission::where('siswa_id', $siswaId)
            ->whereNotNull('score')
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('assignment', function($sub) use ($kelasId) {
                    $sub->where('kelas_id', $kelasId);
                });
            })
            ->get();

        return [
            'practical_avg' => $practicalScores->avg('score') ?? 0,
            'assignment_avg' => $assignmentScores->avg('score') ?? 0,
            'total_practical_scores' => $practicalScores->count(),
            'total_graded_assignments' => $assignmentScores->count(),
            'overall_avg' => $this->calculateOverallAverageFromCollections($practicalScores, $assignmentScores),
        ];
    }

    protected function calculateOverallAverageFromCollections($practicalScores, $assignmentScores)
    {
        $practicalAvg = $practicalScores->avg('score') ?? 0;
        $assignmentAvg = $assignmentScores->avg('score') ?? 0;

        $practicalCount = $practicalScores->count();
        $assignmentCount = $assignmentScores->count();

        $totalCount = $practicalCount + $assignmentCount;

        if ($totalCount === 0) {
            return 0;
        }

        return (($practicalAvg * $practicalCount) + ($assignmentAvg * $assignmentCount)) / $totalCount;
    }

    /**
     * Get chart data for scores (AJAX).
     */
    public function getChartData(): JsonResponse
    {
        $siswaId = Auth::id();
        $kelasId = Auth::user()->kelas_id ?? null;

        $practicalScores = PracticalScore::where('student_id', $siswaId)
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('practical', function($sub) use ($kelasId) {
                    $sub->where('kelas_id', $kelasId);
                });
            })
            ->selectRaw('DATE(created_at) as date, AVG(score) as average_score')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $assignmentScores = AssignmentSubmission::where('student_id', $siswaId)
            ->whereNotNull('score')
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('assignment', function($sub) use ($kelasId) {
                    $sub->where('kelas_id', $kelasId);
                });
            })
            ->selectRaw('DATE(created_at) as date, AVG(score) as average_score')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'practical_scores' => $practicalScores,
            'assignment_scores' => $assignmentScores,
        ]);
    }

    /**
     * Export scores.
     */
    public function exportScores(): View
    {
        $siswaId = Auth::id();
        $kelasId = Auth::user()->kelas_id ?? null;

        $practicalScores = PracticalScore::with(['practical', 'criteria'])
            ->where('siswa_id', $siswaId)
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('practical', function($sub) use ($kelasId) {
                    $sub->where('kelas_id', $kelasId);
                });
            })
            ->get();

        $assignmentScores = AssignmentSubmission::with(['assignment', 'assignment.guru'])
            ->where('siswa_id', $siswaId)
            ->whereNotNull('score')
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('assignment', function($sub) use ($kelasId) {
                    $sub->where('kelas_id', $kelasId);
                });
            })
            ->get();

        Log::info('Scores exported', [
            'siswa_id' => $siswaId,
            'ip' => request()->ip()
        ]);

        return view('siswa.nilai.export', compact('practicalScores', 'assignmentScores'));
    }
}
