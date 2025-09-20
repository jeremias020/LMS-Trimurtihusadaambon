<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of active assignments.
     */
    public function index(): View
    {
        $assignments = Assignment::with(['submissions' => function($query) {
            $query->where('siswa_id', Auth::id());
        }])
        ->where('is_published', true)
        ->where('deadline', '>', now())
        ->orderBy('deadline', 'asc')
        ->paginate(10);

        return view('siswa.assignments.index', compact('assignments'));
    }

    /**
     * Display the specified assignment.
     */
    public function show($id): View
    {
        $assignment = Assignment::with('guru')
            ->where('is_published', true)
            ->findOrFail($id);

        $submission = AssignmentSubmission::where('assignment_id', $id)
            ->where('siswa_id', Auth::id())
            ->first();

        $isExpired = now() > $assignment->deadline;
        $canSubmit = !$isExpired;

        return view('siswa.assignments.show', compact('assignment', 'submission', 'isExpired', 'canSubmit'));
    }

    /**
     * Store a submission for the assignment.
     */
    public function submit(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'submission_text' => 'nullable|string|max:5000',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,zip,rar,jpg,jpeg,png|max:5120', // ✅ Perbaikan: gunakan 'file'
        ]);

        $assignment = Assignment::where('is_published', true)
            ->findOrFail($id);

        // Validasi deadline
        if (now() > $assignment->deadline) {
            return back()->with('error', 'Batas waktu pengumpulan telah berlalu.');
        }

        try {
            $submission = AssignmentSubmission::firstOrNew([
                'assignment_id' => $id,
                'siswa_id' => Auth::id()
            ]);

            // Jika sudah ada submission dan sudah dinilai, tidak boleh edit
            if ($submission->exists && $submission->score !== null) {
                return back()->with('error', 'Tugas sudah dinilai dan tidak dapat diubah.');
            }

            $submission->submission_text = $request->submission_text;

            if ($request->hasFile('file')) { // ✅ Perbaikan: gunakan 'file'
                // Hapus file lama jika ada
                if ($submission->file_path) {
                    Storage::disk('public')->delete('assignment_submissions/' . $submission->file_path);
                }

                $file = $request->file('file'); // ✅ Perbaikan: gunakan 'file'
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('assignment_submissions', $filename, 'public');
                $submission->file_path = $filename;
            }

            // Set submitted_at hanya jika belum ada
            if (!$submission->submitted_at) {
                $submission->submitted_at = now();
            }

            $submission->save();

            Log::info('Assignment submitted successfully', [
                'assignment_id' => $id,
                'siswa_id' => Auth::id(),
                'file_uploaded' => $request->hasFile('file'),
                'ip' => $request->ip()
            ]);

            return redirect()->route('siswa.assignments.show', $id)
                ->with('success', 'Tugas berhasil dikumpulkan!');

        } catch (\Exception $e) {
            Log::error('Error submitting assignment: ' . $e->getMessage(), [
                'assignment_id' => $id,
                'siswa_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mengumpulkan tugas.');
        }
    }

    /**
     * Display submission history.
     */
    public function history(): View
    {
        $submissions = AssignmentSubmission::with(['assignment', 'assignment.guru'])
            ->where('siswa_id', Auth::id())
            ->orderBy('submitted_at', 'desc')
            ->paginate(10);

        return view('siswa.assignments.history', compact('submissions'));
    }

    /**
     * Download submission file.
     */
    public function downloadFile($submissionId): BinaryFileResponse
    {
        $submission = AssignmentSubmission::where('siswa_id', Auth::id())
            ->findOrFail($submissionId);

        if (!$submission->file_path) {
            abort(404, 'File tidak ditemukan.');
        }

        $filePath = storage_path('app/public/assignment_submissions/' . $submission->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan di server.');
        }

        return response()->download($filePath, $submission->file_path);
    }

    /**
     * Display archived (expired) assignments.
     */
    public function archived(): View
    {
        $assignments = Assignment::with(['submissions' => function($query) {
            $query->where('siswa_id', Auth::id());
        }])
        ->where('is_published', true)
        ->where('deadline', '<=', now())
        ->orderBy('deadline', 'desc')
        ->paginate(10);

        return view('siswa.assignments.archived', compact('assignments'));
    }
}
