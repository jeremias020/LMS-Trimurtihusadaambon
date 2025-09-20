<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = Assignment::with(['guru', 'submissions'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gurus = User::where('role', 'guru')->get();
        return view('admin.assignments.create', compact('gurus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'guru_id' => 'required|exists:users,id',
            'due_date' => 'required|date|after:now',
            'max_score' => 'required|numeric|min:0|max:100',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $data = $request->all();
        
        // Map due_date to deadline for model compatibility
        $data['deadline'] = $data['due_date'];
        unset($data['due_date']);
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('assignments', $filename, 'public');
            $data['file'] = $filename; // Store just filename, not full path
        }

        $data['is_published'] = $request->has('is_published');

        Assignment::create($data);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Tugas berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        $assignment->load(['guru', 'submissions.siswa']);
        return view('admin.assignments.show', compact('assignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        $gurus = User::where('role', 'guru')->get();
        return view('admin.assignments.edit', compact('assignment', 'gurus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'guru_id' => 'required|exists:users,id',
            'due_date' => 'required|date|after:now',
            'max_score' => 'required|numeric|min:0|max:100',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $data = $request->all();
        
        // Map due_date to deadline for model compatibility
        $data['deadline'] = $data['due_date'];
        unset($data['due_date']);
        
        if ($request->hasFile('attachment')) {
            // Delete old attachment
            if ($assignment->file) {
                Storage::disk('public')->delete('assignments/' . $assignment->file);
            }
            
            $file = $request->file('attachment');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('assignments', $filename, 'public');
            $data['file'] = $filename; // Store just filename, not full path
        }

        $data['is_published'] = $request->has('is_published');

        $assignment->update($data);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        // Delete attachment if exists
        if ($assignment->file) {
            Storage::disk('public')->delete('assignments/' . $assignment->file);
        }

        $assignment->delete();

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * Toggle publish status
     */
    public function togglePublish(Assignment $assignment)
    {
        $assignment->update(['is_published' => !$assignment->is_published]);
        
        $status = $assignment->is_published ? 'dipublikasikan' : 'tidak dipublikasikan';
        return redirect()->back()
            ->with('success', "Tugas berhasil {$status}.");
    }

    /**
     * Bulk delete assignments
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'assignment_ids' => 'required|array',
            'assignment_ids.*' => 'exists:assignments,id'
        ]);

        $assignments = Assignment::whereIn('id', $request->assignment_ids);
        
        // Delete attachments
        foreach ($assignments->get() as $assignment) {
            if ($assignment->file) {
                Storage::disk('public')->delete('assignments/' . $assignment->file);
            }
        }
        
        $assignments->delete();

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Tugas yang dipilih berhasil dihapus.');
    }
}
