<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\User;

class TestNotificationsCommand extends Command
{
    protected $signature = 'test:notifications';
    protected $description = 'Test Notification model functionality';

    public function handle()
    {
        $this->info('=== NOTIFICATION MODEL TEST ===');
        
        try {
            // Test basic query
            $count = Notification::count();
            $this->info("✅ Total Notifications: {$count}");
            
            // Test for user query (the one that was failing)
            $userNotifications = Notification::where(function($query) {
                $query->where('penerima_id', 1)
                      ->orWhere('tipe_penerima', 'semua');
            })->orderBy('created_at', 'desc')->limit(10)->get();
            
            $this->info("✅ User notifications query: {$userNotifications->count()} records");
            
            // Test unread notifications
            $unread = Notification::unread()->count();
            $this->info("✅ Unread notifications: {$unread}");
            
            // Test with sample data creation
            $admin = User::where('role', 'admin')->first();
            if ($admin && $count === 0) {
                // Create sample notification
                $notification = Notification::create([
                    'pengirim_id' => $admin->id,
                    'penerima_id' => null,
                    'tipe_penerima' => 'semua',
                    'tipe' => 'info',
                    'judul' => 'Welcome to LMS Trimurti',
                    'pesan' => 'Sistem LMS telah berhasil di-setup dan siap digunakan.',
                    'url_aksi' => '/dashboard',
                    'prioritas' => 'sedang',
                    'status' => 'belum_dibaca'
                ]);
                
                $this->line("✅ Created sample notification: {$notification->judul}");
            }
            
            // Test all notifications
            $allNotifications = Notification::with(['sender', 'receiver'])->get();
            $this->info("✅ All notifications with relations: {$allNotifications->count()}");
            
            foreach ($allNotifications as $notif) {
                $sender = $notif->sender ? $notif->sender->name : 'System';
                $this->line("  - {$notif->judul}");
                $this->line("    From: {$sender}");
                $this->line("    Type: {$notif->tipe}");
                $this->line("    Priority: {$notif->prioritas}");
                $this->line("    Status: " . ($notif->isUnread ? 'Unread' : 'Read'));
                $this->line("");
            }
            
            $this->info('✅ Notification model working correctly!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
