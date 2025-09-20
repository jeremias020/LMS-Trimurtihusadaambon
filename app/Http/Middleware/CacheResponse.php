<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CacheResponse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $ttl = 60): SymfonyResponse
    {
        // Validasi TTL
        $ttl = max(1, (int)$ttl);

        // Skip caching jika dinonaktifkan atau bukan GET
        if (!config('app.cache_enabled', false) || !$request->isMethod('GET')) {
            return $next($request);
        }

        // Skip caching untuk user yang terautentikasi
        if (Auth::check()) {
            return $next($request);
        }

        // Pengecualian untuk path tertentu
        $excludedPaths = [
            'api/*',
            'search',
            'checkout',
            'cart',
            'login',
            'register',
        ];

        foreach ($excludedPaths as $path) {
            if ($request->is($path)) {
                return $next($request);
            }
        }

        // Normalisasi URL — hapus parameter yang tidak relevan
        $filteredQuery = $request->query();
        unset($filteredQuery['_token'], $filteredQuery['session']);

        $url = $request->url();
        if (!empty($filteredQuery)) {
            $url .= '?' . http_build_query($filteredQuery);
        }

        $key = 'route_' . md5($url);

        if (Cache::has($key)) {
            $cachedResponse = Cache::get($key);

            Log::info('Cache HIT', [
                'url' => $url,
                'key' => $key,
                'ip' => $request->ip()
            ]);

            return response($cachedResponse['content'])
                ->header('Content-Type', $cachedResponse['content_type'])
                ->header('X-Cache', 'HIT')
                ->header('Cache-Control', "public, max-age={$ttl}")
                ->header('Expires', now()->addSeconds($ttl)->format('D, d M Y H:i:s \G\M\T'));
        }

        $response = $next($request);

        // Cache hanya response yang successful
        if ($response->isSuccessful() && $response->getContent() !== null) {
            Cache::put($key, [
                'content' => $response->getContent(),
                'content_type' => $response->headers->get('Content-Type')
            ], $ttl);

            // Tambahkan header standar
            $response->headers->set('X-Cache', 'MISS');
            $response->headers->set('Cache-Control', "public, max-age={$ttl}");
            $response->headers->set('Expires', now()->addSeconds($ttl)->format('D, d M Y H:i:s \G\M\T'));

            Log::info('Cache MISS - stored', [
                'url' => $url,
                'key' => $key,
                'ip' => $request->ip()
            ]);
        } else {
            Log::info('Cache skipped - non-successful response', [
                'url' => $url,
                'status' => $response->getStatusCode(),
                'ip' => $request->ip()
            ]);
        }

        return $response;
    }
}