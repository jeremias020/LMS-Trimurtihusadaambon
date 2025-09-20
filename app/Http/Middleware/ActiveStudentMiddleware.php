<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Siswa;

class ActiveStudentMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Hanya berlaku untuk role siswa
        if ($user->role === 'siswa') {
            /** @var \App\Models\Siswa $siswa */
            $siswa = $user->siswa;

            // Cek apakah relasi siswa ada dan status aktif
            if (!$siswa || $siswa->status !== 'aktif') {
                Log::warning('Inactive student attempted to access protected route', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip()
                ]);

                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Akun siswa tidak aktif. Silakan hubungi administrator.');
            }
        }

        // Lanjutkan request untuk user yang valid
        return $next($request);
    }
}