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
        $kelas = Kelas::with(['jurusan', 'waliKelas'])
                     ->withCount('siswa')
                     ->orderBy('tingkat')
                     ->orderBy('nama')
                     ->get();
                     
        return view('admin.kelas.index', compact('kelas'));
    }

    /**
     * Show the form for creating a new kelas
     */
    public function create(): View
    {
        $jurusan = Jurusan::active()->get();
        $availableGuru = User::where('role', 'guru')
                           ->where('status', 'active')
                           ->whereDoesntHave('kelasWali') // Guru yang belum jadi wali kelas
                           ->get();
        
        return view('admin.kelas.create', compact('jurusan', 'availableGuru'));
    }

    /**
     * Store a newly created kelas
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kelas,nama',
            'tingkat' => 'required|in:X,XI,XII',
            'jurusan_id' => 'required|exists:jurusan,id',
            'kapasitas' => 'nullable|integer|min:1|max:50',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'tahun_ajaran' => 'nullable|string|max:20',
            'ruangan' => 'nullable|string|max:50'
        ]);

        // Validate wali kelas
        if ($validated['wali_kelas_id']) {
            $guru = User::find($validated['wali_kelas_id']);
            if ($guru->role !== 'guru' || $guru->status !== 'active') {
                return redirect()
                    ->back()
                    ->with('error', 'Wali kelas harus guru yang aktif.')
                    ->withInput();
            }
        }

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
        $kelas->load(['jurusan', 'waliKelas', 'siswa']);
        
        return view('admin.kelas.show', compact('kelas'));
    }

    /**
     * Show the form for editing kelas
     */
    public function edit(Kelas $kelas): View
    {
        $jurusan = Jurusan::active()->get();
        $availableGuru = User::where('role', 'guru')
                           ->where('status', 'active')
                           ->where(function($query) use ($kelas) {
                               $query->whereDoesntHave('kelasWali')
                                    ->orWhere('id', $kelas->wali_kelas_id);
                           })
                           ->get();
        
        return view('admin.kelas.edit', compact('kelas', 'jurusan', 'availableGuru'));
    }

    /**
     * Update the specified kelas
     */
    public function update(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kelas,nama,' . $kelas->id,
            'tingkat' => 'required|in:X,XI,XII',
            'jurusan_id' => 'required|exists:jurusan,id',
            'kapasitas' => 'nullable|integer|min:1|max:50',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'tahun_ajaran' => 'nullable|string|max:20',
            'ruangan' => 'nullable|string|max:50'
        ]);

        // Validate wali kelas
        if ($validated['wali_kelas_id']) {
            $guru = User::find($validated['wali_kelas_id']);
            if ($guru->role !== 'guru' || $guru->status !== 'active') {
                return redirect()
                    ->back()
                    ->with('error', 'Wali kelas harus guru yang aktif.')
                    ->withInput();
            }
        }

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
        $usedRooms = Kelas::whereNotNull('ruangan')->pluck('ruangan')->toArray();
        $availableRooms = array_diff($rooms, $usedRooms);
        
        return response()->json(array_values($availableRooms));
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
        
        if (($currentCount + $newCount) > $targetKelas->kapasitas) {
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
        $tingkat = $request->input('tingkat');
        $jurusanId = $request->input('jurusan_id');
        
        if (!$tingkat || !$jurusanId) {
            return response()->json(['error' => 'Tingkat dan Jurusan diperlukan'], 400);
        }
        
        $jurusan = Jurusan::find($jurusanId);
        if (!$jurusan) {
            return response()->json(['error' => 'Jurusan tidak ditemukan'], 404);
        }
        
        // Count existing classes for this tingkat and jurusan
        $count = Kelas::where('tingkat', $tingkat)
                     ->where('jurusan_id', $jurusanId)
                     ->count();
        
        $letter = chr(65 + $count); // A, B, C, etc.
        $nama = "{$tingkat} {$jurusan->kode} {$letter}";
        
        return response()->json(['nama' => $nama]);
    }

    /**
     * Bulk actions for kelas
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,change_wali',
            'kelas_ids' => 'required|array|min:1',
            'kelas_ids.*' => 'exists:kelas,id',
            'wali_kelas_id' => 'nullable|exists:users,id'
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
                    
            case 'change_wali':
                if (!$validated['wali_kelas_id']) {
                    return redirect()
                        ->back()
                        ->with('error', 'Wali kelas harus dipilih untuk aksi ini.');
                }
                
                Kelas::whereIn('id', $validated['kelas_ids'])
                    ->update(['wali_kelas_id' => $validated['wali_kelas_id']]);
                    
                return redirect()
                    ->route('admin.kelas.index')
                    ->with('success', "Wali kelas untuk {$kelasCount} kelas berhasil diubah.");
        }
        
        return redirect()->back();
    }
}