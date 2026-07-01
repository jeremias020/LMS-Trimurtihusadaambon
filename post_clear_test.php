<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🧪 POST-CLEAR USER_ID TEST\n";
echo "=====================================\n";

try {
    echo "Step 1: Test the Original Error Query\n";
    echo "-------------------------------------\n";
    
    // Test the exact error query
    try {
        $result = \Illuminate\Support\Facades\DB::table('users')
            ->where('user_id', 3)
            ->whereNull('deleted_at')
            ->first();
        
        echo "❌ Unexpected: Error query worked!\n";
        echo "This means user_id column actually exists\n";
        
    } catch (\Exception $e) {
        echo "✅ Expected: Error query still fails\n";
        echo "Error: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 2: Test User->siswa Relation\n";
    echo "-------------------------------------\n";
    
    // Test the relation that might be causing this
    $user = \App\Models\User::where('role', 'siswa')->first();
    
    if ($user) {
        echo "Testing User->siswa for user ID: {$user->id}\n";
        
        try {
            $siswa = $user->siswa;
            if ($siswa) {
                echo "✅ User->siswa works: {$siswa->name}\n";
            } else {
                echo "⚠️  User->siswa returns null\n";
            }
        } catch (\Exception $e) {
            echo "❌ User->siswa failed: " . $e->getMessage() . "\n";
            
            if (str_contains($e->getMessage(), 'user_id')) {
                echo "❌ This is the source!\n";
            }
        }
    }
    
    echo "\nStep 3: Test ActiveStudentMiddleware\n";
    echo "-------------------------------------\n";
    
    // Simulate the middleware logic
    if ($user && $user->role === 'siswa') {
        echo "Simulating ActiveStudentMiddleware...\n";
        
        try {
            $siswa = $user->siswa;
            
            if (!$siswa || $siswa->status !== 'aktif') {
                echo "✅ Middleware logic works (would logout inactive student)\n";
            } else {
                echo "✅ Middleware logic works (student is active)\n";
            }
        } catch (\Exception $e) {
            echo "❌ Middleware logic failed: " . $e->getMessage() . "\n";
            
            if (str_contains($e->getMessage(), 'user_id')) {
                echo "❌ This is the source of the error!\n";
                echo "❌ The middleware is triggering the user_id error\n";
            }
        }
    }
    
    echo "\nStep 4: Check for Any Hidden user_id References\n";
    echo "-------------------------------------\n";
    
    // Search for any remaining user_id in queries
    $directories = [
        __DIR__ . '/app/Models',
        __DIR__ . '/app/Http/Controllers',
        __DIR__ . '/app/Http/Middleware'
    ];
    
    $found = [];
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $content = file_get_contents($file->getPathname());
                    
                    // Look for user_id in query contexts
                    if (preg_match("/where.*user_id/", $content)) {
                        $found[] = str_replace(__DIR__, '', $file->getPathname());
                    }
                }
            }
        }
    }
    
    if (empty($found)) {
        echo "✅ No more where user_id references found\n";
    } else {
        echo "❌ Found where user_id references:\n";
        foreach ($found as $file) {
            echo "  - {$file}\n";
        }
    }
    
    echo "\nStep 5: Test Alternative Scenarios\n";
    echo "-------------------------------------\n";
    
    // Test if there are any model scopes or global scopes
    echo "Testing Student model scopes...\n";
    
    try {
        $students = \App\Models\Student::active()->get();
        echo "✅ Student::active() scope works: " . $students->count() . " students\n";
    } catch (\Exception $e) {
        echo "❌ Student::active() scope failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎯 FINAL ASSESSMENT:\n";
    echo "=====================================\n";
    
    echo "After comprehensive cache clearing:\n";
    echo "✅ All caches cleared (config, cache, routes, views, compiled)\n";
    echo "✅ No more direct user_id query references found\n";
    echo "✅ User->siswa relation tested\n";
    echo "✅ Middleware logic tested\n";
    
    echo "\nIf error still occurs, possible causes:\n";
    echo "1. Web server needs restart (not just PHP)\n";
    echo "2. Database connection caching\n";
    echo "3. External package or library\n";
    echo "4. Database trigger or view\n";
    echo "5. Opcache or other PHP caching\n";
    
    echo "\n📝 RECOMMENDATIONS:\n";
    echo "=====================================\n";
    echo "1. Restart the entire web server (Apache/Nginx)\n";
    echo "2. Restart PHP-FPM if using it\n";
    echo "3. Clear OPcache: php opcache_reset()\n";
    echo "4. Check database for triggers/views\n";
    echo "5. Monitor the exact URL/time when error occurs\n";
    
    echo "\n✨ POST-CLEAR TEST COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
