<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TimeoutService;
use Symfony\Component\HttpFoundation\Response;

class TimeoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $operation = 'default'): Response
    {
        // Set appropriate timeout based on operation
        TimeoutService::setExecutionTime($operation);
        
        // Add timeout headers for debugging
        $response = $next($request);
        
        $response->headers->set('X-Execution-Time', microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']);
        $response->headers->set('X-Memory-Usage', memory_get_usage(true));
        $response->headers->set('X-Memory-Peak', memory_get_peak_usage(true));
        
        return $response;
    }
}
