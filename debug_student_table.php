<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG STUDENT TABLE STRUCTURE\n";
echo "=====================================\n\n";

try {
    // Check students table structure
    echo "📊 Students Table Structure:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM students");
    
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Check if user_id exists
    $hasUserId = false;
    foreach ($columns as $column) {
        if ($column->Field === 'user_id') {
            $hasUserId = true;
            break;
        }
    }
    
    if (!$hasUserId) {
        echo "❌ Column 'user_id' NOT found in students table!\n\n";
        
        // Check what ID column exists
        echo "🔍 Checking for ID columns:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        foreach ($columns as $column) {
            if (stripos($column->Field, 'id') !== false) {
                echo "- {$column->Field}\n";
            }
        }
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    } else {
        echo "✅ Column 'user_id' found in students table\n\n";
    }
    
    // Check sample data
    echo "📋 Sample Students Data:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $sampleStudents = \Illuminate\Support\Facades\DB::table('students')->limit(3)->get();
    
    foreach ($sampleStudents as $student) {
        echo "ID: {$student->id}\n";
        echo "Name: {$student->name}\n";
        echo "Email: {$student->email}\n";
        echo "NIS: {$student->nis}\n";
        echo "Kelas ID: {$student->kelas_id}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
    
    // Check users table structure
    echo "\n📊 Users Table Structure:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $userColumns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM users");
    
    foreach ($userColumns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Check sample users
    echo "📋 Sample Users Data:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $sampleUsers = \Illuminate\Support\Facades\DB::table('users')->limit(3)->get();
    
    foreach ($sampleUsers as $user) {
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Role: {$user->role}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n✅ Debug selesai\n";
?>
