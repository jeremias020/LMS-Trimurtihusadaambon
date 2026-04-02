<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ADDING PRACTICAL ASSESSMENT (FIXED) ===" . PHP_EOL;

// Get guru
$guru = DB::table('users')->where('email', 'guru@trimurti.sch.id')->first();
if (!$guru) {
    echo "❌ Guru not found!" . PHP_EOL;
    exit(1);
}
echo "✅ Found guru: {$guru->name} (ID: {$guru->id})" . PHP_EOL;

// Get subject
$subject = DB::table('subjects')->where('name', 'Keperawatan Dasar')->first();
if (!$subject) {
    echo "❌ Subject not found!" . PHP_EOL;
    exit(1);
}
echo "✅ Found subject: {$subject->name} (ID: {$subject->id})" . PHP_EOL;

// Add the missing practical with correct skill_level length
$practical = [
    'judul' => 'Praktikum Pemasangan Infus',
    'deskripsi' => 'Praktikum pemasangan infus IV pada pasien simulasi dengan teknik yang benar dan aman',
    'subject_id' => $subject->id,
    'guru_id' => $guru->id,
    'max_score' => 100,
    'tanggal' => now()->addDays(21),
    'lokasi' => 'Lab Keperawatan C',
    'durasi' => 150,
    'tools' => 'IV set, Catheter, Tourniquet, IV pole',
    'bahan' => 'Infus solution, Alcohol swab, Tape',
    'instruksi' => 'Lakukan pemasangan infus dengan teknik yang benar dan aman',
    'is_published' => true,
    'waktu_mulai' => '08:30:00',
    'waktu_selesai' => '11:00:00',
    'skill_level' => 'Lanjutan', // Fixed: 8 characters max
    'keselamatan' => 'Ikuti protokol keselamatan',
    'created_at' => now(),
    'updated_at' => now(),
];

$practicalId = DB::table('practicals')->insertGetId($practical);
echo "✅ Created practical: {$practical['judul']} (ID: {$practicalId})" . PHP_EOL;

// Create comprehensive assessment criteria
$criteriaTemplates = [
    'persiapan' => [
        ['nama' => 'Peralatan lengkap dan siap', 'deskripsi' => 'Memastikan semua peralatan tersedia dan dalam kondisi baik', 'bobot' => 0.20],
        ['nama' => 'Standar APD lengkap', 'deskripsi' => 'Menggunakan APD sesuai standar keselamatan', 'bobot' => 0.15],
        ['nama' => 'Area kerja steril', 'deskripsi' => 'Menyiapkan area kerja yang steril dan aman', 'bobot' => 0.15],
    ],
    'pelaksanaan' => [
        ['nama' => 'Tindakan sesuai SOP', 'deskripsi' => 'Melakukan tindakan sesuai standar operasional prosedur', 'bobot' => 0.25],
        ['nama' => 'Teknik yang benar', 'deskripsi' => 'Menggunakan teknik yang benar dan efisien', 'bobot' => 0.20],
        ['nama' => 'Keamanan pasien', 'deskripsi' => 'Memastikan keamanan dan kenyamanan pasien', 'bobot' => 0.20],
    ],
    'hasil' => [
        ['nama' => 'Hasil optimal', 'deskripsi' => 'Mencapai hasil yang diharapkan dengan kualitas baik', 'bobot' => 0.30],
        ['nama' => 'Dokumentasi lengkap', 'deskripsi' => 'Mendokumentasikan tindakan dengan lengkap dan benar', 'bobot' => 0.20],
    ],
    'sikap' => [
        ['nama' => 'Komunikasi efektif', 'deskripsi' => 'Berkomunikasi dengan pasien dengan baik', 'bobot' => 0.15],
        ['nama' => 'Sikap profesional', 'deskripsi' => 'Menunjukkan sikap profesional dan etika keperawatan', 'bobot' => 0.20],
    ],
];

$totalCriteria = 0;
foreach ($criteriaTemplates as $kategori => $criteriaList) {
    foreach ($criteriaList as $criteria) {
        DB::table('kriteria_penilaian')->insert([
            'nama' => $criteria['nama'],
            'kategori' => $kategori,
            'deskripsi' => $criteria['deskripsi'],
            'bobot' => $criteria['bobot'],
            'mata_praktik' => 'Keperawatan Dasar',
            'tingkat_kelas' => 'XI',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $totalCriteria++;
    }
}

echo "✅ Created {$totalCriteria} assessment criteria" . PHP_EOL;

// Check final count
$totalPracticals = DB::table('practicals')->where('guru_id', $guru->id)->where('is_published', true)->count();
echo PHP_EOL . "🎉 SUCCESS!" . PHP_EOL;
echo "📊 Summary:" . PHP_EOL;
echo "   - Total practicals for this guru: {$totalPracticals}" . PHP_EOL;
echo "   - Total criteria created: {$totalCriteria}" . PHP_EOL;
echo "   - Criteria categories: Persiapan, Pelaksanaan, Hasil, Sikap" . PHP_EOL;

echo PHP_EOL . "🎯 Ready for assessment!" . PHP_EOL;
echo "📱 Visit: /guru/penilaian/create" . PHP_EOL;
