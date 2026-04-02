<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Attendance::with(['siswa']);

            // Filter by date range
            if ($request->filled('start_date')) {
                $query->whereDate('date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('date', '<=', $request->end_date);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by student
            if ($request->filled('siswa_id')) {
                $query->where('siswa_id', $request->siswa_id);
            }

            $attendances = $query->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            // Get students for filter dropdown
            $students = User::where('role', 'siswa')
                ->orderBy('name')
                ->get();

            // Get statistics
            $stats = $this->getAttendanceStats($request);

            return view('admin.attendance.index', compact('attendances', 'students', 'stats'));
        } catch (\Exception $e) {
            return view('admin.attendance.index', [
                'attendances' => collect(),
                'students' => collect(),
                'stats' => [
                    'total' => 0,
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alpha' => 0,
                    'attendance_rate' => 0
                ],
                'error' => 'Error loading attendance data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $students = User::where('role', 'siswa')
                ->orderBy('name')
                ->get();

            return view('admin.attendance.create', compact('students'));
        } catch (\Exception $e) {
            return view('admin.attendance.create', [
                'students' => collect(),
                'error' => 'Error loading students: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:255',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i|after:waktu_masuk',
        ]);

        $data = $request->all();
        
        // Combine date and time for waktu_masuk and waktu_keluar
        if ($request->waktu_masuk) {
            $data['waktu_masuk'] = Carbon::parse($request->date . ' ' . $request->waktu_masuk);
        }
        if ($request->waktu_keluar) {
            $data['waktu_keluar'] = Carbon::parse($request->date . ' ' . $request->waktu_keluar);
        }

        Attendance::create($data);

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Data absensi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        $attendance->load(['siswa']);
        return view('admin.attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $students = User::where('role', 'siswa')
            ->orderBy('name')
            ->get();

        return view('admin.attendance.edit', compact('attendance', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'siswa_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:255',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i|after:waktu_masuk',
        ]);

        $data = $request->all();
        
        // Combine date and time for waktu_masuk and waktu_keluar
        if ($request->waktu_masuk) {
            $data['waktu_masuk'] = Carbon::parse($request->date . ' ' . $request->waktu_masuk);
        } else {
            $data['waktu_masuk'] = null;
        }
        
        if ($request->waktu_keluar) {
            $data['waktu_keluar'] = Carbon::parse($request->date . ' ' . $request->waktu_keluar);
        } else {
            $data['waktu_keluar'] = null;
        }

        $attendance->update($data);

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Data absensi berhasil dihapus.');
    }

    /**
     * Bulk update attendance status
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'attendance_ids' => 'required|array',
            'attendance_ids.*' => 'exists:attendances,id',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:255'
        ]);

        Attendance::whereIn('id', $request->attendance_ids)
            ->update([
                'status' => $request->status,
                'keterangan' => $request->keterangan
            ]);

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Data absensi berhasil diperbarui secara massal.');
    }

    /**
     * Get attendance statistics
     */
    private function getAttendanceStats($request)
    {
        $query = Attendance::query();

        // Apply same filters as main query
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        $total = $query->count();
        $hadir = (clone $query)->where('status', 'hadir')->count();
        $izin = (clone $query)->where('status', 'izin')->count();
        $sakit = (clone $query)->where('status', 'sakit')->count();
        $alpha = (clone $query)->where('status', 'alpha')->count();

        return [
            'total' => $total,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpha' => $alpha,
            'attendance_rate' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0
        ];
    }
}
