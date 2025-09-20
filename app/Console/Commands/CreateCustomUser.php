<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateCustomUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user interactively';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('👥 Membuat User Baru untuk LMS Trimurti Husada');
        $this->newLine();
        
        // Collect user information
        $name = $this->ask('📝 Nama lengkap');
        $email = $this->ask('📧 Email');
        
        // Validate email
        while (User::where('email', $email)->exists()) {
            $this->error('❌ Email sudah digunakan!');
            $email = $this->ask('📧 Email (masukkan email lain)');
        }
        
        $password = $this->secret('🔒 Password (minimal 8 karakter)');
        
        while (strlen($password) < 8) {
            $this->error('❌ Password minimal 8 karakter!');
            $password = $this->secret('🔒 Password (minimal 8 karakter)');
        }
        
        $role = $this->choice(
            '📄 Pilih Role',
            ['admin', 'guru', 'siswa'],
            0
        );
        
        $phone = $this->ask('📱 No. Telepon (opsional)', null);
        $address = $this->ask('🏠 Alamat (opsional)', null);
        
        $gender = $this->choice(
            '🚺Jenis Kelamin',
            ['L' => 'Laki-laki', 'P' => 'Perempuan'],
            'L'
        );
        
        // Confirm data
        $this->newLine();
        $this->line('📋 Data yang akan dibuat:');
        $this->line("Nama: {$name}");
        $this->line("Email: {$email}");
        $this->line("Role: {$role}");
        $this->line("Gender: " . ($gender === 'L' ? 'Laki-laki' : 'Perempuan'));
        if ($phone) $this->line("Telepon: {$phone}");
        if ($address) $this->line("Alamat: {$address}");
        
        if (!$this->confirm('🚀 Lanjutkan membuat user?')) {
            $this->info('❌ Dibatalkan');
            return;
        }
        
        try {
            // Create user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
                'phone' => $phone,
                'address' => $address,
                'gender' => $gender,
                'status' => 'active',
                'email_verified_at' => now()
            ]);
            
            $this->newLine();
            $this->info('✅ User berhasil dibuat!');
            $this->newLine();
            
            $this->line('🎉 Detail Login:');
            $this->line("Email: {$email}");
            $this->line("Password: [hidden]");
            $this->line("Role: {$role}");
            $this->line("ID: {$user->id}");
            
            $this->newLine();
            $this->line('🌐 URL Login: http://127.0.0.1:8000/login');
            
            // Show role-specific information
            switch ($role) {
                case 'admin':
                    $this->line('👨‍💼 Admin Dashboard: http://127.0.0.1:8000/admin/dashboard');
                    break;
                case 'guru':
                    $this->line('👨‍🏫 Guru Dashboard: http://127.0.0.1:8000/guru/dashboard');
                    break;
                case 'siswa':
                    $this->line('👨‍🎓 Siswa Dashboard: http://127.0.0.1:8000/siswa/dashboard');
                    break;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Gagal membuat user: ' . $e->getMessage());
        }
    }
}
