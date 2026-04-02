<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class TestNotificationQueryCommand extends Command
{
    protected $signature = 'test:notification-query';
    protected $description = 'Test the specific notification query that was failing';

    public function handle()
    {
        $this->info('=== TESTING SPECIFIC NOTIFICATION QUERY ===');
        
        try {
            // This is the exact query that was failing
            $notifications = Notification::where(function($query) {
                $query->where('penerima_id', 1)
                      ->orWhere('tipe_penerima', 'semua');
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
            $this->info("✅ Query executed successfully!");
            $this->info("✅ Found {$notifications->count()} notifications");
            
            foreach ($notifications as $notif) {
                $this->line("  - {$notif->judul}");
                $this->line("    Message: " . substr($notif->pesan, 0, 50) . "...");
                $this->line("    Type: {$notif->tipe_penerima}");
                $this->line("    Created: {$notif->created_at}");
                $this->line("");
            }
            
            $this->info('✅ Notification query working correctly!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
