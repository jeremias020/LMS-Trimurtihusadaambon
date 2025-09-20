<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Student; // ← TAMBAHKAN INI

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle web login.
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,guru,siswa'
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid'
        ]);

        // Cari user
        $user = User::where('email', $request->email)->first();

        // Cek kredensial
        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::warning('Login failed: invalid credentials', [
                'email' => $request->email,
                'ip' => $request->ip()
            ]);

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        // Cek role
        if ($user->role !== $request->role) {
            Log::warning('Login failed: role mismatch', [
                'email' => $request->email,
                'requested_role' => $request->role,
                'actual_role' => $user->role,
                'ip' => $request->ip()
            ]);

            throw ValidationException::withMessages([
                'role' => 'Anda tidak memiliki akses sebagai ' . $request->role,
            ]);
        }

        // Cek status - periksa apakah field status ada dan bernilai 'active'
        if (isset($user->status) && $user->status !== 'active') {
            Log::warning('Login failed: inactive account', [
                'email' => $request->email,
                'status' => $user->status,
                'ip' => $request->ip()
            ]);

            throw ValidationException::withMessages([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ]);
        }

        // Login user
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'ip' => $request->ip()
        ]);

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Redirect user based on role.
     */
    protected function redirectBasedOnRole($user)
    {
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard')->with('success', 'Selamat datang, Admin!'),
            'guru' => redirect()->route('guru.dashboard')->with('success', 'Selamat datang, Guru!'),
            'siswa' => redirect()->route('siswa.dashboard')->with('success', 'Selamat datang, Siswa!'),
            default => redirect('/home')->with('success', 'Selamat datang!')
        };
    }

    /**
     * Handle API login.
     */
    public function apiLogin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,guru,siswa'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::warning('API Login failed: invalid credentials', [
                'email' => $request->email,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        if ($user->role !== $request->role) {
            Log::warning('API Login failed: role mismatch', [
                'email' => $request->email,
                'requested_role' => $request->role,
                'actual_role' => $user->role,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses sebagai ' . $request->role
            ], 403);
        }

        if (isset($user->status) && $user->status !== 'active') {
            Log::warning('API Login failed: inactive account', [
                'email' => $request->email,
                'status' => $user->status,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
            ], 403);
        }

        // Hapus token lama
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('API Token')->plainTextToken;

        Log::info('API User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'ip' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $user->photo_url, // ✅ Sudah diperbaiki (pastikan accessor ada di model User)
                    'class' => $user->isStudent() ? optional($user->student)->class : null, // ✅ Sudah diperbaiki
                    'status' => $user->status
                ],
                'token' => $token
            ]
        ], 200);
    }

    /**
     * Handle API logout.
     */
    public function apiLogout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            if ($user) {
                $user->currentAccessToken()->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Logout failed: ' . $e->getMessage(), [
                'user_id' => optional($request->user())->id,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal logout',
                'error' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan internal'
            ], 500);
        }
    }

    /**
     * Handle web logout.
     */
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Log sebelum logout untuk debugging
            Log::info('Logout attempt started', [
                'user_id' => $user ? $user->id : 'no user',
                'user_email' => $user ? $user->email : 'no user',
                'user_role' => $user ? $user->role : 'no user',
                'session_id' => $request->session()->getId(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            if ($user) {
                Log::info('User logging out', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role,
                    'ip' => $request->ip()
                ]);
            }

            // Proses logout
            Auth::logout();
            
            // Invalidate session
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Clear semua session data
            $request->session()->flush();
            
            Log::info('Logout completed successfully', [
                'previous_user' => $user ? $user->email : 'no user',
                'ip' => $request->ip()
            ]);

            // Redirect berdasarkan role sebelumnya atau ke home
            if ($user && $user->role === 'guru') {
                return redirect()->route('login')->with('success', 'Anda telah berhasil logout dari dashboard guru.');
            } elseif ($user && $user->role === 'admin') {
                return redirect()->route('login')->with('success', 'Anda telah berhasil logout dari dashboard admin.');
            } elseif ($user && $user->role === 'siswa') {
                return redirect()->route('login')->with('success', 'Anda telah berhasil logout dari dashboard siswa.');
            }
            
            // Default redirect ke login
            return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
            
        } catch (\Exception $e) {
            Log::error('Logout failed with exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user' => Auth::user() ? Auth::user()->email : 'no user',
                'ip' => $request->ip()
            ]);
            
            // Paksa logout meskipun ada error
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat logout, namun Anda telah berhasil logout.');
        }
    }

    /**
     * Get current user (API).
     */
    public function getCurrentUser(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            return response()->json([
                'success' => true,
                'message' => 'Data user berhasil diambil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'avatar' => $user->photo_url, // ✅ Sudah diperbaiki
                        'class' => $user->isStudent() ? optional($user->student)->class : null, // ✅ Sudah diperbaiki
                        'status' => $user->status
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to get current user: ' . $e->getMessage(), [
                'user_id' => optional($request->user())->id,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data user',
                'error' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan internal'
            ], 500);
        }
    }
}
