<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG STUDENT RELATIONSHIP FIX\n";
echo "=====================================\n\n";

try {
    // Test Student model relationship
    echo "📊 Testing Student Model Relationship:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Get a sample student
    $student = \App\Models\Student::first();
    
    if ($student) {
        echo "Student ID: {$student->id}\n";
        echo "Student Name: {$student->name}\n";
        echo "Student Email: {$student->email}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Test user relationship
        echo "Testing user() relationship:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        try {
            $user = $student->user;
            if ($user) {
                echo "✅ User found:\n";
                echo "User ID: {$user->id}\n";
                echo "User Name: {$user->name}\n";
                echo "User Email: {$user->email}\n";
                echo "User Role: {$user->role}\n";
                echo "User siswa_id: {$user->siswa_id}\n";
            } else {
                echo "❌ No user found for this student\n";
            }
        } catch (\Exception $e) {
            echo "❌ Error in user relationship: " . $e->getMessage() . "\n";
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Test reverse relationship
        echo "Testing User -> Student relationship:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        $userWithSiswaId = \App\Models\User::where('siswa_id', $student->id)->first();
        
        if ($userWithSiswaId) {
            echo "✅ User found with siswa_id = {$student->id}:\n";
            echo "User ID: {$userWithSiswaId->id}\n";
            echo "User Name: {$userWithSiswaId->name}\n";
            echo "User Email: {$userWithSiswaId->email}\n";
            echo "User Role: {$userWithSiswaId->role}\n";
            echo "User siswa_id: {$userWithSiswaId->siswa_id}\n";
        } else {
            echo "❌ No user found with siswa_id = {$student->id}\n";
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Test the problematic query
        echo "Testing problematic query (Student::where('user_id', 1)):\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        try {
            $result = \App\Models\Student::where('user_id', 1)->first();
            echo "✅ Query succeeded (shouldn't happen): ";
            print_r($result);
        } catch (\Exception $e) {
            echo "❌ Expected error: " . $e->getMessage() . "\n";
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Test the correct query
        echo "Testing correct query (Student::where('id', 1)):\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        try {
            $result = \App\Models\Student::where('id', 1)->first();
            if ($result) {
                echo "✅ Query succeeded:\n";
                echo "Student ID: {$result->id}\n";
                echo "Student Name: {$result->name}\n";
            } else {
                echo "❌ No student found with id = 1\n";
            }
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
    } else {
        echo "❌ No students found in database\n";
    }
    
    // Check all controllers that might be using wrong queries
    echo "🔍 Checking for problematic code patterns:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $problematicFiles = [
        'app/Http/Controllers/Siswa/ProfileController.php',
        'app/Http/Controllers/Siswa/ProfileControllerNew.php',
        'app/Http/Controllers/Siswa/PracticalController.php',
        'app/Http/Controllers/Siswa/MaterialController.php',
        'app/Http/Controllers/Siswa/MaterialTrackingController.php',
    ];
    
    foreach ($problematicFiles as $file) {
        $filePath = __DIR__ . '/' . $file;
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            if (strpos($content, "Student::where('id',") !== false) {
                echo "✅ {$file} - Using correct query (Student::where('id', ...))\n";
            } elseif (strpos($content, "Student::where('user_id',") !== false) {
                echo "❌ {$file} - Using wrong query (Student::where('user_id', ...))\n";
            } else {
                echo "⚠️  {$file} - No Student queries found\n";
            }
        } else {
            echo "⚠️  {$file} - File not found\n";
        }
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n✅ Debug selesai\n";
?>
