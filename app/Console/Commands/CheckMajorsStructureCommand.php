<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMajorsStructureCommand extends Command
{
    protected $signature = 'check:majors';
    protected $description = 'Check majors table structure';

    public function handle()
    {
        $this->info('=== MAJORS TABLE STRUCTURE ===');
        
        $columns = \Schema::getColumnListing('majors');
        
        $this->info("\n📊 Majors table columns:");
        foreach ($columns as $column) {
            $this->line("  - {$column}");
        }
        
        $this->info("\n🔍 Checking fillable fields in Jurusan model:");
        $jurusan = new \App\Models\Jurusan();
        $fillable = $jurusan->getFillable();
        foreach ($fillable as $field) {
            $exists = in_array($field, $columns);
            $status = $exists ? '✅' : '❌';
            $this->line("  {$status} {$field}" . ($exists ? '' : ' - NOT IN TABLE'));
        }
        
        return Command::SUCCESS;
    }
}
