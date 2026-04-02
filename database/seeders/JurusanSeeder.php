<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing jurusan data
        Jurusan::query()->delete();

        $jurusanData = [
            [
                'nama' => 'Keperawatan',
                'kode' => 'KPR',
                'deskripsi' => 'Program Keahlian Keperawatan',
                'mata_pelajaran' => [
                    'Anatomi Fisiologi',
                    'Patologi',
                    'Farmakologi',
                    'Keperawatan Dasar',
                    'Keperawatan Medikal Bedah',
                    'Keperawatan Anak',
                    'Keperawatan Maternitas',
                    'Keperawatan Jiwa',
                    'Keperawatan Komunitas'
                ],
                'kapasitas_total' => 120,
                'status' => true,
            ],
            [
                'nama' => 'Farmasi',
                'kode' => 'FAR',
                'deskripsi' => 'Program Keahlian Farmasi Klinis dan Komunitas',
                'mata_pelajaran' => [
                    'Kimia Farmasi',
                    'Farmakologi',
                    'Farmasetika',
                    'Farmakognosi',
                    'Farmasi Klinik',
                    'Managemen Farmasi',
                    'Kimia Analisis',
                    'Biologi Farmasi'
                ],
                'kapasitas_total' => 80,
                'status' => true,
            ],
            [
                'nama' => 'Teknologi Laboratorium Medik',
                'kode' => 'TLM',
                'deskripsi' => 'Program Keahlian Analis Kesehatan',
                'mata_pelajaran' => [
                    'Hematologi',
                    'Kimia Klinik',
                    'Mikrobiologi',
                    'Parasitologi',
                    'Imunologi',
                    'Urinalisis',
                    'Histopatologi',
                    'Toksikologi'
                ],
                'kapasitas_total' => 60,
                'status' => true,
            ],
            [
                'nama' => 'Gizi',
                'kode' => 'GIZ',
                'deskripsi' => 'Program Keahlian Gizi',
                'mata_pelajaran' => [
                    'Ilmu Gizi',
                    'Biokimia Gizi',
                    'Gizi Seimbang',
                    'Gizi Klinik',
                    'Gizi Masyarakat',
                    'Dietetika',
                    'Teknologi Pangan',
                    'Kesehatan Masyarakat'
                ],
                'kapasitas_total' => 40,
                'status' => true,
            ],
            [
                'nama' => 'Rekam Medis',
                'kode' => 'RMK',
                'deskripsi' => 'Program Keahlian Rekam Medis dan Informasi Kesehatan',
                'mata_pelajaran' => [
                    'Anatomi dan Fisiologi',
                    'Dasar-dasar Rekam Medis',
                    'ICD-10',
                    'Rekam Medis Rawat Jalan',
                    'Rekam Medis Rawat Inap',
                    'Rekam Medis UGD',
                    'Informasi Kesehatan',
                    'Etika Profesi',
                    'Komputer dan TI Kesehatan'
                ],
                'kapasitas_total' => 50,
                'status' => true,
            ],
            [
                'nama' => 'Fisioterapi',
                'kode' => 'FIS',
                'deskripsi' => 'Program Keahlian Fisioterapi',
                'mata_pelajaran' => [
                    'Anatomi Fisiologi',
                    'Biomekanika',
                    'Fisioterapi Dasar',
                    'Fisioterapi Neuromuskuloskeletal',
                    'Fisioterapi Kardiopulmoner',
                    'Fisioterapi Anak',
                    'Fisioterapi Geriatri',
                    'Elektroterapi',
                    'Latihan Terapi'
                ],
                'kapasitas_total' => 45,
                'status' => true,
            ]
        ];

        // Insert jurusan data
        foreach ($jurusanData as $jurusan) {
            Jurusan::create($jurusan);
        }

        $this->command->info('Data jurusan berhasil dibuat!');
        $this->command->info('Total jurusan: ' . count($jurusanData));
    }
}
