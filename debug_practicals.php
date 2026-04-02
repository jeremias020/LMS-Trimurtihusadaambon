<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check practicals data
echo "=== PRACTICALS DEBUG ===" . PHP_EOL;

$totalPracticals = \App\Models\Practical::count();
echo "Total practicals: " . $totalPracticals . PHP_EOL;

$publishedPracticals = \App\Models\Practical::where('is_published', true)->count();
echo "Published practicals: " . $publishedPracticals . PHP_EOL;

// Get current guru (assuming ID 1 for testing)
$guruId = 1;
echo "Checking for Guru ID: " . $guruId . PHP_EOL;

$guruPracticals = \App\Models\Practical::where('guru_id', $guruId)->where('is_published', true)->count();
echo "Guru practicals (published): " . $guruPracticals . PHP_EOL;

// Show all practicals details
$allPracticals = \App\Models\Practical::all();
echo PHP_EOL . "All practicals details:" . PHP_EOL;
foreach ($allPracticals as $practical) {
    echo "- ID: " . $practical->id . PHP_EOL;
    echo "  Judul: " . $practical->judul . PHP_EOL;
    echo "  Guru ID: " . $practical->guru_id . PHP_EOL;
    echo "  Published: " . ($practical->is_published ? 'Yes' : 'No') . PHP_EOL;
    echo "  Created: " . $practical->created_at . PHP_EOL;
    echo PHP_EOL;
}

// Check guru users
echo "=== GURU USERS ===" . PHP_EOL;
$gurus = \App\Models\User::where('role', 'guru')->get();
foreach ($gurus as $guru) {
    echo "- ID: " . $guru->id . PHP_EOL;
    echo "  Name: " . $guru->name . PHP_EOL;
    echo "  Email: " . $guru->email . PHP_EOL;
    echo PHP_EOL;
}
