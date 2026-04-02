<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExamSchedule;

class TestExamScheduleCommand extends Command
{
    protected $signature = 'test:exam-schedule';
    protected $description = 'Test ExamSchedule model functionality';

    public function handle()
    {
        $this->info('=== EXAM SCHEDULE MODEL TEST ===');
        
        try {
            // Test basic query
            $count = ExamSchedule::count();
            $this->info("✅ Total Exam Schedules: {$count}");
            
            // Test upcoming exams
            $upcoming = ExamSchedule::published()->upcoming()->count();
            $this->info("✅ Upcoming Published Exams: {$upcoming}");
            
            // Test with relations
            $schedules = ExamSchedule::with(['subject', 'kelas', 'creator'])->get();
            $this->info("✅ Schedules with relations: {$schedules->count()}");
            
            foreach ($schedules as $schedule) {
                $subject = $schedule->subject ? $schedule->subject->name : 'No Subject';
                $kelas = $schedule->kelas ? $schedule->kelas->name : 'No Class';
                $creator = $schedule->creator ? $schedule->creator->name : 'No Creator';
                
                $this->line("  - {$schedule->title}");
                $this->line("    Subject: {$subject}");
                $this->line("    Class: {$kelas}");
                $this->line("    Creator: {$creator}");
                $this->line("    Status: {$schedule->status}");
                $this->line("");
            }
            
            $this->info('✅ ExamSchedule model working correctly!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
