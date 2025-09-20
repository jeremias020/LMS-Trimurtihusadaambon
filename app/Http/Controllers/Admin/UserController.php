<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Subject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,guru,siswa',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'status' => 'active',
            ]);

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
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
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

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

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
