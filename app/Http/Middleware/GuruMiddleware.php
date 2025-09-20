<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuruMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // ✅ PERBAIKAN: Ganti redirect dengan abort(403)
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Akses ditolak. Hanya guru yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
