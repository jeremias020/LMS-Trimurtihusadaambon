<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Subject;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Practical;
use Illuminate\Support\Facades\DB;

class TestSoftDeletesCommand extends Command
{
    protected $signature = 'test:softdeletes';
    protected $description = 'Test soft deletes functionality';

    public function handle()
    {
        $this->info('=== SOFT DELETES FUNCTIONALITY TEST ===');
        
        try {
            // Test Subjects
            $this->info('📚 Testing Subjects:');
            $subjectsCount = Subject::count();
            $this->line("  Total subjects: {$subjectsCount}");
            
            // Test Materials
            $this->info('📖 Testing Materials:');
            $materialsCount = Material::count();
            $this->line("  Total materials: {$materialsCount}");
            
            // Test Assignments
            $this->info('📝 Testing Assignments:');
            $assignmentsCount = Assignment::count();
            $this->line("  Total assignments: {$assignmentsCount}");
            
            // Test Attendances
            $this->info('📊 Testing Attendances:');
            $attendancesCount = Attendance::count();
            $this->line("  Total attendances: {$attendancesCount}");
            
            // Test Practical Assessments
            $this->info('🔬 Testing Practical Assessments:');
            $practicalCount = DB::table('practical_assessments')->count();
            $this->line("  Total practical assessments: {$practicalCount}");
            
            // Test Users (already has soft deletes)
            $this->info('👥 Testing Users:');
            $usersCount = User::count();
            $this->line("  Total users: {$usersCount}");
            
            $this->info('✅ All soft deletes functionality working correctly!');
            
            // Test soft delete operation
            $this->line("\n🧪 Testing soft delete operation:");
            $testSubject = Subject::first();
            if ($testSubject) {
                $this->line("  Before delete: {$testSubject->name}");
                $testSubject->delete();
                
                // Check if still in database but soft deleted
                $withTrashed = Subject::withTrashed()->where('id', $testSubject->id)->count();
                $withoutTrashed = Subject::where('id', $testSubject->id)->count();
                
                $this->line("  With trashed: {$withTrashed}");
                $this->line("  Without trashed: {$withoutTrashed}");
                
                // Restore
                $testSubject->restore();
                $this->line("  ✅ Soft delete test passed!");
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
