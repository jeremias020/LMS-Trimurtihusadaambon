<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run()
    {
        $guru = User::where('role', 'guru')->first();
        if (!$guru) {
            $this->command->error('❌ Tidak ada user dengan role "guru". Silakan jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $subject = Subject::first();
        if (!$subject) {
            $this->command->error('❌ Tidak ada subject. Silakan jalankan SubjectSeeder terlebih dahulu.');
            return;
        }

        // Hapus material lama untuk guru & subject ini agar tidak duplikat
        Material::where('guru_id', $guru->id)
                ->where('subject_id', $subject->id)
                ->delete();

        $materials = [
            [
                'guru_id' => $guru->id,
                'subject_id' => $subject->id,
                'judul' => 'Anatomi Dasar Manusia',
                'description' => 'Materi pembelajaran tentang struktur anatomi dasar tubuh manusia untuk siswa SMK Kesehatan',
                'file' => 'anatomi-dasar.pdf',
                'file_size' => 2500000,
                'file_type' => 'pdf',
                'is_published' => true,
                'views_count' => 45,
                'downloads_count' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'guru_id' => $guru->id,
                'subject_id' => $subject->id,
                'judul' => 'Dasar-Dasar Keperawatan',
                'description' => 'Pengenalan dasar-dasar ilmu keperawatan dan teknik dasar untuk perawatan pasien',
                'file' => 'dasar-keperawatan.pdf',
                'file_size' => 1800000,
                'file_type' => 'pdf',
                'is_published' => true,
                'views_count' => 38,
                'downloads_count' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'guru_id' => $guru->id,
                'subject_id' => $subject->id,
                'judul' => 'Farmakologi Dasar',
                'description' => 'Materi tentang pengenalan obat-obatan, dosis penggunaan, dan efek samping',
                'file' => 'farmakologi-dasar.pdf',
                'file_size' => 3200000,
                'file_type' => 'pdf',
                'is_published' => true,
                'views_count' => 28,
                'downloads_count' => 18,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'guru_id' => $guru->id,
                'subject_id' => $subject->id,
                'judul' => 'Teknik Sterilisasi Alat Medis',
                'description' => 'Panduan teknik sterilisasi alat medis yang benar dan prosedur kebersihan',
                'file' => 'teknik-sterilisasi.pdf',
                'file_size' => 1500000,
                'file_type' => 'pdf',
                'is_published' => true,
                'views_count' => 32,
                'downloads_count' => 22,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'guru_id' => $guru->id,
                'subject_id' => $subject->id,
                'judul' => 'Etika Profesi Kesehatan',
                'description' => 'Materi tentang etika dan profesionalisme dalam bidang kesehatan dan keperawatan',
                'file' => 'etika-profesi.pdf',
                'file_size' => 2100000,
                'file_type' => 'pdf',
                'is_published' => true,
                'views_count' => 40,
                'downloads_count' => 28,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }

        $count = count($materials);
        $this->command->info("✅ MaterialSeeder: {$count} materi berhasil disimpan untuk guru: {$guru->name} dan subject: {$subject->name}");
    }
}
