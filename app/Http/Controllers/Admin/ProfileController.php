<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Optional: defense in depth
        // if (! $user->hasRole('admin')) { abort(403); }

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(UpdateProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        try {
            $data = $request->only([
                'name',
                'email',
                'phone',
                'address',
                'birth_date',
                'gender',
            ]);

            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if (!empty($user->photo) && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                $path = $request->file('photo')->store('photos', 'public');
                $data['photo'] = $path;
            }

            $user->update($data);

            return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Profile Update Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.')
                ->withInput();
        }
    }
}
