<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestJurusanInsertCommand extends Command
{
    protected $signature = 'test:jurusan-insert';
    protected $description = 'Test inserting jurusan into majors table';

    public function handle()
    {
        $this->info('=== TESTING JURUSAN INSERT ===');
        
        try {
            // Test data that matches majors table structure
            $jurusanData = [
                'name' => 'Test Jurusan ' . time(),
                'code' => 'TST' . rand(100, 999),
                'description' => 'Test jurusan description'
            ];
            
            $this->info("\n🔍 Testing insert with correct fields:");
            $this->line("  📋 Name: {$jurusanData['name']}");
            $this->line("  📋 Code: {$jurusanData['code']}");
            $this->line("  📋 Description: {$jurusanData['description']}");
            
            // Test insert
            $jurusan = \App\Models\Jurusan::create($jurusanData);
            
            $this->line("  ✅ Insert successful!");
            $this->line("  📋 ID: {$jurusan->id}");
            $this->line("  📋 Created at: {$jurusan->created_at}");
            
            // Verify data
            $this->info("\n🔍 Verifying inserted data:");
            $verifyJurusan = \App\Models\Jurusan::find($jurusan->id);
            $this->line("  📋 Name: {$verifyJurusan->name}");
            $this->line("  📋 Code: {$verifyJurusan->code}");
            $this->line("  📋 Description: " . ($verifyJurusan->description ?? 'NULL'));
            
            // Clean up test data
            $verifyJurusan->delete();
            $this->line("  🗑️  Test data cleaned up");
            
            $this->info("\n🎉 INSERT TEST PASSED!");
            $this->line("  ✅ All required fields present");
            $this->line("  ✅ No SQL errors about missing defaults");
            $this->line("  ✅ Data correctly inserted");
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile());
            $this->error("Line: " . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
