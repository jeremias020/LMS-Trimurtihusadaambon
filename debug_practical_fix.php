<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DEBUG PRAKTIKUM PENILAIAN ===" . PHP_EOL;

// Check guru login
$guru = DB::table('users')->where('email', 'guru@trimurti.sch.id')->first();
if (!$guru) {
    echo "❌ Guru tidak ditemukan!" . PHP_EOL;
    exit(1);
}

echo "✅ Guru: {$guru->name} (ID: {$guru->id})" . PHP_EOL;

// Check practicals for this guru
$practicals = DB::table('practicals')
    ->where('guru_id', $guru->id)
    ->where('is_published', true)
    ->get();

echo PHP_EOL . "📊 PRAKTIKUM UNTUK GURU INI:" . PHP_EOL;
echo "Total practicals: " . $practicals->count() . PHP_EOL . PHP_EOL;

if ($practicals->isEmpty()) {
    echo "❌ TIDAK ADA PRAKTIKUM!" . PHP_EOL;
    
    // Check all practicals
    $allPracticals = DB::table('practicals')->get();
    echo PHP_EOL . "📋 SEMUA PRAKTIKUM DI DATABASE:" . PHP_EOL;
    foreach ($allPracticals as $p) {
        echo "- ID: {$p->id}, Judul: {$p->judul}, Guru: {$p->guru_id}, Published: " . ($p->is_published ? 'Yes' : 'No') . PHP_EOL;
    }
} else {
    foreach ($practicals as $practical) {
        echo "✅ ID: {$practical->id}" . PHP_EOL;
        echo "   Judul: {$practical->judul}" . PHP_EOL;
        echo "   Subject ID: {$practical->subject_id}" . PHP_EOL;
        echo "   Published: " . ($practical->is_published ? 'Yes' : 'No') . PHP_EOL;
        echo "   Max Score: {$practical->max_score}" . PHP_EOL;
        echo "   Tanggal: {$practical->tanggal}" . PHP_EOL;
        echo "   Skill Level: {$practical->skill_level}" . PHP_EOL;
        echo PHP_EOL;
    }
}

// Check assignments juga
$assignments = DB::table('assignments')
    ->where('guru_id', $guru->id)
    ->where('is_published', true)
    ->get();

echo "📚 ASSIGNMENTS UNTUK GURU INI:" . PHP_EOL;
echo "Total assignments: " . $assignments->count() . PHP_EOL . PHP_EOL;

if ($assignments->isNotEmpty()) {
    foreach ($assignments as $assignment) {
        echo "✅ ID: {$assignment->id}" . PHP_EOL;
        echo "   Judul: {$assignment->title}" . PHP_EOL;
        echo "   Subject ID: {$assignment->subject_id}" . PHP_EOL;
        echo "   Published: " . ($assignment->is_published ? 'Yes' : 'No') . PHP_EOL;
        echo "   Max Score: {$assignment->max_score}" . PHP_EOL;
        echo PHP_EOL;
    }
}

echo PHP_EOL . "🎯 RECOMMENDATIONS:" . PHP_EOL;
if ($practicals->isEmpty()) {
    echo "❌ Masalah: Tidak ada praktikum untuk guru ini" . PHP_EOL;
    echo "🔧 Solusi: Buat praktikum baru atau assign guru yang benar" . PHP_EOL;
} else {
    echo "✅ Praktikum tersedia, seharusnya muncul di dropdown" . PHP_EOL;
    echo "🔧 Cek: JavaScript error, cache browser, atau view yang tidak terupdate" . PHP_EOL;
}

echo PHP_EOL . "🔧 TROUBLESHOOTING STEPS:" . PHP_EOL;
echo "1. Clear browser cache (Ctrl+F5)" . PHP_EOL;
echo "2. Clear Laravel cache (php artisan view:clear)" . PHP_EOL;
echo "3. Check JavaScript console (F12)" . PHP_EOL;
echo "4. Verify guru login session" . PHP_EOL;
echo "5. Check if practicals are published" . PHP_EOL;
