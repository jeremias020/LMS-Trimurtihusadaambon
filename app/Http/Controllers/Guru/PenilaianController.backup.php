<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use App\Models\PracticalSubmission;
use App\Models\Assignment;
use App\Models\Practical;
use App\Models\Subject;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PenilaianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:guru');
    }

    /**
     * Generate feedback otomatis berdasarkan nilai dan detail penilaian
     */
    private function generateFeedback(float $nilaiAkhir, array $detailPenilaian): string
    {
        $feedback = "Hasil Penilaian Praktik:\n";
        $feedback .= "Nilai Akhir: " . round($nilaiAkhir, 2) . "\n\n";
        
        // Kelompokkan berdasarkan kategori
        $kategoriNilai = [];
        foreach ($detailPenilaian as $detail) {
            $kategori = $detail['kategori'];
            if (!isset($kategoriNilai[$kategori])) {
                $kategoriNilai[$kategori] = [
                    'total_nilai' => 0,
                    'total_bobot' => 0,
                    'items' => []
                ];
            }
            
            $kategoriNilai[$kategori]['total_nilai'] += $detail['nilai_terbobot'];
            $kategoriNilai[$kategori]['total_bobot'] += $detail['bobot'];
            $kategoriNilai[$kategori]['items'][] = $detail;
        }
        
        // Tambahkan detail per kategori
        foreach ($kategoriNilai as $kategori => $data) {
            $nilaiKategori = ($data['total_bobot'] > 0) ? ($data['total_nilai'] / $data['total_bobot']) * 100 : 0;
            $feedback .= "Kategori " . ucfirst($kategori) . ": " . round($nilaiKategori, 2) . "\n";
            
            foreach ($data['items'] as $item) {
                $feedback .= "- " . $item['nama_kriteria'] . ": " . $item['nilai'] . "\n";
            }
            
            // Tambahkan masukan berdasarkan nilai kategori
            $feedback .= $this->getMasukanKategori($kategori, $nilaiKategori) . "\n\n";
        }
        
        // Tambahkan masukan keseluruhan
        $feedback .= "Kesimpulan: " . $this->getMasukanKeseluruhan($nilaiAkhir);
        
        return $feedback;
    }
    
    /**
     * Mendapatkan masukan berdasarkan kategori dan nilai
     */
    private function getMasukanKategori(string $kategori, float $nilai): string
    {
        $masukan = "Masukan untuk " . ucfirst($kategori) . ": ";
        
        if ($kategori == 'persiapan') {
            if ($nilai >= 90) {
                $masukan .= "Persiapan sangat baik dan lengkap.";
            } elseif ($nilai >= 75) {
                $masukan .= "Persiapan sudah baik, namun masih bisa ditingkatkan.";
            } else {
                $masukan .= "Perlu meningkatkan persiapan sebelum praktik.";
            }
        } elseif ($kategori == 'pelaksanaan') {
            if ($nilai >= 90) {
                $masukan .= "Pelaksanaan praktik sangat baik dan sesuai prosedur.";
            } elseif ($nilai >= 75) {
                $masukan .= "Pelaksanaan praktik cukup baik, namun perlu lebih teliti.";
            } else {
                $masukan .= "Perlu meningkatkan keterampilan dalam pelaksanaan praktik.";
            }
        } elseif ($kategori == 'hasil') {
            if ($nilai >= 90) {
                $masukan .= "Hasil praktik sangat baik dan sesuai standar.";
            } elseif ($nilai >= 75) {
                $masukan .= "Hasil praktik cukup baik, namun masih ada yang perlu diperbaiki.";
            } else {
                $masukan .= "Hasil praktik perlu ditingkatkan dengan latihan lebih banyak.";
            }
        } elseif ($kategori == 'sikap') {
            if ($nilai >= 90) {
                $masukan .= "Sikap selama praktik sangat baik dan profesional.";
            } elseif ($nilai >= 75) {
                $masukan .= "Sikap selama praktik cukup baik, namun perlu lebih disiplin.";
            } else {
                $masukan .= "Perlu meningkatkan sikap dan kedisiplinan selama praktik.";
            }
        }
        
        return $masukan;
    }
    
    /**
     * Mendapatkan masukan keseluruhan berdasarkan nilai akhir
     */
    private function getMasukanKeseluruhan(float $nilai): string
    {
        if ($nilai >= 90) {
            return "Praktik dilaksanakan dengan sangat baik. Pertahankan kualitas kerja ini.";
        } elseif ($nilai >= 80) {
            return "Praktik dilaksanakan dengan baik. Tingkatkan aspek yang masih kurang untuk hasil yang lebih baik.";
        } elseif ($nilai >= 70) {
            return "Praktik cukup baik. Perhatikan masukan per kategori untuk meningkatkan keterampilan.";
        } elseif ($nilai >= 60) {
            return "Praktik perlu ditingkatkan. Lakukan latihan lebih banyak dan perhatikan prosedur dengan teliti.";
        } else {
            return "Praktik masih kurang. Diperlukan bimbingan lebih lanjut dan latihan intensif.";
        }
    }

    /**
     * Display a listing of items to grade.
     */
    public function index(Request $request): View
    {
        $guruId = Auth::id();
        $assignmentId = $request->get('assignment_id');
        
        if ($assignmentId) {
            // Show submissions for specific assignment
            $assignment = Assignment::where('id', $assignmentId)
                ->where('guru_id', $guruId)
                ->firstOrFail();
            
            $submissions = AssignmentSubmission::with(['siswa', 'assignment'])
                ->where('assignment_id', $assignmentId)
                ->latest()
                ->paginate(15);
                
            return view('guru.penilaian.assignment', compact('assignment', 'submissions'));
        }

        // Show all pending submissions
        $assignmentSubmissions = AssignmentSubmission::with(['siswa.kelas', 'assignment.subject'])
            ->whereHas('assignment', function($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->whereNull('score')
            ->latest('submitted_at')
            ->limit(20)
            ->get();

        $practicalSubmissions = collect();
        if (class_exists(PracticalSubmission::class)) {
            $practicalSubmissions = PracticalSubmission::with(['siswa.kelas', 'practical.subject'])
                ->whereHas('practical', function($query) use ($guruId) {
                    $query->where('guru_id', $guruId);
                })
                ->whereNull('score')
                ->latest('submitted_at')
                ->limit(20)
                ->get();
        }

        $allSubmissions = $assignmentSubmissions
            ->merge($practicalSubmissions)
            ->sortByDesc('submitted_at');

        // Get subjects for filter dropdown
        $subjects = Subject::where('is_active', true)->get();
        
        // Transform submissions to match view expectations (as assessments)
        $assessments = collect();
        
        // Transform assignment submissions
        foreach ($assignmentSubmissions as $submission) {
            $assessments->push((object) [
                'id' => $submission->id,
                'type' => 'assignment',
                'student' => $submission->siswa,
                'subject' => $submission->assignment->subject ?? null,
                'subject_id' => $submission->assignment->subject_id ?? null,
                'class' => $submission->siswa->kelas->name ?? 'Tidak diketahui',
                'activity' => $submission->assignment,
                'score' => $submission->score,
                'max_score' => $submission->assignment->max_score ?? 100,
                'assessment_date' => $submission->submitted_at ?? $submission->created_at,
                'created_at' => $submission->created_at
            ]);
        }
        
        // Transform practical submissions
        foreach ($practicalSubmissions as $submission) {
            $assessments->push((object) [
                'id' => $submission->id,
                'type' => 'practical',
                'student' => $submission->siswa,
                'subject' => $submission->practical->subject ?? null,
                'subject_id' => $submission->practical->subject_id ?? null,
                'class' => $submission->siswa->kelas->name ?? 'Tidak diketahui',
                'activity' => $submission->practical,
                'score' => $submission->score,
                'max_score' => $submission->practical->max_score ?? 100,
                'assessment_date' => $submission->submitted_at ?? $submission->created_at,
                'created_at' => $submission->created_at
            ]);
        }
        
        // Sort by assessment date
        $assessments = $assessments->sortByDesc('assessment_date');

        // Debug logging
        \Log::info('Penilaian Debug', [
            'assignment_submissions_count' => $assignmentSubmissions->count(),
            'practical_submissions_count' => $practicalSubmissions->count(),
            'total_assessments' => $assessments->count(),
            'guru_id' => $guruId,
            'has_assignment_submissions_table' => \Schema::hasTable('assignment_submissions'),
            'has_practical_submissions_table' => \Schema::hasTable('practical_submissions'),
            'total_assignment_submissions_in_db' => \DB::table('assignment_submissions')->count(),
            'total_practical_submissions_in_db' => \DB::table('practical_submissions')->count(),
            'ungraded_assignments_in_db' => \DB::table('assignment_submissions')->whereNull('score')->count(),
            'ungraded_practicals_in_db' => \DB::table('practical_submissions')->whereNull('score')->count(),
        ]);

        return view('guru.penilaian.index', compact('allSubmissions', 'assessments', 'subjects'));
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
                // Verify guru owns the assignment
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
                // Verify guru owns the practical
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
                    $detailPenilaian = [];
                    
                    foreach ($kriteriaPenilaian as $kriteria) {
                        $kriteriaId = $kriteria->id;
                        $nilai = $request->input("kriteria_nilai.{$kriteriaId}", 0);
                        $bobot = $kriteria->bobot;
                        
                        $nilaiTerbobot = $nilai * $bobot;
                        $totalNilai += $nilaiTerbobot;
                        $totalBobot += $bobot;
                        
                        $detailPenilaian[$kriteriaId] = [
                            'kriteria_id' => $kriteriaId,
                            'nama_kriteria' => $kriteria->nama,
                            'kategori' => $kriteria->kategori,
                            'nilai' => $nilai,
                            'bobot' => $bobot,
                            'nilai_terbobot' => $nilaiTerbobot
                        ];
                    }
                    
                    // Hitung nilai akhir (skala 100)
                    $nilaiAkhir = ($totalBobot > 0) ? ($totalNilai / $totalBobot) * 100 : 0;
                    
                    // Generate feedback otomatis berdasarkan nilai
                    $feedbackOtomatis = $this->generateFeedback($nilaiAkhir, $detailPenilaian);
                    $combinedFeedback = $request->feedback ? $request->feedback . "\n\n" . $feedbackOtomatis : $feedbackOtomatis;
                    
                    // Create or update practical submission
                    $submission = PracticalSubmission::updateOrCreate(
                        [
                            'practical_id' => $request->practical_id,
                            'siswa_id' => $request->siswa_id,
                        ],
                        [
                            'score' => round($nilaiAkhir, 2),
                            'feedback' => $combinedFeedback,
                            'graded_at' => now(),
                            'graded_by' => $guruId,
                            'submitted_at' => $request->assessment_date,
                            'detail_penilaian' => json_encode($detailPenilaian)
                        ]
                    );
                    
                    // Simpan detail nilai per kriteria
                    foreach ($detailPenilaian as $detail) {
                        \App\Models\NilaiPraktik::updateOrCreate(
                            [
                                'submission_id' => $submission->id,
                                'kriteria_id' => $detail['kriteria_id']
                            ],
                            [
                                'nilai' => $detail['nilai'],
                                'nilai_terbobot' => $detail['nilai_terbobot']
                            ]
                        );
                    }
                } else {
                    return back()->with('error', 'Fitur penilaian praktikum belum tersedia.');
                }
                
                $message = 'Penilaian praktikum berhasil disimpan!';
            }

            Log::info('Assessment created manually', [
                'submission_id' => $submission->id,
                'type' => $request->type,
                'score' => $request->score,
                'guru_id' => $guruId,
                'ip' => $request->ip()
            ]);

            return redirect()->route('guru.penilaian.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Manual assessment creation failed: ' . $e->getMessage(), [
                'type' => $request->type,
                'guru_id' => $guruId,
                'ip' => $request->ip()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified submission.
     */
    public function edit($id): View
    {
        $guruId = Auth::id();
        
        // Try to find in assignment submissions first
        $submission = AssignmentSubmission::with(['siswa', 'assignment'])
            ->whereHas('assignment', function($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->find($id);

        if ($submission) {
            return view('guru.penilaian.edit', compact('submission'));
        }

        // Try to find in practical submissions
        if (class_exists(PracticalSubmission::class)) {
            $submission = PracticalSubmission::with(['siswa', 'practical'])
                ->whereHas('practical', function($query) use ($guruId) {
                    $query->where('guru_id', $guruId);
                })
                ->find($id);

            if ($submission) {
                return view('guru.penilaian.edit-practical', compact('submission'));
            }
        }

        abort(404, 'Submission tidak ditemukan');
    }

    /**
     * Update the specified submission grade.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $guruId = Auth::id();
        
        // Try to find in assignment submissions first
        $submission = AssignmentSubmission::with(['assignment'])
            ->whereHas('assignment', function($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->find($id);

        if ($submission) {
            return $this->updateAssignmentSubmission($request, $submission);
        }

        // Try to find in practical submissions
        if (class_exists(PracticalSubmission::class)) {
            $submission = PracticalSubmission::with(['practical'])
                ->whereHas('practical', function($query) use ($guruId) {
                    $query->where('guru_id', $guruId);
                })
                ->find($id);

            if ($submission) {
                return $this->updatePracticalSubmission($request, $submission);
            }
        }

        abort(404, 'Submission tidak ditemukan');
    }

    /**
     * Update assignment submission grade.
     */
    private function updateAssignmentSubmission(Request $request, AssignmentSubmission $submission): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'score' => 'required|numeric|min:0|max:' . $submission->assignment->max_score,
            'feedback' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $submission->update([
                'score' => $request->score,
                'feedback' => $request->feedback,
                'graded_at' => now(),
                'graded_by' => Auth::id(),
            ]);

            $submission->assignment->touch();

            Log::info('Assignment submission graded', [
                'submission_id' => $submission->id,
                'assignment_id' => $submission->assignment->id,
                'score' => $request->score,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->route('guru.penilaian.index')
                ->with('success', 'Nilai tugas berhasil disimpan!');

        } catch (\Exception $e) {
            Log::error('Assignment submission grading failed: ' . $e->getMessage(), [
                'submission_id' => $submission->id,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified assessment.
     */
    public function destroy($id): RedirectResponse
    {
        $guruId = Auth::id();
        
        // Try to find in assignment submissions first
        $submission = AssignmentSubmission::with(['assignment'])
            ->whereHas('assignment', function($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->find($id);

        if ($submission) {
            try {
                Log::info('Assignment submission deleted', [
                    'submission_id' => $submission->id,
                    'assignment_id' => $submission->assignment->id,
                    'guru_id' => $guruId
                ]);
                
                $submission->delete();
                
                return redirect()->route('guru.penilaian.index')
                    ->with('success', 'Penilaian tugas berhasil dihapus!');
                    
            } catch (\Exception $e) {
                Log::error('Assignment submission deletion failed: ' . $e->getMessage());
                return back()->with('error', 'Gagal menghapus penilaian.');
            }
        }

        // Try to find in practical submissions
        if (class_exists(PracticalSubmission::class)) {
            $submission = PracticalSubmission::with(['practical'])
                ->whereHas('practical', function($query) use ($guruId) {
                    $query->where('guru_id', $guruId);
                })
                ->find($id);

            if ($submission) {
                try {
                    Log::info('Practical submission deleted', [
                        'submission_id' => $submission->id,
                        'practical_id' => $submission->practical->id,
                        'guru_id' => $guruId
                    ]);
                    
                    $submission->delete();
                    
                    return redirect()->route('guru.penilaian.index')
                        ->with('success', 'Penilaian praktikum berhasil dihapus!');
                        
                } catch (\Exception $e) {
                    Log::error('Practical submission deletion failed: ' . $e->getMessage());
                    return back()->with('error', 'Gagal menghapus penilaian.');
                }
            }
        }

        return back()->with('error', 'Penilaian tidak ditemukan.');
    }

    /**
     * Show auto assessment form for practical.
     */
    public function autoAssessment(): View
    {
        $guruId = Auth::id();
        
        // Get students
        $students = User::where('role', 'siswa')
            ->where('status', 'active')
            ->with('kelas')
            ->orderBy('name')
            ->get();
        
        // Get practicals
        $practicals = Practical::where('guru_id', $guruId)
            ->where('is_published', true)
            ->with('subject')
            ->latest()
            ->get();
        
        return view('guru.penilaian.auto', compact('students', 'practicals'));
    }

    /**
     * Save auto assessment for practical.
     */
    public function saveAutoAssessment(Request $request): JsonResponse
    {
        $guruId = Auth::id();
        
        try {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|exists:users,id',
                'practical_id' => 'required|exists:practicals,id',
                'score' => 'required|numeric|min:0|max:100',
                'criteria' => 'required|array',
                'criteria.*.id' => 'required|integer',
                'criteria.*.nilai' => 'required|integer|min:0|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . $validator->errors()->first()
                ], 422);
            }

            // Verify ownership
            $practical = Practical::where('id', $request->practical_id)
                ->where('guru_id', $guruId)
                ->firstOrFail();

            // Create or update practical submission
            $submission = PracticalSubmission::updateOrCreate(
                [
                    'practical_id' => $request->practical_id,
                    'siswa_id' => $request->student_id,
                ],
                [
                    'score' => $request->score,
                    'feedback' => $this->generateAutoFeedback($request->criteria),
                    'graded_at' => now(),
                    'graded_by' => $guruId,
                    'submitted_at' => now(),
                    'detail_penilaian' => json_encode($request->criteria)
                ]
            );

            // Save individual criteria scores
            foreach ($request->criteria as $criteria) {
                \App\Models\NilaiPraktik::updateOrCreate(
                    [
                        'submission_id' => $submission->id,
                        'kriteria_id' => $criteria['id'],
                    ],
                    [
                        'nilai' => $criteria['nilai'],
                        'nilai_terbobot' => $criteria['nilai'] * 0.01 // Assuming bobot is in percentage
                    ]
                );
            }

            Log::info('Auto practical assessment saved', [
                'submission_id' => $submission->id,
                'practical_id' => $request->practical_id,
                'student_id' => $request->student_id,
                'score' => $request->score,
                'guru_id' => $guruId,
                'criteria_count' => count($request->criteria)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Penilaian praktik otomatis berhasil disimpan!',
                'submission_id' => $submission->id
            ]);

        } catch (\Exception $e) {
            Log::error('Auto practical assessment failed: ' . $e->getMessage(), [
                'practical_id' => $request->practical_id,
                'student_id' => $request->student_id,
                'guru_id' => $guruId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate automatic feedback based on criteria scores.
     */
    private function generateAutoFeedback(array $criteria): string
    {
        $feedback = "Hasil Penilaian Praktik Otomatis:\n\n";
        
        // Group by category
        $categories = [];
        foreach ($criteria as $item) {
            $category = $item['kategori'] ?? 'umum';
            if (!isset($categories[$category])) {
                $categories[$category] = [
                    'total' => 0,
                    'count' => 0,
                    'items' => []
                ];
            }
            
            $categories[$category]['total'] += $item['nilai'];
            $categories[$category]['count']++;
            $categories[$category]['items'][] = $item;
        }
        
        // Add category breakdown
        foreach ($categories as $category => $data) {
            $average = $data['count'] > 0 ? $data['total'] / $data['count'] : 0;
            $categoryName = ucfirst($category);
            
            $feedback .= "Kategori {$categoryName}:\n";
            $feedback .= "- Rata-rata: " . round($average, 1) . "/100\n";
            
            foreach ($data['items'] as $item) {
                $feedback .= "- {$item['nama']}: {$item['nilai']}/100\n";
            }
            $feedback .= "\n";
        }
        
        // Add overall assessment
        $totalAverage = array_sum(array_column($criteria, 'nilai')) / count($criteria);
        $feedback .= "Nilai Akhir: " . round($totalAverage, 1) . "/100\n";
        
        if ($totalAverage >= 90) {
            $feedback .= "Keterangan: Sangat Baik - Praktik dilaksanakan dengan sempurna.";
        } elseif ($totalAverage >= 80) {
            $feedback .= "Keterangan: Baik - Praktik dilaksanakan dengan baik.";
        } elseif ($totalAverage >= 70) {
            $feedback .= "Keterangan: Cukup - Praktik dilaksanakan dengan cukup baik.";
        } elseif ($totalAverage >= 60) {
            $feedback .= "Keterangan: Kurang - Perlu peningkatan dalam praktik.";
        } else {
            $feedback .= "Keterangan: Belum Lulus - Perlu bimbingan lebih intensif.";
        }
        
        return $feedback;
    }

    /**
     * Update practical submission grade.
     */
    private function updatePracticalSubmission(Request $request, $submission): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $submission->update([
                'score' => $request->score,
                'feedback' => $request->feedback,
                'graded_at' => now(),
                'graded_by' => Auth::id(),
            ]);

            $submission->practical->touch();

            Log::info('Practical submission graded', [
                'submission_id' => $submission->id,
                'practical_id' => $submission->practical->id,
                'score' => $request->score,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->route('guru.penilaian.index')
                ->with('success', 'Nilai praktikum berhasil disimpan!');

        } catch (\Exception $e) {
            Log::error('Practical submission grading failed: ' . $e->getMessage(), [
                'submission_id' => $submission->id,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}