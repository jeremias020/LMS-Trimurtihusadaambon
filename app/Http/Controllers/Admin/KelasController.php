<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of kelas
     */
    public function index(): View
    {
        try {
            $kelas = Kelas::with('guru')
                         ->orderBy('name')
                         ->get();
            
            // Calculate statistics
            $totalSiswa = \App\Models\User::where('role', 'siswa')->count();
            $kelasKeperawatan = $kelas->count(); // Tidak ada field major, hitung semua
            $kelasFarmasi = 0; // Tidak ada field major, set 0
                         
            return view('admin.kelas.index', compact('kelas', 'totalSiswa', 'kelasKeperawatan', 'kelasFarmasi'));
        } catch (\Exception $e) {
            return view('admin.kelas.index', [
                'kelas' => collect(),
                'totalSiswa' => 0,
                'kelasKeperawatan' => 0,
                'kelasFarmasi' => 0,
                'error' => 'Error loading kelas: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new kelas
     */
    public function create(): View
    {
        $availableGuru = User::where('role', 'guru')
                           ->where('is_active', true)
                           ->get();
        $jurusans = Jurusan::orderBy('name')->get();
        
        return view('admin.kelas.create', compact('availableGuru', 'jurusans'));
    }

    /**
     * Store a newly created kelas
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:kelas,name',
            'code' => 'required|string|max:20|unique:kelas,code',
            'grade' => 'required|in:X,XI,XII',
            'major' => 'required_without:jurusan_id|string|max:50',
            'description' => 'nullable|string|max:500',
            'capacity' => 'nullable|integer|min:1|max:50',
            'guru_id' => 'nullable|exists:users,id',
            'academic_year' => 'required|string|max:20',
            'jurusan_id' => 'nullable|exists:jurusan,id'
        ]);

        // Validate guru (wali kelas)
        if ($validated['guru_id']) {
            $guru = User::find($validated['guru_id']);
            if ($guru->role !== 'guru' || !$guru->isActive()) {
                return redirect()
                    ->back()
                    ->with('error', 'Wali kelas harus guru yang aktif.')
                    ->withInput();
            }
        }

        // Sync major from jurusan if provided
        if (!empty($validated['jurusan_id'])) {
            $jurusan = Jurusan::find($validated['jurusan_id']);
            if ($jurusan) {
                $validated['major'] = $jurusan->nama;
            }
        }

        // Set default values
        $validated['capacity'] = $validated['capacity'] ?? 40;

        Kelas::create($validated);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified kelas
     */
    public function show(Kelas $kelas): View
    {
        $kelas->load(['guru', 'siswa']);
        
        return view('admin.kelas.show', compact('kelas'));
    }

    /**
     * Show the form for editing kelas
     */
    public function edit(Kelas $kelas): View
    {
        $availableGuru = User::where('role', 'guru')
                           ->where('is_active', true)
                           ->get();
        $jurusans = Jurusan::orderBy('name')->get();
        
        return view('admin.kelas.edit', compact('kelas', 'availableGuru', 'jurusans'));
    }

    /**
     * Update the specified kelas
     */
    public function update(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:kelas,name,' . $kelas->id,
            'code' => 'required|string|max:10|unique:kelas,code,' . $kelas->id,
            'grade' => 'required|in:X,XI,XII',
            'major' => 'required_without:jurusan_id|string|max:50',
            'description' => 'nullable|string|max:500',
            'capacity' => 'nullable|integer|min:1|max:50',
            'guru_id' => 'nullable|exists:users,id',
            'academic_year' => 'required|string|max:20',
            'jurusan_id' => 'nullable|exists:jurusan,id'
        ]);

        // Validate guru (wali kelas)
        if ($validated['guru_id']) {
            $guru = User::find($validated['guru_id']);
            if ($guru->role !== 'guru' || !$guru->isActive()) {
                return redirect()
                    ->back()
                    ->with('error', 'Wali kelas harus guru yang aktif.')
                    ->withInput();
            }
        }

        // Set default values
        $validated['capacity'] = $validated['capacity'] ?? 40;

        $kelas->update($validated);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified kelas
     */
    public function destroy(Kelas $kelas): RedirectResponse
    {
        // Check if kelas has siswa
        if ($kelas->siswa()->count() > 0) {
            return redirect()
                ->route('admin.kelas.index')
                ->with('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa.');
        }

        $kelas->delete();

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    /**
     * Get available rooms for AJAX
     */
    public function getAvailableRooms()
    {
        $rooms = ['Lab 1', 'Lab 2', 'Lab 3', 'Ruang 101', 'Ruang 102', 'Ruang 103', 'Ruang 201', 'Ruang 202', 'Ruang 203'];
        // Since we don't have a room field in the current schema, return all rooms
        return response()->json(array_values($rooms));
    }

    /**
     * Move siswa to different kelas
     */
    public function moveSiswa(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:users,id',
            'target_kelas_id' => 'required|exists:kelas,id|different:' . $kelas->id
        ]);

        $targetKelas = Kelas::find($validated['target_kelas_id']);
        
        // Check capacity
        $currentCount = $targetKelas->siswa()->count();
        $newCount = count($validated['siswa_ids']);
        
        if (($currentCount + $newCount) > $targetKelas->capacity) {
            return redirect()
                ->back()
                ->with('error', 'Kapasitas kelas tujuan tidak mencukupi.');
        }

        // Move siswa
        User::whereIn('id', $validated['siswa_ids'])
            ->update(['kelas_id' => $validated['target_kelas_id']]);

        return redirect()
            ->route('admin.kelas.show', $kelas)
            ->with('success', count($validated['siswa_ids']) . ' siswa berhasil dipindahkan.');
    }

    /**
     * Generate kelas name automatically
     */
    public function generateKelasName(Request $request)
    {
        $grade = $request->input('grade');
        $major = $request->input('major');
        
        if (!$grade || !$major) {
            return response()->json(['error' => 'Grade dan Major diperlukan'], 400);
        }
        
        // Count existing classes (tanpa filter grade dan major karena tidak ada)
        $count = Kelas::count();
        
        $letter = chr(65 + $count); // A, B, C, etc.
        $name = "{$grade} {$major} {$letter}";
        $code = strtoupper(substr($major, 0, 3)) . $grade . $letter;
        
        return response()->json([
            'name' => $name,
            'code' => $code
        ]);
    }

    /**
     * Bulk actions for kelas
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,change_guru',
            'kelas_ids' => 'required|array|min:1',
            'kelas_ids.*' => 'exists:kelas,id',
            'guru_id' => 'nullable|exists:users,id'
        ]);

        $kelasCount = count($validated['kelas_ids']);
        
        switch ($validated['action']) {
            case 'delete':
                $kelasWithSiswa = Kelas::whereIn('id', $validated['kelas_ids'])
                                     ->has('siswa')
                                     ->count();
                                     
                if ($kelasWithSiswa > 0) {
                    return redirect()
                        ->back()
                        ->with('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa.');
                }
                
                Kelas::whereIn('id', $validated['kelas_ids'])->delete();
                return redirect()
                    ->route('admin.kelas.index')
                    ->with('success', "{$kelasCount} kelas berhasil dihapus.");
                    
            case 'change_guru':
                if (!$validated['guru_id']) {
                    return redirect()
                        ->back()
                        ->with('error', 'Guru wali kelas harus dipilih untuk aksi ini.');
                }
                
                Kelas::whereIn('id', $validated['kelas_ids'])
                    ->update(['guru_id' => $validated['guru_id']]);
                    
                return redirect()
                    ->route('admin.kelas.index')
                    ->with('success', "Wali kelas untuk {$kelasCount} kelas berhasil diubah.");
        }
        
        return redirect()->back();
    }
}