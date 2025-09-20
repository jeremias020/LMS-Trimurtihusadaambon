<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CleanTestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:test-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove test users from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧽 Menghapus Akun Test');
        $this->newLine();
        
        $testUsers = User::where('email', 'like', '%@test.com')->get();
        
        if ($testUsers->isEmpty()) {
            $this->info('✅ Tidak ada akun test ditemukan');
            return;
        }
        
        $this->line('📋 Akun test yang akan dihapus:');
        foreach ($testUsers as $user) {
            $this->line("- {$user->name} ({$user->email}) - {$user->role}");
        }
        
        $this->newLine();
        if (!$this->confirm('⚠️  Hapus ' . $testUsers->count() . ' akun test?')) {
            $this->info('❌ Dibatalkan');
            return;
        }
        
        $deleted = 0;
        foreach ($testUsers as $user) {
            $user->delete();
            $deleted++;
            $this->line("✅ Deleted: {$user->name}");
        }
        
        $this->newLine();
        $this->info("✅ {$deleted} akun test berhasil dihapus!");
        
        // Show remaining users
        $remainingUsers = User::count();
        $this->line("📋 Sisa user: {$remainingUsers}");
    }
}
