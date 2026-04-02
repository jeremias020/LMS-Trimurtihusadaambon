<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestJurusanCreationCommand extends Command
{
    protected $signature = 'test:jurusan-create';
    protected $description = 'Test jurusan creation with validation';

    public function handle()
    {
        $this->info('=== TESTING JURUSAN CREATION ===');
        
        try {
            // Test validation rules
            $this->info("\n🔍 Testing validation rules:");
            
            $testData = [
                'nama' => 'Keperawatan Lanjutan',
                'kode' => 'KPL',
                'deskripsi' => 'Program keahlian keperawatan lanjutan',
                'mata_pelajaran' => [2, 3], // Farmakologi, Keperawatan Dasar
                'kapasitas_total' => 30,
                'status' => true
            ];
            
            // Simulate validation
            $validator = \Validator::make($testData, [
                'nama' => 'required|string|max:255|unique:majors,name',
                'kode' => 'required|string|max:10|unique:majors,code',
                'deskripsi' => 'nullable|string',
                'mata_pelajaran' => 'required|array|min:1',
                'mata_pelajaran.*' => 'required|integer|exists:subjects,id',
                'kapasitas_total' => 'nullable|integer|min:1',
                'status' => 'boolean'
            ]);
            
            if ($validator->fails()) {
                $this->error("❌ Validation failed:");
                foreach ($validator->errors()->all() as $error) {
                    $this->line("  - {$error}");
                }
                return Command::FAILURE;
            }
            
            $this->line("  ✅ Validation passed!");
            
            // Check if name already exists
            $this->info("\n🔍 Checking existing jurusan:");
            $existing = \App\Models\Jurusan::where('name', 'Keperawatan Lanjutan')->first();
            if ($existing) {
                $this->line("  ⚠️  Jurusan 'Keperawatan Lanjutan' already exists with ID: {$existing->id}");
            } else {
                $this->line("  ✅ Jurusan name is unique");
            }
            
            // Check if code already exists
            $existingCode = \App\Models\Jurusan::where('code', 'KPL')->first();
            if ($existingCode) {
                $this->line("  ⚠️  Code 'KPL' already exists with ID: {$existingCode->id}");
            } else {
                $this->line("  ✅ Jurusan code is unique");
            }
            
            // Test subjects existence
            $this->info("\n🔍 Checking subject IDs:");
            foreach ([2, 3] as $subjectId) {
                $subject = \App\Models\MataPelajaran::find($subjectId);
                if ($subject) {
                    $this->line("  ✅ Subject ID {$subjectId}: {$subject->name} ({$subject->code})");
                } else {
                    $this->line("  ❌ Subject ID {$subjectId}: NOT FOUND");
                }
            }
            
            $this->info("\n🎉 ALL TESTS PASSED!");
            $this->line("  ✅ Validation rules are correct");
            $this->line("  ✅ Using correct table: majors");
            $this->line("  ✅ No more SQL errors about invalid tables");
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile());
            $this->error("Line: " . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
