<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNOSING USER_ID COLUMN ERROR ===\n\n";

try {
    echo "Step 1: Checking table structures...\n";
    
    // Check users table structure
    echo "USERS table columns:\n";
    $usersColumns = \Schema::getColumnListing('users');
    foreach ($usersColumns as $column) {
        echo "  - {$column}\n";
    }
    
    echo "\nUSERS_CENTRAL table columns:\n";
    $usersCentralColumns = \Schema::getColumnListing('users_central');
    foreach ($usersCentralColumns as $column) {
        echo "  - {$column}\n";
    }
    
    echo "\nStep 2: Checking User model configuration...\n";
    $userModel = new \App\Models\User();
    echo "User model table: " . $userModel->getTable() . "\n";
    echo "User model primary key: " . $userModel->getKeyName() . "\n";
    
    echo "\nStep 3: Checking Student model configuration...\n";
    $studentModel = new \App\Models\Student();
    echo "Student model table: " . $studentModel->getTable() . "\n";
    echo "Student model primary key: " . $studentModel->getKeyName() . "\n";
    
    echo "\nStep 4: Testing relationships...\n";
    
    // Test User -> Student relationship
    $user = \App\Models\User::find(3);
    if ($user) {
        echo "Found user (ID: 3): {$user->name}\n";
        
        try {
            $student = $user->siswa;
            if ($student) {
                echo "✓ Student relationship works: {$student->name}\n";
            } else {
                echo "✗ Student relationship returns null\n";
            }
        } catch (Exception $e) {
            echo "✗ Student relationship error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "✗ User with ID 3 not found\n";
    }
    
    echo "\nStep 5: Testing direct Student query...\n";
    
    try {
        $student = \App\Models\Student::where('user_id', 3)->first();
        if ($student) {
            echo "✓ Direct student query works: {$student->name}\n";
        } else {
            echo "✗ Direct student query returns null\n";
        }
    } catch (Exception $e) {
        echo "✗ Direct student query error: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 6: Checking problematic query...\n";
    
    try {
        // This is the query that's causing the error
        $problematicQuery = \App\Models\User::where('user_id', 3)->first();
        echo "✗ This query should fail (user_id doesn't exist in users table)\n";
    } catch (Exception $e) {
        echo "✗ Expected error: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== RECOMMENDATIONS ===\n";
    echo "1. The error occurs because query is looking for 'user_id' column in 'users' table\n";
    echo "2. 'users' table uses 'id' as primary key, not 'user_id'\n";
    echo "3. 'users_central' table also uses 'id' as primary key\n";
    echo "4. Student model relationships should use 'id' instead of 'user_id'\n";
    echo "5. Check if there are any hardcoded queries using 'user_id'\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
