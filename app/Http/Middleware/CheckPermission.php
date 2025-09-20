<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $permission): SymfonyResponse
    {
        // Validasi permission
        if (empty($permission)) {
            Log::error('CheckPermission middleware called without permission name', [
                'url' => $request->fullUrl(),
                'ip' => $request->ip()
            ]);
            abort(500, 'Permission name is required');
        }

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Opsional: Tambahkan pengecualian untuk super admin
        // if ($user->hasRole('super-admin')) {
        //     return $next($request);
        // }

        if (!$user->can($permission)) {
            Log::warning('Permission denied', [
                'user_id' => $user->id,
                'email' => $user->email,
                'permission' => $permission,
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);

            abort(403, 'Akses ditolak. Anda tidak memiliki izin yang diperlukan.');
        }

        return $next($request);
    }
}