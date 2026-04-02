<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\User;

class FixStudentNamesCommand extends Command
{
    protected $signature = 'fix:student-names';
    protected $description = 'Fix student names by copying from users table';

    public function handle()
    {
        $this->info('=== FIXING STUDENT NAMES ===');
        
        try {
            $students = Student::whereNull('name')->orWhere('name', '')->get();
            $fixedCount = 0;
            
            foreach ($students as $student) {
                // Get name from users table
                $user = User::find($student->user_id);
                if ($user) {
                    $student->update(['name' => $user->name]);
                    $this->line("✅ Fixed name for student ID {$student->id}: {$user->name}");
                    $fixedCount++;
                } else {
                    $this->line("❌ No user found for student ID {$student->id}");
                }
            }
            
            $this->info("✅ Successfully fixed {$fixedCount} student names!");
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
