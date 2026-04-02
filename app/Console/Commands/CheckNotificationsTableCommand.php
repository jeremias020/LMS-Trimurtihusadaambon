<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckNotificationsTableCommand extends Command
{
    protected $signature = 'check:notifications-table';
    protected $description = 'Check notifications table structure';

    public function handle()
    {
        $this->info('=== NOTIFICATIONS TABLE STRUCTURE ===');
        
        try {
            if (!Schema::hasTable('notifications')) {
                $this->error('❌ Table notifications does not exist');
                return Command::FAILURE;
            }
            
            $columns = DB::select("SHOW COLUMNS FROM notifications");
            
            $this->info('Current columns:');
            foreach ($columns as $column) {
                $this->line("  - {$column->Field} ({$column->Type})");
            }
            
            $this->info('✅ Table structure check completed!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
