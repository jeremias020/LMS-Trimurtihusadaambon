<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->error('❌ Tidak ada user dengan role "admin". Silakan jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Hapus data lama agar tidak duplikat
        Subject::whereNotNull('id')->delete();

        $subjects = [
            [
                'name' => 'Keperawatan Dasar',
                'code' => 'KD001',
                'description' => 'Mata pelajaran dasar-dasar keperawatan dan teknik perawatan pasien',
                'is_active' => true,
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anatomi dan Fisiologi',
                'code' => 'AF002',
                'description' => 'Mata pelajaran struktur dan fungsi tubuh manusia',
                'is_active' => true,
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Farmakologi',
                'code' => 'FM003',
                'description' => 'Mata pelajaran tentang obat-obatan dan terapinya',
                'is_active' => true,
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Keperawatan Medikal Bedah',
                'code' => 'KMB004',
                'description' => 'Mata pelajaran keperawatan pada pasien bedah',
                'is_active' => true,
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gawat Darurat',
                'code' => 'GD005',
                'description' => 'Mata pelajaran penanganan pasien gawat darurat',
                'is_active' => true,
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        $count = count($subjects);
        $this->command->info("✅ SubjectSeeder: {$count} mata pelajaran berhasil disimpan.");
    }
}
