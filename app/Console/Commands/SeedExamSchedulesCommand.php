<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExamSchedule;
use App\Models\Subject;
use App\Models\Kelas;
use App\Models\User;

class SeedExamSchedulesCommand extends Command
{
    protected $signature = 'seed:exam-schedules';
    protected $description = 'Seed sample exam schedules';

    public function handle()
    {
        $this->info('=== SEEDING EXAM SCHEDULES ===');
        
        try {
            // Get sample data
            $subjects = Subject::limit(3)->get();
            $classes = Kelas::limit(2)->get();
            $admin = User::where('role', 'admin')->first();
            
            if ($subjects->isEmpty() || $classes->isEmpty() || !$admin) {
                $this->error('❌ Required data not found. Please run seeder first.');
                return Command::FAILURE;
            }
            
            $examData = [
                [
                    'title' => 'Ujian Tengah Semester Anatomi Fisiologi',
                    'description' => 'Ujian tengah semester untuk mata pelajaran Anatomi Fisiologi',
                    'exam_type' => 'UTS',
                    'subject_id' => $subjects[0]->id,
                    'kelas_id' => $classes[0]->id,
                    'created_by' => $admin->id,
                    'start_time' => now()->addDays(7)->setTime(9, 0),
                    'end_time' => now()->addDays(7)->setTime(11, 0),
                    'location' => 'Ruang Ujian 1',
                    'duration_minutes' => 120,
                    'is_published' => true,
                ],
                [
                    'title' => 'Ujian Praktikum Keperawatan Dasar',
                    'description' => 'Ujian praktikum untuk mata pelajaran Keperawatan Dasar',
                    'exam_type' => 'Praktik',
                    'subject_id' => $subjects[1]->id,
                    'kelas_id' => $classes[0]->id,
                    'created_by' => $admin->id,
                    'start_time' => now()->addDays(14)->setTime(13, 0),
                    'end_time' => now()->addDays(14)->setTime(16, 0),
                    'location' => 'Lab Keperawatan',
                    'duration_minutes' => 180,
                    'is_published' => true,
                ],
                [
                    'title' => 'Ujian Akhir Semester Farmakologi',
                    'description' => 'Ujian akhir semester untuk mata pelajaran Farmakologi',
                    'exam_type' => 'UAS',
                    'subject_id' => $subjects[2]->id,
                    'kelas_id' => $classes[1]->id,
                    'created_by' => $admin->id,
                    'start_time' => now()->addDays(21)->setTime(8, 0),
                    'end_time' => now()->addDays(21)->setTime(10, 0),
                    'location' => 'Ruang Ujian 2',
                    'duration_minutes' => 120,
                    'is_published' => false, // Draft
                ],
            ];
            
            foreach ($examData as $data) {
                ExamSchedule::create($data);
                $this->line("✅ Created: {$data['title']}");
            }
            
            $this->info('✅ Exam schedules seeded successfully!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
