<?php
echo "=== CHECKING SUBJECTS DATA ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Checking subjects and exam schedules data...\n";
    
    // 1. Check subjects table
    echo "\n=== 1. SUBJECTS TABLE CHECK ===\n";
    
    $subjectsTable = \Illuminate\Support\Facades\Schema::hasTable('subjects');
    echo "Subjects table exists: " . ($subjectsTable ? 'YES' : 'NO') . "\n";
    
    if ($subjectsTable) {
        $subjectsCount = \App\Models\Subject::count();
        echo "Total subjects: $subjectsCount\n";
        
        $activeSubjects = \App\Models\Subject::where('is_active', true)->count();
        echo "Active subjects: $activeSubjects\n";
        
        if ($subjectsCount > 0) {
            echo "\nAvailable subjects:\n";
            $subjects = \App\Models\Subject::limit(10)->get();
            foreach ($subjects as $subject) {
                echo "- ID: {$subject->id}, Name: {$subject->nama}, Active: " . ($subject->is_active ? 'YES' : 'NO') . "\n";
            }
        }
    }
    
    // 2. Check exam schedules table
    echo "\n=== 2. EXAM SCHEDULES TABLE CHECK ===\n";
    
    $schedulesTable = \Illuminate\Support\Facades\Schema::hasTable('exam_schedules_new');
    echo "Exam schedules table exists: " . ($schedulesTable ? 'YES' : 'NO') . "\n";
    
    if ($schedulesTable) {
        $schedulesCount = \Illuminate\Support\Facades\DB::table('exam_schedules_new')->count();
        echo "Total exam schedules: $schedulesCount\n";
        
        if ($schedulesCount > 0) {
            echo "\nExam schedules with subject data:\n";
            $schedules = \Illuminate\Support\Facades\DB::table('exam_schedules_new')
                ->leftJoin('subjects', 'exam_schedules_new.subject_id', '=', 'subjects.id')
                ->select('exam_schedules_new.*', 'subjects.nama as subject_name')
                ->limit(5)
                ->get();
            
            foreach ($schedules as $schedule) {
                echo "- Schedule ID: {$schedule->id}, Title: {$schedule->title}\n";
                echo "  Subject ID: {$schedule->subject_id}, Subject Name: " . ($schedule->subject_name ?? 'NULL') . "\n";
                echo "  Exam Type: {$schedule->exam_type}\n\n";
            }
        }
    }
    
    // 3. Test relationship
    echo "\n=== 3. RELATIONSHIP TEST ===\n";
    
    if ($schedulesCount > 0) {
        try {
            $schedule = \App\Models\ExamSchedule::with('subject')->first();
            echo "✅ Schedule loaded with relationship\n";
            echo "Schedule title: {$schedule->title}\n";
            
            if ($schedule->subject) {
                echo "✅ Subject relationship working: {$schedule->subject->nama}\n";
            } else {
                echo "❌ Subject relationship not working - subject is null\n";
                echo "Subject ID in schedule: {$schedule->subject_id}\n";
            }
            
        } catch (Exception $e) {
            echo "❌ Relationship test failed: " . $e->getMessage() . "\n";
        }
    }
    
    // 4. Check if there are any subject_id issues
    echo "\n=== 4. SUBJECT_ID CONSISTENCY CHECK ===\n";
    
    if ($schedulesCount > 0) {
        $invalidSubjectIds = \Illuminate\Support\Facades\DB::table('exam_schedules_new')
            ->whereNotNull('subject_id')
            ->whereNotIn('subject_id', function($query) {
                $query->select('id')->from('subjects');
            })
            ->count();
        
        echo "Schedules with invalid subject_id: $invalidSubjectIds\n";
        
        $nullSubjectIds = \Illuminate\Support\Facades\DB::table('exam_schedules_new')
            ->whereNull('subject_id')
            ->count();
        
        echo "Schedules with null subject_id: $nullSubjectIds\n";
        
        if ($invalidSubjectIds > 0) {
            echo "\n⚠️ Found schedules with invalid subject_id\n";
            echo "These schedules need to be fixed\n";
        }
    }
    
    // 5. Recommendations
    echo "\n=== 5. RECOMMENDATIONS ===\n";
    
    if (!$subjectsTable) {
        echo "❌ Subjects table doesn't exist\n";
        echo "   Run subjects migration\n";
    } elseif ($subjectsCount == 0) {
        echo "❌ No subjects found in database\n";
        echo "   Seed subjects data\n";
    } else {
        echo "✅ Subjects table exists with data\n";
    }
    
    if (!$schedulesTable) {
        echo "❌ Exam schedules table doesn't exist\n";
    } else {
        echo "✅ Exam schedules table exists\n";
        
        if ($invalidSubjectIds > 0) {
            echo "⚠️ Fix schedules with invalid subject_id\n";
        }
        
        if ($nullSubjectIds > 0) {
            echo "⚠️ Update schedules with null subject_id\n";
        }
    }
    
    echo "\n=== COMPLETE ===\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
