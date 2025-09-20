<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\PracticeModule;
use Illuminate\Database\Seeder;

class PracticeModuleSeeder extends Seeder
{
    public function run()
    {
        $subject = Subject::first();

        if (!$subject) {
            $this->command->error('❌ Tidak ada data subject. Silakan jalankan SubjectSeeder terlebih dahulu.');
            return;
        }

        // Hapus data lama agar tidak duplikat
        PracticeModule::whereNotNull('id')->delete();

        $modules = [
            [
                'code' => 'PM001',
                'name' => 'Pemeriksaan Tanda-Tanda Vital', // ✅ UBAH: nama → name
                'description' => 'Modul praktik pemeriksaan tanda-tanda vital pasien untuk siswa keperawatan', // ✅ UBAH: deskripsi → description
                'subject_id' => $subject->id, // ✅ UBAH: mata_pelajaran_id → subject_id
                'class' => 'X', // ✅ UBAH: kelas → class
                'semester' => '1',
                'credits' => 2, // ✅ UBAH: sks → credits
                'duration' => 120, // ✅ UBAH: durasi → duration
                'tools_required' => 'Stetoskop, Tensimeter, Termometer, Stopwatch, Charts', // ✅ UBAH: alat_diperlukan → tools_required
                'materials_required' => 'Formulir pencatatan, Alkohol swab, Handsanitizer', // ✅ UBAH: bahan_diperlukan → materials_required
                'safety_procedures' => 'Gunakan APD lengkap, Cuci tangan sebelum dan setelah praktik, Disinfeksi alat', // ✅ UBAH: prosedur_keselamatan → safety_procedures
                'learning_objectives' => 'Siswa mampu melakukan pemeriksaan tanda-tanda vital dengan teknik yang benar dan akurat', // ✅ UBAH: tujuan_pembelajaran → learning_objectives
                'competency_indicators' => json_encode([ // ✅ UBAH: indikator_kompetensi → competency_indicators
                    ['indicator' => 'Mengukur tekanan darah dengan benar', 'weight' => 30], // ✅ UBAH: indikator → indicator, bobot → weight
                    ['indicator' => 'Mengukur suhu tubuh dengan akurat', 'weight' => 25],
                    ['indicator' => 'Menghitung denyut nadi dengan tepat', 'weight' => 25],
                    ['indicator' => 'Mengukur laju pernapasan dengan benar', 'weight' => 20]
                ]),
                'assessment_criteria' => json_encode([ // ✅ UBAH: kriteria_penilaian → assessment_criteria
                    ['criterion' => 'Ketepatan pengukuran dan hasil', 'weight' => 40], // ✅ UBAH: kriteria → criterion, bobot → weight
                    ['criterion' => 'Teknik dan prosedur yang benar', 'weight' => 30],
                    ['criterion' => 'Komunikasi dengan pasien', 'weight' => 20],
                    ['criterion' => 'Dokumentasi hasil', 'weight' => 10]
                ]),
                'status' => 'active', // ✅ UBAH: aktif → active
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PM002',
                'name' => 'Pemberian Injeksi Intramuskular',
                'description' => 'Modul praktik pemberian injeksi intramuskular dengan teknik yang aman',
                'subject_id' => $subject->id,
                'class' => 'XI',
                'semester' => '2',
                'credits' => 3,
                'duration' => 180,
                'tools_required' => 'Suntikan 3cc/5cc, Kapas alkohol, Sarung tangan steril, Safety box',
                'materials_required' => 'Obat latihan, Plaster, Kasa steril',
                'safety_procedures' => 'Gunakan sarung tangan steril, Perhatikan teknik aseptik, Buang jarum di safety box',
                'learning_objectives' => 'Siswa mampu melakukan injeksi intramuskular dengan teknik yang benar, aman, dan nyaman untuk pasien',
                'competency_indicators' => json_encode([
                    ['indicator' => 'Mempersiapkan alat dan bahan dengan benar', 'weight' => 20],
                    ['indicator' => 'Melakukan teknik aseptik yang tepat', 'weight' => 30],
                    ['indicator' => 'Memilih lokasi injeksi yang tepat', 'weight' => 25],
                    ['indicator' => 'Melakukan injeksi dengan teknik yang benar', 'weight' => 25]
                ]),
                'assessment_criteria' => json_encode([
                    ['criterion' => 'Keamanan prosedur dan pasien', 'weight' => 40],
                    ['criterion' => 'Ketepatan teknik dan lokasi', 'weight' => 30],
                    ['criterion' => 'Kenyamanan dan komunikasi dengan pasien', 'weight' => 20],
                    ['criterion' => 'Dokumentasi dan disposisi alat', 'weight' => 10]
                ]),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($modules as $module) {
            PracticeModule::create($module);
        }

        $count = count($modules);
        $this->command->info("✅ PracticeModuleSeeder: {$count} modul praktik berhasil disimpan untuk subject: {$subject->name}");
    }
}
