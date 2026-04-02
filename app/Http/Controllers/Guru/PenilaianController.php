<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePenilaianRequest;
use App\Http\Requests\UpdatePenilaianRequest;
use App\Models\NilaiPraktik;
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
require_once base_path('app/Traits/PenilaianWithCriteriaTrait.php');

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
            
        // Get all nilai praktik records (show all for now to debug)
        $nilaiPraktiks = NilaiPraktik::with(['siswa', 'guru', 'practical.subject'])
            ->where('graded_by', $guruId)
            ->latest('graded_at')
            ->get();
        
        // Combine and sort all assessments
        $allAssessments = collect()
            ->merge($assignmentSubmissions)
            ->merge($nilaiPraktiks)
            ->sortByDesc(function($assessment) {
                return $assessment->updated_at ?? $assessment->graded_at ?? $assessment->created_at;
            });
        
        // Get active subjects for filter
        $subjects = Subject::where('is_active', true)->get();
        
        return view('guru.penilaian.index', compact('allAssessments', 'subjects'));
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
            ->with('subject')
            ->latest()
            ->get();
            
        $practicals = Practical::where('guru_id', $guruId)
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
                        'status' => 'graded',
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
                
                // Use NilaiPraktik model for practical assessments
                $totalNilai = $request->score ?? 0;
                
                // Create or update practical assessment
                $nilai = NilaiPraktik::updateOrCreate(
                    [
                        'siswa_id' => $request->siswa_id,
                        'mata_praktik' => $practical->judul ?? 'Praktikum',
                        'tanggal_praktik' => $request->assessment_date,
                    ],
                    [
                        'guru_id' => $guruId,
                        'total_nilai' => $totalNilai,
                        'grade' => $this->calculateGrade($totalNilai),
                        'catatan_guru' => $request->feedback,
                        'status' => 'final',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                
                $message = 'Penilaian praktikum berhasil disimpan! Nilai: ' . number_format($totalNilai, 1);
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
        // Find assessment by ID (could be assignment submission or nilai praktik)
        $assessment = AssignmentSubmission::find($id) ?? NilaiPraktik::find($id);
        
        if (!$assessment) {
            abort(404);
        }
        
        // Verify ownership
        $guruId = Auth::id();
        if ($assessment instanceof AssignmentSubmission) {
            if ($assessment->assignment->guru_id !== $guruId) {
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
            ->with('subject')
            ->latest()
            ->get();
            
        $practicals = Practical::where('guru_id', $guruId)
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
        $assessment = AssignmentSubmission::find($id) ?? NilaiPraktik::find($id);
        
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
                'status' => 'graded',
                'graded_by' => $guruId,
            ]);
            
        } elseif ($assessment instanceof NilaiPraktik) {
            if ($assessment->guru_id !== $guruId) {
                abort(403);
            }
            
            $assessment->update([
                'nilai' => $request->score,
                'feedback' => $request->feedback,
                'tanggal_praktik' => $request->assessment_date,
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
        $assessment = AssignmentSubmission::find($id) ?? NilaiPraktik::find($id);
        
        if (!$assessment) {
            abort(404);
        }
        
        // Verify ownership
        if ($assessment instanceof AssignmentSubmission) {
            if ($assessment->assignment->guru_id !== $guruId) {
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
        $subjects = Subject::where('is_active', true)->with('jurusan')->get();
        $classes = Kelas::with('jurusan')->get();
        
        // Get students with proper class relationships
        $students = User::where('role', 'siswa')
            ->where('is_active', true)
            ->with(['siswa.kelas.jurusan'])
            ->orderBy('name')
            ->get();
        
        $assignments = Assignment::where('guru_id', $guruId)
            ->with(['subject.jurusan'])
            ->latest()
            ->get();
            
        $practicals = Practical::where('guru_id', $guruId)
            ->with(['subject.jurusan', 'kelas.jurusan'])
            ->latest()
            ->get();
            
        // If no practicals found, try to get all practicals for testing
        if ($practicals->count() === 0) {
            $practicals = Practical::with(['subject.jurusan', 'kelas.jurusan'])
                ->latest()
                ->get();
        }
        
        return view('guru.penilaian.auto', compact('subjects', 'classes', 'students', 'assignments', 'practicals'));
    }

    /**
     * Show auto assessment with criteria page.
     */
    public function autoWithCriteria(): View
    {
        $guruId = Auth::id();
        
        // Get data
        $subjects = Subject::where('is_active', true)->with('jurusan')->get();
        $classes = Kelas::with('jurusan')->get();
        
        // Get students with proper class relationships
        $students = User::where('role', 'siswa')
            ->where('is_active', true)
            ->with(['siswa.kelas.jurusan'])
            ->orderBy('name')
            ->get();
        
        $assignments = Assignment::where('guru_id', $guruId)
            ->with(['subject.jurusan'])
            ->latest()
            ->get();
            
        $practicals = Practical::where('guru_id', $guruId)
            ->with(['subject.jurusan', 'kelas.jurusan'])
            ->latest()
            ->get();
            
        // If no practicals found, try to get all practicals for testing
        if ($practicals->count() === 0) {
            $practicals = Practical::with(['subject.jurusan', 'kelas.jurusan'])
                ->latest()
                ->get();
        }
        
        return view('guru.penilaian.auto_with_criteria', compact('subjects', 'classes', 'students', 'assignments', 'practicals'));
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

            // Create assessment record using NilaiPraktik
            $nilai = NilaiPraktik::updateOrCreate(
                [
                    'siswa_id' => $request->siswa_id,
                    'mata_praktik' => $practical->judul ?? 'Praktikum',
                    'tanggal_praktik' => $request->assessment_date,
                ],
                [
                    'guru_id' => $guruId,
                    'total_nilai' => $totalWeightedScore,
                    'grade' => $this->calculateGrade($totalWeightedScore),
                    'feedback_otomatis' => $request->feedback,
                    'status' => 'final',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return redirect()
                ->route('guru.penilaian.index')
                ->with('success', 'Penilaian otomatis berhasil disimpan! Nilai: ' . number_format($totalWeightedScore, 1));

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Save auto assessment with criteria.
     */
    public function saveAutoAssessmentWithCriteria(Request $request): RedirectResponse
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

            // Get SOP criteria weights
            $criteriaWeights = [
                'prep_1' => 0.10, 'prep_2' => 0.10, 'prep_3' => 0.15,
                'exec_1' => 0.20, 'exec_2' => 0.15, 'exec_3' => 0.10,
                'eval_1' => 0.15, 'eval_2' => 0.05
            ];

            $totalWeightedScore = 0;
            $checkedCriteria = $request->kriteria_nilai ?? [];

            foreach ($criteriaWeights as $criterionId => $weight) {
                if (in_array($criterionId, $checkedCriteria)) {
                    $totalWeightedScore += 100 * $weight;
                }
            }

            // Create assessment record using NilaiPraktik
            $nilai = NilaiPraktik::updateOrCreate(
                [
                    'siswa_id' => $request->siswa_id,
                    'mata_praktik' => $practical->judul ?? 'Praktikum',
                    'tanggal_praktik' => $request->assessment_date,
                ],
                [
                    'guru_id' => $guruId,
                    'nilai' => $totalWeightedScore,
                    'grade' => $this->calculateGrade($totalWeightedScore),
                    'feedback' => $request->feedback,
                    'status' => 'final',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return redirect()
                ->route('guru.penilaian.index')
                ->with('success', 'Penilaian SOP berhasil disimpan! Nilai: ' . number_format($totalWeightedScore, 1));

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Calculate grade based on score
     */
    public function calculateGrade($score): string
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'E';
    }

    /**
     * Export penilaian data
     */
    public function export(Request $request)
    {
        $guruId = Auth::id();
        $format = $request->get('format', 'excel');
        
        try {
            // Get all assessments for this guru
            $assignmentSubmissions = AssignmentSubmission::whereHas('assignment', function($query) use ($guruId) {
                    $query->where('guru_id', $guruId);
                })
                ->with(['assignment.subject', 'siswa.kelas'])
                ->latest()
                ->get();
                
            $nilaiPraktiks = NilaiPraktik::with(['siswa', 'guru', 'practical.subject'])
                ->where('graded_by', $guruId)
                ->latest('graded_at')
                ->get();
            
            // Combine and sort all assessments
            $allAssessments = collect()
                ->merge($assignmentSubmissions)
                ->merge($nilaiPraktiks)
                ->sortByDesc(function($assessment) {
                    return $assessment->updated_at ?? $assessment->graded_at ?? $assessment->created_at;
                });
            
            // Prepare data for export
            $exportData = $allAssessments->map(function($assessment) {
                $score = $this->getAssessmentScore($assessment);
                
                return [
                    'ID' => $assessment->id,
                    'Tipe' => $assessment->assignment_id ? 'Tugas' : 'Praktikum',
                    'NIS' => $assessment->siswa->nis_nip ?? '-',
                    'Nama Siswa' => $assessment->siswa->name ?? '-',
                    'Email Siswa' => $assessment->siswa->email ?? '-',
                    'Kelas' => $assessment->siswa->kelas->name ?? '-',
                    'Mata Pelajaran' => $assessment->assignment_id 
                        ? ($assessment->assignment->subject->name ?? '-')
                        : ($assessment->practical->subject->name ?? '-'),
                    'Judul' => $assessment->assignment_id 
                        ? $assessment->assignment->title
                        : $assessment->practical->judul,
                    'Nilai' => $score ?? '-',
                    'Grade' => $score ? $this->calculateGrade($score) : '-',
                    'Status' => $score ? 'Sudah Dinilai' : 'Belum Dinilai',
                    'Tanggal' => $assessment->assignment_id 
                        ? ($assessment->assignment->due_date ? $assessment->assignment->due_date->format('d M Y H:i') : '-')
                        : ($assessment->practical->date ? $assessment->practical->date->format('d M Y') : '-'),
                    'Feedback' => $assessment->feedback ?? '-',
                ];
            });
            
            if ($format === 'excel') {
                return $this->exportExcel($exportData);
            } elseif ($format === 'pdf') {
                return $this->exportPdf($exportData);
            } else {
                return back()->with('error', 'Format tidak didukung');
            }
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
    
    /**
     * Export to Excel
     */
    private function exportExcel($data)
    {
        $filename = 'penilaian_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, array_keys($data->first()));
            
            // Add data rows
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export to PDF
     */
    private function exportPdf($data)
    {
        $filename = 'penilaian_' . date('Y-m-d_H-i-s') . '.pdf';
        
        $pdf = \PDF::loadView('guru.penilaian.export-pdf', compact('data'));
        
        return $pdf->download($filename);
    }
    
    /**
     * Helper function to get assessment score
     */
    public function getAssessmentScore($assessment)
    {
        if (isset($assessment->score) && $assessment->score !== null) {
            return (float) $assessment->score;
        }
        if (isset($assessment->total_nilai) && $assessment->total_nilai !== null) {
            return (float) $assessment->total_nilai;
        }
        return null;
    }
}
