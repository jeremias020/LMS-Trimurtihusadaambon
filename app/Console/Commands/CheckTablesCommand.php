<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckTablesCommand extends Command
{
    protected $signature = 'check:tables';
    protected $description = 'Check database tables structure';

    public function handle()
    {
        $this->info('=== CHECKING DATABASE TABLES ===');
        
        try {
            $tables = \DB::select('SHOW TABLES');
            
            $this->info("\n📋 Available Tables:");
            foreach ($tables as $table) {
                $tableName = $table->{'Tables_in_lms_trimurti'};
                $this->line("  - {$tableName}");
            }
            
            // Check specifically for jurusan/majors
            $this->info("\n🔍 Checking jurusan/majors tables:");
            
            $hasJurusan = in_array('jurusan', array_column($tables, 'Tables_in_lms_trimurti'));
            $hasMajors = in_array('majors', array_column($tables, 'Tables_in_lms_trimurti'));
            
            $this->line("  - jurusan: " . ($hasJurusan ? '✅ EXISTS' : '❌ NOT FOUND'));
            $this->line("  - majors: " . ($hasMajors ? '✅ EXISTS' : '❌ NOT FOUND'));
            
            if ($hasMajors) {
                $this->info("\n📊 Majors table structure:");
                $columns = \Schema::getColumnListing('majors');
                foreach ($columns as $column) {
                    $this->line("  - {$column}");
                }
            }
            
            if ($hasJurusan) {
                $this->info("\n📊 Jurusan table structure:");
                $columns = \Schema::getColumnListing('jurusan');
                foreach ($columns as $column) {
                    $this->line("  - {$column}");
                }
            }
            
            // Check model configuration
            $this->info("\n🔍 Checking Jurusan model:");
            $jurusanModel = new \App\Models\Jurusan();
            $this->line("  Table: {$jurusanModel->getTable()}");
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
