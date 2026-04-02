<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== MENAMBAHKAN DATA PRAKTIKUM ===\n";

// Get guru data
$guru = \App\Models\User::where('role', 'guru')->first();
if (!$guru) {
    echo "Error: Tidak ada guru ditemukan\n";
    exit;
}

echo "Guru: {$guru->name} (ID: {$guru->id})\n";

// Get subject data (Keperawatan)
$subject = \App\Models\Subject::where('name', 'like', '%keperawatan%')->first();
if (!$subject) {
    // Create subject if not exists
    $subject = \App\Models\Subject::create([
        'name' => 'Keperawatan',
        'description' => 'Mata Pelajaran Keperawatan',
        'is_active' => true,
        'created_by' => $guru->id
    ]);
    echo "Mata pelajaran Keperawatan dibuat\n";
}
echo "Subject: {$subject->name} (ID: {$subject->id})\n";

// Get class data
$kelas = \App\Models\Kelas::where('name', 'A')->first();
if (!$kelas) {
    $kelas = \App\Models\Kelas::create([
        'name' => 'A',
        'tingkat' => 'XII',
        'jurusan' => 'Keperawatan',
        'status' => 'active'
    ]);
    echo "Kelas A dibuat\n";
}
echo "Kelas: {$kelas->name} (ID: {$kelas->id})\n";

// Create Practical 1: Pemasangan Infus
$practical1 = \App\Models\Practical::updateOrCreate(
    [
        'judul' => 'Pemasangan Infus',
        'guru_id' => $guru->id
    ],
    [
        'subject_id' => $subject->id,
        'judul' => 'Pemasangan Infus',
        'deskripsi' => 'Praktikum pemasangan infus intravena pada pasien',
        'tanggal' => now()->addDays(7),
        'waktu_mulai' => '08:00',
        'waktu_selesai' => '10:00',
        'lokasi' => 'Lab Keperawatan',
        'durasi' => 120,
        'skill_level' => 'intermediate',
        'tools' => [
            'Infus set',
            'Catheter',
            'Alkohol swab',
            'Glove',
            'Tourniquet',
            'Plaster',
            'Kasa'
        ],
        'bahan' => [
            'NaCl 0.9%',
            'Dextrose 5%',
            'Aqua bidestilata',
            'Antiseptik'
        ],
        'instruksi' => [
            'Cuci tangan dan gunakan glove',
            'Pilih vena yang tepat',
            'Lakukan antisepsis',
            'Pasang tourniquet',
            'Masukkan catheter dengan sudut 30-45 derajat',
            'Periksa aliran darah',
            'Fiksasi catheter dengan plaster',
            'Atur kecepatan infus'
        ],
        'keselamatan' => [
            'Gunakan APD dengan benar',
            'Periksa alergi pasien',
            'Monitor tanda vital',
            'Hindari infiltrasi',
            'Buang limbah medis dengan benar'
        ],
        'kelas_id' => $kelas->id,
        'max_score' => 100,
        'is_published' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]
);

echo "Praktikum 1: {$practical1->judul} (ID: {$practical1->id})\n";

// Create Practical 2: Pengecekan Golongan Darah
$practical2 = \App\Models\Practical::updateOrCreate(
    [
        'judul' => 'Pengecekan Golongan Darah',
        'guru_id' => $guru->id
    ],
    [
        'subject_id' => $subject->id,
        'judul' => 'Pengecekan Golongan Darah',
        'deskripsi' => 'Praktikum pengecekan golongan darah ABO dan Rhesus',
        'tanggal' => now()->addDays(14),
        'waktu_mulai' => '10:00',
        'waktu_selesai' => '12:00',
        'lokasi' => 'Lab Keperawatan',
        'durasi' => 120,
        'skill_level' => 'basic',
        'tools' => [
            'Lancet',
            'Slide glass',
            'Pipette',
            'Petri dish',
            'Glove',
            'Alkohol swab',
            'Kapas'
        ],
        'bahan' => [
            'Anti-A serum',
            'Anti-B serum',
            'Anti-D serum',
            'Akuades',
            'Alkohol 70%'
        ],
        'instruksi' => [
            'Siapkan alat dan bahan',
            'Bersihkan area pengambilan sampel',
            'Lakukan aseptik',
            'Ambil sampel darah',
            'Taruh pada slide glass',
            'Tambahkan serum anti-A, anti-B, anti-D',
            'Amati reaksi aglutinasi',
            'Tentukan golongan darah'
        ],
        'keselamatan' => [
            'Gunakan glove dan APD',
            'Lakukan teknik aseptik',
            'Buang limbah medis dengan benar',
            'Cuci tangan setelah praktikum',
            'Laporkan insiden jika terjadi'
        ],
        'kelas_id' => $kelas->id,
        'max_score' => 100,
        'is_published' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]
);

echo "Praktikum 2: {$practical2->judul} (ID: {$practical2->id})\n";

// Create Practical 3: Teknik Injeksi Intramuskular
$practical3 = \App\Models\Practical::updateOrCreate(
    [
        'judul' => 'Teknik Injeksi Intramuskular',
        'guru_id' => $guru->id
    ],
    [
        'subject_id' => $subject->id,
        'judul' => 'Teknik Injeksi Intramuskular',
        'deskripsi' => 'Praktikum teknik injeksi intramuskular pada pasien',
        'tanggal' => now()->addDays(21),
        'waktu_mulai' => '13:00',
        'waktu_selesai' => '15:00',
        'lokasi' => 'Lab Keperawatan',
        'durasi' => 120,
        'skill_level' => 'intermediate',
        'tools' => [
            'Syringe 3ml',
            'Needle 23G',
            'Glove',
            'Alkohol swab',
            'Kapas',
            'Kidney basin',
            'Obat'
        ],
        'bahan' => [
            'Obat suntik',
            'Alkohol 70%',
            'Akuades',
            'Antiseptik'
        ],
        'instruksi' => [
            'Cuci tangan dan gunakan glove',
            'Periksa resep obat',
            'Siapkan obat dengan benar',
            'Pilih lokasi injeksi yang tepat',
            'Bersihkan area dengan alkohol',
            'Lakukan injeksi dengan sudut 90 derajat',
            'Tarik jarum dengan cepat',
            'Berikan tekanan lembut pada area'
        ],
        'keselamatan' => [
            'Gunakan teknik aseptik',
            'Periksa alergi pasien',
            'Gunakan APD dengan benar',
            'Hindari injeksi intravena',
            'Buang limbah medis dengan benar'
        ],
        'kelas_id' => $kelas->id,
        'max_score' => 100,
        'is_published' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]
);

echo "Praktikum 3: {$practical3->judul} (ID: {$practical3->id})\n";

// Verify data
$totalPracticals = \App\Models\Practical::count();
echo "\nTotal praktikum di database: {$totalPracticals}\n";

echo "\n=== DATA PRAKTIKUM BERHASIL DITAMBAHKAN ===\n";
echo "1. Pemasangan Infus\n";
echo "2. Pengecekan Golongan Darah\n";
echo "3. Teknik Injeksi Intramuskular\n";
echo "\nSilakan refresh halaman untuk melihat dropdown praktikum yang sudah terisi!\n";
