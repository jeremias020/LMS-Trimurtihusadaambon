<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileControllerNew extends Controller
{
    /**
     * Show the student profile edit form.
     */
    public function edit(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role !== 'siswa') {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak.');
        }

        /** @var \App\Models\Siswa $student */
        $student = \App\Models\Siswa::with('kelas')->where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Data siswa tidak ditemukan.');
        }

        return view('siswa.profile.edit_simple', compact('user', 'student'));
    }

    /**
     * Update the student profile.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role !== 'siswa') {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak.');
        }

        $student = Siswa::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Data siswa tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users_central,email,' . $user->id,
            'nis'              => 'nullable|string|unique:siswa,nis,' . $student->id,
            'jenis_kelamin'    => 'nullable|in:L,P',
            'tanggal_lahir'    => 'nullable|date',
            'alamat'           => 'nullable|string|max:500',
            'phone'            => 'nullable|string|max:15',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'current_password' => 'required_with:password',
            'password'         => 'nullable|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $userData = ['name' => $request->name, 'email' => $request->email];

            if ($request->filled('password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return redirect()->back()
                        ->with('error', 'Password saat ini tidak sesuai')
                        ->withInput();
                }
                $userData['password'] = Hash::make($request->password);
            }
            if ($request->filled('phone')) {
                $userData['phone'] = $request->phone;
            }
            $user->update($userData);

            // Update siswa profile
            $studentData = [];
            if ($request->hasFile('foto')) {
                if ($student->foto) Storage::disk('public')->delete($student->foto);
                $foto = $request->file('foto');
                $path = $foto->storeAs('student_photos', time() . '_' . $foto->getClientOriginalName(), 'public');
                $studentData['foto'] = $path;
            }
            if ($request->filled('jenis_kelamin'))  $studentData['jenis_kelamin']  = $request->jenis_kelamin;
            if ($request->filled('tanggal_lahir'))  $studentData['tanggal_lahir']  = $request->tanggal_lahir;
            if ($request->filled('alamat'))         $studentData['alamat']         = $request->alamat;
            if ($request->filled('nis'))            $studentData['nis']            = $request->nis;

            if (!empty($studentData)) $student->update($studentData);

            return redirect()->route('siswa.profile.edit')
                ->with('success', 'Profil berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error updating student profile', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.')
                ->withInput();
        }
    }
}
