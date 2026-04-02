<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanupExamSchedulesCommand extends Command
{
    protected $signature = 'cleanup:exam-schedules';
    protected $description = 'Cleanup corrupted exam_schedules tablespace';

    public function handle()
    {
        $this->info('=== CLEANING EXAM_SCHEDULES TABLESPACE ===');
        
        try {
            // Drop any existing exam_schedules table
            DB::statement("DROP TABLE IF EXISTS exam_schedules");
            $this->info('✅ Dropped any existing exam_schedules table');
            
            $this->info('✅ Cleanup completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
