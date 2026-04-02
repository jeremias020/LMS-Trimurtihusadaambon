<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\User;
use App\Models\Kelas;

class SeedStudentsCommand extends Command
{
    protected $signature = 'seed:students';
    protected $description = 'Seed sample student data';

    public function handle()
    {
        $this->info('=== SEEDING STUDENT DATA ===');
        
        try {
            // Get siswa users without student records
            $siswaUsers = User::where('role', 'siswa')->get();
            $classes = Kelas::all();
            
            if ($siswaUsers->isEmpty()) {
                $this->error('❌ No siswa users found. Please create siswa users first.');
                return Command::FAILURE;
            }
            
            if ($classes->isEmpty()) {
                $this->error('❌ No classes found. Please create classes first.');
                return Command::FAILURE;
            }
            
            $createdCount = 0;
            foreach ($siswaUsers as $user) {
                // Check if student already exists
                $existingStudent = Student::where('user_id', $user->id)->first();
                if ($existingStudent) {
                    $this->line("ℹ️  Student already exists for {$user->name}");
                    continue;
                }
                
                // Assign to class based on user ID or randomly
                $classIndex = ($user->id - 1) % $classes->count();
                $kelas = $classes[$classIndex];
                
                $student = Student::create([
                    'user_id' => $user->id,
                    'nis' => 'SIS' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                    'nisn' => '000' . str_pad($user->id, 7, '0', STR_PAD_LEFT),
                    'jenis_kelamin' => $user->gender ?? 'L',
                    'tempat_lahir' => 'Ambon',
                    'tanggal_lahir' => now()->subYears(16 + ($user->id % 3))->format('Y-m-d'),
                    'alamat' => $user->address ?? 'Alamat tidak tersedia',
                    'no_telepon' => $user->phone ?? '0000000000',
                    'kelas_id' => $kelas->id,
                    'major' => $kelas->major ?? 'Keperawatan',
                    'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
                    'nama_ortu' => 'Orang Tua ' . $user->name,
                    'no_telepon_ortu' => '0812345678' . str_pad($user->id, 1, '0', STR_PAD_LEFT),
                    'golongan_darah' => ['A', 'B', 'AB', 'O'][($user->id - 1) % 4],
                    'status' => 'aktif'
                ]);
                
                $this->line("✅ Created student: {$student->nis} - {$user->name}");
                $createdCount++;
            }
            
            $this->info("✅ Successfully created {$createdCount} student records!");
            
            // Show summary
            $totalStudents = Student::count();
            $this->info("📊 Total students in database: {$totalStudents}");
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
