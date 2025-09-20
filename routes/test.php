<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Test route untuk debugging logout - hanya untuk development
if (config('app.debug')) {
    Route::prefix('test')->group(function () {
        
        // Test session status
        Route::get('/session-info', function (Request $request) {
            return response()->json([
                'session_id' => $request->session()->getId(),
                'authenticated' => Auth::check(),
                'user' => Auth::user(),
                'session_data' => $request->session()->all(),
                'cookies' => $request->cookies->all(),
            ]);
        });
        
        // Test logout langsung tanpa middleware
        Route::post('/force-logout', function (Request $request) {
            $user = Auth::user();
            
            Log::info('Force logout test', [
                'user' => $user ? $user->email : 'no user',
                'session_before' => $request->session()->getId()
            ]);
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->flush();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Force logout completed',
                'redirectUrl' => route('login')
            ]);
        });
        
        // Test auth status
        Route::get('/auth-status', function () {
            return response()->json([
                'authenticated' => Auth::check(),
                'user_id' => Auth::id(),
                'user' => Auth::user(),
                'guard' => Auth::getDefaultDriver()
            ]);
        });
        
    });
}