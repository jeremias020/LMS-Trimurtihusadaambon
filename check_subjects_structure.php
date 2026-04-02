<?php
echo "=== CHECKING SUBJECTS TABLE STRUCTURE ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Checking subjects table structure...\n";
    
    // Check table structure
    echo "\n=== SUBJECTS TABLE STRUCTURE ===\n";
    
    if (\Illuminate\Support\Facades\Schema::hasTable('subjects')) {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('subjects');
        echo "Subjects table columns:\n";
        foreach ($columns as $column) {
            echo "- $column\n";
        }
        
        // Check sample data
        echo "\n=== SAMPLE DATA ===\n";
        $subjects = \Illuminate\Support\Facades\DB::table('subjects')->limit(3)->get();
        foreach ($subjects as $subject) {
            echo "Subject ID: {$subject->id}\n";
            foreach ($columns as $column) {
                if ($column !== 'id') {
                    echo "  $column: " . ($subject->$column ?? 'NULL') . "\n";
                }
            }
            echo "\n";
        }
        
    } else {
        echo "❌ Subjects table doesn't exist\n";
    }
    
    // Check exam schedules with correct column names
    echo "\n=== EXAM SCHEDULES WITH CORRECT SUBJECT JOIN ===\n";
    
    $schedules = \Illuminate\Support\Facades\DB::table('exam_schedules_new')
        ->leftJoin('subjects', 'exam_schedules_new.subject_id', '=', 'subjects.id')
        ->select('exam_schedules_new.*', 'subjects.name as subject_name')
        ->limit(5)
        ->get();
    
    foreach ($schedules as $schedule) {
        echo "- Schedule ID: {$schedule->id}, Title: {$schedule->title}\n";
        echo "  Subject ID: {$schedule->subject_id}, Subject Name: " . ($schedule->subject_name ?? 'NULL') . "\n";
        echo "  Exam Type: {$schedule->exam_type}\n\n";
    }
    
    // Test model relationship
    echo "\n=== TESTING MODEL RELATIONSHIP ===\n";
    
    try {
        $schedule = \App\Models\ExamSchedule::with('subject')->first();
        echo "✅ Schedule loaded with relationship\n";
        echo "Schedule title: {$schedule->title}\n";
        
        if ($schedule->subject) {
            echo "✅ Subject relationship working: " . ($schedule->subject->name ?? $schedule->subject->nama ?? 'Unknown') . "\n";
        } else {
            echo "❌ Subject relationship not working - subject is null\n";
            echo "Subject ID in schedule: {$schedule->subject_id}\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Relationship test failed: " . $e->getMessage() . "\n";
    }
    
    // Check Subject model
    echo "\n=== CHECKING SUBJECT MODEL ===\n";
    
    if (class_exists('\App\Models\Subject')) {
        $subject = new \App\Models\Subject();
        echo "✅ Subject model exists\n";
        echo "Subject table: " . $subject->getTable() . "\n";
        echo "Subject fillable: " . implode(', ', $subject->getFillable()) . "\n";
        
        // Test subject access
        $testSubject = \App\Models\Subject::first();
        if ($testSubject) {
            echo "Test subject found:\n";
            echo "  ID: {$testSubject->id}\n";
            echo "  Name: " . ($testSubject->name ?? $testSubject->nama ?? 'Unknown') . "\n";
            echo "  Active: " . ($testSubject->is_active ?? 'Unknown') . "\n";
        }
    } else {
        echo "❌ Subject model doesn't exist\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
