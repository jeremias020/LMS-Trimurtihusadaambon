<?php
echo "=== MENAMBAHKAN KRITERIA PENILAIAN PRAKTIK ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Cek apakah tabel kriteria_penilaian ada
    if (!\Illuminate\Support\Facades\Schema::hasTable('kriteria_penilaian')) {
        echo "❌ Tabel kriteria_penilaian tidak ada\n";
        exit;
    }
    
    echo "✅ Tabel kriteria_penilaian ditemukan\n";
    
    // Data Pemasangan Infus
    $pemasanganInfus = [
        [
            'nama' => 'Pemasangan Infus',
            'kategori' => 'praktik',
            'deskripsi' => 'Prosedur pemasangan infus pada pasien',
            'created_at' => now(),
            'updated_at' => now()
        ],
        // Tahap Pra Interaksi
        ['nama' => 'Mengecek Catatan Medic Pasien', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Mengecek catatan medic pasien', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Cuci Tangan', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Cuci tangan', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Persiapan Alat Dan Bahan', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Persiapan alat dan bahan', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Perlak Dan Pengalasnya', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Perlak dan pengalasnya', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Tourniquet', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Tourniquet', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Kapas Alkohol/Alkohol Sweb', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Kapas alkohol/alkohol sweb', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Plester', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Plester', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Gunting Perban', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Gunting perban', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Kain Kasa Steril', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Kain kasa steril', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Jarum Infus (Abbocath)', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Jarum infus (Abbocath)', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Cairan Infus', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Cairan infus', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Bengkok', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Bengkok', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Bak Instumen Steril', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Bak instumen steril', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Sarung Tangan Bersih', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Sarung tangan bersih', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Standar Infus', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Pra Interaksi - Standar infus', 'created_at' => now(), 'updated_at' => now()],
        
        // Tahap Orientasi
        ['nama' => 'Memberikan salam dan menyebutkan nama pasien', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Orientasi - Memberikan salam dan menyebutkan nama pasien', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Menjelaskan tujuan dan prosedur', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Orientasi - Menjelaskan tujuan dan prosedur yang akan dilakukan', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Memberikan kesempatan bertanya', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Orientasi - Memberikan kesempatan kepada pasien untuk bertanya', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Kontak waktu', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Orientasi - Kontak waktu', 'created_at' => now(), 'updated_at' => now()],
        
        // Tahapan Kerja (ambil beberapa contoh)
        ['nama' => 'Jaga privasi klien', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahapan Kerja - Jaga privasi klien', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Membawa alat kedekat pasien', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahapan Kerja - Membawa alat kedekat pasien', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Mencuci tangan', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahapan Kerja - Mencuci tangan', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Memakai sarung tangan', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahapan Kerja - Memakai sarung tangan', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Membuka daerah yang akan di infus', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahapan Kerja - Membuka daerah yang akan di infus', 'created_at' => now(), 'updated_at' => now()],
        
        // Tahap Terminasi
        ['nama' => 'Aliran dan tetesan infus', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Terminasi - Aliran dan tetesan infus', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Tidak terjadi hematom', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Terminasi - Tidak terjadi hematom', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Sterilitas terjaga', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Terminasi - Sterilitas terjaga', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Infus terpasang rapi', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Terminasi - Infus terpasang rapi', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Pesien nyaman', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Terminasi - Pesien nyaman', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Lingkungan bersih', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Terminasi - Lingkungan bersih', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Menanyakan respon klien', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Terminasi - Menanyakan respon klien', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Mengakhiri pemeriksaan', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Terminasi - Mengakhiri pemeriksaan dengan mengucapkan salam penutup', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Dokumentasi', 'kategori' => 'praktik', 'parent_id' => 1, 'deskripsi' => 'Tahap Terminasi - Dokumentasi', 'created_at' => now(), 'updated_at' => now()],
    ];
    
    // Data Pemeriksaan Golongan Darah
    $golonganDarah = [
        [
            'nama' => 'Pemeriksaan Golongan Darah',
            'kategori' => 'praktik',
            'deskripsi' => 'Prosedur pemeriksaan golongan darah (TLM)',
            'created_at' => now(),
            'updated_at' => now()
        ],
        // Tahap Pra Analitik
        ['nama' => 'Konfirmasi Pemeriksaan Pasien', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Konfirmasi pemeriksaan pasien', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Persiapan Alat Dan Bahan', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Persiapan alat dan bahan', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Plate golda/Slide golda', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Plate golda/Slide golda', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Batang pengaduk', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Batang pengaduk', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Tissue', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Tissue', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Handscoon', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Handscoon', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Masker', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Masker', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Pipet', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Pipet', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Darah EDTA', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Darah EDTA', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Reagent golda', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Reagent golda', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Antisera A', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Antisera A', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Antisera B', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Antisera B', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Antisera AB', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Antisera AB', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Antisera D', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Pra Analitik - Antisera D', 'created_at' => now(), 'updated_at' => now()],
        
        // Tahap Analitik
        ['nama' => 'Teteskan darah pada plate golda', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Analitik - Teteskan darah pada plate golda/slide golda ke masing-masing lingkaran', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Teteskan reagent', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Analitik - Teteskan 1 tetes reagen masing-masing ke lingkaran sesuai dengan antisera golda', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Aduk dan goyangkan', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Analitik - Aduk dengan batang pengaduk sampai tercampur, kemudian goyangkan kurang lebih 1 menit', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Perhatikan aglutinasi', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Tahap Analitik - Perhatikan aglutinasi yang mungkin terjadi di tiap lingkaran', 'created_at' => now(), 'updated_at' => now()],
        
        // Pasca Analitik
        ['nama' => 'Golongan darah A', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Pasca Analitik - Golongan darah A: adanya aglutinasi pada lingkaran A dan AB', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Golongan darah B', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Pasca Analitik - Golongan darah B: adanya aglutinasi pada lingkaran B dan AB', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Golongan darah O', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Pasca Analitik - Golongan darah O: tidak terjadi aglutinasi pada lingkaran A, B, dan AB', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Golongan darah AB', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Pasca Analitik - Golongan darah AB: adanya aglutinasi pada pada lingkaran A, B dan AB', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Rhesus positif', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Pasca Analitik - Rhesus positif: aglutinasi pada lingkaran D', 'created_at' => now(), 'updated_at' => now()],
        ['nama' => 'Rhesus negatif', 'kategori' => 'praktik', 'parent_id' => 31, 'deskripsi' => 'Pasca Analitik - Rhesus negatif: tidak terjadi aglutinasi pada lingkaran D', 'created_at' => now(), 'updated_at' => now()],
    ];
    
    echo "\n=== MENAMBAHKAN DATA PEMASANGAN INFUS ===\n";
    $insertedInfus = 0;
    foreach ($pemasanganInfus as $data) {
        try {
            \Illuminate\Support\Facades\DB::table('kriteria_penilaian')->insert($data);
            echo "✅ {$data['nama']}\n";
            $insertedInfus++;
        } catch (\Exception $e) {
            echo "❌ {$data['nama']}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== MENAMBAHKAN DATA GOLONGAN DARAH ===\n";
    $insertedDarah = 0;
    foreach ($golonganDarah as $data) {
        try {
            \Illuminate\Support\Facades\DB::table('kriteria_penilaian')->insert($data);
            echo "✅ {$data['nama']}\n";
            $insertedDarah++;
        } catch (\Exception $e) {
            echo "❌ {$data['nama']}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "Pemasangan Infus: $insertedInfus data berhasil ditambahkan\n";
    echo "Pemeriksaan Golongan Darah: $insertedDarah data berhasil ditambahkan\n";
    echo "Total: " . ($insertedInfus + $insertedDarah) . " data berhasil ditambahkan\n";
    
    echo "\n=== VERIFICATION ===\n";
    $totalKriteria = \Illuminate\Support\Facades\DB::table('kriteria_penilaian')->count();
    echo "Total kriteria penilaian di database: $totalKriteria\n";
    
    // Tampilkan beberapa data yang baru ditambahkan
    $newData = \Illuminate\Support\Facades\DB::table('kriteria_penilaian')
        ->where('nama', 'like', '%Pemasangan Infus%')
        ->orWhere('nama', 'like', '%Pemeriksaan Golongan Darah%')
        ->orderBy('id', 'desc')
        ->limit(5)
        ->get();
    
    echo "\nLatest added criteria:\n";
    foreach ($newData as $item) {
        echo "- {$item->nama} (ID: {$item->id})\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
