<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSubjectsInsertCommand extends Command
{
    protected $signature = 'test:subjects-insert';
    protected $description = 'Test inserting subjects without major_id';

    public function handle()
    {
        $this->info('=== TESTING SUBJECTS INSERT ===');
        
        try {
            // Test inserting subject without major_id
            $this->info("\n🔍 Testing insert without major_id:");
            
            $subjectData = [
                'name' => 'KEPERAWATAN 2',
                'code' => 'KEP2',
                'description' => 'Mata pelajaran keperawatan tingkat 2',
                'type' => 'praktikum',
                'sks' => 2,
                'is_active' => 1
            ];
            
            $subject = \App\Models\MataPelajaran::create($subjectData);
            
            $this->line("  ✅ Subject created successfully!");
            $this->line("  📋 ID: {$subject->id}");
            $this->line("  📋 Name: {$subject->name}");
            $this->line("  📋 Code: {$subject->code}");
            $this->line("  📋 Type: {$subject->type}");
            $this->line("  📋 Major ID: " . ($subject->major_id ?? 'NULL'));
            
            // Test with major_id
            $this->info("\n🔍 Testing insert with major_id:");
            
            $subjectWithMajor = \App\Models\MataPelajaran::create([
                'name' => 'FARMASI 1',
                'code' => 'FAR1',
                'description' => 'Mata pelajaran farmasi tingkat 1',
                'type' => 'teori',
                'sks' => 3,
                'is_active' => 1,
                'major_id' => 1 // Assuming major with ID 1 exists
            ]);
            
            $this->line("  ✅ Subject with major created successfully!");
            $this->line("  📋 ID: {$subjectWithMajor->id}");
            $this->line("  📋 Major ID: {$subjectWithMajor->major_id}");
            
            $this->info("\n🎉 ALL TESTS PASSED!");
            $this->line("  ✅ major_id is now nullable");
            $this->line("  ✅ Can insert subjects without major");
            $this->line("  ✅ Can insert subjects with major");
            $this->line("  ✅ No more SQL errors about major_id default value");
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile());
            $this->error("Line: " . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
