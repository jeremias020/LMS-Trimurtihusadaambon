<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalUjian;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Notification;
use App\Models\ScheduledNotification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JadwalUjianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of jadwal ujian
     */
    public function index(): View
    {
        $jadwal = JadwalUjian::with(['kelas.jurusan', 'pengawas'])
                            ->orderBy('tanggal')
                            ->orderBy('waktu_mulai')
                            ->paginate(20);
                            
        return view('admin.jadwal-ujian.index', compact('jadwal'));
    }

    /**
     * Show the form for creating new jadwal ujian
     */
    public function create(): View
    {
        $kelas = Kelas::with('jurusan')->get();
        $pengawas = User::where('role', 'guru')->where('status', 'active')->get();
        $jenisUjianList = JadwalUjian::getJenisUjianList();
        
        return view('admin.jadwal-ujian.create', compact('kelas', 'pengawas', 'jenisUjianList'));
    }

    /**
     * Store newly created jadwal ujian
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date|after:today',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'mata_pelajaran' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_ujian' => 'required|in:quiz,uts,uas,praktik',
            'pengawas_id' => 'nullable|exists:users,id',
            'ruangan' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string'
        ]);

        // Validate pengawas
        if ($validated['pengawas_id']) {
            $pengawas = User::find($validated['pengawas_id']);
            if ($pengawas->role !== 'guru' || $pengawas->status !== 'active') {
                return redirect()
                    ->back()
                    ->with('error', 'Pengawas harus guru yang aktif.')
                    ->withInput();
            }
        }

        // Check for conflicting schedules
        $conflicts = JadwalUjian::where('tanggal', $validated['tanggal'])
                               ->where(function($query) use ($validated) {
                                   $query->where('kelas_id', $validated['kelas_id'])
                                         ->orWhere('pengawas_id', $validated['pengawas_id'])
                                         ->orWhere('ruangan', $validated['ruangan']);
                               })
                               ->where(function($query) use ($validated) {
                                   $query->whereBetween('waktu_mulai', [$validated['waktu_mulai'], $validated['waktu_selesai']])
                                         ->orWhereBetween('waktu_selesai', [$validated['waktu_mulai'], $validated['waktu_selesai']])
                                         ->orWhere(function($q) use ($validated) {
                                             $q->where('waktu_mulai', '<=', $validated['waktu_mulai'])
                                               ->where('waktu_selesai', '>=', $validated['waktu_selesai']);
                                         });
                               })
                               ->exists();

        if ($conflicts) {
            return redirect()
                ->back()
                ->with('error', 'Terdapat konflik jadwal dengan kelas, pengawas, atau ruangan yang sama.')
                ->withInput();
        }

        JadwalUjian::create($validated);

        return redirect()
            ->route('admin.jadwal-ujian.index')
            ->with('success', 'Jadwal ujian berhasil ditambahkan dan notifikasi otomatis telah dijadwalkan.');
    }

    /**
     * Display the specified jadwal ujian
     */
    public function show(JadwalUjian $jadwalUjian): View
    {
        $jadwalUjian->load(['kelas.jurusan', 'pengawas', 'scheduledNotifications']);
        
        return view('admin.jadwal-ujian.show', compact('jadwalUjian'));
    }

    /**
     * Show the form for editing jadwal ujian
     */
    public function edit(JadwalUjian $jadwalUjian): View
    {
        $kelas = Kelas::with('jurusan')->get();
        $pengawas = User::where('role', 'guru')->where('status', 'active')->get();
        $jenisUjianList = JadwalUjian::getJenisUjianList();
        
        return view('admin.jadwal-ujian.edit', compact('jadwalUjian', 'kelas', 'pengawas', 'jenisUjianList'));
    }

    /**
     * Update the specified jadwal ujian
     */
    public function update(Request $request, JadwalUjian $jadwalUjian): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date|after:today',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'mata_pelajaran' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_ujian' => 'required|in:quiz,uts,uas,praktik',
            'pengawas_id' => 'nullable|exists:users,id',
            'ruangan' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled'
        ]);

        // Validate pengawas
        if ($validated['pengawas_id']) {
            $pengawas = User::find($validated['pengawas_id']);
            if ($pengawas->role !== 'guru' || $pengawas->status !== 'active') {
                return redirect()
                    ->back()
                    ->with('error', 'Pengawas harus guru yang aktif.')
                    ->withInput();
            }
        }

        // Check for conflicting schedules (excluding current)
        $conflicts = JadwalUjian::where('id', '!=', $jadwalUjian->id)
                               ->where('tanggal', $validated['tanggal'])
                               ->where(function($query) use ($validated) {
                                   $query->where('kelas_id', $validated['kelas_id'])
                                         ->orWhere('pengawas_id', $validated['pengawas_id'])
                                         ->orWhere('ruangan', $validated['ruangan']);
                               })
                               ->where(function($query) use ($validated) {
                                   $query->whereBetween('waktu_mulai', [$validated['waktu_mulai'], $validated['waktu_selesai']])
                                         ->orWhereBetween('waktu_selesai', [$validated['waktu_mulai'], $validated['waktu_selesai']])
                                         ->orWhere(function($q) use ($validated) {
                                             $q->where('waktu_mulai', '<=', $validated['waktu_mulai'])
                                               ->where('waktu_selesai', '>=', $validated['waktu_selesai']);
                                         });
                               })
                               ->exists();

        if ($conflicts) {
            return redirect()
                ->back()
                ->with('error', 'Terdapat konflik jadwal dengan kelas, pengawas, atau ruangan yang sama.')
                ->withInput();
        }

        $jadwalUjian->update($validated);

        return redirect()
            ->route('admin.jadwal-ujian.index')
            ->with('success', 'Jadwal ujian berhasil diperbarui.');
    }

    /**
     * Remove the specified jadwal ujian
     */
    public function destroy(JadwalUjian $jadwalUjian): RedirectResponse
    {
        // Only allow deletion of scheduled exams
        if ($jadwalUjian->status !== 'scheduled') {
            return redirect()
                ->route('admin.jadwal-ujian.index')
                ->with('error', 'Hanya jadwal ujian yang berstatus "Terjadwal" yang dapat dihapus.');
        }

        $jadwalUjian->delete();

        return redirect()
            ->route('admin.jadwal-ujian.index')
            ->with('success', 'Jadwal ujian berhasil dihapus.');
    }

    /**
     * Get upcoming exams
     */
    public function upcoming(): View
    {
        $upcomingExams = JadwalUjian::upcoming()
                                  ->with(['kelas.jurusan', 'pengawas'])
                                  ->limit(10)
                                  ->get();
                                  
        return view('admin.jadwal-ujian.upcoming', compact('upcomingExams'));
    }

    /**
     * Update exam status
     */
    public function updateStatus(Request $request, JadwalUjian $jadwalUjian): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,ongoing,completed,cancelled'
        ]);

        $jadwalUjian->update(['status' => $validated['status']]);
        
        $statusLabels = JadwalUjian::getStatusList();
        
        return redirect()
            ->back()
            ->with('success', 'Status ujian berhasil diubah ke: ' . $statusLabels[$validated['status']]);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,update_status,change_pengawas',
            'jadwal_ids' => 'required|array|min:1',
            'jadwal_ids.*' => 'exists:jadwal_ujian,id',
            'status' => 'nullable|in:scheduled,ongoing,completed,cancelled',
            'pengawas_id' => 'nullable|exists:users,id'
        ]);

        $jadwalCount = count($validated['jadwal_ids']);
        
        switch ($validated['action']) {
            case 'delete':
                JadwalUjian::whereIn('id', $validated['jadwal_ids'])
                          ->where('status', 'scheduled')
                          ->delete();
                          
                return redirect()
                    ->route('admin.jadwal-ujian.index')
                    ->with('success', "{$jadwalCount} jadwal ujian berhasil dihapus.");
                    
            case 'update_status':
                if (!$validated['status']) {
                    return redirect()->back()->with('error', 'Status harus dipilih.');
                }
                
                JadwalUjian::whereIn('id', $validated['jadwal_ids'])
                          ->update(['status' => $validated['status']]);
                          
                return redirect()
                    ->route('admin.jadwal-ujian.index')
                    ->with('success', "Status {$jadwalCount} jadwal ujian berhasil diubah.");
                    
            case 'change_pengawas':
                if (!$validated['pengawas_id']) {
                    return redirect()->back()->with('error', 'Pengawas harus dipilih.');
                }
                
                JadwalUjian::whereIn('id', $validated['jadwal_ids'])
                          ->update(['pengawas_id' => $validated['pengawas_id']]);
                          
                return redirect()
                    ->route('admin.jadwal-ujian.index')
                    ->with('success', "Pengawas untuk {$jadwalCount} jadwal ujian berhasil diubah.");
        }
        
        return redirect()->back();
    }

    /**
     * Get siswa list for specific jadwal
     */
    public function getSiswaList(JadwalUjian $jadwalUjian)
    {
        $siswa = $jadwalUjian->getSiswaList();
        
        return response()->json([
            'siswa' => $siswa,
            'total' => $siswa->count()
        ]);
    }

    /**
     * Check room availability
     */
    public function checkRoomAvailability(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $waktuMulai = $request->input('waktu_mulai');
        $waktuSelesai = $request->input('waktu_selesai');
        $excludeId = $request->input('exclude_id');
        
        $busyRooms = JadwalUjian::where('tanggal', $tanggal)
                               ->when($excludeId, function($query) use ($excludeId) {
                                   return $query->where('id', '!=', $excludeId);
                               })
                               ->where(function($query) use ($waktuMulai, $waktuSelesai) {
                                   $query->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                                         ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                                         ->orWhere(function($q) use ($waktuMulai, $waktuSelesai) {
                                             $q->where('waktu_mulai', '<=', $waktuMulai)
                                               ->where('waktu_selesai', '>=', $waktuSelesai);
                                         });
                               })
                               ->whereNotNull('ruangan')
                               ->pluck('ruangan')
                               ->toArray();
        
        $allRooms = ['Lab 1', 'Lab 2', 'Lab 3', 'Ruang 101', 'Ruang 102', 'Ruang 103'];
        $availableRooms = array_diff($allRooms, $busyRooms);
        
        return response()->json([
            'available_rooms' => array_values($availableRooms),
            'busy_rooms' => $busyRooms
        ]);
    }
    
    /**
     * Send notification to teachers and students about upcoming exam
     */
    public function sendNotification(JadwalUjian $jadwal): RedirectResponse
    {
        // Ambil data guru dan siswa yang terkait dengan jadwal ujian
        $pengawas = User::find($jadwal->pengawas_id);
        $siswa = User::where('role', 'siswa')
                    ->whereHas('student', function($query) use ($jadwal) {
                        $query->where('kelas_id', $jadwal->kelas_id);
                    })
                    ->get();
        
        // Buat notifikasi untuk guru pengawas
        if ($pengawas) {
            $pengawas->notifications()->create([
                'title' => 'Jadwal Ujian: ' . $jadwal->mata_pelajaran,
                'content' => "Anda dijadwalkan sebagai pengawas ujian {$jadwal->jenis_ujian} {$jadwal->mata_pelajaran} pada tanggal " . 
                            date('d-m-Y', strtotime($jadwal->tanggal)) . " pukul {$jadwal->waktu_mulai} - {$jadwal->waktu_selesai} di {$jadwal->ruangan}.",
                'type' => 'jadwal_ujian',
                'read' => false,
                'data' => json_encode([
                    'jadwal_id' => $jadwal->id,
                    'tanggal' => $jadwal->tanggal,
                    'waktu_mulai' => $jadwal->waktu_mulai,
                    'waktu_selesai' => $jadwal->waktu_selesai,
                    'ruangan' => $jadwal->ruangan
                ])
            ]);
        }
        
        // Buat notifikasi untuk siswa
        foreach ($siswa as $student) {
            $student->notifications()->create([
                'title' => 'Jadwal Ujian: ' . $jadwal->mata_pelajaran,
                'content' => "Ujian {$jadwal->jenis_ujian} {$jadwal->mata_pelajaran} akan dilaksanakan pada tanggal " . 
                            date('d-m-Y', strtotime($jadwal->tanggal)) . " pukul {$jadwal->waktu_mulai} - {$jadwal->waktu_selesai} di {$jadwal->ruangan}.",
                'type' => 'jadwal_ujian',
                'read' => false,
                'data' => json_encode([
                    'jadwal_id' => $jadwal->id,
                    'tanggal' => $jadwal->tanggal,
                    'waktu_mulai' => $jadwal->waktu_mulai,
                    'waktu_selesai' => $jadwal->waktu_selesai,
                    'ruangan' => $jadwal->ruangan
                ])
            ]);
        }
        
        // Jadwalkan notifikasi otomatis untuk 1 hari sebelum ujian
        $jadwal->scheduledNotifications()->create([
            'scheduled_at' => date('Y-m-d H:i:s', strtotime($jadwal->tanggal . ' ' . $jadwal->waktu_mulai . ' -1 day')),
            'title' => 'Pengingat Ujian: ' . $jadwal->mata_pelajaran,
            'content' => "Pengingat: Ujian {$jadwal->jenis_ujian} {$jadwal->mata_pelajaran} akan dilaksanakan besok pada pukul {$jadwal->waktu_mulai} - {$jadwal->waktu_selesai} di {$jadwal->ruangan}.",
            'recipient_type' => 'all',
            'status' => 'pending'
        ]);
        
        return redirect()
            ->route('admin.jadwal-ujian.index')
            ->with('success', 'Notifikasi jadwal ujian berhasil dikirim ke guru dan siswa.');
    }
    
    /**
     * Automatically send notifications for upcoming exams
     * This method can be called by a scheduled task
     */
    public function sendAutomaticNotifications()
    {
        // Ambil jadwal ujian yang akan dilaksanakan dalam 3 hari ke depan
        $upcomingExams = JadwalUjian::whereBetween('tanggal', [
                now()->format('Y-m-d'), 
                now()->addDays(3)->format('Y-m-d')
            ])
            ->whereDoesntHave('scheduledNotifications', function($query) {
                $query->where('status', 'sent')
                      ->where('scheduled_at', '>=', now()->subDays(1));
            })
            ->get();
            
        foreach ($upcomingExams as $exam) {
            $this->sendNotification($exam);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi otomatis berhasil dikirim untuk ' . $upcomingExams->count() . ' jadwal ujian.'
        ]);
    }
}