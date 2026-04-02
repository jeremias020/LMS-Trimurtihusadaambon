<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckUserTablesCommand extends Command
{
    protected $signature = 'check:user-tables';
    protected $description = 'Check user tables structure';

    public function handle()
    {
        $this->info('=== CHECKING USER TABLES ===');
        
        $tables = ['users', 'admins', 'gurus', 'siswa'];
        
        foreach ($tables as $table) {
            $this->info("\n--- Table: {$table} ---");
            
            if (Schema::hasTable($table)) {
                $columns = DB::select("SHOW COLUMNS FROM {$table}");
                foreach ($columns as $column) {
                    $this->line("  - {$column->Field} ({$column->Type})");
                }
            } else {
                $this->line("  ❌ Table does not exist");
            }
        }
        
        return Command::SUCCESS;
    }
}
