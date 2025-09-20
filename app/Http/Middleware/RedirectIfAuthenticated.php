<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): SymfonyResponse|RedirectResponse
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                /** @var \App\Models\User $user */
                $user = Auth::guard($guard)->user();

                $redirectRoute = $this->getRedirectRoute($user->role);

                Log::info('Redirecting authenticated user', [
                    'user_id' => $user->id,
                    'role' => $user->role,
                    'ip' => $request->ip(),
                    'redirect_to' => $redirectRoute
                ]);

                // ✅ PERBAIKAN: Gunakan redirect() biasa вместо intended()
                return redirect($redirectRoute);
            }
        }

        return $next($request);
    }

    /**
     * Get redirect route based on user role.
     */
    protected function getRedirectRoute($role): string
    {
        return match($role) {
            'admin' => route('admin.dashboard'),
            'guru' => route('guru.dashboard'),
            'siswa' => route('siswa.dashboard'),
            default => '/'
        };
    }
}
