<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckForMaintenanceMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        if (app()->isDownForMaintenance()) {
            $allowedIps = config('maintenance.allowed_ips', ['127.0.0.1', '::1']);
            $allowedRoles = config('maintenance.allowed_roles', ['admin']);

            $ip = $request->ip();
            $ipAllowed = in_array($ip, $allowedIps);

            $roleAllowed = false;
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $roleAllowed = $user && in_array($user->role, $allowedRoles);
            }

            if (!$ipAllowed && !$roleAllowed) {
                Log::warning('Access denied during maintenance mode', [
                    'ip' => $ip,
                    'user_id' => Auth::id(),
                    'role' => Auth::check() ? Auth::user()->role : null,
                    'url' => $request->fullUrl()
                ]);

                return response()->view('errors.503', [], 503)
                    ->header('Retry-After', 3600);
            }

            Log::info('Access granted during maintenance mode', [
                'ip' => $ip,
                'user_id' => Auth::id(),
                'role' => Auth::check() ? Auth::user()->role : null,
                'reason' => $ipAllowed ? 'ip_allowed' : 'role_allowed'
            ]);
        }

        return $next($request);
    }
}