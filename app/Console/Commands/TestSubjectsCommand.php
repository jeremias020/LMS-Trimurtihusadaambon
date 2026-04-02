<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subject;

class TestSubjectsCommand extends Command
{
    protected $signature = 'test:subjects';
    protected $description = 'Test Subject model functionality';

    public function handle()
    {
        $this->info('=== SUBJECT MODEL TEST ===');
        
        try {
            // Test basic query
            $count = Subject::count();
            $this->info("✅ Total Subjects: {$count}");
            
            // Test with relations
            $subjects = Subject::with('jurusan')->get();
            $this->info("✅ Subjects with relations loaded: {$subjects->count()}");
            
            foreach ($subjects as $subject) {
                $jurusan = $subject->jurusan ? $subject->jurusan->name : 'No Major';
                $this->line("  - {$subject->name} ({$subject->code}) - {$jurusan}");
            }
            
            $this->info('✅ Subject model working correctly!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
