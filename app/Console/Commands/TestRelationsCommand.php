<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class TestRelationsCommand extends Command
{
    protected $signature = 'test:relations';
    protected $description = 'Test database relationships';

    public function handle()
    {
        $this->info('=== DATABASE RELATIONSHIPS TEST ===');
        
        try {
            // Test majors with classes
            $this->info('📚 Majors and Classes:');
            $majors = Jurusan::with('kelas')->get();
            foreach ($majors as $major) {
                $this->line("  {$major->name} ({$major->code}):");
                foreach ($major->kelas as $class) {
                    $this->line("    - {$class->name} ({$class->academic_year})");
                }
            }
            
            $this->line(str_repeat("-", 50));
            
            // Test subjects with majors
            $this->info('📖 Subjects by Major:');
            $subjects = Subject::with('jurusan')->get();
            foreach ($subjects->groupBy('major_id') as $majorId => $subjectGroup) {
                $major = $subjectGroup->first()->jurusan;
                $this->line("  {$major->name}:");
                foreach ($subjectGroup as $subject) {
                    $this->line("    - {$subject->name} ({$subject->code})");
                }
            }
            
            $this->line(str_repeat("-", 50));
            
            // Test class subjects (schedule)
            $this->info('📅 Class Schedule:');
            $classSubjects = DB::table('class_subjects')
                ->join('classes', 'class_subjects.class_id', '=', 'classes.id')
                ->join('subjects', 'class_subjects.subject_id', '=', 'subjects.id')
                ->join('users', 'class_subjects.teacher_id', '=', 'users.id')
                ->select(
                    'classes.name as class_name',
                    'subjects.name as subject_name',
                    'users.name as teacher_name',
                    'class_subjects.day',
                    'class_subjects.start_time',
                    'class_subjects.end_time',
                    'class_subjects.room'
                )
                ->get();
                
            foreach ($classSubjects as $schedule) {
                $this->line("  {$schedule->class_name} - {$schedule->subject_name}");
                $this->line("    Guru: {$schedule->teacher_name}");
                $this->line("    Jadwal: {$schedule->day}, {$schedule->start_time} - {$schedule->end_time}");
                $this->line("    Ruang: {$schedule->room}");
                $this->line("");
            }
            
            $this->line(str_repeat("-", 50));
            
            // Test class students
            $this->info('👥 Class Students:');
            $classStudents = DB::table('class_students')
                ->join('classes', 'class_students.class_id', '=', 'classes.id')
                ->join('users', 'class_students.student_id', '=', 'users.id')
                ->select(
                    'classes.name as class_name',
                    'users.name as student_name',
                    'users.email as student_email',
                    'users.nis_nip as student_nis'
                )
                ->get();
                
            foreach ($classStudents->groupBy('class_name') as $className => $students) {
                $this->line("  {$className}:");
                foreach ($students as $student) {
                    $this->line("    - {$student->student_name} ({$student->student_nis})");
                }
            }
            
            $this->info('✅ All relationships working correctly!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
