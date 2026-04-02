<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ADDING PRACTICAL ASSESSMENT (FINAL FIX) ===" . PHP_EOL;

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

// Add the missing practical with correct skill_level length (max 8 chars)
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
    'skill_level' => 'Lanjut', // Fixed: 6 characters
    'keselamatan' => 'Ikuti protokol keselamatan',
    'created_at' => now(),
    'updated_at' => now(),
];

$practicalId = DB::table('practicals')->insertGetId($practical);
echo "✅ Created practical: {$practical['judul']} (ID: {$practicalId})" . PHP_EOL;

// Create comprehensive assessment criteria
$criteriaList = [
    ['nama' => 'Peralatan lengkap dan siap', 'kategori' => 'persiapan', 'deskripsi' => 'Memastikan semua peralatan tersedia', 'bobot' => 0.20],
    ['nama' => 'Standar APD lengkap', 'kategori' => 'persiapan', 'deskripsi' => 'Menggunakan APD sesuai standar', 'bobot' => 0.15],
    ['nama' => 'Area kerja steril', 'kategori' => 'persiapan', 'deskripsi' => 'Menyiapkan area kerja steril', 'bobot' => 0.15],
    ['nama' => 'Tindakan sesuai SOP', 'kategori' => 'pelaksanaan', 'deskripsi' => 'Melakukan tindakan sesuai SOP', 'bobot' => 0.25],
    ['nama' => 'Teknik yang benar', 'kategori' => 'pelaksanaan', 'deskripsi' => 'Menggunakan teknik yang benar', 'bobot' => 0.20],
    ['nama' => 'Keamanan pasien', 'kategori' => 'pelaksanaan', 'deskripsi' => 'Memastikan keamanan pasien', 'bobot' => 0.20],
    ['nama' => 'Hasil optimal', 'kategori' => 'hasil', 'deskripsi' => 'Mencapai hasil yang diharapkan', 'bobot' => 0.30],
    ['nama' => 'Dokumentasi lengkap', 'kategori' => 'hasil', 'deskripsi' => 'Mendokumentasikan tindakan', 'bobot' => 0.20],
    ['nama' => 'Komunikasi efektif', 'kategori' => 'sikap', 'deskripsi' => 'Berkomunikasi dengan baik', 'bobot' => 0.15],
    ['nama' => 'Sikap profesional', 'kategori' => 'sikap', 'deskripsi' => 'Menunjukkan sikap profesional', 'bobot' => 0.20],
];

foreach ($criteriaList as $criteria) {
    DB::table('kriteria_penilaian')->insert([
        'nama' => $criteria['nama'],
        'kategori' => $criteria['kategori'],
        'deskripsi' => $criteria['deskripsi'],
        'bobot' => $criteria['bobot'],
        'mata_praktik' => 'Keperawatan Dasar',
        'tingkat_kelas' => 'XI',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

echo "✅ Created " . count($criteriaList) . " assessment criteria" . PHP_EOL;

// Check final count
$totalPracticals = DB::table('practicals')->where('guru_id', $guru->id)->where('is_published', true)->count();
echo PHP_EOL . "🎉 SUCCESS!" . PHP_EOL;
echo "📊 Summary:" . PHP_EOL;
echo "   - Total practicals for this guru: {$totalPracticals}" . PHP_EOL;
echo "   - Total criteria created: " . count($criteriaList) . PHP_EOL;
echo "   - Criteria categories: Persiapan, Pelaksanaan, Hasil, Sikap" . PHP_EOL;

echo PHP_EOL . "🎯 Ready for assessment!" . PHP_EOL;
echo "📱 Visit: /guru/penilaian/create" . PHP_EOL;
