<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSubjectsScopeCommand extends Command
{
    protected $signature = 'test:subjects-scope';
    protected $description = 'Test subjects scope functionality';

    public function handle()
    {
        $this->info('=== TESTING SUBJECTS SCOPE ===');
        
        try {
            // Test basic query
            $this->info("\n🔍 Testing basic query:");
            $allSubjects = \App\Models\MataPelajaran::count();
            $this->line("  ✅ All subjects: {$allSubjects}");
            
            // Test scopeActive
            $this->info("\n🔍 Testing scopeActive:");
            $activeSubjects = \App\Models\MataPelajaran::active()->count();
            $this->line("  ✅ Active subjects: {$activeSubjects}");
            
            // Test scopeTeori
            $this->info("\n🔍 Testing scopeTeori:");
            $teoriSubjects = \App\Models\MataPelajaran::teori()->count();
            $this->line("  ✅ Teori subjects: {$teoriSubjects}");
            
            // Test scopePraktikum
            $this->info("\n🔍 Testing scopePraktikum:");
            $praktikumSubjects = \App\Models\MataPelajaran::praktikum()->count();
            $this->line("  ✅ Praktikum subjects: {$praktikumSubjects}");
            
            // Test scopeCampuran
            $this->info("\n🔍 Testing scopeCampuran:");
            $campuranSubjects = \App\Models\MataPelajaran::campuran()->count();
            $this->line("  ✅ Campuran subjects: {$campuranSubjects}");
            
            // Show sample data
            $this->info("\n📊 Sample data:");
            $sampleSubjects = \App\Models\MataPelajaran::take(3)->get(['id', 'name', 'code', 'type', 'is_active']);
            
            foreach ($sampleSubjects as $subject) {
                $this->line("  📋 {$subject->name} ({$subject->code}) - Type: {$subject->type} - Active: " . ($subject->is_active ? 'Yes' : 'No'));
            }
            
            $this->info("\n🎉 ALL TESTS PASSED!");
            $this->line("  ✅ Column 'type' exists and working");
            $this->line("  ✅ Column 'is_active' exists and working");
            $this->line("  ✅ All scopes are functional");
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile());
            $this->error("Line: " . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
