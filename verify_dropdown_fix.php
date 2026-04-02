<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== VERIFIKASI PERBAIKAN DROPDOWN PRAKTIKUM ===\n";

// Simulate current guru
$guru = \App\Models\User::where('role', 'guru')->first();
echo "Current Guru: {$guru->name} (ID: {$guru->id})\n";

// Get practicals like controller does
$practicals = \App\Models\Practical::where('guru_id', $guru->id)
    ->where('is_published', true)
    ->with('subject')
    ->latest()
    ->get();

echo "Practicals found: {$practicals->count()}\n";

if ($practicals->count() > 0) {
    echo "\nDropdown options should show:\n";
    foreach ($practicals as $practical) {
        // Simulate the blade template logic
        $displayName = $practical->judul ?? 'Praktikum ' . $practical->id;
        echo "- <option value=\"{$practical->id}\">{$displayName}</option>\n";
    }
    
    echo "\nField values:\n";
    foreach ($practicals as $practical) {
        echo "- judul: '{$practical->judul}'\n";
        echo "- nama_praktik: " . ($practical->nama_praktik ?? 'null') . "\n";
        echo "- name: " . ($practical->name ?? 'null') . "\n";
        echo "\n";
    }
} else {
    echo "No practicals found - will show 'Tidak ada praktikum tersedia'\n";
}

echo "\n=== VERIFIKASI SELESAI ===\n";
