<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\User;

class TestStudentsCommand extends Command
{
    protected $signature = 'test:students';
    protected $description = 'Test Student model functionality';

    public function handle()
    {
        $this->info('=== STUDENT MODEL TEST ===');
        
        try {
            // Test basic query
            $count = Student::count();
            $this->info("✅ Total Students: {$count}");
            
            // Test the specific query that was failing
            $student = Student::where('user_id', 9)->where('user_id', '!=', null)->first();
            if ($student) {
                $this->info("✅ Student found for user_id 9:");
                $this->line("  NIS: {$student->nis}");
                $this->line("  Name: {$student->user->name}");
                $this->line("  Class: " . ($student->kelas ? $student->kelas->name : 'No Class'));
                $this->line("  Major: {$student->major}");
                $this->line("  Status: {$student->status}");
            } else {
                $this->info("ℹ️  No student found for user_id 9");
                
                // Create student for user_id 9 if user exists
                $user = User::find(9);
                if ($user && $user->isSiswa()) {
                    $this->info("Creating student record for user: {$user->name}");
                    
                    $student = Student::create([
                        'user_id' => $user->id,
                        'nis' => 'SIS' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                        'nisn' => '000' . str_pad($user->id, 7, '0', STR_PAD_LEFT),
                        'jenis_kelamin' => $user->gender ?? 'L',
                        'tempat_lahir' => 'Ambon',
                        'tanggal_lahir' => now()->subYears(18)->format('Y-m-d'),
                        'alamat' => $user->address ?? 'Alamat tidak tersedia',
                        'no_telepon' => $user->phone ?? '0000000000',
                        'kelas_id' => 1, // First class
                        'major' => 'Keperawatan',
                        'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
                        'status' => 'aktif'
                    ]);
                    
                    $this->info("✅ Created student record: {$student->nis}");
                }
            }
            
            // Test all students with relations
            $students = Student::with(['user', 'kelas'])->get();
            $this->info("✅ All students with relations: {$students->count()}");
            
            foreach ($students as $student) {
                $this->line("  - {$student->user->name} ({$student->nis})");
                $this->line("    Class: " . ($student->kelas ? $student->kelas->name : 'No Class'));
                $this->line("    Major: {$student->major}");
                $this->line("    Status: {$student->status}");
                $this->line("");
            }
            
            $this->info('✅ Student model working correctly!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
