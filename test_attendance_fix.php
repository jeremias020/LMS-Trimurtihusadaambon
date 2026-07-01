<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 TESTING ATTENDANCE MODEL FIX\n";
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
            } else {
                echo "⚠️  No student found for this attendance\n";
            }
        } catch (\Exception $e) {
            echo "❌ Error in student relationship: " . $e->getMessage() . "\n";
            
            if (strpos($e->getMessage(), 'user_id') !== false) {
                echo "🎯 FOUND THE ERROR! This is the source of user_id error\n";
            }
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
    } else {
        echo "❌ No attendance records found\n";
    }
    
    // Test creating attendance record
    echo "🔍 Testing attendance creation:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    try {
        $student = \App\Models\Student::first();
        if ($student) {
            $newAttendance = \App\Models\Attendance::create([
                'siswa_id' => $student->id,
                'status' => 'hadir',
                'tanggal' => now(),
                'created_by' => 1 // Assuming admin user ID 1
            ]);
            
            echo "✅ Attendance created successfully\n";
            echo "New Attendance ID: {$newAttendance->id}\n";
            
            // Test student relationship on new record
            $relatedStudent = $newAttendance->student;
            if ($relatedStudent) {
                echo "✅ Student relationship works correctly\n";
                echo "Related Student: {$relatedStudent->name}\n";
            } else {
                echo "⚠️  Student relationship failed\n";
            }
            
            // Clean up
            $newAttendance->delete();
            
        } else {
            echo "⚠️  No students found for testing\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Error creating attendance: " . $e->getMessage() . "\n";
        
        if (strpos($e->getMessage(), 'user_id') !== false) {
            echo "🎯 FOUND THE ERROR in attendance creation!\n";
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
