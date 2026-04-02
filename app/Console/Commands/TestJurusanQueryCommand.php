<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestJurusanQueryCommand extends Command
{
    protected $signature = 'test:jurusan-query';
    protected $description = 'Test jurusan query with name column';

    public function handle()
    {
        $this->info('=== TESTING JURUSAN QUERY ===');
        
        try {
            // Test basic query
            $this->info("\n🔍 Testing basic query:");
            $allJurusan = \App\Models\Jurusan::count();
            $this->line("  ✅ All jurusan: {$allJurusan}");
            
            // Test orderBy name
            $this->info("\n🔍 Testing orderBy('name'):");
            $sortedJurusan = \App\Models\Jurusan::orderBy('name')->get();
            $this->line("  ✅ Sorted jurusan: {$sortedJurusan->count()}");
            
            // Show sample data
            $this->info("\n📊 Sample data (sorted by name):");
            foreach ($sortedJurusan->take(5) as $jurusan) {
                $this->line("  📋 {$jurusan->name} ({$jurusan->code})");
            }
            
            // Test with count relations
            $this->info("\n🔍 Testing withCount relations:");
            $jurusanWithCount = \App\Models\Jurusan::withCount(['kelas', 'siswa'])
                                                 ->orderBy('name')
                                                 ->get();
            $this->line("  ✅ Jurusan with counts: {$jurusanWithCount->count()}");
            
            foreach ($jurusanWithCount->take(3) as $jurusan) {
                $this->line("  📋 {$jurusan->name}: {$jurusan->kelas_count} kelas, {$jurusan->siswa_count} siswa");
            }
            
            $this->info("\n🎉 ALL TESTS PASSED!");
            $this->line("  ✅ Column 'name' exists and working");
            $this->line("  ✅ orderBy('name') queries are functional");
            $this->line("  ✅ No more SQL errors about nama column");
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile());
            $this->error("Line: " . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
