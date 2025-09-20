<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:password {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset user password by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if (!$email) {
            // Show available users
            $this->info('🔑 Reset Password User');
            $this->newLine();
            
            $users = User::where('email', 'not like', '%@test.com')
                        ->orderBy('role')
                        ->get(['id', 'name', 'email', 'role']);
            
            if ($users->isEmpty()) {
                $this->warn('Tidak ada user non-test ditemukan');
                return;
            }
            
            $this->line('📊 User yang tersedia (non-test):');
            $headers = ['ID', 'Nama', 'Email', 'Role'];
            $data = [];
            
            foreach ($users as $user) {
                $roleIcon = match($user->role) {
                    'admin' => '👨‍💼',
                    'guru' => '👨‍🏫',
                    'siswa' => '👨‍🎓',
                    default => '👤'
                };
                
                $data[] = [
                    $user->id,
                    $user->name,
                    $user->email,
                    $roleIcon . ' ' . $user->role
                ];
            }
            
            $this->table($headers, $data);
            
            $email = $this->ask('📧 Masukkan email user yang ingin direset passwordnya');
        }
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error('❌ User dengan email tersebut tidak ditemukan!');
            return;
        }
        
        $this->line("👤 User ditemukan: {$user->name} ({$user->role})");
        
        $newPassword = $this->secret('🔒 Masukkan password baru (minimal 8 karakter)');
        
        while (strlen($newPassword) < 8) {
            $this->error('❌ Password minimal 8 karakter!');
            $newPassword = $this->secret('🔒 Masukkan password baru (minimal 8 karakter)');
        }
        
        if ($this->confirm("🚀 Reset password untuk {$user->name}?")) {
            $user->update([
                'password' => Hash::make($newPassword)
            ]);
            
            $this->newLine();
            $this->info('✅ Password berhasil direset!');
            $this->newLine();
            
            $this->line('🎉 Detail Login:');
            $this->line("Nama: {$user->name}");
            $this->line("Email: {$user->email}");
            $this->line("Role: {$user->role}");
            $this->line("Password: [hidden]");
            
            $this->newLine();
            $this->line('🌐 URL Login: http://127.0.0.1:8000/login');
            
            // Show role-specific dashboard
            switch ($user->role) {
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
            
        } else {
            $this->info('❌ Reset password dibatalkan');
        }
    }
}
