<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestUsersCommand extends Command
{
    protected $signature = 'test:users';
    protected $description = 'Test user data and login functionality';

    public function handle()
    {
        $this->info('=== USER LOGIN TEST ===');
        
        try {
            // Test admin user
            $admin = User::where('email', 'admin@lms-trimurti.sch.id')->first();
            
            if ($admin) {
                $this->info('✅ Admin User Found:');
                $this->line("  Name: {$admin->name}");
                $this->line("  Email: {$admin->email}");
                $this->line("  Role: {$admin->role}");
                $this->line("  Active: " . ($admin->is_active ? 'Yes' : 'No'));
                $this->line("  Deleted At: " . ($admin->deleted_at ? $admin->deleted_at : 'NULL'));
            } else {
                $this->error('❌ Admin user not found!');
            }
            
            $this->line(str_repeat("-", 40));
            
            // Test guru user
            $guru = User::where('email', 'siti@lms-trimurti.sch.id')->first();
            
            if ($guru) {
                $this->info('✅ Guru User Found:');
                $this->line("  Name: {$guru->name}");
                $this->line("  Email: {$guru->email}");
                $this->line("  Role: {$guru->role}");
                $this->line("  NIP: {$guru->nis_nip}");
            } else {
                $this->error('❌ Guru user not found!');
            }
            
            $this->line(str_repeat("-", 40));
            
            // Test siswa user
            $siswa = User::where('email', 'agus.setiawan@lms-trimurti.sch.id')->first();
            
            if ($siswa) {
                $this->info('✅ Siswa User Found:');
                $this->line("  Name: {$siswa->name}");
                $this->line("  Email: {$siswa->email}");
                $this->line("  Role: {$siswa->role}");
                $this->line("  NIS: {$siswa->nis_nip}");
            } else {
                $this->error('❌ Siswa user not found!');
            }
            
            $this->info('=== TEST COMPLETED ===');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
        }
        
        return Command::SUCCESS;
    }
}
