<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckSubjectsCommand extends Command
{
    protected $signature = 'check:subjects';
    protected $description = 'Check available subjects';

    public function handle()
    {
        $this->info('=== AVAILABLE SUBJECTS ===');
        
        try {
            $subjects = \App\Models\MataPelajaran::orderBy('name')->get(['id', 'name', 'code']);
            
            $this->info("\n📋 Available Subjects:");
            foreach ($subjects as $subject) {
                $this->line("  ID: {$subject->id} - {$subject->name} ({$subject->code})");
            }
            
            $this->info("\n📊 Total: {$subjects->count()} subjects");
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
