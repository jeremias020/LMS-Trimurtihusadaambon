<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SubmissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:guru');
    }

    public function index(Request $request): View
    {
        try {
            $guru = Auth::user();
            
            // Get submissions for this guru's assignments
            $query = AssignmentSubmission::with(['assignment.subject', 'assignment.kelas', 'siswa'])
                ->whereHas('assignment', function($q) use ($guru) {
                    $q->where('guru_id', $guru->id);
                });

            // Filter by class
            if ($request->has('kelas_id')) {
                $query->whereHas('assignment', function($q) use ($request) {
                    $q->where('kelas_id', $request->kelas_id);
                });
            }

            // Filter by subject
            if ($request->has('subject_id')) {
                $query->whereHas('assignment', function($q) use ($request) {
                    $q->where('subject_id', $request->subject_id);
                });
            }

            // Filter by status
            if ($request->has('status')) {
                if ($request->status === 'graded') {
                    $query->whereNotNull('score');
                } elseif ($request->status === 'ungraded') {
                    $query->whereNull('score');
                }
            }

            $allSubmissions = $query->orderBy('created_at', 'desc')->paginate(10);

            // Calculate statistics
            $stats = [
                'total_submissions' => $allSubmissions->total(),
                'pending_grading' => AssignmentSubmission::whereHas('assignment', function($q) use ($guru) {
                    $q->where('guru_id', $guru->id);
                })->whereNull('score')->count(),
                'graded' => AssignmentSubmission::whereHas('assignment', function($q) use ($guru) {
                    $q->where('guru_id', $guru->id);
                })->whereNotNull('score')->count(),
                'average_score' => round(AssignmentSubmission::whereHas('assignment', function($q) use ($guru) {
                    $q->where('guru_id', $guru->id);
                })->whereNotNull('score')->avg('score') ?? 0, 1)
            ];

            // Get filter options
            $kelas = Kelas::where('status', 'active')
                ->whereHas('guru', function($q) use ($guru) {
                    $q->where('user_id', $guru->id);
                })
                ->orderBy('name')
                ->pluck('name', 'id');

            return view('guru.submissions.index', compact('allSubmissions', 'kelas', 'stats'));
            
        } catch (\Exception $e) {
            \Log::error('Error in submissions index: ' . $e->getMessage());
            
            return view('guru.submissions.index', [
                'allSubmissions' => collect(),
                'kelas' => collect(),
                'stats' => [
                    'total_submissions' => 0,
                    'pending_grading' => 0,
                    'graded' => 0,
                    'average_score' => 0
                ],
                'error' => 'Terjadi kesalahan saat memuat data submissions.'
            ]);
        }
    }

    public function show(AssignmentSubmission $submission): View
    {
        try {
            $this->authorizeSubmission($submission);
            
            $submission->load(['assignment.subject', 'assignment.kelas', 'siswa']);
            
            return view('guru.submissions.show', compact('submission'));
            
        } catch (\Exception $e) {
            \Log::error('Error in submission show: ' . $e->getMessage());
            
            return redirect()
                ->route('guru.submissions.index')
                ->with('error', 'Submission tidak ditemukan atau terjadi kesalahan.');
        }
    }

    public function grade(Request $request, AssignmentSubmission $submission)
    {
        try {
            $this->authorizeSubmission($submission);
            
            $request->validate([
                'score' => 'required|numeric|min:0|max:100',
                'feedback' => 'nullable|string|max:1000'
            ]);

            $submission->update([
                'score' => $request->score,
                'feedback' => $request->feedback,
                'status' => 'graded'
            ]);

            return redirect()
                ->route('guru.submissions.show', $submission->id)
                ->with('success', 'Submission berhasil dinilai');
                
        } catch (\Exception $e) {
            \Log::error('Error in submission grade: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memberikan nilai.');
        }
    }

    private function authorizeSubmission(AssignmentSubmission $submission): void
    {
        // Check if the guru has access to this submission
        $guru = Auth::user();
        
        if (!$submission->assignment || 
            $submission->assignment->guru_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke submission ini.');
        }
    }
}
