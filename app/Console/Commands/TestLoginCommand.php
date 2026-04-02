<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TestLoginCommand extends Command
{
    protected $signature = 'test:login {email=admin@lms-trimurti.sch.id}';
    protected $description = 'Test user login functionality';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("=== LOGIN TEST FOR: {$email} ===");
        
        try {
            // Find user
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error('❌ User not found!');
                return Command::FAILURE;
            }
            
            $this->info('✅ User found:');
            $this->line("  Name: {$user->name}");
            $this->line("  Email: {$user->email}");
            $this->line("  Role: {$user->role}");
            $this->line("  Active: " . ($user->is_active ? 'Yes' : 'No'));
            $this->line("  Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No'));
            
            // Test password verification
            $password = 'password';
            if (\Hash::check($password, $user->password)) {
                $this->info('✅ Password verification: SUCCESS');
            } else {
                $this->error('❌ Password verification: FAILED');
                return Command::FAILURE;
            }
            
            // Test authentication
            Auth::login($user);
            
            if (Auth::check()) {
                $this->info('✅ Authentication: SUCCESS');
                $this->line("  Logged in user: " . Auth::user()->name);
                $this->line("  User ID: " . Auth::id());
                
                // Test role-based access
                if (Auth::user()->isAdmin()) {
                    $this->info('✅ Admin access: GRANTED');
                } elseif (Auth::user()->isGuru()) {
                    $this->info('✅ Guru access: GRANTED');
                } elseif (Auth::user()->isSiswa()) {
                    $this->info('✅ Siswa access: GRANTED');
                }
                
            } else {
                $this->error('❌ Authentication: FAILED');
                return Command::FAILURE;
            }
            
            // Logout
            Auth::logout();
            $this->info('✅ Logout: SUCCESS');
            
            $this->info('=== LOGIN TEST COMPLETED ===');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
