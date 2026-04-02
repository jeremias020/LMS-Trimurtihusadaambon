<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;

class TestStudentQueryCommand extends Command
{
    protected $signature = 'test:student-query';
    protected $description = 'Test specific student query that was failing';

    public function handle()
    {
        $this->info('=== TESTING SPECIFIC STUDENT QUERY ===');
        
        try {
            // This is the exact query that was failing
            $student = Student::where('user_id', 9)
                            ->where('user_id', '!=', null)
                            ->first();
            
            if ($student) {
                $this->info("✅ Query executed successfully!");
                $this->info("✅ Found student: {$student->user->name}");
                $this->line("  NIS: {$student->nis}");
                $this->line("  NISN: {$student->nisn}");
                $this->line("  Gender: {$student->jenis_kelamin}");
                $this->line("  Birth Place: {$student->tempat_lahir}");
                $this->line("  Birth Date: {$student->date_lahir}");
                $this->line("  Address: {$student->alamat}");
                $this->line("  Phone: {$student->no_telepon}");
                $this->line("  Class: " . ($student->kelas ? $student->kelas->name : 'No Class'));
                $this->line("  Major: {$student->major}");
                $this->line("  Academic Year: {$student->tahun_ajaran}");
                $this->line("  Status: {$student->status}");
            } else {
                $this->info("ℹ️  No student found for user_id 9");
            }
            
            $this->info('✅ Student query working correctly!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
