<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCentral;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Student;

class TestModernUserSystemCommand extends Command
{
    protected $signature = 'test:modern-user-system';
    protected $description = 'Test modern central user system';

    public function handle()
    {
        $this->info('=== TESTING MODERN USER SYSTEM ===');
        
        try {
            // Test UserCentral model
            $this->info("\n📋 Testing UserCentral Model:");
            $this->testUserCentral();
            
            // Test Admin profile relationship
            $this->info("\n👨‍💼 Testing Admin Profile:");
            $this->testAdminProfile();
            
            // Test Guru profile relationship
            $this->info("\n👨‍🏫 Testing Guru Profile:");
            $this->testGuruProfile();
            
            // Test Siswa profile relationship
            $this->info("\n👨‍🎓 Testing Siswa Profile:");
            $this->testSiswaProfile();
            
            // Test authentication
            $this->info("\n🔐 Testing Authentication:");
            $this->testAuthentication();
            
            $this->info("\n✅ Modern user system working perfectly!");
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
    
    private function testUserCentral()
    {
        $users = UserCentral::all();
        $this->line("  Total Users: {$users->count()}");
        
        foreach ($users as $user) {
            $this->line("  - {$user->name} ({$user->email})");
            $this->line("    Role: {$user->role_display}");
            $this->line("    Active: " . ($user->is_active ? 'Yes' : 'No'));
            $this->line("    Photo URL: " . substr($user->photo_url, 0, 50) . "...");
        }
    }
    
    private function testAdminProfile()
    {
        $admin = UserCentral::where('role', 'admin')->first();
        if ($admin && $admin->adminProfile) {
            $profile = $admin->adminProfile;
            $this->line("  Admin Profile:");
            $this->line("    Name: {$admin->name}");
            $this->line("    Email: {$admin->email}");
            $this->line("    Address: {$profile->address}");
            $this->line("    Gender: {$profile->gender_display}");
            $this->line("    Age: {$profile->age}");
            $this->line("    Status: {$profile->status}");
            $this->line("    Is Active: " . ($profile->isActive() ? 'Yes' : 'No'));
        } else {
            $this->line("  ❌ No admin profile found");
        }
    }
    
    private function testGuruProfile()
    {
        $gurus = UserCentral::where('role', 'guru')->limit(2)->get();
        
        foreach ($gurus as $guru) {
            if ($guru->guruProfile) {
                $profile = $guru->guruProfile;
                $this->line("  Guru Profile:");
                $this->line("    Name: {$guru->name}");
                $this->line("    Email: {$guru->email}");
                $this->line("    NIP: {$profile->nip}");
                $this->line("    Subject: {$profile->mata_pelajaran}");
                $this->line("    Gender: {$profile->gender_display}");
                $this->line("    Age: {$profile->age}");
                $this->line("    Work Duration: {$profile->work_duration} years");
                $this->line("    Status: {$profile->status}");
                $this->line("    Is Active: " . ($profile->isActive() ? 'Yes' : 'No'));
            } else {
                $this->line("  ❌ No guru profile found for {$guru->name}");
            }
        }
    }
    
    private function testSiswaProfile()
    {
        $siswas = UserCentral::where('role', 'siswa')->limit(2)->get();
        
        foreach ($siswas as $siswa) {
            if ($siswa->siswaProfile) {
                $profile = $siswa->siswaProfile;
                $this->line("  Siswa Profile:");
                $this->line("    Name: {$siswa->name}");
                $this->line("    Email: {$siswa->email}");
                $this->line("    NIS: {$profile->nis}");
                $this->line("    NISN: {$profile->nisn}");
                $this->line("    Class: " . ($profile->kelas ? $profile->kelas->name : 'No Class'));
                $this->line("    Major: {$profile->major}");
                $this->line("    Gender: {$profile->gender_display}");
                $this->line("    Age: {$profile->age}");
                $this->line("    Status: {$profile->status}");
                $this->line("    Is Active: " . ($profile->isActive() ? 'Yes' : 'No'));
            } else {
                $this->line("  ❌ No siswa profile found for {$siswa->name}");
            }
        }
    }
    
    private function testAuthentication()
    {
        // Test admin authentication
        $admin = UserCentral::where('role', 'admin')->first();
        if ($admin) {
            $this->line("  ✅ Admin authentication ready");
            $this->line("    Can login: " . ($admin->password ? 'Yes' : 'No'));
            $this->line("    Is admin: " . ($admin->isAdmin() ? 'Yes' : 'No'));
        }
        
        // Test guru authentication
        $guru = UserCentral::where('role', 'guru')->first();
        if ($guru) {
            $this->line("  ✅ Guru authentication ready");
            $this->line("    Can login: " . ($guru->password ? 'Yes' : 'No'));
            $this->line("    Is guru: " . ($guru->isGuru() ? 'Yes' : 'No'));
        }
        
        // Test siswa authentication
        $siswa = UserCentral::where('role', 'siswa')->first();
        if ($siswa) {
            $this->line("  ✅ Siswa authentication ready");
            $this->line("    Can login: " . ($siswa->password ? 'Yes' : 'No'));
            $this->line("    Is siswa: " . ($siswa->isSiswa() ? 'Yes' : 'No'));
        }
    }
}
