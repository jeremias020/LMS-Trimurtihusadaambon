<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $mataPelajarans = MataPelajaran::orderBy('name')->get();
        $mataPelajaranUmum = MataPelajaran::umum()->count();
        $mataPelajaranKejuruan = MataPelajaran::kejuruan()->count();
        $mataPelajaranAktif = MataPelajaran::active()->count();

        return view('admin.mata-pelajaran.index', compact(
            'mataPelajarans',
            'mataPelajaranUmum',
            'mataPelajaranKejuruan',
            'mataPelajaranAktif'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.mata-pelajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
            'code' => 'required|string|max:20|unique:subjects,code',
            'description' => 'nullable|string',
            'type' => 'required|in:teori,praktikum,campuran',
            'sks' => 'required|integer|min:1|max:10',
            'is_active' => 'boolean'
        ]);

        MataPelajaran::create($validated);

        return redirect()
            ->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MataPelajaran $mataPelajaran): View
    {
        return view('admin.mata-pelajaran.show', compact('mataPelajaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataPelajaran $mataPelajaran): View
    {
        return view('admin.mata-pelajaran.edit', compact('mataPelajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MataPelajaran $mataPelajaran): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $mataPelajaran->id,
            'code' => 'required|string|max:20|unique:subjects,code,' . $mataPelajaran->id,
            'description' => 'nullable|string',
            'type' => 'required|in:teori,praktikum,campuran',
            'sks' => 'required|integer|min:1|max:10',
            'is_active' => 'boolean'
        ]);

        $mataPelajaran->update($validated);

        return redirect()
            ->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataPelajaran $mataPelajaran): RedirectResponse
    {
        $mataPelajaran->delete();

        return redirect()
            ->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata Pelajaran berhasil dihapus.');
    }

    /**
     * Toggle status mata pelajaran
     */
    public function toggleStatus(MataPelajaran $mataPelajaran): RedirectResponse
    {
        $mataPelajaran->update(['status' => !$mataPelajaran->status]);

        $status = $mataPelajaran->status ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()
            ->route('admin.mata-pelajaran.index')
            ->with('success', "Mata Pelajaran berhasil {$status}.");
    }

    /**
     * Seed default mata pelajaran
     */
    public function seedDefault(): RedirectResponse
    {
        $defaultMapel = [
            ['name' => 'Pendidikan Agama', 'code' => 'PA', 'type' => 'teori', 'sks' => 2, 'is_active' => true],
            ['name' => 'Pendidikan Kewarganegaraan', 'code' => 'PKW', 'type' => 'teori', 'sks' => 2, 'is_active' => true],
            ['name' => 'Bahasa Indonesia', 'code' => 'BI', 'type' => 'teori', 'sks' => 4, 'is_active' => true],
            ['name' => 'Matematika', 'code' => 'MTK', 'type' => 'teori', 'sks' => 4, 'is_active' => true],
            ['name' => 'Bahasa Inggris', 'code' => 'ENG', 'type' => 'teori', 'sks' => 4, 'is_active' => true],
            ['name' => 'Biologi', 'code' => 'BIO', 'type' => 'teori', 'sks' => 3, 'is_active' => true],
            ['name' => 'Fisika', 'code' => 'FIS', 'type' => 'teori', 'sks' => 3, 'is_active' => true],
            ['name' => 'Kimia', 'code' => 'KIM', 'type' => 'teori', 'sks' => 3, 'is_active' => true],
            ['name' => 'Keperawatan Dasar', 'code' => 'KD', 'type' => 'praktikum', 'sks' => 6, 'is_active' => true],
            ['name' => 'Kebidanan Dasar', 'code' => 'KBD', 'type' => 'praktikum', 'sks' => 6, 'is_active' => true],
            ['name' => 'Farmasi Dasar', 'code' => 'FD', 'type' => 'praktikum', 'sks' => 6, 'is_active' => true],
        ];

        foreach ($defaultMapel as $mapel) {
            MataPelajaran::firstOrCreate(
                ['code' => $mapel['code']],
                $mapel
            );
        }

        return redirect()
            ->route('admin.mata-pelajaran.index')
            ->with('success', 'Data mata pelajaran default berhasil ditambahkan.');
    }
}
