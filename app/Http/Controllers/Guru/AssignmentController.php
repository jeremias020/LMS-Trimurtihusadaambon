<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:guru');
    }

    /**
     * Display a listing of assignments.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'active'); // Default ke tab 'active'
        
        // Base query
        $query = Assignment::with(['subject', 'submissions' => function($query) {
            $query->select('assignment_id', 'score', 'submitted_at')
                  ->whereNotNull('score');
        }])
        ->withCount([
            'submissions',
            'submissions as graded_count' => function($query) {
                $query->whereNotNull('score');
            },
            'submissions as ungraded_count' => function($query) {
                $query->whereNull('score');
            }
        ])
        ->where('guru_id', Auth::id());
        
        // Apply tab-specific filters
        if ($tab === 'active') {
            // Tugas aktif: yang dipublikasi dan belum lewat deadline atau tanpa deadline
            $query->where('is_published', true)
                  ->where(function($q) {
                      $q->where('deadline', '>', now())
                        ->orWhereNull('deadline');
                  });
        } elseif ($tab === 'history') {
            // Semua tugas untuk riwayat
            // Tidak ada filter tambahan, semua tugas ditampilkan
        }
        
        // Apply additional filters if provided
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }
        
        if ($request->filled('status')) {
            $now = now();
            switch ($request->status) {
                case 'active':
                    $query->where('deadline', '>', $now)
                          ->where('is_published', true);
                    break;
                case 'completed':
                    $query->where('deadline', '<', $now);
                    break;
                case 'draft':
                    $query->where('is_published', false);
                    break;
            }
        }
        
        if ($request->filled('period') && $tab === 'history') {
            switch ($request->period) {
                case 'week':
                    $query->where('created_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->subMonth());
                    break;
                case 'semester':
                    $query->where('created_at', '>=', now()->subMonths(6));
                    break;
            }
        }
        
        // Get assignments with pagination
        $assignments = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Calculate statistics for history tab
        if ($tab === 'history') {
            $assignments->getCollection()->transform(function ($assignment) {
                $graded = $assignment->submissions->where('score', '!==', null);
                $assignment->average_score = $graded->count() > 0 ? round($graded->avg('score'), 1) : null;
                $assignment->completion_rate = $assignment->submissions_count > 0 
                    ? round(($assignment->graded_count / $assignment->submissions_count) * 100, 1)
                    : 0;
                return $assignment;
            });
        }
        
        // Get subjects and stats for filters
        $subjects = Subject::where('is_active', true)->get();
        
        // Calculate stats for dashboard
        $totalStats = [
            'total_assignments' => Assignment::where('guru_id', Auth::id())->count(),
            'active_assignments' => Assignment::where('guru_id', Auth::id())
                ->where('is_published', true)
                ->where(function($q) {
                    $q->where('deadline', '>', now())->orWhereNull('deadline');
                })->count(),
            'total_submissions' => AssignmentSubmission::whereHas('assignment', function($q) {
                $q->where('guru_id', Auth::id());
            })->count(),
            'graded_submissions' => AssignmentSubmission::whereHas('assignment', function($q) {
                $q->where('guru_id', Auth::id());
            })->whereNotNull('score')->count(),
        ];

        return view('guru.assignments.index', compact('assignments', 'subjects', 'tab', 'totalStats'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create()
    {
        $subjects = Subject::where('is_active', true)->get();
        return view('guru.assignments.create', compact('subjects'));
    }

    /**
     * Store a newly created assignment.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'class' => 'nullable|string|max:10',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt,zip,rar|max:20480',
            'deadline' => 'required|date|after:now',
            'max_score' => 'required|numeric|min:1|max:1000',
            'allow_late' => 'boolean',
            'is_published' => 'boolean',
        ], [
            'deadline.after' => 'Deadline harus setelah waktu sekarang',
            'max_score.min' => 'Nilai maksimal minimal 1',
            'file.max' => 'Ukuran file maksimal 20MB',
            'subject_id.exists' => 'Mata pelajaran yang dipilih tidak valid',
        ]);

        if ($validator->fails()) {
            Log::warning('Assignment creation validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['file']),
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form');
        }

        Log::info('Assignment creation attempt', [
            'input' => $request->except(['file']),
            'guru_id' => Auth::id(),
            'ip' => $request->ip()
        ]);
        
        try {
            $assignment = new Assignment();
            $assignment->guru_id = Auth::id();
            $assignment->title = $request->title;
            $assignment->description = $request->description;
            $assignment->instructions = $request->instructions;
            $assignment->deadline = $request->deadline;
            $assignment->max_score = $request->max_score;
            $assignment->subject_id = $request->subject_id;
            $assignment->class = $request->class;
            $assignment->allow_late = $request->has('allow_late');
            $assignment->is_published = $request->has('is_published');

            if ($request->hasFile('file')) {
                $fileData = $this->handleFileUpload($request->file('file'));
                $assignment->fill($fileData);
            }

            $assignment->save();

            Log::info('Assignment created', [
                'assignment_id' => $assignment->id,
                'guru_id' => Auth::id(),
                'title' => $assignment->title,
                'ip' => $request->ip()
            ]);

            return redirect()->route('guru.assignments.index')
                ->with('success', 'Tugas berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Assignment creation failed: ' . $e->getMessage(), [
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified assignment.
     */
    public function show(Assignment $assignment)
    {
        // ✅ Security: Double-check ownership
        if ($assignment->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('view', $assignment);

        $submissions = AssignmentSubmission::with(['siswa', 'assignment'])
            ->where('assignment_id', $assignment->id)
            ->latest()
            ->paginate(15);

        // ✅ Optimized: Use collection instead of new queries
        $gradedSubmissions = $submissions->getCollection()->filter(fn($s) => $s->score !== null);

        $stats = [
            'total_submissions' => $submissions->total(),
            'graded_count' => $gradedSubmissions->count(),
            'average_score' => round($gradedSubmissions->avg('score') ?: 0, 2),
        ];

        return view('guru.assignments.show', compact('assignment', 'submissions', 'stats'));
    }

    /**
     * Show the form for editing the assignment.
     */
    public function edit(Assignment $assignment)
    {
        if ($assignment->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('update', $assignment);
        $subjects = Subject::where('is_active', true)->get();
        return view('guru.assignments.edit', compact('assignment', 'subjects'));
    }

    /**
     * Update the specified assignment.
     */
    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        if ($assignment->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('update', $assignment);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'class' => 'nullable|string|max:10',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt,zip,rar|max:20480',
            'deadline' => 'required|date|after:now',
            'max_score' => 'required|numeric|min:1|max:1000',
            'allow_late' => 'boolean',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $assignment->title = $request->title;
            $assignment->description = $request->description;
            $assignment->instructions = $request->instructions;
            $assignment->deadline = $request->deadline;
            $assignment->max_score = $request->max_score;
            $assignment->subject_id = $request->subject_id;
            $assignment->class = $request->class;
            $assignment->allow_late = $request->has('allow_late');
            $assignment->is_published = $request->has('is_published');

            if ($request->hasFile('file')) {
                $fileData = $this->handleFileUpload($request->file('file'), $assignment->file);
                $assignment->fill($fileData);
            }

            $assignment->save();

            Log::info('Assignment updated', [
                'assignment_id' => $assignment->id,
                'guru_id' => Auth::id(),
                'title' => $assignment->title,
                'ip' => $request->ip()
            ]);

            return redirect()->route('guru.assignments.index')
                ->with('success', 'Tugas berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Assignment update failed: ' . $e->getMessage(), [
                'assignment_id' => $assignment->id,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified assignment.
     */
    public function destroy(Assignment $assignment): RedirectResponse
    {
        if ($assignment->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('delete', $assignment);

        try {
            if ($assignment->file) {
                Storage::disk('public')->delete('assignments/' . $assignment->file);
            }

            $assignment->delete();

            Log::info('Assignment deleted', [
                'assignment_id' => $assignment->id,
                'guru_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            return redirect()->route('guru.assignments.index')
                ->with('success', 'Tugas berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Assignment deletion failed: ' . $e->getMessage(), [
                'assignment_id' => $assignment->id,
                'guru_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Grade a submission.
     */
    public function gradeSubmission(Request $request, AssignmentSubmission $submission): RedirectResponse
    {
        $assignment = $submission->assignment;

        if ($assignment->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('update', $assignment);

        $validator = Validator::make($request->all(), [
            'score' => 'required|numeric|min:0|max:' . $assignment->max_score,
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

            // ✅ Touch assignment to update its timestamp
            $assignment->touch();

            Log::info('Submission graded', [
                'submission_id' => $submission->id,
                'assignment_id' => $assignment->id,
                'score' => $request->score,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('success', 'Nilai berhasil disimpan!');

        } catch (\Exception $e) {
            Log::error('Submission grading failed: ' . $e->getMessage(), [
                'submission_id' => $submission->id,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Toggle publish status of assignment.
     */
    public function togglePublish(Assignment $assignment): RedirectResponse
    {
        if ($assignment->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('update', $assignment);

        $assignment->update([
            'is_published' => !$assignment->is_published
        ]);

        $status = $assignment->is_published ? 'dipublikasikan' : 'disembunyikan';

        Log::info('Assignment publish status toggled', [
            'assignment_id' => $assignment->id,
            'guru_id' => Auth::id(),
            'is_published' => $assignment->is_published,
            'ip' => request()->ip()
            ]);

        return back()->with('success', "Tugas berhasil $status!");
    }

    /**
     * Show submissions for a specific assignment.
     */
    public function submissions(Assignment $assignment)
    {
        if ($assignment->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('view', $assignment);

        $submissions = AssignmentSubmission::with(['siswa', 'assignment'])
            ->where('assignment_id', $assignment->id)
            ->latest()
            ->paginate(15);

        $stats = [
            'total_submissions' => $submissions->total(),
            'graded_count' => $submissions->getCollection()->filter(fn($s) => $s->score !== null)->count(),
            'average_score' => round($submissions->getCollection()->filter(fn($s) => $s->score !== null)->avg('score') ?: 0, 2),
        ];

        return view('guru.assignments.submissions', compact('assignment', 'submissions', 'stats'));
    }

    /**
     * Grade a specific submission.
     */
    public function grade(Request $request, Assignment $assignment, AssignmentSubmission $submission)
    {
        if ($assignment->guru_id !== Auth::id() || $submission->assignment_id !== $assignment->id) {
            abort(403);
        }

        $this->authorize('update', $assignment);

        $validator = Validator::make($request->all(), [
            'score' => 'required|numeric|min:0|max:' . $assignment->max_score,
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

            $assignment->touch();

            Log::info('Submission graded', [
                'submission_id' => $submission->id,
                'assignment_id' => $assignment->id,
                'score' => $request->score,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('success', 'Nilai berhasil disimpan!');

        } catch (\Exception $e) {
            Log::error('Submission grading failed: ' . $e->getMessage(), [
                'submission_id' => $submission->id,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }


    /**
     * Handle file upload and cleanup.
     */
    private function handleFileUpload($file, $oldFilename = null)
    {
        // Delete old file if exists
        if ($oldFilename) {
            Storage::disk('public')->delete('assignments/' . $oldFilename);
        }

        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $path = $file->storeAs('assignments', $filename, 'public');

        return [
            'file' => $filename,
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'file_type' => $file->getClientOriginalExtension(),
        ];
    }
}
