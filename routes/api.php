<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StudentController;

// API Routes untuk LMS
Route::middleware(['auth'])->group(function () {
    
    // Students API - untuk load siswa berdasarkan subject dan class
    Route::get('students', [StudentController::class, 'getStudentsBySubjectAndClass']);
    
});

// Fallback API route jika tidak ada
Route::fallback(function() {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found'
    ], 404);
});
