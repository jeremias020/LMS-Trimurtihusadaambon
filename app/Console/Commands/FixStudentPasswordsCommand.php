<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\User;

class FixStudentPasswordsCommand extends Command
{
    protected $signature = 'fix:student-passwords';
    protected $description = 'Fix student passwords by copying from users table';

    public function handle()
    {
        $this->info('=== FIXING STUDENT PASSWORDS ===');
        
        try {
            $students = Student::whereNull('password')->orWhere('password', '')->get();
            $fixedCount = 0;
            
            foreach ($students as $student) {
                // Get password from users table
                $user = User::find($student->user_id);
                if ($user) {
                    $student->update(['password' => $user->password]);
                    $this->line("✅ Fixed password for student: {$student->name}");
                    $fixedCount++;
                } else {
                    $this->line("❌ No user found for student ID {$student->id}");
                }
            }
            
            $this->info("✅ Successfully fixed {$fixedCount} student passwords!");
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
