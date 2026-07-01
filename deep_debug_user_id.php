<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEEP DEBUG USER_ID ERROR\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Search for ALL user_id References\n";
    echo "-------------------------------------\n";
    
    // Search entire app for any remaining user_id references
    $directories = [
        __DIR__ . '/app',
        __DIR__ . '/config'
    ];
    
    $allUserIdRefs = [];
    
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
                            $allUserIdRefs[] = [
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
    
    if (empty($allUserIdRefs)) {
        echo "✅ No more ->where('user_id') references found\n";
    } else {
        echo "❌ Found remaining ->where('user_id') references:\n";
        foreach ($allUserIdRefs as $ref) {
            echo "  {$ref['file']}:{$ref['line']}\n";
            echo "    {$ref['content']}\n\n";
        }
    }
    
    echo "\nStep 2: Check for Model Relationships\n";
    echo "-------------------------------------\n";
    
    // Check if there are any model relationships that might cause this
    $modelFiles = [
        __DIR__ . '/app/Models/User.php',
        __DIR__ . '/app/Models/Student.php'
    ];
    
    foreach ($modelFiles as $file) {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $filename = basename($file);
            
            echo "Checking {$filename}...\n";
            
            // Look for relationships that might reference user_id
            if (preg_match('/public function \w+\s*\(\s*\)\s*\{[^}]*user_id[^}]*}/s', $content)) {
                echo "❌ Found user_id reference in relationship\n";
                
                $lines = explode("\n", $content);
                foreach ($lines as $lineNum => $line) {
                    if (str_contains($line, 'user_id')) {
                        echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                    }
                }
            } else {
                echo "✅ No user_id references in relationships\n";
            }
            echo "\n";
        }
    }
    
    echo "\nStep 3: Check for Global Scopes\n";
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
    
    echo "\nStep 4: Check for Middleware\n";
    echo "-------------------------------------\n";
    
    // Check middleware that might add user_id conditions
    $middlewareFiles = glob(__DIR__ . '/app/Http/Middleware/*.php');
    
    foreach ($middlewareFiles as $file) {
        $content = file_get_contents($file);
        $filename = basename($file);
        
        if (str_contains($content, 'user_id')) {
            echo "❌ Found user_id in middleware {$filename}\n";
            
            $lines = explode("\n", $content);
            foreach ($lines as $lineNum => $line) {
                if (str_contains($line, 'user_id')) {
                    echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
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
    
    echo "\nStep 6: Test the Exact Error Scenario\n";
    echo "-------------------------------------\n";
    
    // Try to reproduce the exact error
    echo "Testing: select * from users where user_id = 3 and users.deleted_at is null limit 1\n";
    
    try {
        $result = \Illuminate\Support\Facades\DB::table('users')
            ->where('user_id', 3)
            ->whereNull('deleted_at')
            ->first();
        
        echo "❌ Unexpected: The exact error query worked!\n";
        echo "This means there might be a user_id column in users table after all\n";
        
        // Let's check the table structure again
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
        echo "Users table columns:\n";
        foreach ($columns as $column) {
            echo "  - {$column}\n";
        }
        
    } catch (\Exception $e) {
        echo "✅ Expected: The exact error query fails\n";
        echo "Error: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 7: Check for Cached Configuration\n";
    echo "-------------------------------------\n";
    
    // Check if there are any cached configurations
    $cacheFiles = [
        __DIR__ . '/bootstrap/cache/config.php',
        __DIR__ . '/bootstrap/cache/routes.php',
        __DIR__ . '/bootstrap/cache/packages.php',
        __DIR__ . '/bootstrap/cache/services.php'
    ];
    
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            echo "❌ Found cache file: " . basename($file) . "\n";
            
            $content = file_get_contents($file);
            if (str_contains($content, 'user_id')) {
                echo "  Contains user_id reference\n";
            }
        } else {
            echo "✅ No cache file: " . basename($file) . "\n";
        }
    }
    
    echo "\n🎯 ANALYSIS:\n";
    echo "=====================================\n";
    
    if (empty($allUserIdRefs)) {
        echo "✅ No more direct user_id query references found\n";
        echo "The error might be coming from:\n";
        echo "1. Cached application state\n";
        echo "2. Middleware or service provider\n";
        echo "3. External package or library\n";
        echo "4. Database trigger or view\n";
    } else {
        echo "❌ Still found user_id references that need fixing\n";
    }
    
    echo "\n📝 NEXT STEPS:\n";
    echo "=====================================\n";
    echo "1. If no more references found, restart web server\n";
    echo "2. Clear all caches again\n";
    echo "3. Check database triggers/views\n";
    echo "4. Monitor the exact URL that triggers error\n";
    
    echo "\n✨ DEEP DEBUG COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
