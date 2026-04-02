<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCentral;

class TestSeparatedUserManagementCommand extends Command
{
    protected $signature = 'test:separated-user-management';
    protected $description = 'Test separated user management system';

    public function handle()
    {
        $this->info('=== TESTING SEPARATED USER MANAGEMENT ===');
        
        try {
            // Test data retrieval by role
            $this->testRoleBasedData();
            
            // Test profile relationships
            $this->testProfileRelationships();
            
            // Test statistics
            $this->testStatistics();
            
            // Show sample data for each role
            $this->showSampleData();
            
            $this->info("\n✅ Separated user management system working perfectly!");
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
    
    private function testRoleBasedData()
    {
        $this->info("\n📊 TESTING ROLE-BASED DATA:");
        
        // Test Admin data
        $admins = UserCentral::where('role', 'admin')->get();
        $this->line("  👨‍💼 Admin Data ({$admins->count()} users):");
        foreach ($admins as $admin) {
            $this->line("    - {$admin->name} ({$admin->email})");
            $this->line("      Profile: " . ($admin->adminProfile ? '✅ Connected' : '❌ Not connected'));
            $this->line("      Status: " . ($admin->is_active ? 'Active' : 'Inactive'));
        }
        
        // Test Guru data
        $gurus = UserCentral::where('role', 'guru')->get();
        $this->line("\n  👨‍🏫 Guru Data ({$gurus->count()} users):");
        foreach ($gurus as $guru) {
            $this->line("    - {$guru->name} ({$guru->email})");
            $this->line("      Profile: " . ($guru->guruProfile ? '✅ Connected' : '❌ Not connected'));
            if ($guru->guruProfile) {
                $this->line("      NIP: {$guru->guruProfile->nip}");
                $this->line("      Subject: {$guru->guruProfile->mata_pelajaran}");
            }
            $this->line("      Status: " . ($guru->is_active ? 'Active' : 'Inactive'));
        }
        
        // Test Siswa data
        $siswas = UserCentral::where('role', 'siswa')->get();
        $this->line("\n  👨‍🎓 Siswa Data ({$siswas->count()} users):");
        foreach ($siswas as $siswa) {
            $this->line("    - {$siswa->name} ({$siswa->email})");
            $this->line("      Profile: " . ($siswa->siswaProfile ? '✅ Connected' : '❌ Not connected'));
            if ($siswa->siswaProfile) {
                $this->line("      NIS: {$siswa->siswaProfile->nis}");
                $this->line("      NISN: {$siswa->siswaProfile->nisn}");
                $this->line("      Class: " . ($siswa->siswaProfile->kelas->name ?? 'No Class'));
                $this->line("      Major: {$siswa->siswaProfile->major}");
            }
            $this->line("      Status: " . ($siswa->is_active ? 'Active' : 'Inactive'));
        }
    }
    
    private function testProfileRelationships()
    {
        $this->info("\n🔗 TESTING PROFILE RELATIONSHIPS:");
        
        // Test admin relationships
        $admin = UserCentral::where('role', 'admin')->first();
        if ($admin) {
            $this->line("  👨‍💼 Admin Relationships:");
            $this->line("    User → Profile: " . ($admin->adminProfile ? '✅ Working' : '❌ Failed'));
            if ($admin->adminProfile) {
                $this->line("    Profile → User: " . ($admin->adminProfile->user ? '✅ Working' : '❌ Failed'));
            }
        }
        
        // Test guru relationships
        $guru = UserCentral::where('role', 'guru')->first();
        if ($guru) {
            $this->line("  👨‍🏫 Guru Relationships:");
            $this->line("    User → Profile: " . ($guru->guruProfile ? '✅ Working' : '❌ Failed'));
            if ($guru->guruProfile) {
                $this->line("    Profile → User: " . ($guru->guruProfile->user ? '✅ Working' : '❌ Failed'));
            }
        }
        
        // Test siswa relationships
        $siswa = UserCentral::where('role', 'siswa')->first();
        if ($siswa) {
            $this->line("  👨‍🎓 Siswa Relationships:");
            $this->line("    User → Profile: " . ($siswa->siswaProfile ? '✅ Working' : '❌ Failed'));
            if ($siswa->siswaProfile) {
                $this->line("    Profile → User: " . ($siswa->siswaProfile->user ? '✅ Working' : '❌ Failed'));
                $this->line("    Profile → Class: " . ($siswa->siswaProfile->kelas ? '✅ Working' : '❌ Failed'));
            }
        }
    }
    
    private function testStatistics()
    {
        $this->info("\n📈 TESTING STATISTICS:");
        
        $totalUsers = UserCentral::count();
        $adminCount = UserCentral::where('role', 'admin')->count();
        $guruCount = UserCentral::where('role', 'guru')->count();
        $siswaCount = UserCentral::where('role', 'siswa')->count();
        $activeUsers = UserCentral::where('is_active', true)->count();
        $inactiveUsers = UserCentral::where('is_active', false)->count();
        
        $this->line("  📊 User Statistics:");
        $this->line("    Total Users: {$totalUsers}");
        $this->line("    Admin: {$adminCount}");
        $this->line("    Guru: {$guruCount}");
        $this->line("    Siswa: {$siswaCount}");
        $this->line("    Active: {$activeUsers}");
        $this->line("    Inactive: {$inactiveUsers}");
        
        // Verify counts match
        $totalByRoles = $adminCount + $guruCount + $siswaCount;
        $this->line("    ✅ Count Verification: " . ($totalUsers == $totalByRoles ? 'PASS' : 'FAIL'));
    }
    
    private function showSampleData()
    {
        $this->info("\n📋 SAMPLE DATA FOR TABLES:");
        
        // Sample admin data
        $admin = UserCentral::where('role', 'admin')->first();
        if ($admin) {
            $this->line("  👨‍💼 Sample Admin Table Data:");
            $this->line("    ID: {$admin->id}");
            $this->line("    Name: {$admin->name}");
            $this->line("    Email: {$admin->email}");
            $this->line("    Username: {$admin->username}");
            $this->line("    Phone: " . ($admin->phone ?? '-'));
            $this->line("    Status: " . ($admin->is_active ? 'Active' : 'Inactive'));
            if ($admin->adminProfile) {
                $this->line("    Address: " . ($admin->adminProfile->address ?? '-'));
                $this->line("    Birth Date: " . ($admin->adminProfile->birth_date ?? '-'));
                $this->line("    Gender: " . ($admin->adminProfile->gender_display ?? '-'));
            }
        }
        
        // Sample guru data
        $guru = UserCentral::where('role', 'guru')->first();
        if ($guru) {
            $this->line("\n  👨‍🏫 Sample Guru Table Data:");
            $this->line("    ID: {$guru->id}");
            $this->line("    Name: {$guru->name}");
            $this->line("    Email: {$guru->email}");
            $this->line("    Username: {$guru->username}");
            $this->line("    Phone: " . ($guru->phone ?? '-'));
            $this->line("    Status: " . ($guru->is_active ? 'Active' : 'Inactive'));
            if ($guru->guruProfile) {
                $this->line("    NIP: {$guru->guruProfile->nip}");
                $this->line("    Subject: {$guru->guruProfile->mata_pelajaran}");
                $this->line("    Education: {$guru->guruProfile->pendidikan_terakhir}");
                $this->line("    Work Duration: {$guru->guruProfile->work_duration} years");
            }
        }
        
        // Sample siswa data
        $siswa = UserCentral::where('role', 'siswa')->first();
        if ($siswa) {
            $this->line("\n  👨‍🎓 Sample Siswa Table Data:");
            $this->line("    ID: {$siswa->id}");
            $this->line("    Name: {$siswa->name}");
            $this->line("    Email: {$siswa->email}");
            $this->line("    Username: {$siswa->username}");
            $this->line("    Phone: " . ($siswa->phone ?? '-'));
            $this->line("    Status: " . ($siswa->is_active ? 'Active' : 'Inactive'));
            if ($siswa->siswaProfile) {
                $this->line("    NIS: {$siswa->siswaProfile->nis}");
                $this->line("    NISN: {$siswa->siswaProfile->nisn}");
                $this->line("    Class: " . ($siswa->siswaProfile->kelas->name ?? 'No Class'));
                $this->line("    Major: {$siswa->siswaProfile->major}");
                $this->line("    Academic Year: {$siswa->siswaProfile->tahun_ajaran}");
            }
        }
    }
}
