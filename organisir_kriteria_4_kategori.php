<?php
echo "=== MENGORGANISIR KRITERIA PENILAIIAN KE 4 KATEGORI ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Mengorganisir kriteria penilaian...\n";
    
    // Hapus semua data existing untuk reorganisasi yang bersih
    \Illuminate\Support\Facades\DB::table('criteria')->delete();
    echo "✅ Data existing dihapus\n";
    
    // Definisi 4 kategori untuk setiap mata praktik
    $kriteriaOrganisir = [
        'Pemasangan Infus' => [
            'persiapan' => [
                [
                    'name' => 'Persiapan Alat dan Bahan',
                    'description' => 'Tahap Pra Interaksi - Menyiapkan semua alat dan bahan yang diperlukan untuk pemasangan infus',
                    'weight' => 5,
                    'max_score' => 100,
                    'is_active' => true
                ],
                [
                    'name' => 'Cuci Tangan',
                    'description' => 'Tahap Pra Interaksi - Melakukan cuci tangan dengan teknik yang benar',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Persiapan Perlak',
                    'description' => 'Tahap Pra Interaksi - Menyiapkan perlak dan pengalasnya dengan benar',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Persiapan Tourniquet',
                    'description' => 'Tahap Pra Interaksi - Menyiapkan tourniquet untuk pembuluh darah',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Persiapan Antisepis',
                    'description' => 'Tahap Pra Interaksi - Menyiapkan cairan antisepis untuk kulit',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ]
            ],
            'pelaksanaan' => [
                [
                    'name' => 'Orientasi Pasien',
                    'description' => 'Tahap Orientasi - Memperkenalkan diri dan menjelaskan prosedur',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Pemeriksaan Kondisi Pasien',
                    'description' => 'Tahap Orientasi - Memeriksa kondisi umum dan vena pasien',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Pemilihan Vena',
                    'description' => 'Tahap Orientasi - Memilih vena yang tepat untuk infus',
                    'weight' => 4,
                    'max_score' => 10,
                    'is_active' => true
                ],
                [
                    'name' => 'Pemasangan Tourniquet',
                    'description' => 'Tahapan Kerja - Memasang tourniquet dengan teknik yang benar',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Disinfeksi Kulit',
                    'description' => 'Tahapan Kerja - Mendisinfeksi area kulit dengan antisepis',
                    'weight' => 4,
                    'max_score' => 10,
                    'is_active' => true
                ],
                [
                    'name' => 'Pemasangan Abbocath',
                    'description' => 'Tahapan Kerja - Memasang abbocath/kanula dengan teknik aseptik',
                    'weight' => 5,
                    'max_score' => 10,
                    'is_active' => true
                ],
                [
                    'name' => 'Konfirmasi Pemasangan',
                    'description' => 'Tahapan Kerja - Memastikan abbocath terpasang dengan benar',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ]
            ],
            'hasil' => [
                [
                    'name' => 'Pemasangan Selang Infus',
                    'description' => 'Tahap Terminasi - Menghubungkan selang infus ke abbocath',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Pengaturan Aliran',
                    'description' => 'Tahap Terminasi - Mengatur kecepatan aliran infus',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Pemasangan Fixation',
                    'description' => 'Tahap Terminasi - Memperbaiki posisi infus dengan fixation',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Pemeriksaan Aliran',
                    'description' => 'Tahap Terminasi - Memeriksa aliran dan tetesan infus',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Dokumentasi',
                    'description' => 'Tahap Terminasi - Mendokumentasikan prosedur yang dilakukan',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ]
            ],
            'sikap' => [
                [
                    'name' => 'Komunikasi Efektif',
                    'description' => 'Sikap Profesional - Berkomunikasi dengan baik dan empati',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Kebersihan dan Sterilitas',
                    'description' => 'Sikap Profesional - Menjaga kebersihan dan prinsip sterilitas',
                    'weight' => 4,
                    'max_score' => 10,
                    'is_active' => true
                ],
                [
                    'name' => 'Keselamatan Pasien',
                    'description' => 'Sikap Profesional - Memprioritaskan keselamatan dan kenyamanan pasien',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Tanggung Jawab Profesional',
                    'description' => 'Sikap Profesional - Menunjukkan tanggung jawab dalam praktik',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ]
            ]
        ],
        'Pemeriksaan Golongan Darah' => [
            'persiapan' => [
                [
                    'name' => 'Persiapan Alat Laboratorium',
                    'description' => 'Tahap Pra Analitik - Menyiapkan semua alat dan bahan untuk pemeriksaan golongan darah',
                    'weight' => 5,
                    'max_score' => 100,
                    'is_active' => true
                ],
                [
                    'name' => 'Persiapan Antisera',
                    'description' => 'Tahap Pra Analitik - Menyiapkan antisera A, B, dan AB',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Persiapan Slides',
                    'description' => 'Tahap Pra Analitik - Menyiapkan slides gelas yang bersih',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Pemeriksaan Identitas Sampel',
                    'description' => 'Tahap Pra Analitik - Memeriksa identitas dan label sampel darah',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Cuci Tangan Laboratorium',
                    'description' => 'Tahap Pra Analitik - Melakukan cuci tangan sebelum prosedur',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Pemeriksaan Kondisi Alat',
                    'description' => 'Tahap Pra Analitik - Memeriksa kondisi dan sterilisasi alat',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Persiapan Area Kerja',
                    'description' => 'Tahap Pra Analitik - Menyiapkan area kerja yang steril dan aman',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ]
            ],
            'pelaksanaan' => [
                [
                    'name' => 'Pengambilan Sampel Darah',
                    'description' => 'Tahap Analitik - Mengambil sampel darah dengan teknik yang benar',
                    'weight' => 4,
                    'max_score' => 10,
                    'is_active' => true
                ],
                [
                    'name' => 'Labeling Sampel',
                    'description' => 'Tahap Analitik - Memberi label pada sampel dengan benar',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Pembuatan Smear Darah',
                    'description' => 'Tahap Analitik - Membuat smear darah pada slides',
                    'weight' => 4,
                    'max_score' => 10,
                    'is_active' => true
                ],
                [
                    'name' => 'Penambahan Antisera A',
                    'description' => 'Tahap Analitik - Menambahkan antisera A pada slide pertama',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Penambahan Antisera B',
                    'description' => 'Tahap Analitik - Menambahkan antisera B pada slide kedua',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Pencampuran Sampel',
                    'description' => 'Tahap Analitik - Mencampur sampel darah dengan antisera',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Observasi Awal',
                    'description' => 'Tahap Analitik - Mengamati reaksi aglutinasi awal',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ]
            ],
            'hasil' => [
                [
                    'name' => 'Pemeriksaan Aglutinasi',
                    'description' => 'Pasca Analitik - Memeriksa adanya aglutinasi pada kedua slide',
                    'weight' => 4,
                    'max_score' => 10,
                    'is_active' => true
                ],
                [
                    'name' => 'Interpretasi Hasil',
                    'description' => 'Pasca Analitik - Menginterpretasi hasil pemeriksaan',
                    'weight' => 5,
                    'max_score' => 10,
                    'is_active' => true
                ],
                [
                    'name' => 'Konfirmasi Hasil',
                    'description' => 'Pasca Analitik - Mengkonfirmasi hasil dengan kontrol',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Pencatatan Hasil',
                    'description' => 'Pasca Analitik - Mencatat hasil dalam dokumentasi',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Pelaporan Kepada Dokter',
                    'description' => 'Pasca Analitik - Melaporkan hasil kepada dokter penanggung jawab',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Penyimpanan Sampel',
                    'description' => 'Pasca Analitik - Menyimpan sampel sesuai prosedur',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ]
            ],
            'sikap' => [
                [
                    'name' => 'Prinsip Aseptik',
                    'description' => 'Sikap Profesional - Menerapkan prinsip aseptik dalam laboratorium',
                    'weight' => 4,
                    'max_score' => 10,
                    'is_active' => true
                ],
                [
                    'name' => 'Keselamatan Laboratorium',
                    'description' => 'Sikap Profesional - Mematuhi protokol keselamatan laboratorium',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Teliti dan Cermat',
                    'description' => 'Sikap Profesional - Bekerja dengan teliti dan perhatian detail',
                    'weight' => 3,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Manajemen Waktu',
                    'description' => 'Sikap Profesional - Mengatur waktu dengan efisien',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ],
                [
                    'name' => 'Komunikasi Laboratorium',
                    'description' => 'Sikap Profesional - Berkomunikasi efektif dengan tim laboratorium',
                    'weight' => 2,
                    'max_score' => 5,
                    'is_active' => true
                ]
            ]
        ]
    ];
    
    // Insert data ke database
    $totalInserted = 0;
    foreach ($kriteriaOrganisir as $mataPraktik => $kategoriData) {
        echo "\n--- Memproses: $mataPraktik ---\n";
        
        foreach ($kategoriData as $kategori => $items) {
            echo "  Kategori: $kategori (" . count($items) . " items)\n";
            
            foreach ($items as $item) {
                // Tambahkan field kategori untuk filtering
                $item['description'] = "[{$kategori}] " . $item['description'];
                
                try {
                    \Illuminate\Support\Facades\DB::table('criteria')->insert($item);
                    $totalInserted++;
                    echo "    ✅ {$item['name']}\n";
                } catch (\Exception $e) {
                    echo "    ❌ Error inserting {$item['name']}: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "✅ Total kriteria yang diinsert: $totalInserted\n";
    
    // Verifikasi data
    $totalRecords = \Illuminate\Support\Facades\DB::table('criteria')->count();
    echo "✅ Total records di database: $totalRecords\n";
    
    // Tampilkan distribusi per mata praktik dan kategori
    echo "\n=== DISTRIBUSI DATA ===\n";
    
    foreach ($kriteriaOrganisir as $mataPraktik => $kategoriData) {
        echo "\n$mataPraktik:\n";
        foreach ($kategoriData as $kategori => $items) {
            $count = count($items);
            echo "  - $kategori: $count kriteria\n";
        }
    }
    
    echo "\n🎉 Kriteria penilaian berhasil diorganisir ke dalam 4 kategori!\n";
    echo "📱 Silakan akses: http://127.0.0.1:8000/admin/kriteria-penilaian\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
