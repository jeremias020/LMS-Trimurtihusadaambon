<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogActivityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        // ✅ Pengecualian route
        $excludedRoutes = [
            'logout',
            'password.update',
            'notifications.read',
            'profile.update-password',
        ];

        $routeName = $request->route()?->getName();
        if ($routeName && in_array($routeName, $excludedRoutes)) {
            return $next($request);
        }

        // ✅ Log sebelum processing request
        if (Auth::check() && in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $this->logActivity($request, 'attempted');
        }

        $start = microtime(true);
        $response = $next($request);
        $duration = (microtime(true) - $start) * 1000;

        // ✅ Log setelah processing request
        if (Auth::check() && in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $this->logActivity($request, 'completed', $response->getStatusCode(), $duration);
        }

        return $response;
    }

    /**
     * Log activity ke file
     */
    protected function logActivity(Request $request, string $status, ?int $statusCode = null, ?float $duration = null): void
    {
        // ✅ Sanitasi data sensitif
        $except = ['password', 'password_confirmation', 'token', 'api_token', '_token'];
        $input = collect($request->except($except))->map(function ($value, $key) {
            if (is_string($value) && strlen($value) > 100) {
                return substr($value, 0, 100) . '...[TRUNCATED]';
            }
            if (is_array($value) || is_object($value)) {
                return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            return $value;
        })->toArray();

        $logData = [
            'user_id' => Auth::id(),
            'user_role' => Auth::user()?->role ?? 'unknown', // ✅ Nullsafe
            'activity' => $request->route()?->getName() ?? $request->path(),
            'method' => $request->method(),
            'status' => $status,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent() ?? 'Unknown',
            'status_code' => $statusCode,
            'duration_ms' => $duration ? round($duration, 2) : null,
            'input' => $input,
            'timestamp' => now()->toDateTimeString()
        ];

        // ✅ Fallback jika channel activity tidak ada
        try {
            Log::channel('activity')->info('Activity Log', $logData);
        } catch (\Exception $e) {
            Log::info('Activity Log (fallback)', $logData);
        }
    }
}