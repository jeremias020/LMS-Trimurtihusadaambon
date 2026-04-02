<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== VERIFIKASI DATA PRAKTIKUM UNTUK GURU ===\n";

// Get all guru users
$gurus = \App\Models\User::where('role', 'guru')->get();
echo "Daftar Guru:\n";
foreach ($gurus as $guru) {
    $practicalCount = \App\Models\Practical::where('guru_id', $guru->id)->count();
    echo "- {$guru->name} (ID: {$guru->id}) - {$practicalCount} praktikum\n";
    
    if ($practicalCount > 0) {
        $practicals = \App\Models\Practical::where('guru_id', $guru->id)->get();
        foreach ($practicals as $p) {
            echo "  * {$p->judul} (Published: " . ($p->is_published ? 'Yes' : 'No') . ")\n";
        }
    }
}

echo "\nTotal praktikum di database: " . \App\Models\Practical::count() . "\n";

// Show all practicals with guru info
echo "\nSemua Praktikum:\n";
$allPracticals = \App\Models\Practical::with('guru')->get();
foreach ($allPracticals as $p) {
    $guruName = $p->guru ? $p->guru->name : 'No Guru';
    echo "- {$p->judul} (Guru: {$guruName})\n";
}

echo "\n=== VERIFIKASI SELESAI ===\n";
