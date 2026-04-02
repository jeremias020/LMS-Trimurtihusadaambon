<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Practical;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PracticalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $practicals = Practical::with(['guru', 'scores'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.practicals.index', compact('practicals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gurus = User::where('role', 'guru')->get();
        return view('admin.practicals.create', compact('gurus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'guru_id' => 'required|exists:users,id',
            'date' => 'required|date|after:now',
            'lokasi' => 'required|string|max:255',
            'durasi' => 'required|integer|min:1|max:480', // Max 8 hours
            'tools' => 'nullable|string',
            'bahan' => 'nullable|string',
            'instruksi' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['is_published'] = $request->has('is_published');

        Practical::create($data);

        return redirect()->route('admin.practicals.index')
            ->with('success', 'Praktikum berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Practical $practical)
    {
        $practical->load(['guru', 'scores.siswa']);
        return view('admin.practicals.show', compact('practical'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Practical $practical)
    {
        $gurus = User::where('role', 'guru')->get();
        return view('admin.practicals.edit', compact('practical', 'gurus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Practical $practical)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'guru_id' => 'required|exists:users,id',
            'date' => 'required|date|after:now',
            'lokasi' => 'required|string|max:255',
            'durasi' => 'required|integer|min:1|max:480', // Max 8 hours
            'tools' => 'nullable|string',
            'bahan' => 'nullable|string',
            'instruksi' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['is_published'] = $request->has('is_published');

        $practical->update($data);

        return redirect()->route('admin.practicals.index')
            ->with('success', 'Praktikum berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Practical $practical)
    {
        $practical->delete();

        return redirect()->route('admin.practicals.index')
            ->with('success', 'Praktikum berhasil dihapus.');
    }

    /**
     * Toggle publish status
     */
    public function togglePublish(Practical $practical)
    {
        $practical->update(['is_published' => !$practical->is_published]);
        
        $status = $practical->is_published ? 'dipublikasikan' : 'tidak dipublikasikan';
        return redirect()->back()
            ->with('success', "Praktikum berhasil {$status}.");
    }

    /**
     * Bulk delete practicals
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'practical_ids' => 'required|array',
            'practical_ids.*' => 'exists:practicals,id'
        ]);

        Practical::whereIn('id', $request->practical_ids)->delete();

        return redirect()->route('admin.practicals.index')
            ->with('success', 'Praktikum yang dipilih berhasil dihapus.');
    }
}
