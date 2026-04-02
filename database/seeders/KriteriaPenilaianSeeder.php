<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaPenilaianSeeder extends Seeder
{
    public function run()
    {
        $kriteria = [
            // Kriteria untuk Keperawatan Dasar - XI
            [
                'nama' => 'Peralatan lengkap dan siap',
                'kategori' => 'persiapan',
                'deskripsi' => 'Memastikan semua peralatan praktik tersedia dan dalam kondisi baik',
                'bobot' => 0.20,
                'mata_praktik' => 'Keperawatan Dasar',
                'tingkat_kelas' => 'XI',
                'is_active' => true,
            ],
            [
                'nama' => 'Bahan praktik tersedia',
                'kategori' => 'persiapan',
                'deskripsi' => 'Semua bahan yang dibutuhkan untuk praktik sudah disiapkan',
                'bobot' => 0.15,
                'mata_praktik' => 'Keperawatan Dasar',
                'tingkat_kelas' => 'XI',
                'is_active' => true,
            ],
            [
                'nama' => 'Prosedur diikuti dengan benar',
                'kategori' => 'pelaksanaan',
                'deskripsi' => 'Langkah-langkah praktik dilakukan sesuai SOP yang berlaku',
                'bobot' => 0.25,
                'mata_praktik' => 'Keperawatan Dasar',
                'tingkat_kelas' => 'XI',
                'is_active' => true,
            ],
            [
                'nama' => 'Teknik yang digunakan tepat',
                'kategori' => 'pelaksanaan',
                'deskripsi' => 'Teknik pelaksanaan praktik sesuai dengan yang diajarkan',
                'bobot' => 0.20,
                'mata_praktik' => 'Keperawatan Dasar',
                'tingkat_kelas' => 'XI',
                'is_active' => true,
            ],
            [
                'nama' => 'Hasil praktik sesuai target',
                'kategori' => 'hasil',
                'deskripsi' => 'Hasil akhir praktik memenuhi kriteria yang ditentukan',
                'bobot' => 0.15,
                'mata_praktik' => 'Keperawatan Dasar',
                'tingkat_kelas' => 'XI',
                'is_active' => true,
            ],
            [
                'nama' => 'Disiplin dan kebersihan',
                'kategori' => 'sikap',
                'deskripsi' => 'Menunjukkan sikap disiplin dan menjaga kebersihan selama praktik',
                'bobot' => 0.05,
                'mata_praktik' => 'Keperawatan Dasar',
                'tingkat_kelas' => 'XI',
                'is_active' => true,
            ],
            
            // Kriteria untuk Anatomi dan Fisiologi - X
            [
                'nama' => 'Bahan referensi lengkap',
                'kategori' => 'persiapan',
                'deskripsi' => 'Membawa buku referensi dan materi pendukung',
                'bobot' => 0.25,
                'mata_praktik' => 'Anatomi dan Fisiologi',
                'tingkat_kelas' => 'X',
                'is_active' => true,
            ],
            [
                'nama' => 'Pemahaman konsep dasar',
                'kategori' => 'persiapan',
                'deskripsi' => 'Memahami konsep dasar sebelum praktik dimulai',
                'bobot' => 0.15,
                'mata_praktik' => 'Anatomi dan Fisiologi',
                'tingkat_kelas' => 'X',
                'is_active' => true,
            ],
            [
                'nama' => 'Identifikasi struktur benar',
                'kategori' => 'pelaksanaan',
                'deskripsi' => 'Mampu mengidentifikasi struktur anatomi dengan benar',
                'bobot' => 0.30,
                'mata_praktik' => 'Anatomi dan Fisiologi',
                'tingkat_kelas' => 'X',
                'is_active' => true,
            ],
            [
                'nama' => 'Penjelasan fungsi tepat',
                'kategori' => 'pelaksanaan',
                'deskripsi' => 'Mampu menjelaskan fungsi organ dengan tepat',
                'bobot' => 0.20,
                'mata_praktik' => 'Anatomi dan Fisiologi',
                'tingkat_kelas' => 'X',
                'is_active' => true,
            ],
            [
                'nama' => 'Laporan praktik lengkap',
                'kategori' => 'hasil',
                'deskripsi' => 'Membuat laporan praktik yang lengkap dan sistematis',
                'bobot' => 0.10,
                'mata_praktik' => 'Anatomi dan Fisiologi',
                'tingkat_kelas' => 'X',
                'is_active' => true,
            ],
            
            // Kriteria untuk Farmakologi - XII
            [
                'nama' => 'Obat dan alat siap',
                'kategori' => 'persiapan',
                'deskripsi' => 'Menyiapkan obat-obatan dan alat yang dibutuhkan',
                'bobot' => 0.20,
                'mata_praktik' => 'Farmakologi',
                'tingkat_kelas' => 'XII',
                'is_active' => true,
            ],
            [
                'nama' => 'Dosis perhitungan benar',
                'kategori' => 'persiapan',
                'deskripsi' => 'Menghitung dosis obat dengan tepat',
                'bobot' => 0.15,
                'mata_praktik' => 'Farmakologi',
                'tingkat_kelas' => 'XII',
                'is_active' => true,
            ],
            [
                'nama' => 'Prosedur pemberian benar',
                'kategori' => 'pelaksanaan',
                'deskripsi' => 'Melakukan prosedur pemberian obat sesuai SOP',
                'bobot' => 0.25,
                'mata_praktik' => 'Farmakologi',
                'tingkat_kelas' => 'XII',
                'is_active' => true,
            ],
            [
                'nama' => 'Monitoring efek samping',
                'kategori' => 'pelaksanaan',
                'deskripsi' => 'Memantau efek samping obat dengan teliti',
                'bobot' => 0.20,
                'mata_praktik' => 'Farmakologi',
                'tingkat_kelas' => 'XII',
                'is_active' => true,
            ],
            [
                'nama' => 'Dokumentasi lengkap',
                'kategori' => 'hasil',
                'deskripsi' => 'Mendokumentasikan pemberian obat dengan lengkap',
                'bobot' => 0.15,
                'mata_praktik' => 'Farmakologi',
                'tingkat_kelas' => 'XII',
                'is_active' => true,
            ],
            [
                'nama' => 'Etika profesi',
                'kategori' => 'sikap',
                'deskripsi' => 'Menunjukkan etika profesi dalam praktik',
                'bobot' => 0.05,
                'mata_praktik' => 'Farmakologi',
                'tingkat_kelas' => 'XII',
                'is_active' => true,
            ],
        ];

        foreach ($kriteria as $item) {
            DB::table('kriteria_penilaian')->updateOrInsert(
                [
                    'nama' => $item['nama'],
                    'mata_praktik' => $item['mata_praktik'],
                    'tingkat_kelas' => $item['tingkat_kelas'],
                ],
                $item
            );
        }

        $this->command->info('Kriteria penilaian seeded successfully!');
        $this->command->info('Total kriteria: ' . count($kriteria));
        $this->command->info('Subjects: Keperawatan Dasar, Anatomi dan Fisiologi, Farmakologi');
        $this->command->info('Levels: X, XI, XII');
    }
}
