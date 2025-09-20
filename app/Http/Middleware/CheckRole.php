<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): SymfonyResponse
    {
        // Validasi roles
        if (empty($roles)) {
            Log::error('CheckRole middleware called without roles', [
                'url' => $request->fullUrl(),
                'ip' => $request->ip()
            ]);
            abort(500, 'At least one role must be specified');
        }

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            $rolesList = implode(', ', $roles);
            Log::warning('Role access denied', [
                'user_id' => $user->id,
                'email' => $user->email,
                'user_role' => $user->role,
                'required_roles' => $roles,
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);

            abort(403, "Akses ditolak. Hanya peran {$rolesList} yang dapat mengakses halaman ini.");
        }

        return $next($request);
    }
}