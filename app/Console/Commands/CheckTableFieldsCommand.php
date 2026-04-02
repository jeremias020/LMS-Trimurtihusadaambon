<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckTableFieldsCommand extends Command
{
    protected $signature = 'check:table-fields';
    protected $description = 'Check actual fields in profile tables';

    public function handle()
    {
        $this->info('=== CHECKING TABLE FIELDS ===');
        
        $tables = ['admins', 'gurus', 'siswa'];
        
        foreach ($tables as $table) {
            $this->info("\n--- Table: {$table} ---");
            
            $columns = DB::select("SHOW COLUMNS FROM {$table}");
            foreach ($columns as $column) {
                $this->line("  - {$column->Field} ({$column->Type})");
            }
        }
        
        return Command::SUCCESS;
    }
}
