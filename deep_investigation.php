<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEEP INVESTIGATION OF REMAINING USER_ID ERROR\n";
echo "=====================================\n";

try {
    echo "Step 1: Test the Exact Error Query\n";
    echo "-------------------------------------\n";
    
    // This is the exact error query
    $sql = "select * from `users` where `user_id` = 3 and `users`.`deleted_at` is null limit 1";
    echo "Testing: {$sql}\n";
    
    try {
        $result = \Illuminate\Support\Facades\DB::select($sql);
        echo "❌ Unexpected: The error query worked!\n";
        echo "This means user_id column actually exists\n";
        
        // Check table structure again
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
        echo "Users table columns:\n";
        foreach ($columns as $column) {
            if (str_contains($column, 'user')) {
                echo "  - {$column} ⭐\n";
            } else {
                echo "  - {$column}\n";
            }
        }
        
    } catch (\Exception $e) {
        echo "✅ Expected: Error query fails\n";
        echo "Error: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 2: Check for Any Hidden user_id References\n";
    echo "-------------------------------------\n";
    
    // Search entire app for ANY user_id references
    $directories = [
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes'
    ];
    
    $allUserRefs = [];
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
            $files = [];
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $files[] = $file->getPathname();
                }
            }
            
            foreach ($files as $file) {
                $content = file_get_contents($file);
                $filename = str_replace(__DIR__, '', $file);
                
                // Look for any user_id references in queries
                if (preg_match("/->where\(['\"]user_id['\"]/", $content)) {
                    $lines = explode("\n", $content);
                    foreach ($lines as $lineNum => $line) {
                        if (preg_match("/->where\(['\"]user_id['\"]/", $line)) {
                            $allUserRefs[] = [
                                'file' => $filename,
                                'line' => $lineNum + 1,
                                'content' => trim($line)
                            ];
                        }
                    }
                }
            }
        }
    }
    
    if (empty($allUserRefs)) {
        echo "✅ No more ->where('user_id') references found\n";
    } else {
        echo "❌ Found remaining ->where('user_id') references:\n";
        foreach ($allUserRefs as $ref) {
            echo "  {$ref['file']}:{$ref['line']}\n";
            echo "    {$ref['content']}\n\n";
        }
    }
    
    echo "\nStep 3: Check for Model Scopes or Global Scopes\n";
    echo "-------------------------------------\n";
    
    // Check for global scopes that might add user_id conditions
    $modelFiles = glob(__DIR__ . '/app/Models/*.php');
    
    foreach ($modelFiles as $file) {
        $content = file_get_contents($file);
        $filename = basename($file);
        
        if (preg_match('/protected static function boot\s*\([^)]*\)\s*\{[^}]*user_id[^}]*}/s', $content)) {
            echo "❌ Found user_id in global scope in {$filename}\n";
            
            $lines = explode("\n", $content);
            foreach ($lines as $lineNum => $line) {
                if (str_contains($line, 'user_id') && str_contains($line, 'boot')) {
                    echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
    
    echo "\nStep 4: Check for Route Model Binding Issues\n";
    echo "-------------------------------------\n";
    
    // Check if there are any route bindings that might cause this
    $routeFiles = [
        __DIR__ . '/routes/web.php',
        __DIR__ . '/routes/api.php'
    ];
    
    foreach ($routeFiles as $file) {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $filename = basename($file);
            
            echo "Checking {$filename}...\n";
            
            // Look for route model bindings
            if (preg_match('/Route::.*\{user\}/', $content)) {
                echo "❌ Found {user} parameter in routes\n";
                
                $lines = explode("\n", $content);
                foreach ($lines as $lineNum => $line) {
                    if (preg_match('/Route::.*\{user\}/', $line)) {
                        echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                    }
                }
            }
            
            if (preg_match('/Route::model\([\'"]user[\'"]/', $content)) {
                echo "❌ Found Route::model('user') binding\n";
                
                $lines = explode("\n", $content);
                foreach ($lines as $lineNum => $line) {
                    if (preg_match('/Route::model\([\'"]user[\'"]/', $line)) {
                        echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                    }
                }
            }
        }
    }
    
    echo "\nStep 5: Check for Service Providers\n";
    echo "-------------------------------------\n";
    
    // Check service providers that might affect queries
    $providerFiles = glob(__DIR__ . '/app/Providers/*.php');
    
    foreach ($providerFiles as $file) {
        $content = file_get_contents($file);
        $filename = basename($file);
        
        if (str_contains($content, 'user_id')) {
            echo "❌ Found user_id in provider {$filename}\n";
            
            $lines = explode("\n", $content);
            foreach ($lines as $lineNum => $line) {
                if (str_contains($line, 'user_id')) {
                    echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
    
    echo "\nStep 6: Test Specific User Access Patterns\n";
    echo "-------------------------------------\n";
    
    // Test different patterns that might trigger this
    echo "Testing User::find(3)...\n";
    try {
        $user = \App\Models\User::find(3);
        if ($user) {
            echo "✅ User::find(3) works: {$user->name}\n";
        } else {
            echo "⚠️  User::find(3) returns null\n";
        }
    } catch (\Exception $e) {
        echo "❌ User::find(3) failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nTesting User::where('id', 3)->first()...\n";
    try {
        $user = \App\Models\User::where('id', 3)->first();
        if ($user) {
            echo "✅ User::where('id', 3)->first() works: {$user->name}\n";
        } else {
            echo "⚠️  User::where('id', 3)->first() returns null\n";
        }
    } catch (\Exception $e) {
        echo "❌ User::where('id', 3)->first() failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎯 COMPREHENSIVE ANALYSIS:\n";
    echo "=====================================\n";
    
    if (empty($allUserRefs)) {
        echo "✅ No more direct user_id query references found\n";
        echo "The error might be coming from:\n";
        echo "1. Cached application state\n";
        echo "2. External package or library\n";
        echo "3. Database trigger or view\n";
        echo "4. Laravel's internal route model binding\n";
        echo "5. Middleware that adds conditions\n";
    } else {
        echo "❌ Still found user_id references that need fixing\n";
    }
    
    echo "\n📝 NEXT STEPS:\n";
    echo "=====================================\n";
    echo "1. If no more references found, restart web server\n";
    echo "2. Clear all Laravel caches again\n";
    echo "3. Check database for triggers/views\n";
    echo "4. Monitor the exact URL that triggers error\n";
    echo "5. Check Laravel logs for more context\n";
    
    echo "\n✨ DEEP INVESTIGATION COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
