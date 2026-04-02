<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Subject;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Practical;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use App\Models\NilaiPraktik;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

// ✅ PERBAIKI USE STATEMENT INI:
use App\Http\Controllers\Controller; // Dari ini
// use Illuminate\Routing\Controller; // Atau ini (pilih salah satu)

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kelas = Kelas::orderBy('grade')->orderBy('name')->get();
        $jurusans = Jurusan::orderBy('name')->get();
        return view('admin.users.create', compact('kelas', 'jurusans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,guru,siswa',
            'password' => 'required|min:8|confirmed',
        ];

        // Conditional rules based on role
        // For Guru
        $rules['nip'] = 'required_if:role,guru|string|min:3';
        $rules['subject'] = 'required_if:role,guru|string|min:2';

        // For Siswa
        $rules['nis'] = 'required_if:role,siswa|string|min:3';
        $rules['kelas_id'] = 'required_if:role,siswa|exists:kelas,id';
        $rules['jurusan_id'] = 'nullable|exists:jurusan_new,id';
        $rules['birth_date'] = 'required_if:role,siswa|date';
        $rules['address'] = 'required_if:role,siswa|string|min:5';

        $messages = [
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password harus sama dengan password.',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain.',
            'role.required' => 'Silakan pilih role pengguna.',
            'nip.required_if' => 'NIP wajib diisi untuk role Guru.',
            'subject.required_if' => 'Mata pelajaran wajib dipilih untuk role Guru.',
            'nis.required_if' => 'NIS wajib diisi untuk role Siswa.',
            'class.required_if' => 'Kelas wajib dipilih untuk role Siswa.',
            'birth_date.required_if' => 'Tanggal lahir wajib diisi untuk role Siswa.',
            'address.required_if' => 'Alamat wajib diisi untuk role Siswa.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'status' => 'active',
            ]);

            // Attach class and major (jurusan) for student role
            if ($request->role === 'siswa') {
                // Set kelas_id and jurusan_id on user if provided
                if ($request->filled('kelas_id')) {
                    $user->kelas_id = (int) $request->kelas_id;
                }
                if ($request->filled('jurusan_id')) {
                    $user->jurusan_id = (int) $request->jurusan_id;
                }
                $user->save();
            }

            DB::commit();

            Log::info('User created successfully', [
                'email' => $request->email,
                'role' => $request->role,
                'ip' => $request->ip()
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User creation failed: ' . $e->getMessage(), [
                'email' => $request->email,
                'ip' => $request->ip()
            ]);
            return redirect()->back()->with('error', 'Gagal menambah user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Eager load relations needed for the detail page
        $user = User::with(['guru', 'siswa.kelas', 'kelas', 'jurusan'])->findOrFail($id);

        // Prepare stats based on role
        $stats = [];
        if ($user->isGuru()) {
            $stats = [
                'materials_count' => Material::where('guru_id', $user->id)->count(),
                'assignments_count' => Assignment::where('guru_id', $user->id)->count(),
                'practicals_count' => Practical::where('guru_id', $user->id)->count(),
                // Catatan: tanpa relasi pengajar-kelas yang jelas, gunakan estimasi jumlah siswa total
                'students_count' => \App\Models\Student::count(),
            ];
        } elseif ($user->isSiswa()) {
            $totalAttendance = Attendance::where('siswa_id', $user->id)->count();
            $presentAttendance = Attendance::where('siswa_id', $user->id)->where('status', 'hadir')->count();
            $kelasId = $user->kelas_id; // accessor yang mengembalikan kelas_id siswa

            $pendingTasks = 0;
            if ($kelasId) {
                $pendingTasks = Assignment::where('kelas_id', $kelasId)
                    ->published()
                    ->whereDoesntHave('submissions', function ($q) use ($user) {
                        $q->where('siswa_id', $user->id);
                    })
                    ->count();
            }

            $stats = [
                'completed_assignments' => AssignmentSubmission::where('siswa_id', $user->id)->count(),
                'average_score' => round((float) (Score::where('siswa_id', $user->id)->avg('score') ?? 0)),
                'attendance_rate' => $totalAttendance > 0 ? round($presentAttendance * 100 / $totalAttendance) : 0,
                'pending_tasks' => $pendingTasks,
                'practical_grades_count' => NilaiPraktik::where('siswa_id', $user->id)->final()->count(),
                'practical_average' => round((float) (NilaiPraktik::where('siswa_id', $user->id)->final()->avg('total_nilai') ?? 0), 1),
                'practical_latest_grade' => NilaiPraktik::where('siswa_id', $user->id)->final()->latest()->first(),
            ];
        }

        // Recent activities (optional, if activitylog package is available)
        try {
            $activities = Activity::causedBy($user)->latest()->limit(10)->get();
        } catch (\Throwable $e) {
            $activities = collect();
            Log::warning('Activity log not available: ' . $e->getMessage());
        }

        return view('admin.users.show', compact('user', 'stats', 'activities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $user = User::findOrFail($id);
        $kelas = Kelas::orderBy('grade')->orderBy('name')->get();
        $jurusans = Jurusan::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'kelas', 'jurusans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,guru,siswa',
            'password' => 'nullable|min:6|confirmed',
            // siswa-specific
            'kelas_id' => 'required_if:role,siswa|exists:kelas,id',
            'jurusan_id' => 'nullable|exists:jurusan,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            // Apply password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // For siswa, persist kelas_id and jurusan_id
            if ($request->role === 'siswa') {
                if ($request->filled('kelas_id')) {
                    $user->kelas_id = (int) $request->kelas_id;
                }
                // jurusan_id optional
                $user->jurusan_id = $request->filled('jurusan_id') ? (int) $request->jurusan_id : null;
                $user->save();
            } else {
                // For non-student roles, clear class/jurusan if desired (optional)
                // $user->kelas_id = null;
                // $user->jurusan_id = null;
                // $user->save();
            }

            DB::commit();

            Log::info('User updated successfully', [
                'user_id' => $id,
                'ip' => $request->ip()
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User update failed: ' . $e->getMessage(), [
                'user_id' => $id,
                'ip' => $request->ip()
            ]);
            return redirect()->back()->with('error', 'Gagal memperbarui user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            Log::info('User deleted successfully', [
                'user_id' => $id,
                'ip' => request()->ip()
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('User deletion failed: ' . $e->getMessage(), [
                'user_id' => $id,
                'ip' => request()->ip()
            ]);
            return redirect()->back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    /**
     * Update user status (custom method untuk route POST)
     */
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $user->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status user berhasil diperbarui.');
    }

    /**
     * Handle bulk actions for users
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $users = User::whereIn('id', $request->user_ids);
            $count = count($request->user_ids);

            switch ($request->action) {
                case 'delete':
                    $users->delete();
                    $message = "$count user(s) berhasil dihapus.";
                    break;
                    
                case 'activate':
                    $users->update(['status' => 'active']);
                    $message = "$count user(s) berhasil diaktifkan.";
                    break;
                    
                case 'deactivate':
                    $users->update(['status' => 'inactive']);
                    $message = "$count user(s) berhasil dinonaktifkan.";
                    break;
            }

            DB::commit();

            Log::info('Bulk action performed on users', [
                'action' => $request->action,
                'user_count' => $count,
                'ip' => $request->ip()
            ]);

            return redirect()->route('admin.users.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk action failed: ' . $e->getMessage(), [
                'action' => $request->action,
                'user_ids' => $request->user_ids,
                'ip' => $request->ip()
            ]);
            return redirect()->back()->with('error', 'Gagal melakukan bulk action: ' . $e->getMessage());
        }
    }
}
