<?php
echo "=== MENAMBAHKAN KRITERIA PENILAIAN PRAKTIK ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "✅ Tabel criteria ditemukan\n";
    
    // Data Pemasangan Infus
    $pemasanganInfus = [
        [
            'name' => 'Pemasangan Infus',
            'description' => 'Prosedur pemasangan infus pada pasien',
            'weight' => 100,
            'max_score' => 100,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ],
        // Tahap Pra Interaksi
        ['name' => 'Mengecek Catatan Medic Pasien', 'description' => 'Tahap Pra Interaksi - Mengecek catatan medic pasien', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Cuci Tangan', 'description' => 'Tahap Pra Interaksi - Cuci tangan', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Persiapan Alat Dan Bahan', 'description' => 'Tahap Pra Interaksi - Persiapan alat dan bahan', 'weight' => 5, 'max_score' => 10, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Perlak Dan Pengalasnya', 'description' => 'Tahap Pra Interaksi - Perlak dan pengalasnya', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Tourniquet', 'description' => 'Tahap Pra Interaksi - Tourniquet', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Kapas Alkohol/Alkohol Sweb', 'description' => 'Tahap Pra Interaksi - Kapas alkohol/alkohol sweb', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Plester', 'description' => 'Tahap Pra Interaksi - Plester', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Gunting Perban', 'description' => 'Tahap Pra Interaksi - Gunting perban', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Kain Kasa Steril', 'description' => 'Tahap Pra Interaksi - Kain kasa steril', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Jarum Infus (Abbocath)', 'description' => 'Tahap Pra Interaksi - Jarum infus (Abbocath)', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Cairan Infus', 'description' => 'Tahap Pra Interaksi - Cairan infus', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Bengkok', 'description' => 'Tahap Pra Interaksi - Bengkok', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Bak Instumen Steril', 'description' => 'Tahap Pra Interaksi - Bak instumen steril', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Sarung Tangan Bersih', 'description' => 'Tahap Pra Interaksi - Sarung tangan bersih', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Standar Infus', 'description' => 'Tahap Pra Interaksi - Standar infus', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        
        // Tahap Orientasi
        ['name' => 'Memberikan salam dan menyebutkan nama pasien', 'description' => 'Tahap Orientasi - Memberikan salam dan menyebutkan nama pasien', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Menjelaskan tujuan dan prosedur', 'description' => 'Tahap Orientasi - Menjelaskan tujuan dan prosedur yang akan dilakukan', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Memberikan kesempatan bertanya', 'description' => 'Tahap Orientasi - Memberikan kesempatan kepada pasien untuk bertanya', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Kontak waktu', 'description' => 'Tahap Orientasi - Kontak waktu', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        
        // Tahapan Kerja (ambil beberapa contoh)
        ['name' => 'Jaga privasi klien', 'description' => 'Tahapan Kerja - Jaga privasi klien', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Membawa alat kedekat pasien', 'description' => 'Tahapan Kerja - Membawa alat kedekat pasien', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Mencuci tangan (kerja)', 'description' => 'Tahapan Kerja - Mencuci tangan', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Memakai sarung tangan', 'description' => 'Tahapan Kerja - Memakai sarung tangan', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Membuka daerah yang akan di infus', 'description' => 'Tahapan Kerja - Membuka daerah yang akan di infus', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        
        // Tahap Terminasi
        ['name' => 'Aliran dan tetesan infus', 'description' => 'Tahap Terminasi - Aliran dan tetesan infus', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Tidak terjadi hematom', 'description' => 'Tahap Terminasi - Tidak terjadi hematom', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Sterilitas terjaga', 'description' => 'Tahap Terminasi - Sterilitas terjaga', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Infus terpasang rapi', 'description' => 'Tahap Terminasi - Infus terpasang rapi', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Pesien nyaman', 'description' => 'Tahap Terminasi - Pesien nyaman', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Lingkungan bersih', 'description' => 'Tahap Terminasi - Lingkungan bersih', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Menanyakan respon klien', 'description' => 'Tahap Terminasi - Menanyakan respon klien', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Mengakhiri pemeriksaan', 'description' => 'Tahap Terminasi - Mengakhiri pemeriksaan dengan mengucapkan salam penutup', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Dokumentasi', 'description' => 'Tahap Terminasi - Dokumentasi', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
    ];
    
    // Data Pemeriksaan Golongan Darah
    $golonganDarah = [
        [
            'name' => 'Pemeriksaan Golongan Darah',
            'description' => 'Prosedur pemeriksaan golongan darah (TLM)',
            'weight' => 100,
            'max_score' => 100,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ],
        // Tahap Pra Analitik
        ['name' => 'Konfirmasi Pemeriksaan Pasien', 'description' => 'Tahap Pra Analitik - Konfirmasi pemeriksaan pasien', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Persiapan Alat Dan Bahan (Darah)', 'description' => 'Tahap Pra Analitik - Persiapan alat dan bahan', 'weight' => 5, 'max_score' => 10, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Plate golda/Slide golda', 'description' => 'Tahap Pra Analitik - Plate golda/Slide golda', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Batang pengaduk', 'description' => 'Tahap Pra Analitik - Batang pengaduk', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Tissue', 'description' => 'Tahap Pra Analitik - Tissue', 'weight' => 1, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Handscoon', 'description' => 'Tahap Pra Analitik - Handscoon', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Masker', 'description' => 'Tahap Pra Analitik - Masker', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Pipet', 'description' => 'Tahap Pra Analitik - Pipet', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Darah EDTA', 'description' => 'Tahap Pra Analitik - Darah EDTA', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Reagent golda', 'description' => 'Tahap Pra Analitik - Reagent golda', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Antisera A', 'description' => 'Tahap Pra Analitik - Antisera A', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Antisera B', 'description' => 'Tahap Pra Analitik - Antisera B', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Antisera AB', 'description' => 'Tahap Pra Analitik - Antisera AB', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Antisera D', 'description' => 'Tahap Pra Analitik - Antisera D', 'weight' => 2, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        
        // Tahap Analitik
        ['name' => 'Teteskan darah pada plate golda', 'description' => 'Tahap Analitik - Teteskan darah pada plate golda/slide golda ke masing-masing lingkaran', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Teteskan reagent', 'description' => 'Tahap Analitik - Teteskan 1 tetes reagen masing-masing ke lingkaran sesuai dengan antisera golda', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Aduk dan goyangkan', 'description' => 'Tahap Analitik - Aduk dengan batang pengaduk sampai tercampur, kemudian goyangkan kurang lebih 1 menit', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Perhatikan aglutinasi', 'description' => 'Tahap Analitik - Perhatikan aglutinasi yang mungkin terjadi di tiap lingkaran', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        
        // Pasca Analitik
        ['name' => 'Golongan darah A', 'description' => 'Pasca Analitik - Golongan darah A: adanya aglutinasi pada lingkaran A dan AB', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Golongan darah B', 'description' => 'Pasca Analitik - Golongan darah B: adanya aglutinasi pada lingkaran B dan AB', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Golongan darah O', 'description' => 'Pasca Analitik - Golongan darah O: tidak terjadi aglutinasi pada lingkaran A, B, dan AB', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Golongan darah AB', 'description' => 'Pasca Analitik - Golongan darah AB: adanya aglutinasi pada pada lingkaran A, B dan AB', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Rhesus positif', 'description' => 'Pasca Analitik - Rhesus positif: aglutinasi pada lingkaran D', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Rhesus negatif', 'description' => 'Pasca Analitik - Rhesus negatif: tidak terjadi aglutinasi pada lingkaran D', 'weight' => 3, 'max_score' => 5, 'is_active' => true, 'subject_id' => null, 'created_at' => now(), 'updated_at' => now()],
    ];
    
    echo "\n=== MENAMBAHKAN DATA PEMASANGAN INFUS ===\n";
    $insertedInfus = 0;
    foreach ($pemasanganInfus as $data) {
        try {
            \Illuminate\Support\Facades\DB::table('criteria')->insert($data);
            echo "✅ {$data['name']}\n";
            $insertedInfus++;
        } catch (\Exception $e) {
            echo "❌ {$data['name']}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== MENAMBAHKAN DATA GOLONGAN DARAH ===\n";
    $insertedDarah = 0;
    foreach ($golonganDarah as $data) {
        try {
            \Illuminate\Support\Facades\DB::table('criteria')->insert($data);
            echo "✅ {$data['name']}\n";
            $insertedDarah++;
        } catch (\Exception $e) {
            echo "❌ {$data['name']}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "Pemasangan Infus: $insertedInfus data berhasil ditambahkan\n";
    echo "Pemeriksaan Golongan Darah: $insertedDarah data berhasil ditambahkan\n";
    echo "Total: " . ($insertedInfus + $insertedDarah) . " data berhasil ditambahkan\n";
    
    echo "\n=== VERIFICATION ===\n";
    $totalKriteria = \Illuminate\Support\Facades\DB::table('criteria')->count();
    echo "Total kriteria di database: $totalKriteria\n";
    
    // Tampilkan beberapa data yang baru ditambahkan
    $newData = \Illuminate\Support\Facades\DB::table('criteria')
        ->where('name', 'like', '%Pemasangan Infus%')
        ->orWhere('name', 'like', '%Pemeriksaan Golongan Darah%')
        ->orderBy('id', 'desc')
        ->limit(5)
        ->get();
    
    echo "\nLatest added criteria:\n";
    foreach ($newData as $item) {
        echo "- {$item->name} (ID: {$item->id})\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
