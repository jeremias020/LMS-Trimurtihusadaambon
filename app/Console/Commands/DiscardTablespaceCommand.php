<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DiscardTablespaceCommand extends Command
{
    protected $signature = 'discard:tablespace {table}';
    protected $description = 'Discard tablespace for corrupted table';

    public function handle()
    {
        $tableName = $this->argument('table');
        $this->info("=== DISCARDING TABLESPACE FOR: {$tableName} ===");
        
        try {
            // Discard tablespace
            DB::statement("ALTER TABLE {$tableName} DISCARD TABLESPACE");
            $this->info("✅ Discarded tablespace for {$tableName}");
            
            // Drop the table
            DB::statement("DROP TABLE IF EXISTS {$tableName}");
            $this->info("✅ Dropped table {$tableName}");
            
            $this->info('✅ Tablespace cleanup completed!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
