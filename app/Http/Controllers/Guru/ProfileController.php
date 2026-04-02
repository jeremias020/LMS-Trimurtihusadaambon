<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('guru.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ], [
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format foto yang diizinkan: JPEG, PNG, JPG, GIF',
            'photo.max' => 'Ukuran foto maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                Storage::delete($user->photo);
            }

            // Upload new photo
            $photo = $request->file('photo');
            $photoPath = $photo->store('photos/profiles', 'public');
            $user->photo = $photoPath;
        }

        $user->update($request->only([
            'name',
            'email',
            'phone',
            'address',
            'birth_date',
            'gender',
        ]));

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update profile photo only.
     */
    public function updatePhoto(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ], [
            'photo.required' => 'Foto harus dipilih',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format foto yang diizinkan: JPEG, PNG, JPG, GIF',
            'photo.max' => 'Ukuran foto maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Delete old photo if exists
        if ($user->photo) {
            Storage::delete($user->photo);
        }

        // Upload new photo
        $photo = $request->file('photo');
        $photoPath = $photo->store('photos/profiles', 'public');
        $user->photo = $photoPath;
        $user->save();

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Remove profile photo
     */
    public function removePhoto()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->photo) {
            Storage::delete($user->photo);
            $user->photo = null;
            $user->save();
        }

        return redirect()->back()->with('success', 'Foto profil berhasil dihapus.');
    }
}