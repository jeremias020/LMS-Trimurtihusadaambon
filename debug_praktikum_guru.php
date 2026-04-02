<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== DEBUG PRAKTIKUM UNTUK GURU LOGIN ===\n";

// Simulate current guru (use first guru)
$guru = \App\Models\User::where('role', 'guru')->first();
echo "Current Guru (simulated): {$guru->name} (ID: {$guru->id})\n";

// Get practicals exactly like controller does
$practicals = \App\Models\Practical::where('guru_id', $guru->id)
    ->where('is_published', true)
    ->with('subject')
    ->latest()
    ->get();

echo "Practicals found: {$practicals->count()}\n";

if ($practicals->count() > 0) {
    foreach ($practicals as $practical) {
        echo "- {$practical->judul} (ID: {$practical->id})\n";
        echo "  Published: " . ($practical->is_published ? 'Yes' : 'No') . "\n";
        echo "  Subject: " . ($practical->subject ? $practical->subject->name : 'No Subject') . "\n";
        echo "  nama_praktik: " . ($practical->nama_praktik ?? 'null') . "\n";
        echo "  name: " . ($practical->name ?? 'null') . "\n";
        echo "\n";
    }
} else {
    echo "No practicals found!\n";
    
    // Show all practicals in database
    echo "\nAll practicals in database:\n";
    $allPracticals = \App\Models\Practical::all();
    foreach ($allPracticals as $p) {
        echo "- {$p->judul} (Guru ID: {$p->guru_id}, Published: " . ($p->is_published ? 'Yes' : 'No') . ")\n";
    }
}

// Check if there are any practicals for any guru
echo "\nPracticals by guru:\n";
$gurus = \App\Models\User::where('role', 'guru')->get();
foreach ($gurus as $g) {
    $count = \App\Models\Practical::where('guru_id', $g->id)->count();
    echo "- {$g->name}: {$count} practicals\n";
}

echo "\n=== DEBUG SELESAI ===\n";
