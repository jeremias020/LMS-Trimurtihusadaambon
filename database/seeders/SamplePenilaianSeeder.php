<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Subject;
use App\Models\Assignment;
use App\Models\Practical;
use App\Models\AssignmentSubmission;
use App\Models\PracticalSubmission;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SamplePenilaianSeeder extends Seeder
{
    public function run()
    {
        // Get or create guru
        $guru = User::where('email', 'guru@trimurti.sch.id')->first();
        if (!$guru) {
            $guru = User::create([
                'name' => 'Guru Sample',
                'email' => 'guru@trimurti.sch.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'status' => 'active',
            ]);
        }

        // Get or create kelas
        $kelas = Kelas::where('name', 'XI Keperawatan A')->first();
        if (!$kelas) {
            $kelas = Kelas::create([
                'name' => 'XI Keperawatan A',
                'tingkat' => 'XI',
                'jurusan' => 'Keperawatan',
                'status' => 'active',
            ]);
        }

        // Get or create subject
        $subject = Subject::where('name', 'Keperawatan Dasar')->first();
        if (!$subject) {
            $subject = Subject::create([
                'name' => 'Keperawatan Dasar',
                'kode' => 'KD-001',
                'is_active' => true,
            ]);
        }

        // Create sample students
        $students = [
            [
                'name' => 'Eko Gunawan',
                'email' => 'eko@trimurti.sch.id',
                'nis' => '2021001',
                'role' => 'siswa',
                'status' => 'active',
                'kelas_id' => $kelas->id,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@trimurti.sch.id',
                'nis' => '2021002',
                'role' => 'siswa',
                'status' => 'active',
                'kelas_id' => $kelas->id,
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@trimurti.sch.id',
                'nis' => '2021003',
                'role' => 'siswa',
                'status' => 'active',
                'kelas_id' => $kelas->id,
            ],
            [
                'name' => 'Rina Kartika',
                'email' => 'rina@trimurti.sch.id',
                'nis' => '2021004',
                'role' => 'siswa',
                'status' => 'active',
                'kelas_id' => $kelas->id,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@trimurti.sch.id',
                'nis' => '2021005',
                'role' => 'siswa',
                'status' => 'active',
                'kelas_id' => $kelas->id,
            ],
        ];

        $createdStudents = [];
        foreach ($students as $studentData) {
            $student = User::where('email', $studentData['email'])->first();
            if (!$student) {
                $studentData['password'] = Hash::make('password');
                $student = User::create($studentData);
            }
            $createdStudents[] = $student;
        }

        // Create Assignment for 2 students
        $assignment = Assignment::where('title', 'Tugas Konsep Keperawatan')->first();
        if (!$assignment) {
            $assignment = Assignment::create([
                'title' => 'Tugas Konsep Keperawatan',
                'description' => 'Tugas penulisan konsep dasar keperawatan',
                'subject_id' => $subject->id,
                'guru_id' => $guru->id,
                'max_score' => 100,
                'due_date' => Carbon::now()->addDays(7),
                'is_published' => true,
                'instructions' => 'Tulis esai 500 kata tentang konsep keperawatan',
            ]);
        }

        // Create Practical for 3 students
        $practical = Practical::where('judul', 'Praktikum Tindakan Dasar')->first();
        if (!$practical) {
            $practical = Practical::create([
                'judul' => 'Praktikum Tindakan Dasar',
                'deskripsi' => 'Praktikum tindakan keperawatan dasar',
                'subject_id' => $subject->id,
                'guru_id' => $guru->id,
                'max_score' => 100,
                'tingkat_kelas' => 'XI',
                'tanggal_mulai' => Carbon::now()->subDays(3),
                'tanggal_selesai' => Carbon::now()->addDays(3),
                'is_published' => true,
                'instruksi' => 'Lakukan praktikum tindakan keperawatan dasar sesuai SOP',
            ]);
        }

        // Create Assignment Submissions (2 students)
        $assignmentSubmissions = [
            ['student' => $createdStudents[0], 'score' => null], // Eko Gunawan - ungraded
            ['student' => $createdStudents[1], 'score' => null], // Siti Nurhaliza - ungraded
        ];

        foreach ($assignmentSubmissions as $submissionData) {
            $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
                ->where('siswa_id', $submissionData['student']->id)
                ->first();

            if (!$existing) {
                AssignmentSubmission::create([
                    'assignment_id' => $assignment->id,
                    'siswa_id' => $submissionData['student']->id,
                    'file_path' => 'assignments/sample_' . $submissionData['student']->nis . '.pdf',
                    'submitted_at' => Carbon::now()->subHours(rand(1, 24)),
                    'score' => $submissionData['score'],
                    'feedback' => null,
                ]);
            }
        }

        // Create Practical Submissions (3 students)
        $practicalSubmissions = [
            ['student' => $createdStudents[2], 'score' => null], // Ahmad Fauzi - ungraded
            ['student' => $createdStudents[3], 'score' => null], // Rina Kartika - ungraded
            ['student' => $createdStudents[4], 'score' => null], // Budi Santoso - ungraded
        ];

        foreach ($practicalSubmissions as $submissionData) {
            $existing = PracticalSubmission::where('practical_id', $practical->id)
                ->where('siswa_id', $submissionData['student']->id)
                ->first();

            if (!$existing) {
                PracticalSubmission::create([
                    'practical_id' => $practical->id,
                    'siswa_id' => $submissionData['student']->id,
                    'file_path' => 'practicals/sample_' . $submissionData['student']->nis . '.pdf',
                    'submitted_at' => Carbon::now()->subHours(rand(1, 24)),
                    'score' => $submissionData['score'],
                    'feedback' => null,
                    'detail_penilaian' => null,
                ]);
            }
        }

        $this->command->info('Sample penilaian data created successfully!');
        $this->command->info('Students created:');
        foreach ($createdStudents as $student) {
            $this->command->info("- {$student->name} ({$student->email})");
        }
        $this->command->info('Assignment created: ' . $assignment->title);
        $this->command->info('Practical created: ' . $practical->judul);
        $this->command->info('Assignment submissions: 2 (ungraded)');
        $this->command->info('Practical submissions: 3 (ungraded)');
        $this->command->info('Total submissions ready for grading: 5');
    }
}
