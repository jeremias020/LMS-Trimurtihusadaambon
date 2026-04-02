<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimplePenilaianSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Creating sample practical submissions...');
        
        // Get or create guru
        $guru = DB::table('users')->where('email', 'guru@trimurti.sch.id')->first();
        if (!$guru) {
            $guruId = DB::table('users')->insertGetId([
                'name' => 'Guru Sample',
                'email' => 'guru@trimurti.sch.id',
                'password' => bcrypt('password'),
                'role' => 'guru',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $guruId = $guru->id;
        }

        // Get or create practical
        $practical = DB::table('practicals')->where('judul', 'Praktikum Tindakan Dasar')->first();
        if (!$practical) {
            $subject = DB::table('subjects')->where('name', 'Keperawatan Dasar')->first();
            $subjectId = $subject ? $subject->id : 1;
            
            $practicalId = DB::table('practicals')->insertGetId([
                'judul' => 'Praktikum Tindakan Dasar',
                'deskripsi' => 'Praktikum tindakan keperawatan dasar',
                'subject_id' => $subjectId,
                'guru_id' => $guruId,
                'max_score' => 100,
                'tingkat_kelas' => 'XI',
                'tanggal_mulai' => now()->subDays(3),
                'tanggal_selesai' => now()->addDays(3),
                'is_published' => true,
                'instruksi' => 'Lakukan praktikum tindakan keperawatan dasar sesuai SOP',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $practicalId = $practical->id;
        }

        // Get students
        $students = DB::table('users')->where('role', 'siswa')->where('status', 'active')->limit(5)->get();
        
        if ($students->isEmpty()) {
            $this->command->info('No students found. Creating sample students...');
            
            // Get or create kelas
            $kelas = DB::table('kelas')->where('name', 'XI Keperawatan A')->first();
            if (!$kelas) {
                $kelasId = DB::table('kelas')->insertGetId([
                    'name' => 'XI Keperawatan A',
                    'tingkat' => 'XI',
                    'jurusan' => 'Keperawatan',
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $kelasId = $kelas->id;
            }
            
            // Create sample students
            $studentNames = [
                ['Eko Gunawan', 'eko@trimurti.sch.id', '2021001'],
                ['Siti Nurhaliza', 'siti@trimurti.sch.id', '2021002'],
                ['Ahmad Fauzi', 'ahmad@trimurti.sch.id', '2021003'],
                ['Rina Kartika', 'rina@trimurti.sch.id', '2021004'],
                ['Budi Santoso', 'budi@trimurti.sch.id', '2021005'],
            ];
            
            $students = collect();
            foreach ($studentNames as [$name, $email, $nis]) {
                $studentId = DB::table('users')->insertGetId([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt('password'),
                    'nis' => $nis,
                    'role' => 'siswa',
                    'status' => 'active',
                    'kelas_id' => $kelasId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $students->push((object)['id' => $studentId, 'name' => $name]);
            }
        }

        // Create practical submissions (3 students for practical)
        $practicalStudents = $students->take(3);
        $createdSubmissions = [];
        
        foreach ($practicalStudents as $student) {
            // Check if submission already exists
            $existing = DB::table('practical_submissions')
                ->where('practical_id', $practicalId)
                ->where('siswa_id', $student->id)
                ->first();
            
            if (!$existing) {
                $submissionId = DB::table('practical_submissions')->insertGetId([
                    'practical_id' => $practicalId,
                    'siswa_id' => $student->id,
                    'file_path' => 'practicals/sample_' . $student->id . '.pdf',
                    'submitted_at' => now()->subHours(rand(1, 24)),
                    'score' => null, // Ungraded
                    'feedback' => null,
                    'detail_penilaian' => null,
                    'graded_at' => null,
                    'graded_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $createdSubmissions[] = $submissionId;
                $this->command->info("Created practical submission for: {$student->name}");
            }
        }

        // Create assignment submissions (2 students for assignment)
        $assignment = DB::table('assignments')->where('title', 'Tugas Konsep Keperawatan')->first();
        if ($assignment) {
            $assignmentStudents = $students->skip(3)->take(2);
            
            foreach ($assignmentStudents as $student) {
                // Check if submission already exists
                $existing = DB::table('assignment_submissions')
                    ->where('assignment_id', $assignment->id)
                    ->where('siswa_id', $student->id)
                    ->first();
                
                if (!$existing) {
                    DB::table('assignment_submissions')->insert([
                        'assignment_id' => $assignment->id,
                        'siswa_id' => $student->id,
                        'file_path' => 'assignments/sample_' . $student->id . '.pdf',
                        'submitted_at' => now()->subHours(rand(1, 24)),
                        'score' => null, // Ungraded
                        'feedback' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $this->command->info("Created assignment submission for: {$student->name}");
                }
            }
        }

        $this->command->info('Sample penilaian data created successfully!');
        $this->command->info('Practical submissions: ' . count($createdSubmissions));
        $this->command->info('Assignment submissions: 2');
        $this->command->info('Total submissions ready for grading: ' . (count($createdSubmissions) + 2));
        
        // Update auto assessment to use hardcoded criteria
        $this->command->info('');
        $this->command->info('Auto assessment will use hardcoded criteria:');
        $this->command->info('- Persiapan: Peralatan lengkap (20%), Bahan tersedia (15%)');
        $this->command->info('- Pelaksanaan: Prosedur benar (25%), Teknik tepat (20%)');
        $this->command->info('- Hasil: Sesuai target (15%)');
        $this->command->info('- Sikap: Disiplin dan kebersihan (5%)');
    }
}
