<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG USERS.USER_ID ERROR\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Users Table Structure\n";
    echo "-------------------------------------\n";
    
    $usersColumns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    echo "Users table columns:\n";
    foreach ($usersColumns as $column) {
        echo "  - {$column}\n";
    }
    
    echo "\nStep 2: Check UserCentral Table Structure\n";
    echo "-------------------------------------\n";
    
    $usersCentralColumns = \Illuminate\Support\Facades\Schema::getColumnListing('users_central');
    echo "Users_central table columns:\n";
    foreach ($usersCentralColumns as $column) {
        echo "  - {$column}\n";
    }
    
    echo "\nStep 3: Check Guru Model Configuration\n";
    echo "-------------------------------------\n";
    
    $guruModel = new \App\Models\Guru();
    echo "Guru model table: " . $guruModel->getTable() . "\n";
    
    // Check relationship definitions
    $reflection = new ReflectionClass($guruModel);
    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
    
    foreach ($methods as $method) {
        if ($method->getName() === 'user') {
            echo "Found user() method in Guru model\n";
            break;
        }
    }
    
    echo "\nStep 4: Find Where user_id is Used\n";
    echo "-------------------------------------\n";
    
    // Search for user_id in model files
    $modelPath = __DIR__ . '/app/Models';
    $files = glob($modelPath . '/*.php');
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $filename = basename($file, '.php');
        
        if (str_contains($content, 'user_id')) {
            echo "Found user_id in {$filename} model:\n";
            
            // Find lines with user_id
            $lines = explode("\n", $content);
            foreach ($lines as $lineNum => $line) {
                if (str_contains($line, 'user_id')) {
                    echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                }
            }
            echo "\n";
        }
    }
    
    echo "\nStep 5: Check Current Query\n";
    echo "-------------------------------------\n";
    
    // The error shows query trying to use users.user_id
    // Let's see what's happening
    try {
        // Test the problematic query
        $result = \Illuminate\Support\Facades\DB::table('users')
            ->where('users.user_id', 1)
            ->where('users.user_id', '!=', null)
            ->whereNull('users.deleted_at')
            ->first();
        
        echo "Query executed successfully (unexpected!)\n";
    } catch (\Exception $e) {
        echo "Query failed as expected: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 6: Check Correct Query\n";
    echo "-------------------------------------\n";
    
    try {
        // Test the correct query
        $result = \Illuminate\Support\Facades\DB::table('users')
            ->where('users.id', 1)
            ->whereNull('users.deleted_at')
            ->first();
        
        echo "✅ Correct query works\n";
        if ($result) {
            echo "Found user: {$result->name}\n";
        } else {
            echo "No user found with ID 1\n";
        }
    } catch (\Exception $e) {
        echo "Correct query failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 7: Check User Model Configuration\n";
    echo "-------------------------------------\n";
    
    $userModel = new \App\Models\User();
    echo "User model table: " . $userModel->getTable() . "\n";
    
    $userCentralModel = new \App\Models\UserCentral();
    echo "UserCentral model table: " . $userCentralModel->getTable() . "\n";
    
    echo "\nStep 8: Find Source of user_id Query\n";
    echo "-------------------------------------\n";
    
    // Search in all PHP files for user_id usage
    $directories = [
        __DIR__ . '/app/Http/Controllers',
        __DIR__ . '/app/Models',
        __DIR__ . '/app'
    ];
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
            $files = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
            
            foreach ($files as $file) {
                $content = file_get_contents($file);
                
                // Look for patterns that might cause this error
                if (str_contains($content, 'users.user_id')) {
                    echo "Found 'users.user_id' in: " . str_replace(__DIR__, '', $file) . "\n";
                    
                    $lines = explode("\n", $content);
                    foreach ($lines as $lineNum => $line) {
                        if (str_contains($line, 'users.user_id')) {
                            echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                        }
                    }
                    echo "\n";
                }
            }
        }
    }
    
    echo "\n🔧 SOLUTION:\n";
    echo "=====================================\n";
    echo "The error indicates that somewhere in the code,\n";
    echo "there's a query trying to use 'users.user_id'\n";
    echo "instead of 'users.id'.\n\n";
    
    echo "Common causes:\n";
    echo "1. Incorrect relationship definition\n";
    echo "2. Wrong foreign key reference\n";
    echo "3. Typo in query builder\n\n";
    
    echo "Need to find and fix the source of this query.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
