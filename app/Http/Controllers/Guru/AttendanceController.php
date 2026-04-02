<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:guru');
    }
    
    /**
     * Display praktik attendance form
     */
    public function praktikAttendance(): View
    {
        $date = request('date', Carbon::today()->format('Y-m-d'));
        $class = request('class', 'all');
        $practical_id = request('practical_id');
        
        // Get available praktik
        $practicals = \App\Models\Practical::where('guru_id', Auth::id())
            ->where('status', 'active')
            ->get();
            
        $query = Attendance::with('siswa.kelas')
            ->where('type', 'praktik')
            ->whereDate('date', $date);
            
        if ($practical_id) {
            $query->where('practical_id', $practical_id);
        }

        if ($class !== 'all') {
            $query->whereHas('siswa', function($q) use ($class) {
                $q->where('kelas_id', $class);
            });
        }

        $attendances = $query->latest()->paginate(25);

        $classes = \App\Models\Kelas::where('status', 'active')
            ->whereHas('students')
            ->pluck('name', 'id');
            
        // Get status counts
        $statusCounts = [
            'hadir' => $query->clone()->where('status', 'hadir')->count(),
            'izin' => $query->clone()->where('status', 'izin')->count(),
            'sakit' => $query->clone()->where('status', 'sakit')->count(),
            'alpha' => $query->clone()->where('status', 'alpha')->count(),
        ];

        return view('guru.attendance.praktik', [
            'attendances' => $attendances,
            'classes' => $classes,
            'date' => $date,
            'selectedClass' => $class,
            'statusCounts' => $statusCounts,
            'practicals' => $practicals,
            'practical_id' => $practical_id,
        ]);
    }

    /**
     * Display a listing of attendances.
     */
    public function index(): View
    {
        try {
            // Default to last 30 days if no date specified
            $date = request('date', null);
            $class = request('class', 'all');
            $type = request('type', null);

            // Start with base query - load relationships properly
            $query = Attendance::with([
                'siswa.kelas',
                'subject'
            ])->whereIn('class_subject_id', function($q) {
                $q->select('id')
                  ->from('class_subjects')
                  ->where('teacher_id', Auth::id());
            });

            // If specific date provided, filter by date
            if ($date) {
                $query->whereDate('date', $date);
            } else {
                // Default: show last 30 days of attendance
                $query->where('date', '>=', Carbon::now()->subDays(30));
            }

            if ($class !== 'all') {
                $query->whereHas('siswa.kelas', function($q) use ($class) {
                    $q->where('kelas_id', $class);
                });
            }
            
            // Filter berdasarkan tipe absensi hanya jika type specified
            if ($type) {
                $query->where('type', $type);
            }

            // Get paginated results with proper ordering
            $attendances = $query->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            // Get all active classes
            $classes = \App\Models\Kelas::orderBy('name')
                ->pluck('name', 'id');

            // Get all active subjects
            $subjects = \App\Models\Subject::where('is_active', true)
                ->orderBy('name')
                ->get();

            // Get stats efficiently
            $statsQuery = Attendance::query();
            
            if ($date) {
                $statsQuery->whereDate('date', $date);
            } else {
                $statsQuery->where('date', '>=', Carbon::now()->subDays(30));
            }
            
            if ($type) {
                $statsQuery->where('type', $type);
            }
            
            $statsData = $statsQuery->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            $stats = [
                'total' => $attendances->total(),
                'hadir' => $statsData['present'] ?? 0,
                'izin' => $statsData['permission'] ?? 0,
                'sakit' => $statsData['sick'] ?? 0,
                'alpha' => $statsData['alpha'] ?? 0,
            ];
            
            // Debug logging with detailed info
            \Log::info('Guru Attendance Debug', [
                'attendances_count' => $attendances->count(),
                'attendances_total' => $attendances->total(),
                'attendances_type' => get_class($attendances),
                'classes_count' => $classes->count(),
                'subjects_count' => $subjects->count(),
                'date' => $date,
                'class' => $class,
                'type' => $type,
                'query_sql' => $query->toSql(),
                'has_attendances_table' => \Schema::hasTable('attendances'),
                'attendances_table_count' => \DB::table('attendances')->count(),
                'sample_attendance' => $attendances->first(),
                'sample_attendance_relations' => $attendances->first() ? [
                    'has_siswa' => $attendances->first()->relationLoaded('siswa'),
                    'siswa_id' => $attendances->first()->siswa_id,
                    'siswa_data' => $attendances->first()->siswa,
                    'siswa_name' => $attendances->first()->siswa?->name,
                    'siswa_kelas' => $attendances->first()->siswa?->kelas,
                    'has_subject' => $attendances->first()->relationLoaded('subject'),
                    'subject_id' => $attendances->first()->subject_id,
                    'subject_data' => $attendances->first()->subject,
                ] : null
            ]);
            
            return view('guru.absensi.index', compact('attendances', 'date', 'stats', 'classes', 'class', 'subjects', 'type'));
        } catch (\Exception $e) {
            Log::error('Error in guru attendance index: ' . $e->getMessage());
            return view('guru.absensi.index', [
                'attendances' => collect(),
                'date' => Carbon::today()->format('Y-m-d'),
                'stats' => ['total' => 0, 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0],
                'classes' => collect(),
                'class' => 'all',
                'subjects' => collect(),
                'type' => null,
                'error' => 'Terjadi kesalahan saat memuat data absensi. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Show the form for creating a new attendance record.
     */
    public function create(): View
    {
        try {
            $guruId = Auth::id();
            
            // Get classes where this guru teaches
            $classes = \DB::table('classes')
                ->join('class_subjects', 'classes.id', '=', 'class_subjects.class_id')
                ->where('class_subjects.teacher_id', $guruId)
                ->distinct()
                ->select('classes.id', 'classes.name')
                ->orderBy('classes.name')
                ->pluck('name', 'id');

            $selectedClass = request('class');
            
            // Get students with proper filtering and ordering
            if ($selectedClass) {
                $siswas = \DB::table('users')
                    ->join('class_students', 'users.id', '=', 'class_students.student_id')
                    ->where('users.role', 'siswa')
                    ->where('class_students.class_id', $selectedClass)
                    ->orderBy('users.name')
                    ->select('users.*', 'class_students.class_id')
                    ->get();
            } else {
                $siswas = collect(); // Empty collection if no class selected
            }
                
            // Get subjects assigned to this guru
            $subjects = \DB::table('class_subjects')
                ->join('subjects', 'class_subjects.subject_id', '=', 'subjects.id')
                ->where('class_subjects.teacher_id', $guruId)
                ->where('subjects.is_active', true)
                ->select('class_subjects.id', 'subjects.name')
                ->distinct()
                ->orderBy('subjects.name')
                ->get();

            return view('guru.absensi.create', compact('siswas', 'classes', 'selectedClass', 'subjects'));
        } catch (\Exception $e) {
            return view('guru.absensi.create', [
                'siswas' => collect(),
                'classes' => collect(),
                'selectedClass' => null,
                'subjects' => collect(),
                'error' => 'Error loading create form: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created attendance record.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:users,id',
            'date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:500',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i|after:waktu_masuk',
            'type' => 'nullable|in:regular,praktik',
            'practical_id' => 'required_if:type,praktik|nullable|exists:practicals,id',
        ], [
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini',
            'waktu_keluar.after' => 'Waktu keluar harus setelah waktu masuk',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form');
        }

        // ✅ Cek duplikasi
        if ($this->isAttendanceExists($request->siswa_id, $request->date)) {
            return back()->with('error', 'Absensi untuk siswa ini pada tanggal tersebut sudah ada.')
                ->withInput();
        }

        try {
            $attendance = Attendance::create([
                'siswa_id' => $request->siswa_id,
                'date' => $request->date,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'waktu_masuk' => $request->waktu_masuk,
                'waktu_keluar' => $request->waktu_keluar,
                'type' => $request->type ?? 'regular',
                'practical_id' => $request->practical_id,
                'recorded_by' => Auth::id(),
            ]);

            Log::info('Attendance created', [
                'attendance_id' => $attendance->id,
                'siswa_id' => $request->siswa_id,
                'date' => $request->date,
                'status' => $request->status,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            if ($request->type == 'praktik') {
                return redirect()->route('guru.absensi.praktik')
                    ->with('success', 'Absensi praktik berhasil dicatat!');
            }

            return redirect()->route('guru.absensi.index')
                ->with('success', 'Absensi berhasil dicatat!');

        } catch (\Exception $e) {
            Log::error('Attendance creation failed: ' . $e->getMessage(), [
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
    
    /**
     * Store praktik attendance for multiple students at once.
     */
    public function storePraktikBatch(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'practical_id' => 'required|exists:practicals,id',
            'status' => 'required|array',
            'status.*' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Verify that the practical belongs to the authenticated teacher
            $practical = \App\Models\Practical::where('id', $request->practical_id)
                ->where('guru_id', Auth::id())
                ->firstOrFail();
                
            // Get students for this practical's class
            $students = User::where('role', 'siswa')
                ->whereHas('kelas', function($query) use ($practical) {
                    $query->where('id', $practical->kelas_id);
                })
                ->get();
                
            foreach ($students as $student) {
                if (isset($request->status[$student->id])) {
                    Attendance::updateOrCreate(
                        [
                            'siswa_id' => $student->id,
                            'date' => $request->date,
                            'type' => 'praktik',
                            'practical_id' => $request->practical_id,
                        ],
                        [
                            'status' => $request->status[$student->id],
                            'keterangan' => $request->keterangan[$student->id] ?? null,
                            'recorded_by' => Auth::id(),
                        ]
                    );
                }
            }

            return redirect()->route('guru.absensi.praktik')
                ->with('success', 'Absensi praktik berhasil disimpan untuk semua siswa.');
        } catch (\Exception $e) {
            Log::error('Error creating batch praktik attendance: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan absensi praktik.')
                ->withInput();
        }
    }

    /**
     * Show the form for bulk creating attendance records.
     */
    public function bulkCreate(): View
    {
        $classes = \App\Models\Kelas::whereHas('students', function($query) {
                $query->where('role', 'siswa');
            })
            ->where('status', 'active')
            ->pluck('name', 'id');

        return view('guru.absensi.bulk-create', compact('classes'));
    }

    /**
     * Store multiple attendance records.
     */
    public function bulkStore(Request $request): RedirectResponse
    {
        // ✅ Validasi kelas harus valid
        $validClasses = \App\Models\Kelas::whereHas('students', function($query) {
                $query->where('role', 'siswa');
            })
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();

        $validator = Validator::make($request->all(), [
            'date' => 'required|date|before_or_equal:today',
            'class' => [
                'required',
                Rule::in($validClasses)
            ],
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $siswas = User::where('role', 'siswa')
                ->where('kelas_id', $request->class)
                ->get();

            $createdCount = 0;
            foreach ($siswas as $siswa) {
                if (!$this->isAttendanceExists($siswa->id, $request->date)) {
                    Attendance::create([
                        'siswa_id' => $siswa->id,
                        'date' => $request->date,
                        'status' => $request->status,
                        'keterangan' => $request->keterangan,
                        'recorded_by' => Auth::id(),
                    ]);
                    $createdCount++;
                }
            }

            Log::info('Bulk attendance created', [
                'class' => $request->class,
                'date' => $request->date,
                'status' => $request->status,
                'created_count' => $createdCount,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->route('guru.absensi.index')
                ->with('success', "Absensi massal berhasil dicatat untuk $createdCount siswa!");

        } catch (\Exception $e) {
            Log::error('Bulk attendance creation failed: ' . $e->getMessage(), [
                'class' => $request->class,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the attendance record.
     */
    public function edit(Attendance $absensi): View
    {
        // ✅ Security: Double-check ownership
        if ($absensi->recorded_by !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengedit absensi ini.');
        }

        $this->authorize('update', $absensi);
        return view('guru.absensi.edit', compact('absensi'));
    }

    /**
     * Update the specified attendance record.
     */
    public function update(Request $request, Attendance $absensi): RedirectResponse
    {
        // ✅ Security: Double-check ownership
        if ($absensi->recorded_by !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengedit absensi ini.');
        }

        $this->authorize('update', $absensi);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:500',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i|after:waktu_masuk',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $absensi->update([
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'waktu_masuk' => $request->waktu_masuk,
                'waktu_keluar' => $request->waktu_keluar,
                'updated_by' => Auth::id(),
            ]);

            Log::info('Attendance updated', [
                'attendance_id' => $absensi->id,
                'status' => $request->status,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->route('guru.absensi.index')
                ->with('success', 'Absensi berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Attendance update failed: ' . $e->getMessage(), [
                'attendance_id' => $absensi->id,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified attendance record.
     */
    public function destroy(Attendance $absensi): RedirectResponse
    {
        // ✅ Security: Double-check ownership
        if ($absensi->recorded_by !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan menghapus absensi ini.');
        }

        $this->authorize('delete', $absensi);

        try {
            $absensi->delete();

            Log::info('Attendance deleted', [
                'attendance_id' => $absensi->id,
                'guru_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            return redirect()->route('guru.absensi.index')
                ->with('success', 'Absensi berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Attendance deletion failed: ' . $e->getMessage(), [
                'attendance_id' => $absensi->id,
                'guru_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Check if attendance record already exists.
     */
    private function isAttendanceExists($siswaId, $tanggal): bool
    {
        return Attendance::where('siswa_id', $siswaId)
            ->whereDate('date', $tanggal)
            ->exists();
    }
}