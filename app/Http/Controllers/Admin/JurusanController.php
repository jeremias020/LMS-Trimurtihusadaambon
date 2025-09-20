<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JurusanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of jurusan
     */
    public function index(): View
    {
        $jurusan = Jurusan::withCount(['kelas', 'siswa'])
                          ->orderBy('nama')
                          ->get();
                          
        return view('admin.jurusan.index', compact('jurusan'));
    }

    /**
     * Show the form for creating a new jurusan
     */
    public function create(): View
    {
        return view('admin.jurusan.create');
    }

    /**
     * Store a newly created jurusan
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:jurusan,nama',
            'kode' => 'required|string|max:10|unique:jurusan,kode',
            'deskripsi' => 'nullable|string',
            'mata_pelajaran' => 'required|array|min:1',
            'mata_pelajaran.*' => 'required|string|max:255',
            'kapasitas_total' => 'nullable|integer|min:1',
            'status' => 'boolean'
        ]);

        // Clean mata pelajaran array
        $validated['mata_pelajaran'] = array_filter($validated['mata_pelajaran']);
        
        Jurusan::create($validated);

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    /**
     * Display the specified jurusan
     */
    public function show(Jurusan $jurusan): View
    {
        $jurusan->load(['kelas', 'siswa']);
        
        return view('admin.jurusan.show', compact('jurusan'));
    }

    /**
     * Show the form for editing jurusan
     */
    public function edit(Jurusan $jurusan): View
    {
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    /**
     * Update the specified jurusan
     */
    public function update(Request $request, Jurusan $jurusan): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:jurusan,nama,' . $jurusan->id,
            'kode' => 'required|string|max:10|unique:jurusan,kode,' . $jurusan->id,
            'deskripsi' => 'nullable|string',
            'mata_pelajaran' => 'required|array|min:1',
            'mata_pelajaran.*' => 'required|string|max:255',
            'kapasitas_total' => 'nullable|integer|min:1',
            'status' => 'boolean'
        ]);

        // Clean mata pelajaran array
        $validated['mata_pelajaran'] = array_filter($validated['mata_pelajaran']);
        
        $jurusan->update($validated);

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    /**
     * Remove the specified jurusan
     */
    public function destroy(Jurusan $jurusan): RedirectResponse
    {
        // Check if jurusan has related kelas or siswa
        if ($jurusan->kelas()->count() > 0 || $jurusan->siswa()->count() > 0) {
            return redirect()
                ->route('admin.jurusan.index')
                ->with('error', 'Tidak dapat menghapus jurusan yang masih memiliki kelas atau siswa.');
        }

        $jurusan->delete();

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }

    /**
     * Toggle status jurusan
     */
    public function toggleStatus(Jurusan $jurusan): RedirectResponse
    {
        $jurusan->update(['status' => !$jurusan->status]);
        
        $status = $jurusan->status ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', "Jurusan berhasil {$status}.");
    }

    /**
     * Seed default jurusan kesehatan
     */
    public function seedDefault(): RedirectResponse
    {
        try {
            Jurusan::seedDefault();
            
            return redirect()
                ->route('admin.jurusan.index')
                ->with('success', 'Default jurusan kesehatan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.jurusan.index')
                ->with('error', 'Gagal menambahkan default jurusan: ' . $e->getMessage());
        }
    }

    /**
     * Get mata pelajaran for specific jurusan (for AJAX)
     */
    public function getMataPelajaran(Jurusan $jurusan)
    {
        return response()->json([
            'mata_pelajaran' => $jurusan->mata_pelajaran
        ]);
    }
}