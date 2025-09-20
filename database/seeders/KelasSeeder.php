<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run()
    {
        // Ambil satu guru sebagai wali kelas contoh
        $guruId = User::where('role', 'guru')->inRandomOrder()->first()?->id;

        $classes = [
            [
                'name' => 'A',
                'code' => 'X-KD-A',
                'grade' => 'X',
                'major' => 'Keperawatan',
                'description' => 'Kelas X Keperawatan A',
                'guru_id' => $guruId, // ✅ UBAH: class_teacher_id → guru_id
                'capacity' => 40,
                'academic_year' => '2024/2025',
                'status' => 'active',
            ],
            [
                'name' => 'B',
                'code' => 'X-KD-B',
                'grade' => 'X',
                'major' => 'Keperawatan',
                'description' => 'Kelas X Keperawatan B',
                'guru_id' => $guruId, // ✅ UBAH
                'capacity' => 40,
                'academic_year' => '2024/2025',
                'status' => 'active',
            ],
            [
                'name' => 'A',
                'code' => 'XI-KD-A',
                'grade' => 'XI',
                'major' => 'Keperawatan',
                'description' => 'Kelas XI Keperawatan A',
                'guru_id' => $guruId, // ✅ UBAH
                'capacity' => 40,
                'academic_year' => '2024/2025',
                'status' => 'active',
            ],
        ];

        foreach ($classes as $class) {
            Kelas::create($class);
        }

        $this->command->info('✅ ' . count($classes) . ' kelas berhasil disimpan.');
    }
}
