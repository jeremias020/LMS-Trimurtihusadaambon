<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCentral;
use Illuminate\Support\Facades\Hash;

class TestLoginCredentialsCommand extends Command
{
    protected $signature = 'test:login-credentials';
    protected $description = 'Test login credentials for all user roles';

    public function handle()
    {
        $this->info('=== TESTING LOGIN CREDENTIALS ===');
        
        try {
            // Test Admin login
            $this->testAdminLogin();
            
            // Test Guru login
            $this->testGuruLogin();
            
            // Test Siswa login
            $this->testSiswaLogin();
            
            $this->info("\n📋 SUMMARY:");
            $this->info("✅ Use these credentials for testing:");
            $this->info("\n🔑 ADMIN LOGIN:");
            $this->info("   Email: admin@lms-trimurti.sch.id");
            $this->info("   Password: password");
            $this->info("   Role: admin");
            
            $this->info("\n👨‍🏫 GURU LOGIN:");
            $this->info("   Email: siti@lms-trimurti.sch.id");
            $this->info("   Password: password");
            $this->info("   Role: guru");
            
            $this->info("\n👨‍🎓 SISWA LOGIN:");
            $this->info("   Email: agus.setiawan@lms-trimurti.sch.id");
            $this->info("   Password: password");
            $this->info("   Role: siswa");
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
    
    private function testAdminLogin()
    {
        $this->info("\n🔐 TESTING ADMIN LOGIN:");
        
        $admin = UserCentral::where('role', 'admin')->first();
        
        if ($admin) {
            $this->line("  ✅ Admin found:");
            $this->line("     Name: {$admin->name}");
            $this->line("     Email: {$admin->email}");
            $this->line("     Username: {$admin->username}");
            $this->line("     Active: " . ($admin->is_active ? 'Yes' : 'No'));
            
            // Test password
            if (Hash::check('password', $admin->password)) {
                $this->line("     Password: ✅ Correct (use 'password')");
            } else {
                $this->line("     Password: ❌ Incorrect");
            }
            
            // Test profile
            if ($admin->adminProfile) {
                $this->line("     Profile: ✅ Connected");
            } else {
                $this->line("     Profile: ❌ Not connected");
            }
        } else {
            $this->line("  ❌ No admin found");
        }
    }
    
    private function testGuruLogin()
    {
        $this->info("\n👨‍🏫 TESTING GURU LOGIN:");
        
        $gurus = UserCentral::where('role', 'guru')->limit(3)->get();
        
        foreach ($gurus as $index => $guru) {
            $this->line("  ✅ Guru " . ($index + 1) . ":");
            $this->line("     Name: {$guru->name}");
            $this->line("     Email: {$guru->email}");
            $this->line("     Username: {$guru->username}");
            $this->line("     Active: " . ($guru->is_active ? 'Yes' : 'No'));
            
            // Test password
            if (Hash::check('password', $guru->password)) {
                $this->line("     Password: ✅ Correct (use 'password')");
            } else {
                $this->line("     Password: ❌ Incorrect");
            }
            
            // Test profile
            if ($guru->guruProfile) {
                $profile = $guru->guruProfile;
                $this->line("     Profile: ✅ Connected (NIP: {$profile->nip})");
            } else {
                $this->line("     Profile: ❌ Not connected");
            }
        }
    }
    
    private function testSiswaLogin()
    {
        $this->info("\n👨‍🎓 TESTING SISWA LOGIN:");
        
        $siswas = UserCentral::where('role', 'siswa')->limit(3)->get();
        
        foreach ($siswas as $index => $siswa) {
            $this->line("  ✅ Siswa " . ($index + 1) . ":");
            $this->line("     Name: {$siswa->name}");
            $this->line("     Email: {$siswa->email}");
            $this->line("     Username: {$siswa->username}");
            $this->line("     Active: " . ($siswa->is_active ? 'Yes' : 'No'));
            
            // Test password
            if (Hash::check('password', $siswa->password)) {
                $this->line("     Password: ✅ Correct (use 'password')");
            } else {
                $this->line("     Password: ❌ Incorrect");
            }
            
            // Test profile
            if ($siswa->siswaProfile) {
                $profile = $siswa->siswaProfile;
                $this->line("     Profile: ✅ Connected (NIS: {$profile->nis})");
            } else {
                $this->line("     Profile: ❌ Not connected");
            }
        }
    }
}
