<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 TABLE STRUCTURE ANALYSIS\n";
echo "=====================================\n";

try {
    echo "=== Users Central Table ===\n";
    $usersCentralColumns = \Illuminate\Support\Facades\Schema::getColumnListing('users_central');
    foreach ($usersCentralColumns as $column) {
        echo "- {$column}\n";
    }
    
    echo "\n=== Users Table ===\n";
    $usersColumns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    foreach ($usersColumns as $column) {
        echo "- {$column}\n";
    }
    
    echo "\n=== Testing User Model Relations ===\n";
    
    // Get a siswa user
    $user = \App\Models\User::where('role', 'siswa')->first();
    if ($user) {
        echo "Found siswa user: {$user->name} (ID: {$user->id})\n";
        
        // Test the siswa relation
        try {
            $siswa = $user->siswa;
            if ($siswa) {
                echo "✅ User->siswa relation works: {$siswa->name}\n";
            } else {
                echo "⚠️  User->siswa relation returns null\n";
            }
        } catch (\Exception $e) {
            echo "❌ User->siswa relation failed: " . $e->getMessage() . "\n";
            
            if (str_contains($e->getMessage(), 'user_id') && str_contains($e->getMessage(), 'where clause')) {
                echo "❌ This is the source of the error!\n";
                echo "❌ The relation is trying to query users table with user_id\n";
            }
        }
    } else {
        echo "No siswa user found to test\n";
    }
    
    echo "\n=== Testing Direct Student Query ===\n";
    try {
        $student = \App\Models\Student::where('id', 3)->first();
        echo "✅ Student::where('id', 3) works: " . ($student ? $student->name : 'Not found') . "\n";
    } catch (\Exception $e) {
        echo "❌ Student::where('id', 3) failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== Testing the Problematic Relation Query ===\n";
    try {
        // This simulates what the User->siswa relation does
        $student = \App\Models\Student::where('id', 3)->whereNull('deleted_at')->first();
        echo "✅ Direct relation simulation works: " . ($student ? $student->name : 'Not found') . "\n";
    } catch (\Exception $e) {
        echo "❌ Direct relation simulation failed: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n✨ ANALYSIS COMPLETE! ✨\n";
?>
