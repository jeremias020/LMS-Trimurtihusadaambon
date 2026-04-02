<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckSubjectsTableCommand extends Command
{
    protected $signature = 'check:subjects';
    protected $description = 'Check subjects table structure';

    public function handle()
    {
        $this->info('=== SUBJECTS TABLE CHECK ===');
        
        $columns = \Schema::getColumnListing('subjects');
        $this->info("\n📋 Current columns:");
        foreach ($columns as $column) {
            $this->line("  - {$column}");
        }
        
        $this->info("\n🔍 Checking specific columns:");
        
        $checkColumns = [
            'type' => 'Missing - this causes the error!',
            'is_active' => 'Required for scopeActive()',
            'sks' => 'Required for validation',
            'color' => 'Optional',
            'order' => 'Optional'
        ];
        
        foreach ($checkColumns as $column => $description) {
            $exists = \Schema::hasColumn('subjects', $column);
            $status = $exists ? '✅ EXISTS' : '❌ MISSING';
            $this->line("  {$column}: {$status} - {$description}");
        }
        
        $this->info("\n🔧 Solution:");
        if (!\Schema::hasColumn('subjects', 'type')) {
            $this->line("  ❌ Column 'type' is missing - this is the root cause of the error");
            $this->line("  🛠️  Need to add: type ENUM('teori', 'praktikum', 'campuran') DEFAULT 'teori'");
        }
        
        if (!\Schema::hasColumn('subjects', 'is_active')) {
            $this->line("  ❌ Column 'is_active' is missing");
            $this->line("  🛠️  Need to add: is_active BOOLEAN DEFAULT TRUE");
        }
        
        return Command::SUCCESS;
    }
}
