<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG USERS_CENTRAL TABLE STRUCTURE\n";
echo "=====================================\n\n";

try {
    // Check users_central table structure
    echo "📊 Users_Central Table Structure:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM users_central");
    
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Check if siswa_id exists
    $hasSiswaId = false;
    foreach ($columns as $column) {
        if ($column->Field === 'siswa_id') {
            $hasSiswaId = true;
            break;
        }
    }
    
    if (!$hasSiswaId) {
        echo "❌ Column 'siswa_id' NOT found in users_central table!\n\n";
        
        // Check what ID columns exist
        echo "🔍 Checking for ID columns:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        foreach ($columns as $column) {
            if (stripos($column->Field, 'id') !== false) {
                echo "- {$column->Field}\n";
            }
        }
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    } else {
        echo "✅ Column 'siswa_id' found in users_central table\n\n";
    }
    
    // Check sample data
    echo "📋 Sample Users_Central Data:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $sampleUsers = \Illuminate\Support\Facades\DB::table('users_central')->limit(3)->get();
    
    foreach ($sampleUsers as $user) {
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Role: {$user->role}\n";
        if (isset($user->siswa_id)) {
            echo "Siswa ID: {$user->siswa_id}\n";
        }
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
    
    // Check if there are any siswa users
    echo "\n🔍 Checking for siswa users:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $siswaUsers = \Illuminate\Support\Facades\DB::table('users_central')->where('role', 'siswa')->get();
    echo "Total siswa users: " . $siswaUsers->count() . "\n";
    
    foreach ($siswaUsers as $user) {
        echo "User ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
    }
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Check students table again
    echo "📊 Students Table Data:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $students = \Illuminate\Support\Facades\DB::table('students')->get();
    echo "Total students: " . $students->count() . "\n";
    
    foreach ($students as $student) {
        echo "Student ID: {$student->id}, Name: {$student->name}, Email: {$student->email}\n";
    }
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n✅ Debug selesai\n";
?>
