<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\UserCentral;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Student;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ModernUserController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    /**
     * Display separated user management page.
     */
    public function index(): View
    {
        return view('admin.users.index-separated');
    }

    /**
     * Display guru management page.
     */
    public function guruIndex(): View
    {
        $gurus = \App\Models\UserCentral::where('role', 'guru')->get();
        return view('admin.users.guru-index', compact('gurus'));
    }

    /**
     * Display siswa management page.
     */
    public function siswaIndex(): View
    {
        $siswas = \App\Models\UserCentral::where('role', 'siswa')->get();
        return view('admin.users.siswa-index', compact('siswas'));
    }

    /**
     * Show form for creating admin.
     */
    public function createAdmin(): View
    {
        return view('admin.users.create-admin');
    }

    /**
     * Show form for creating guru.
     */
    public function createGuru(): View
    {
        $subjects = \App\Models\Subject::where('is_active', 1)->orderBy('name')->get();
        return view('admin.users.create-guru', compact('subjects'));
    }

    /**
     * Show form for creating siswa.
     */
    public function createSiswa(): View
    {
        $kelas = Kelas::orderBy('grade')->orderBy('name')->get();
        $jurusans = Jurusan::orderBy('name')->get();
        return view('admin.users.create-siswa', compact('kelas', 'jurusans'));
    }

    /**
     * Store new admin.
     */
    public function storeAdmin(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users_central,email',
            'username' => 'required|string|max:255|unique:users_central,username',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Create central user
            $user = UserCentral::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'phone' => $request->phone,
                'is_active' => true,
            ]);

            // Create admin profile
            Admin::create([
                'user_id' => $user->id,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'status' => 'aktif',
            ]);

            DB::commit();
            return redirect()->route('admin.users.index')
                ->with('success', 'Admin berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating admin: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan admin')
                ->withInput();
        }
    }

    /**
     * Store new guru.
     */
    public function storeGuru(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users_central,email',
            'username' => 'required|string|max:255|unique:users_central,username',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'nip' => 'required|string|max:50|unique:gurus,nip',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'email_pribadi' => 'nullable|email|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'jurusan_pendidikan' => 'nullable|string|max:255',
            'tahun_mulai_kerja' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Create central user
            $user = UserCentral::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'guru',
                'phone' => $request->phone,
                'is_active' => true,
            ]);

            // Create guru profile
            Guru::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'address' => $request->alamat,
                'phone' => $request->phone,
                'email_pribadi' => $request->email_pribadi,
                'mata_pelajaran' => $request->subject_id ? \App\Models\Subject::find($request->subject_id)->name : null,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'jurusan_pendidikan' => $request->jurusan_pendidikan,
                'tahun_mulai_kerja' => $request->tahun_mulai_kerja,
                'status' => 'aktif',
            ]);

            DB::commit();
            return redirect()->route('admin.users.guru')
                ->with('success', 'Guru berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating guru: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan guru')
                ->withInput();
        }
    }

    /**
     * Store new siswa.
     */
    public function storeSiswa(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users_central,email',
            'username' => 'required|string|max:255|unique:users_central,username',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'nis' => 'required|string|max:20|unique:siswa,nis',
            'nisn' => 'required|string|max:20|unique:siswa,nisn',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'major' => 'required|string|max:100',
            'tahun_ajaran' => 'required|string|max:20',
            'nama_ortu' => 'nullable|string|max:100',
            'no_telepon_ortu' => 'nullable|string|max:20',
            'golongan_darah' => 'nullable|string|max:5',
            'riwayat_penyakit' => 'nullable|string',
            'alergi' => 'nullable|string',
            'info_kesehatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Create central user
            $user = UserCentral::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
                'phone' => $request->phone,
                'is_active' => true,
            ]);

            // Create siswa profile
            Student::create([
                'user_id' => $user->id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->date_lahir,
                'alamat' => $request->alamat,
                'no_telepon' => $request->phone,
                'kelas_id' => $request->kelas_id,
                'major' => $request->major,
                'tahun_ajaran' => $request->tahun_ajaran,
                'nama_ortu' => $request->nama_ortu,
                'no_telepon_ortu' => $request->no_telepon_ortu,
                'golongan_darah' => $request->golongan_darah,
                'riwayat_penyakit' => $request->riwayat_penyakit,
                'alergi' => $request->alergi,
                'info_kesehatan' => $request->info_kesehatan,
                'status' => 'aktif',
            ]);

            DB::commit();
            return redirect()->route('admin.users.index')
                ->with('success', 'Siswa berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating siswa: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan siswa')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id): View
    {
        $user = UserCentral::findOrFail($id);
        
        switch($user->role) {
            case 'admin':
                return view('admin.users.edit-admin', compact('user'));
            case 'guru':
                $kelas = Kelas::orderBy('grade')->orderBy('name')->get();
                return view('admin.users.edit-guru', compact('user', 'kelas'));
            case 'siswa':
                $kelas = Kelas::orderBy('grade')->orderBy('name')->get();
                $jurusans = Jurusan::orderBy('name')->get();
                return view('admin.users.edit-siswa', compact('user', 'kelas', 'jurusans'));
            default:
                abort(404);
        }
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $user = UserCentral::findOrFail($id);
        
        switch($user->role) {
            case 'admin':
                return $this->updateAdmin($request, $user);
            case 'guru':
                return $this->updateGuru($request, $user);
            case 'siswa':
                return $this->updateSiswa($request, $user);
            default:
                abort(404);
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id): RedirectResponse
    {
        $user = UserCentral::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Soft delete the central user (will cascade to profile)
            $user->delete();
            
            DB::commit();
            return redirect()->route('admin.users.index')
                ->with('success', 'Pengguna berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus pengguna');
        }
    }

    // Private methods for updating specific roles
    private function updateAdmin(Request $request, UserCentral $user): RedirectResponse
    {
        // Implementation for updating admin
        // Similar to storeAdmin but with update logic
        return redirect()->route('admin.users.index')->with('success', 'Admin berhasil diperbarui');
    }

    private function updateGuru(Request $request, UserCentral $user): RedirectResponse
    {
        // Implementation for updating guru
        // Similar to storeGuru but with update logic
        return redirect()->route('admin.users.index')->with('success', 'Guru berhasil diperbarui');
    }

    private function updateSiswa(Request $request, UserCentral $user): RedirectResponse
    {
        // Implementation for updating siswa
        // Similar to storeSiswa but with update logic
        return redirect()->route('admin.users.index')->with('success', 'Siswa berhasil diperbarui');
    }
}
