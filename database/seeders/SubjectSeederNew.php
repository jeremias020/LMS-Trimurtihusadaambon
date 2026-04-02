<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubjectSeederNew extends Seeder
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
            [
                'name' => 'Pendidikan Agama',
                'code' => 'AGM-01',
                'description' => 'Pendidikan Agama Islam',
                'is_active' => true,
                'created_by' => $createdBy,
            ],
            [
                'name' => 'Pendidikan Kewarganegaraan',
                'code' => 'PKN-01',
                'description' => 'Pendidikan Kewarganegaraan',
                'is_active' => true,
                'created_by' => $createdBy,
            ],
            [
                'name' => 'Bahasa Indonesia',
                'code' => 'BND-01',
                'description' => 'Bahasa Indonesia',
                'is_active' => true,
                'created_by' => $createdBy,
            ],
            [
                'name' => 'Bahasa Inggris',
                'code' => 'ENG-01',
                'description' => 'Bahasa Inggris',
                'is_active' => true,
                'created_by' => $createdBy,
            ],
            [
                'name' => 'Matematika',
                'code' => 'MTK-01',
                'description' => 'Matematika',
                'is_active' => true,
                'created_by' => $createdBy,
            ],
            [
                'name' => 'Olahraga',
                'code' => 'OR-01',
                'description' => 'Pendidikan Jasmani dan Olahraga',
                'is_active' => true,
                'created_by' => $createdBy,
            ],
            [
                'name' => 'Seni Budaya',
                'code' => 'SKB-01',
                'description' => 'Seni Budaya dan Keterampilan',
                'is_active' => true,
                'created_by' => $createdBy,
            ],
            [
                'name' => 'Teknologi Informasi dan Komunikasi',
                'code' => 'TIK-01',
                'description' => 'Teknologi Informasi dan Komunikasi',
                'is_active' => true,
                'created_by' => $createdBy,
            ]
        ];

        // Add general subjects
        foreach ($generalSubjects as $subject) {
            $subjects[] = array_merge($subject, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert all subjects
        Subject::insert($subjects);

        $this->command->info('Data mata pelajaran berhasil dibuat!');
        $this->command->info('Total mata pelajaran: ' . count($subjects));
        
        // Display subjects by jurusan
        foreach ($jurusanList as $jurusan) {
            $count = count($jurusan->mata_pelajaran ?? []);
            $this->command->info("- {$jurusan->nama}: {$count} mata pelajaran");
        }
        $this->command->info("- Mata Pelajaran Umum: " . count($generalSubjects));
    }
}
