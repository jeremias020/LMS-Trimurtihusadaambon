<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePenilaianRequest;
use App\Http\Requests\UpdatePenilaianRequest;
use App\Models\NilaiPraktik;
use App\Models\PracticalSubmission;
use App\Models\AssignmentSubmission;
use App\Models\Subject;
use App\Models\Kelas;
use App\Models\Assignment;
use App\Models\Practical;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

// Include the trait
require_once __DIR__ . '/../../../PenilaianWithCriteriaTrait.php';

class PenilaianController extends Controller
{
    use PenilaianWithCriteriaTrait;
    
    /**
     * Display a listing of the assessments.
     */
    public function index(): View
    {
        $guruId = Auth::id();
        
        // Get all assessments for this guru
        $assignmentSubmissions = AssignmentSubmission::whereHas('assignment', function($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->with(['assignment.subject', 'siswa.kelas'])
            ->latest()
            ->get();
        
        // Get all nilai praktik records
        $nilaiPraktiks = NilaiPraktik::where('guru_id', $guruId)
            ->with(['practical.subject', 'siswa.kelas'])
            ->latest('tanggal_penilaian')
            ->get();
        
        // Combine and sort all assessments
        $allAssessments = collect()
            ->merge($assignmentSubmissions)
            ->merge($nilaiPraktiks)
            ->sortByDesc(function($assessment) {
                return $assessment->updated_at ?? $assessment->tanggal_penilaian ?? $assessment->created_at;
            });
        
        return view('guru.penilaian.index', compact('allAssessments'));
    }

    /**
     * Show the form for creating a new assessment.
     */
    public function create(): View
    {
        $guruId = Auth::id();
        
        // Get active subjects taught by this guru
        $subjects = Subject::where('is_active', true)->get();
        
        // Get active classes
        $classes = Kelas::where('status', 'active')->get();
        
        // Get assignments and practicals by this guru
        $assignments = Assignment::where('guru_id', $guruId)
            ->where('is_published', true)
            ->with('subject')
            ->latest()
            ->get();
            
        $practicals = Practical::where('guru_id', $guruId)
            ->where('is_published', true)
            ->with('subject')
            ->latest()
            ->get();
        
        // Get students
        $students = User::where('role', 'siswa')
            ->where('status', 'active')
            ->with('kelas')
            ->orderBy('name')
            ->get();
        
        return view('guru.penilaian.create', compact('subjects', 'classes', 'assignments', 'practicals', 'students'));
    }

    /**
     * Store a newly created assessment.
     */
    public function store(Request $request): RedirectResponse
    {
        $guruId = Auth::id();
        
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:assignment,practical',
            'assignment_id' => 'required_if:type,assignment|exists:assignments,id',
            'practical_id' => 'required_if:type,practical|exists:practicals,id',
            'siswa_id' => 'required|exists:users,id',
            'score' => 'nullable|numeric|min:0|max:1000',
            'feedback' => 'nullable|string|max:1000',
            'assessment_date' => 'required|date|before_or_equal:today',
            'kriteria_nilai.*' => 'required_if:type,practical|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            if ($request->type === 'assignment') {
                // Verify guru owns assignment
                $assignment = Assignment::where('id', $request->assignment_id)
                    ->where('guru_id', $guruId)
                    ->firstOrFail();
                
                // Create or update assignment submission
                $submission = AssignmentSubmission::updateOrCreate(
                    [
                        'assignment_id' => $request->assignment_id,
                        'siswa_id' => $request->siswa_id,
                    ],
                    [
                        'score' => $request->score,
                        'feedback' => $request->feedback,
                        'graded_at' => now(),
                        'graded_by' => $guruId,
                        'submitted_at' => $request->assessment_date,
                    ]
                );
                
                $message = 'Penilaian tugas berhasil disimpan!';
                
            } else {
                // Verify guru owns practical
                $practical = Practical::where('id', $request->practical_id)
                    ->where('guru_id', $guruId)
                    ->firstOrFail();
                
                if (class_exists(PracticalSubmission::class)) {
                    // Ambil kriteria penilaian dari admin berdasarkan mata praktik
                    $kriteriaPenilaian = \App\Models\KriteriaPenilaian::where('mata_praktik', $practical->subject->name)
                        ->where('tingkat_kelas', $practical->tingkat_kelas)
                        ->orderBy('kategori')
                        ->get();
                    
                    // Hitung nilai berdasarkan kriteria dan bobot
                    $totalNilai = 0;
                    $totalBobot = 0;
                    
                    if ($request->has('kriteria_nilai') && is_array($request->kriteria_nilai)) {
                        foreach ($kriteriaPenilaian as $kriteria) {
                            $nilaiKriteria = $request->kriteria_nilai[$kriteria->id] ?? 0;
                            $totalNilai += $nilaiKriteria * $kriteria->bobot;
                            $totalBobot += $kriteria->bobot;
                        }
                        
                        // Normalisasi nilai jika total bobot tidak 100%
                        if ($totalBobot > 0) {
                            $totalNilai = ($totalNilai / $totalBobot) * 100;
                        }
                    } else {
                        // Fallback ke manual score
                        $totalNilai = $request->score ?? 0;
                    }
                    
                    // Create or update practical submission
                    $submission = PracticalSubmission::updateOrCreate(
                        [
                            'practical_id' => $request->practical_id,
                            'siswa_id' => $request->siswa_id,
                        ],
                        [
                            'guru_id' => $guruId,
                            'nilai' => $totalNilai,
                            'feedback' => $request->feedback,
                            'kriteria_nilai' => $request->has('kriteria_nilai') ? json_encode($request->kriteria_nilai) : null,
                            'tanggal_penilaian' => $request->assessment_date,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                    
                    $message = 'Penilaian praktikum berhasil disimpan! Nilai: ' . number_format($totalNilai, 1);
                    
                } else {
                    // Fallback to NilaiPraktik model
                    $nilai = NilaiPraktik::updateOrCreate(
                        [
                            'practical_id' => $request->practical_id,
                            'siswa_id' => $request->siswa_id,
                        ],
                        [
                            'guru_id' => $guruId,
                            'nilai' => $request->score ?? 0,
                            'feedback' => $request->feedback,
                            'tanggal_penilaian' => $request->assessment_date,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                    
                    $message = 'Penilaian praktikum berhasil disimpan!';
                }
            }
            
            Log::info('Assessment saved', [
                'guru_id' => $guruId,
                'type' => $request->type,
                'siswa_id' => $request->siswa_id,
                'score' => $request->score ?? $totalNilai ?? 0,
                'assessment_date' => $request->assessment_date,
            ]);
            
            return redirect()
                ->route('guru.penilaian.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Failed to save assessment', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);
            
            return back()
                ->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified assessment.
     */
    public function edit($id): View
    {
        // Find assessment by ID (could be assignment submission or practical submission)
        $assessment = AssignmentSubmission::find($id) ?? PracticalSubmission::find($id) ?? NilaiPraktik::find($id);
        
        if (!$assessment) {
            abort(404);
        }
        
        // Verify ownership
        $guruId = Auth::id();
        if ($assessment instanceof AssignmentSubmission) {
            if ($assessment->assignment->guru_id !== $guruId) {
                abort(403);
            }
        } elseif ($assessment instanceof PracticalSubmission) {
            if ($assessment->practical->guru_id !== $guruId) {
                abort(403);
            }
        } elseif ($assessment instanceof NilaiPraktik) {
            if ($assessment->guru_id !== $guruId) {
                abort(403);
            }
        }
        
        // Get data for form
        $subjects = Subject::where('is_active', true)->get();
        $classes = Kelas::where('status', 'active')->get();
        $students = User::where('role', 'siswa')
            ->where('status', 'active')
            ->with('kelas')
            ->orderBy('name')
            ->get();
        
        $assignments = Assignment::where('guru_id', $guruId)
            ->where('is_published', true)
            ->with('subject')
            ->latest()
            ->get();
            
        $practicals = Practical::where('guru_id', $guruId)
            ->where('is_published', true)
            ->with('subject')
            ->latest()
            ->get();
        
        return view('guru.penilaian.edit', compact('assessment', 'subjects', 'classes', 'students', 'assignments', 'practicals'));
    }

    /**
     * Update the specified assessment in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $guruId = Auth::id();
        
        // Find assessment
        $assessment = AssignmentSubmission::find($id) ?? PracticalSubmission::find($id) ?? NilaiPraktik::find($id);
        
        if (!$assessment) {
            abort(404);
        }
        
        // Verify ownership
        if ($assessment instanceof AssignmentSubmission) {
            if ($assessment->assignment->guru_id !== $guruId) {
                abort(403);
            }
            
            $assessment->update([
                'score' => $request->score,
                'feedback' => $request->feedback,
                'graded_at' => now(),
                'graded_by' => $guruId,
            ]);
            
        } elseif ($assessment instanceof PracticalSubmission) {
            if ($assessment->practical->guru_id !== $guruId) {
                abort(403);
            }
            
            $assessment->update([
                'nilai' => $request->score,
                'feedback' => $request->feedback,
                'tanggal_penilaian' => $request->assessment_date,
            ]);
            
        } elseif ($assessment instanceof NilaiPraktik) {
            if ($assessment->guru_id !== $guruId) {
                abort(403);
            }
            
            $assessment->update([
                'nilai' => $request->score,
                'feedback' => $request->feedback,
                'tanggal_penilaian' => $request->assessment_date,
            ]);
        }
        
        return redirect()
            ->route('guru.penilaian.index')
            ->with('success', 'Penilaian berhasil diperbarui!');
    }

    /**
     * Remove the specified assessment from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $guruId = Auth::id();
        
        // Find assessment
        $assessment = AssignmentSubmission::find($id) ?? PracticalSubmission::find($id) ?? NilaiPraktik::find($id);
        
        if (!$assessment) {
            abort(404);
        }
        
        // Verify ownership
        if ($assessment instanceof AssignmentSubmission) {
            if ($assessment->assignment->guru_id !== $guruId) {
                abort(403);
            }
        } elseif ($assessment instanceof PracticalSubmission) {
            if ($assessment->practical->guru_id !== $guruId) {
                abort(403);
            }
        } elseif ($assessment instanceof NilaiPraktik) {
            if ($assessment->guru_id !== $guruId) {
                abort(403);
            }
        }
        
        $assessment->delete();
        
        return redirect()
            ->route('guru.penilaian.index')
            ->with('success', 'Penilaian berhasil dihapus!');
    }

    /**
     * Show auto assessment page.
     */
    public function autoAssessment(): View
    {
        $guruId = Auth::id();
        
        // Get data
        $subjects = Subject::where('is_active', true)->get();
        $classes = Kelas::where('status', 'active')->get();
        $students = User::where('role', 'siswa')
            ->where('status', 'active')
            ->with('kelas')
            ->orderBy('name')
            ->get();
        
        $assignments = Assignment::where('guru_id', $guruId)
            ->where('is_published', true)
            ->with('subject')
            ->latest()
            ->get();
            
        $practicals = Practical::where('guru_id', $guruId)
            ->where('is_published', true)
            ->with('subject')
            ->latest()
            ->get();
        
        return view('guru.penilaian.auto', compact('subjects', 'classes', 'students', 'assignments', 'practicals'));
    }

    /**
     * Save auto assessment.
     */
    public function saveAutoAssessment(Request $request): RedirectResponse
    {
        $guruId = Auth::id();
        
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:users,id',
            'practical_id' => 'required|exists:practicals,id',
            'kriteria_nilai' => 'required|array',
            'feedback' => 'required|string|max:2000',
            'assessment_date' => 'required|date|before_or_equal:today',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Verify guru owns practical
            $practical = Practical::where('id', $request->practical_id)
                ->where('guru_id', $guruId)
                ->firstOrFail();

            // Get criteria weights
            $criteriaWeights = [
                'prep_1' => 0.20, 'prep_2' => 0.15, 'prep_3' => 0.15,
                'exec_1' => 0.25, 'exec_2' => 0.20, 'exec_3' => 0.20,
                'result_1' => 0.30, 'result_2' => 0.20,
                'att_1' => 0.15, 'att_2' => 0.20
            ];

            $totalWeightedScore = 0;
            $checkedCriteria = $request->kriteria_nilai ?? [];

            foreach ($criteriaWeights as $criterionId => $weight) {
                if (in_array($criterionId, $checkedCriteria)) {
                    $totalWeightedScore += 100 * $weight;
                }
            }

            // Create assessment record
            if (class_exists(PracticalSubmission::class)) {
                $submission = PracticalSubmission::updateOrCreate(
                    [
                        'practical_id' => $request->practical_id,
                        'siswa_id' => $request->siswa_id,
                    ],
                    [
                        'guru_id' => $guruId,
                        'nilai' => $totalWeightedScore,
                        'feedback' => $request->feedback,
                        'kriteria_nilai' => json_encode($checkedCriteria),
                        'tanggal_penilaian' => $request->assessment_date,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            } else {
                // Fallback to NilaiPraktik
                $nilai = NilaiPraktik::updateOrCreate(
                    [
                        'practical_id' => $request->practical_id,
                        'siswa_id' => $request->siswa_id,
                    ],
                    [
                        'guru_id' => $guruId,
                        'nilai' => $totalWeightedScore,
                        'feedback' => $request->feedback,
                        'kriteria_nilai' => json_encode($checkedCriteria),
                        'tanggal_penilaian' => $request->assessment_date,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            return redirect()
                ->route('guru.penilaian.index')
                ->with('success', 'Penilaian otomatis berhasil disimpan! Nilai: ' . number_format($totalWeightedScore, 1));

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage())
                ->withInput();
        }
    }
}
