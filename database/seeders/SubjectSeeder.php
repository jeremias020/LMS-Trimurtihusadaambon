<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing subjects
        Subject::query()->delete();

        // Get admin user for created_by
        $admin = User::where('role', 'admin')->first();
        $createdBy = $admin ? $admin->id : 1;

        // Get jurusan data
        $jurusanList = Jurusan::all();
        
        $subjects = [];

        // Subjects for each jurusan
        foreach ($jurusanList as $jurusan) {
            $mataPelajaran = $jurusan->mata_pelajaran;
            
            if (is_array($mataPelajaran)) {
                foreach ($mataPelajaran as $index => $namaMapel) {
                    $subjects[] = [
                        'name' => $namaMapel,
                        'code' => $jurusan->kode . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                        'description' => "Mata Pelajaran {$namaMapel} untuk jurusan {$jurusan->nama}",
                        'is_active' => true,
                        'created_by' => $createdBy,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Additional general subjects
        $generalSubjects = [
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
