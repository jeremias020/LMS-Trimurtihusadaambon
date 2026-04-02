<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestClassesQueryCommand extends Command
{
    protected $signature = 'test:classes-query';
    protected $description = 'Test classes query with grade column';

    public function handle()
    {
        $this->info('=== TESTING CLASSES QUERY ===');
        
        try {
            // Test basic query
            $this->info("\n🔍 Testing basic query:");
            $allClasses = \App\Models\Kelas::count();
            $this->line("  ✅ All classes: {$allClasses}");
            
            // Test orderBy grade
            $this->info("\n🔍 Testing orderBy('grade')->orderBy('name'):");
            $sortedClasses = \App\Models\Kelas::orderBy('grade')->orderBy('name')->get();
            $this->line("  ✅ Sorted classes: {$sortedClasses->count()}");
            
            // Show sample data
            $this->info("\n📊 Sample data (sorted by grade, then name):");
            foreach ($sortedClasses->take(5) as $kelas) {
                $grade = $kelas->grade ?? 'NULL';
                $this->line("  📋 Grade: {$grade} - {$kelas->name}");
            }
            
            // Test with where clause
            $this->info("\n🔍 Testing with where clause:");
            $filteredClasses = \App\Models\Kelas::where('grade', '10')->orderBy('name')->get();
            $this->line("  ✅ Grade 10 classes: {$filteredClasses->count()}");
            
            $this->info("\n🎉 ALL TESTS PASSED!");
            $this->line("  ✅ Column 'grade' exists and working");
            $this->line("  ✅ orderBy('grade') queries are functional");
            $this->line("  ✅ No more SQL errors about grade column");
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile());
            $this->error("Line: " . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
