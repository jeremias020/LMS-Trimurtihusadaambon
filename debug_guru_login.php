<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate login as guru@trimurti.sch.id
$guru = \App\Models\User::where('email', 'guru@trimurti.sch.id')->first();

if ($guru) {
    echo "=== GURU LOGIN SIMULATION ===" . PHP_EOL;
    echo "Guru found: " . $guru->name . " (ID: " . $guru->id . ")" . PHP_EOL;
    echo "Email: " . $guru->email . PHP_EOL;
    echo "Role: " . $guru->role . PHP_EOL;
    echo "Status: " . $guru->status . PHP_EOL;
    
    // Check practicals for this guru
    $practicals = \App\Models\Practical::where('guru_id', $guru->id)
        ->where('is_published', true)
        ->with('subject')
        ->get();
    
    echo PHP_EOL . "Practicals for this guru: " . $practicals->count() . PHP_EOL;
    
    foreach ($practicals as $practical) {
        echo "- " . $practical->judul . PHP_EOL;
        echo "  Subject: " . ($practical->subject ? $practical->subject->name : 'No subject') . PHP_EOL;
        echo "  Max Score: " . $practical->max_score . PHP_EOL;
        echo "  Published: " . ($practical->is_published ? 'Yes' : 'No') . PHP_EOL;
        echo PHP_EOL;
    }
    
    // Check assignments too
    $assignments = \App\Models\Assignment::where('guru_id', $guru->id)
        ->where('is_published', true)
        ->with('subject')
        ->get();
    
    echo "Assignments for this guru: " . $assignments->count() . PHP_EOL;
    
    foreach ($assignments as $assignment) {
        echo "- " . $assignment->title . PHP_EOL;
        echo "  Subject: " . ($assignment->subject ? $assignment->subject->name : 'No subject') . PHP_EOL;
        echo "  Max Score: " . $assignment->max_score . PHP_EOL;
        echo "  Published: " . ($assignment->is_published ? 'Yes' : 'No') . PHP_EOL;
        echo PHP_EOL;
    }
    
} else {
    echo "Guru not found!" . PHP_EOL;
}
