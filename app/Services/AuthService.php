<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Authenticate user with credentials and role
     */
    public function authenticate(string $email, string $password, string $role): User
    {
        // Find user by email
        $user = User::where('email', $email)->first();

        // Check credentials
        if (!$user || !Hash::check($password, $user->password)) {
            $this->logFailedLogin($email, 'invalid_credentials');
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        // Check role
        if ($user->role !== $role) {
            $this->logFailedLogin($email, 'role_mismatch', $role, $user->role);
            throw ValidationException::withMessages([
                'role' => 'Anda tidak memiliki akses sebagai ' . $role,
            ]);
        }

        // Check status
        if ($user->status !== 'active') {
            $this->logFailedLogin($email, 'inactive_account');
            throw ValidationException::withMessages([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ]);
        }

        $this->logSuccessfulLogin($user);
        return $user;
    }

    /**
     * Authenticate user for API
     */
    public function authenticateApi(string $email, string $password, string $role): array
    {
        $user = $this->authenticate($email, $password, $role);

        // Remove old tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('API Token')->plainTextToken;

        return [
            'user' => $this->formatUserData($user),
            'token' => $token
        ];
    }

    /**
     * Format user data for API response
     */
    public function formatUserData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'avatar' => $user->photo_url,
            'class' => $user->isStudent() ? optional($user->siswa)->kelas : null,
            'status' => $user->status,
            'created_at' => $user->created_at->toDateTimeString(),
        ];
    }

    /**
     * Get redirect URL based on user role
     */
    public function getRedirectUrl(User $user): string
    {
        return match($user->role) {
            'admin' => route('admin.dashboard'),
            'guru' => route('guru.dashboard'),
            'siswa' => route('siswa.dashboard'),
            default => route('welcome')
        };
    }

    /**
     * Get welcome message based on user role
     */
    public function getWelcomeMessage(User $user): string
    {
        return match($user->role) {
            'admin' => 'Selamat datang, Admin!',
            'guru' => 'Selamat datang, Guru!',
            'siswa' => 'Selamat datang, Siswa!',
            default => 'Selamat datang!'
        };
    }

    /**
     * Log successful login
     */
    private function logSuccessfulLogin(User $user): void
    {
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * Log failed login attempt
     */
    private function logFailedLogin(string $email, string $reason, ?string $requestedRole = null, ?string $actualRole = null): void
    {
        $context = [
            'email' => $email,
            'reason' => $reason,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ];

        if ($requestedRole) {
            $context['requested_role'] = $requestedRole;
        }

        if ($actualRole) {
            $context['actual_role'] = $actualRole;
        }

        Log::warning('Login failed: ' . $reason, $context);
    }

    /**
     * Log logout
     */
    public function logLogout(User $user): void
    {
        Log::info('User logged out', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip()
        ]);
    }

    /**
     * Validate login credentials
     */
    public function validateLoginCredentials(array $data): array
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,guru,siswa'
        ];

        $messages = [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid'
        ];

        return validator($data, $rules, $messages)->validate();
    }

    /**
     * Check if user can access route
     */
    public function canAccessRoute(User $user, string $route): bool
    {
        $routePermissions = [
            'admin.dashboard' => ['admin'],
            'guru.dashboard' => ['guru'],
            'siswa.dashboard' => ['siswa'],
            'admin.users.*' => ['admin'],
            'guru.materials.*' => ['guru'],
            'siswa.materials.*' => ['siswa'],
        ];

        foreach ($routePermissions as $pattern => $allowedRoles) {
            if (fnmatch($pattern, $route)) {
                return in_array($user->role, $allowedRoles);
            }
        }

        return true; // Default allow if no specific rule
    }

    /**
     * Get user permissions
     */
    public function getUserPermissions(User $user): array
    {
        return $user->getPermissions();
    }

    /**
     * Check if user has permission
     */
    public function hasPermission(User $user, string $permission): bool
    {
        return $user->hasPermission($permission);
    }
}
