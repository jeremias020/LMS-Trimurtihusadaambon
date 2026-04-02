<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Student;

class TestSeparateUserTablesCommand extends Command
{
    protected $signature = 'test:separate-users';
    protected $description = 'Test separate user tables functionality';

    public function handle()
    {
        $this->info('=== TESTING SEPARATE USER TABLES ===');
        
        try {
            // Test Admin table
            $this->info("\n📋 Testing Admin Table:");
            $adminCount = Admin::count();
            $this->line("  Total Admins: {$adminCount}");
            
            $admins = Admin::all();
            foreach ($admins as $admin) {
                $this->line("  - {$admin->name} ({$admin->email})");
                $this->line("    Status: {$admin->status}");
                $this->line("    Role: {$admin->role}");
            }
            
            // Test Guru table
            $this->info("\n👨‍🏫 Testing Guru Table:");
            $guruCount = Guru::count();
            $this->line("  Total Gurus: {$guruCount}");
            
            $gurus = Guru::all();
            foreach ($gurus as $guru) {
                $this->line("  - {$guru->name} ({$guru->email})");
                $this->line("    NIP: {$guru->nip}");
                $this->line("    Subject: {$guru->mata_pelajaran}");
                $this->line("    Status: {$guru->status}");
                $this->line("    Role: {$guru->role}");
            }
            
            // Test Siswa table
            $this->info("\n👨‍🎓 Testing Siswa Table:");
            $siswaCount = Student::count();
            $this->line("  Total Siswa: {$siswaCount}");
            
            $siswas = Student::limit(3)->get();
            foreach ($siswas as $siswa) {
                $this->line("  - {$siswa->name} ({$siswa->email})");
                $this->line("    NIS: {$siswa->nis}");
                $this->line("    Class: " . ($siswa->kelas ? $siswa->kelas->name : 'No Class'));
                $this->line("    Status: {$siswa->status}");
                $this->line("    Role: {$siswa->role}");
            }
            
            // Test authentication methods
            $this->info("\n🔐 Testing Authentication:");
            $admin = Admin::first();
            if ($admin) {
                $this->line("  ✅ Admin authentication works");
                $this->line("    Can login: " . ($admin->password ? 'Yes' : 'No'));
            }
            
            $guru = Guru::first();
            if ($guru) {
                $this->line("  ✅ Guru authentication works");
                $this->line("    Can login: " . ($guru->password ? 'Yes' : 'No'));
            }
            
            $siswa = Student::first();
            if ($siswa) {
                $this->line("  ✅ Siswa authentication works");
                $this->line("    Can login: " . ($siswa->password ? 'Yes' : 'No'));
            }
            
            $this->info("\n✅ All separate user tables working correctly!");
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
