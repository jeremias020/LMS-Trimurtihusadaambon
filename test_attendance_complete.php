<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 TESTING ATTENDANCE MODEL FIX - COMPLETE\n";
echo "=====================================\n\n";

try {
    // Test Attendance model
    echo "📊 Testing Attendance Model:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Get a sample attendance record
    $attendance = \App\Models\Attendance::first();
    
    if ($attendance) {
        echo "✅ Attendance found:\n";
        echo "Attendance ID: {$attendance->id}\n";
        echo "Siswa ID: {$attendance->siswa_id}\n";
        echo "Status: {$attendance->status}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Test student relationship
        echo "Testing student relationship:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        try {
            $student = $attendance->student;
            if ($student) {
                echo "✅ Student found:\n";
                echo "Student ID: {$student->id}\n";
                echo "Student Name: {$student->name}\n";
                echo "Student Email: {$student->email}\n";
                echo "✅ Student relationship works correctly - NO user_id error!\n";
            } else {
                echo "⚠️  No student found for this attendance\n";
            }
        } catch (\Exception $e) {
            echo "❌ Error in student relationship: " . $e->getMessage() . "\n";
            
            if (strpos($e->getMessage(), 'user_id') !== false) {
                echo "🎯 FOUND THE ERROR! This is the source of user_id error\n";
            } else {
                echo "✅ Different error (not user_id related)\n";
            }
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
    } else {
        echo "❌ No attendance records found\n";
    }
    
    // Test the specific scenario that might trigger the error
    echo "🔍 Testing specific error scenarios:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Test 1: Check if there are any attendance queries that might fail
    try {
        $attendances = \App\Models\Attendance::with('student')->get();
        echo "✅ Attendance::with('student') works correctly\n";
        echo "Found " . $attendances->count() . " attendance records\n";
        
        foreach ($attendances as $att) {
            if ($att->student) {
                echo "- Attendance ID {$att->id}: Student {$att->student->name}\n";
            } else {
                echo "- Attendance ID {$att->id}: No student linked\n";
            }
        }
        
    } catch (\Exception $e) {
        echo "❌ Error in Attendance::with('student'): " . $e->getMessage() . "\n";
        
        if (strpos($e->getMessage(), 'user_id') !== false) {
            echo "🎯 FOUND THE ERROR in eager loading!\n";
        }
    }
    
    echo "\n";
    
    // Test 2: Check if the error occurs when accessing student relationship
    try {
        $attendances = \App\Models\Attendance::all();
        foreach ($attendances as $attendance) {
            try {
                $student = $attendance->student;
                if ($student) {
                    echo "✅ Attendance {$attendance->id} -> Student {$student->name}\n";
                }
            } catch (\Exception $e) {
                if (strpos($e->getMessage(), 'user_id') !== false) {
                    echo "🎯 FOUND THE ERROR in attendance {$attendance->id}!\n";
                    echo "Error: " . $e->getMessage() . "\n";
                    break;
                }
            }
        }
        
    } catch (\Exception $e) {
        echo "❌ Error in attendance loop: " . $e->getMessage() . "\n";
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Test 3: Check if there are any other models with similar issues
    echo "🔍 Checking other models for similar issues:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $otherModels = [
        'Score',
        'AssignmentSubmission', 
        'PracticalScore',
        'MaterialView'
    ];
    
    foreach ($otherModels as $modelName) {
        $modelClass = "\\App\\Models\\{$modelName}";
        if (class_exists($modelClass)) {
            echo "Testing {$modelName} model:\n";
            
            try {
                $model = new $modelClass();
                $relationships = [];
                
                // Check if model has student relationship
                if (method_exists($model, 'student')) {
                    echo "  - Has student() relationship\n";
                    
                    // Test the relationship
                    $instance = $modelClass::first();
                    if ($instance) {
                        try {
                            $student = $instance->student;
                            echo "  ✅ Student relationship works\n";
                        } catch (\Exception $e) {
                            if (strpos($e->getMessage(), 'user_id') !== false) {
                                echo "  🎯 FOUND ERROR in {$modelName} student relationship!\n";
                                echo "  Error: " . $e->getMessage() . "\n";
                            }
                        }
                    }
                }
                
            } catch (\Exception $e) {
                echo "  ❌ Error testing {$modelName}: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n✅ Test selesai\n";
?>
