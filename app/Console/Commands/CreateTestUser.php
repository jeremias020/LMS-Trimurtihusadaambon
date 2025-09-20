<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test users for different roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating test users...');
        
        // Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin Test',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'phone' => '081234567890',
                'gender' => 'L',
                'email_verified_at' => now()
            ]
        );
        
        // Guru user
        $guru = User::updateOrCreate(
            ['email' => 'guru@test.com'],
            [
                'name' => 'Guru Test',
                'username' => 'guru',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'status' => 'active',
                'phone' => '081234567891',
                'gender' => 'P',
                'email_verified_at' => now()
            ]
        );
        
        // Siswa user
        $siswa = User::updateOrCreate(
            ['email' => 'siswa@test.com'],
            [
                'name' => 'Siswa Test',
                'username' => 'siswa',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'status' => 'active',
                'phone' => '081234567892',
                'gender' => 'L',
                'email_verified_at' => now()
            ]
        );
        
        $this->info('✅ Test users created successfully!');
        $this->line('');
        $this->line('Login credentials:');
        $this->line('👨‍💼 Admin: admin@test.com / password');
        $this->line('👨‍🏫 Guru: guru@test.com / password');
        $this->line('👨‍🎓 Siswa: siswa@test.com / password');
        $this->line('');
        $this->line('🌐 Login URL: http://127.0.0.1:8000/login');
    }
}
