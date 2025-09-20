<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    public function run()
    {
        $subject = Subject::first();

        if (!$subject) {
            $this->command->error('❌ Tidak ada data subject. Silakan jalankan SubjectSeeder terlebih dahulu.');
            return;
        }

        // Opsional: Hapus kriteria lama untuk subject ini agar tidak duplikat
        Criteria::where('subject_id', $subject->id)->delete();

        $criteriaData = [
            [
                'name' => 'Ketepatan Prosedur',
                'description' => 'Kemampuan siswa dalam mengikuti prosedur praktikum dengan tepat dan urut sesuai standar',
                'weight' => 0.25,
                'max_score' => 100,
                'subject_id' => $subject->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Keterampilan Teknis',
                'description' => 'Kemampuan teknis siswa dalam menggunakan alat medis dan melakukan tindakan keperawatan',
                'weight' => 0.30,
                'max_score' => 100,
                'subject_id' => $subject->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kebersihan dan Kerapian',
                'description' => 'Tingkat kebersihan, sterilitas, dan kerapian selama dan setelah praktikum',
                'weight' => 0.15,
                'max_score' => 100,
                'subject_id' => $subject->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kerjasama Tim',
                'description' => 'Kemampuan siswa dalam bekerja sama dengan anggota tim dalam praktikum',
                'weight' => 0.10,
                'max_score' => 100,
                'subject_id' => $subject->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laporan Praktikum',
                'description' => 'Kualitas laporan hasil praktikum yang dibuat siswa sesuai format yang ditentukan',
                'weight' => 0.20,
                'max_score' => 100,
                'subject_id' => $subject->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($criteriaData as $criterion) {
            Criteria::create($criterion);
        }

        $count = count($criteriaData);
        $this->command->info("✅ CriteriaSeeder: {$count} kriteria berhasil disimpan untuk subject: {$subject->name}");
    }
}
