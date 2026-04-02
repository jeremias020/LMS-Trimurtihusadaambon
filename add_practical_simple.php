<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "=== CHECK TABLES ===" . PHP_EOL;
$tables = Schema::getTableListing();
foreach ($tables as $table) {
    if (strpos($table, 'kriteria') !== false) {
        echo "- " . $table . PHP_EOL;
    }
}

echo PHP_EOL . "=== ADDING PRACTICAL ONLY ===" . PHP_EOL;

// Get guru
$guru = DB::table('users')->where('email', 'guru@trimurti.sch.id')->first();
if (!$guru) {
    echo "❌ Guru not found!" . PHP_EOL;
    exit(1);
}

// Get subject
$subject = DB::table('subjects')->where('name', 'Keperawatan Dasar')->first();
if (!$subject) {
    echo "❌ Subject not found!" . PHP_EOL;
    exit(1);
}

// Add the missing practical
$practical = [
    'judul' => 'Praktikum Pemasangan Infus',
    'deskripsi' => 'Praktikum pemasangan infus IV pada pasien simulasi',
    'subject_id' => $subject->id,
    'guru_id' => $guru->id,
    'max_score' => 100,
    'tanggal' => now()->addDays(21),
    'lokasi' => 'Lab Keperawatan C',
    'durasi' => 150,
    'tools' => 'IV set, Catheter, Tourniquet',
    'bahan' => 'Infus solution, Alcohol swab',
    'instruksi' => 'Lakukan pemasangan infus dengan teknik benar',
    'is_published' => true,
    'waktu_mulai' => '08:30:00',
    'waktu_selesai' => '11:00:00',
    'skill_level' => 'Menengah',
    'keselamatan' => 'Ikuti protokol keselamatan',
    'created_at' => now(),
    'updated_at' => now(),
];

$practicalId = DB::table('practicals')->insertGetId($practical);
echo "✅ Created practical: {$practical['judul']} (ID: {$practicalId})" . PHP_EOL;

// Check final count
$totalPracticals = DB::table('practicals')->where('guru_id', $guru->id)->where('is_published', true)->count();

echo PHP_EOL . "🎉 SUCCESS!" . PHP_EOL;
echo "📊 Summary:" . PHP_EOL;
echo "   - Total practicals for this guru: {$totalPracticals}" . PHP_EOL;
echo "   - New practical added: Praktikum Pemasangan Infus" . PHP_EOL;

echo PHP_EOL . "🎯 Ready for assessment!" . PHP_EOL;
echo "📱 Visit: /guru/penilaian/create" . PHP_EOL;
echo "🎯 Now you have multiple practical assessments to choose from!" . PHP_EOL;
