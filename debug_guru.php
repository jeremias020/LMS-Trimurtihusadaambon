<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== DEBUG GURU DAN NILAI PRAKTIKUM ===\n";

// 1. Check all guru users
$gurus = \App\Models\User::where('role', 'guru')->get();
echo "All Guru Users:\n";
foreach ($gurus as $guru) {
    echo "- {$guru->name} (ID: {$guru->id})\n";
}

// 2. Check who created the nilai praktik records
$nilaiPraktiks = \App\Models\NilaiPraktik::with('guru')->get();
echo "\nNilai Praktik Records:\n";
foreach ($nilaiPraktiks as $nilai) {
    $guruName = $nilai->guru ? $nilai->guru->name : 'No Guru';
    $siswaName = $nilai->siswa ? $nilai->siswa->name : 'No Siswa';
    echo "- {$siswaName} - {$nilai->mata_praktik} (Guru: {$guruName})\n";
}

// 3. Check current authenticated user
echo "\nCurrent Auth User:\n";
try {
    $authUser = \Auth::user();
    if ($authUser) {
        echo "- {$authUser->name} (ID: {$authUser->id}) - Role: {$authUser->role}\n";
    } else {
        echo "- No authenticated user\n";
    }
} catch (Exception $e) {
    echo "- Error: {$e->getMessage()}\n";
}

echo "\n=== END DEBUG ===\n";
