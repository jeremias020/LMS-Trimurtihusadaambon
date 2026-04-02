<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListSubjectsCommand extends Command
{
    protected $signature = 'list:subjects';
    protected $description = 'List all subjects with ID';

    public function handle()
    {
        $this->info('=== SUBJECTS LIST ===');
        
        $subjects = \App\Models\MataPelajaran::orderBy('name')->get();
        
        foreach ($subjects as $subject) {
            $this->line("ID: {$subject->id} - {$subject->name} ({$subject->code})");
        }
        
        $this->info("Total: {$subjects->count()} subjects");
        
        return Command::SUCCESS;
    }
}
