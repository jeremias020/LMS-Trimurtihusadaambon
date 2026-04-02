<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KriteriaPenilaian;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KriteriaPenilaianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of kriteria penilaian
     */
    public function index(): View
    {
        // If table doesn't exist yet (migrations not run), return empty view with guidance
        if (!Schema::hasTable('assessment_criteria')) {
            return view('admin.kriteria-penilaian.index', [
                'kriteria' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20),
                'error' => 'Tabel assessment_criteria belum ada. Jalankan: php artisan migrate',
            ]);
        }

        try {
            $kriteria = KriteriaPenilaian::orderBy('name')
                                        ->orderBy('weight', 'desc')
                                        ->paginate(20);

            return view('admin.kriteria-penilaian.index', compact('kriteria'));
        } catch (\Throwable $e) {
            // Fallback empty collection if something unexpected happens
            return view('admin.kriteria-penilaian.index', [
                'kriteria' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20),
                'error' => 'Gagal memuat data: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating new kriteria
     */
    public function create(): View
    {
        $kategoriList = KriteriaPenilaian::getKategoriList();
        $tingkatKelasList = KriteriaPenilaian::getTingkatKelasList();
        $subjects = \App\Models\MataPelajaran::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('admin.kriteria-penilaian.create', compact('kategoriList', 'tingkatKelasList', 'subjects'));
    }

    /**
     * Show the combined form for creating all categories at once
     */
    public function createCombined(): View
    {
        $tingkatKelasList = KriteriaPenilaian::getTingkatKelasList();
        $kategoriList = KriteriaPenilaian::getKategoriList();
        $subjects = \App\Models\MataPelajaran::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.kriteria-penilaian.create-combined', compact('tingkatKelasList', 'kategoriList', 'subjects'));
    }

    /**
     * Store newly created kriteria
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:persiapan,pelaksanaan,hasil,sikap',
            'bobot' => 'required|integer|min:1|max:100',
            'deskripsi' => 'nullable|string',
            'mata_praktik' => 'required|string|max:255',
            'tingkat_kelas' => 'required|in:X,XI,XII',
            'sop_checklist' => 'required|array|min:1',
            'sop_checklist.*' => 'required|string|max:255',
            'status' => 'boolean'
        ]);

        // Clean SOP checklist
        $validated['sop_checklist'] = array_filter($validated['sop_checklist']);
        
        KriteriaPenilaian::create($validated);

        return redirect()
            ->route('admin.kriteria-penilaian.index')
            ->with('success', 'Kriteria penilaian berhasil ditambahkan.');
    }

    /**
     * Store multiple kriteria at once (combined categories)
     */
    public function storeCombined(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mata_praktik' => 'required|string|max:255',
            'tingkat_kelas' => 'required|in:X,XI,XII',
            'status' => 'nullable|boolean',
            'categories' => 'required|array',
            'categories.*.nama' => 'required|string|max:255',
            'categories.*.bobot' => 'required|integer|min:1|max:100',
            'categories.*.deskripsi' => 'nullable|string',
            'categories.*.sop_checklist' => 'required|array|min:1',
            'categories.*.sop_checklist.*' => 'required|string|max:255',
        ]);

        // Pastikan total bobot = 100 (100%)
        $totalBobot = 0;
        foreach ($validated['categories'] as $cat) {
            $totalBobot += (int) $cat['bobot'];
        }
        if ($totalBobot !== 100) {
            return back()
                ->withInput()
                ->with('error', 'Total bobot dari semua kategori harus sama dengan 100 (100%). Saat ini: ' . $totalBobot);
        }

        $status = (bool) ($validated['status'] ?? true);
        $mapKeyToKategori = [
            'persiapan' => 'persiapan',
            'pelaksanaan' => 'pelaksanaan',
            'hasil' => 'hasil',
            'sikap' => 'sikap',
        ];

        foreach ($validated['categories'] as $key => $cat) {
            $kategori = $mapKeyToKategori[$key] ?? $key; // fallback

            KriteriaPenilaian::create([
                'nama' => $cat['nama'],
                'kategori' => $kategori,
                'bobot' => $cat['bobot'],
                'deskripsi' => $cat['deskripsi'] ?? null,
                'sop_checklist' => array_values(array_filter($cat['sop_checklist'] ?? [])),
                'mata_praktik' => $validated['mata_praktik'],
                'tingkat_kelas' => $validated['tingkat_kelas'],
                'status' => $status,
            ]);
        }

        return redirect()
            ->route('admin.kriteria-penilaian.index')
            ->with('success', 'Semua kategori kriteria berhasil ditambahkan dalam satu kali input.');
    }

    /**
     * Display the specified kriteria
     */
    public function show(KriteriaPenilaian $kriteriaPenilaian): View
    {
        return view('admin.kriteria-penilaian.show', compact('kriteriaPenilaian'));
    }

    /**
     * Show the form for editing kriteria
     */
    public function edit(KriteriaPenilaian $kriteriaPenilaian): View
    {
        $kategoriList = KriteriaPenilaian::getKategoriList();
        $tingkatKelasList = KriteriaPenilaian::getTingkatKelasList();
        $subjects = \App\Models\MataPelajaran::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('admin.kriteria-penilaian.edit', compact('kriteriaPenilaian', 'kategoriList', 'tingkatKelasList', 'subjects'));
    }

    /**
     * Update the specified kriteria
     */
    public function update(Request $request, KriteriaPenilaian $kriteriaPenilaian): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:persiapan,pelaksanaan,hasil,sikap',
            'bobot' => 'required|integer|min:1|max:100',
            'deskripsi' => 'nullable|string',
            'mata_praktik' => 'required|string|max:255',
            'tingkat_kelas' => 'required|in:X,XI,XII',
            'sop_checklist' => 'required|array|min:1',
            'sop_checklist.*' => 'required|string|max:255',
            'status' => 'boolean'
        ]);

        // Clean SOP checklist
        $validated['sop_checklist'] = array_filter($validated['sop_checklist']);
        
        $kriteriaPenilaian->update($validated);

        return redirect()
            ->route('admin.kriteria-penilaian.index')
            ->with('success', 'Kriteria penilaian berhasil diperbarui.');
    }

    /**
     * Remove the specified kriteria
     */
    public function destroy(KriteriaPenilaian $kriteriaPenilaian): RedirectResponse
    {
        // Avoid querying non-existent tables during early setup
        if (\Illuminate\Support\Facades\Schema::hasTable('detail_penilaian')) {
            // Check if kriteria is being used
            if ($kriteriaPenilaian->nilaiPraktik()->count() > 0) {
                return redirect()
                    ->route('admin.kriteria-penilaian.index')
                    ->with('error', 'Tidak dapat menghapus kriteria yang sedang digunakan untuk penilaian.');
            }
        }

        $kriteriaPenilaian->delete();

        return redirect()
            ->route('admin.kriteria-penilaian.index')
            ->with('success', 'Kriteria penilaian berhasil dihapus.');
    }

    /**
     * Seed default kriteria
     */
    public function seedDefault(): RedirectResponse
    {
        try {
            KriteriaPenilaian::seedDefault();
            
            return redirect()
                ->route('admin.kriteria-penilaian.index')
                ->with('success', 'Default kriteria penilaian berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.kriteria-penilaian.index')
                ->with('error', 'Gagal menambahkan default kriteria: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status kriteria
     */
    public function toggleStatus(KriteriaPenilaian $kriteriaPenilaian): RedirectResponse
    {
        $kriteriaPenilaian->update(['status' => !$kriteriaPenilaian->status]);
        
        $status = $kriteriaPenilaian->status ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()
            ->route('admin.kriteria-penilaian.index')
            ->with('success', "Kriteria berhasil {$status}.");
    }

    /**
     * Get kriteria by mata praktik dan tingkat kelas (for AJAX)
     */
    public function getKriteria(Request $request)
    {
        $mataPraktik = $request->input('mata_praktik');
        $tingkatKelas = $request->input('tingkat_kelas');
        
        $kriteria = KriteriaPenilaian::active()
                                   ->byMataPraktik($mataPraktik)
                                   ->byTingkatKelas($tingkatKelas)
                                   ->orderBy('kategori')
                                   ->get();
        
        return response()->json($kriteria);
    }

    /**
     * Validate total bobot (should be 1.0)
     */
    public function validateBobot(Request $request)
    {
        $mataPraktik = $request->input('mata_praktik');
        $tingkatKelas = $request->input('tingkat_kelas');
        $excludeId = $request->input('exclude_id');
        
        $query = KriteriaPenilaian::active()
                                ->byMataPraktik($mataPraktik)
                                ->byTingkatKelas($tingkatKelas);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $totalBobot = $query->sum('bobot');
        $newBobot = (int) $request->input('bobot', 0);
        $totalWithNew = $totalBobot + $newBobot;

        return response()->json([
            'current_total' => $totalBobot,
            'new_total' => $totalWithNew,
            'is_valid' => $totalWithNew <= 100,
            'remaining' => 100 - $totalBobot
        ]);
    }

    /**
     * Duplicate kriteria to different mata praktik or tingkat
     */
    public function duplicate(Request $request, KriteriaPenilaian $kriteriaPenilaian): RedirectResponse
    {
        $validated = $request->validate([
            'target_mata_praktik' => 'required|string|max:255',
            'target_tingkat_kelas' => 'required|in:X,XI,XII'
        ]);

        // Check if kriteria already exists
        $exists = KriteriaPenilaian::where('nama', $kriteriaPenilaian->nama)
                                 ->where('mata_praktik', $validated['target_mata_praktik'])
                                 ->where('tingkat_kelas', $validated['target_tingkat_kelas'])
                                 ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->with('error', 'Kriteria dengan nama yang sama sudah ada di target mata praktik dan tingkat kelas.');
        }

        // Duplicate kriteria
        $newKriteria = $kriteriaPenilaian->replicate();
        $newKriteria->mata_praktik = $validated['target_mata_praktik'];
        $newKriteria->tingkat_kelas = $validated['target_tingkat_kelas'];
        $newKriteria->save();

        return redirect()
            ->route('admin.kriteria-penilaian.index')
            ->with('success', 'Kriteria berhasil diduplikasi.');
    }

    /**
     * Export kriteria template
     */
    public function exportTemplate()
    {
        $kriteria = KriteriaPenilaian::active()->get();
        
        $filename = 'template_kriteria_penilaian_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($kriteria->toArray())
                        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}