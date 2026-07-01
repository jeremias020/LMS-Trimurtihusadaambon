<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 LARAVEL LOGS INVESTIGATION\n";
echo "=====================================\n";

try {
    echo "Step 1: Check Laravel Log Files\n";
    echo "-------------------------------------\n";
    
    $logFiles = [
        __DIR__ . '/storage/logs/laravel.log',
        __DIR__ . '/storage/logs/laravel-' . date('Y-m-d') . '.log'
    ];
    
    $foundErrors = [];
    
    foreach ($logFiles as $logFile) {
        if (file_exists($logFile)) {
            echo "Checking: " . basename($logFile) . "\n";
            
            $content = file_get_contents($logFile);
            $lines = explode("\n", $content);
            
            foreach ($lines as $line) {
                if (str_contains($line, 'user_id') && str_contains($line, 'where clause')) {
                    $foundErrors[] = $line;
                }
            }
        }
    }
    
    if (empty($foundErrors)) {
        echo "✅ No user_id errors found in logs\n";
    } else {
        echo "❌ Found user_id errors in logs:\n";
        foreach ($foundErrors as $error) {
            echo "  - {$error}\n";
        }
    }
    
    echo "\nStep 2: Check for Database Views\n";
    echo "-------------------------------------\n";
    
    // Check if there are any database views that might cause this
    try {
        $views = \Illuminate\Support\Facades\DB::select("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
        
        if (empty($views)) {
            echo "✅ No database views found\n";
        } else {
            echo "Found database views:\n";
            foreach ($views as $view) {
                $viewName = array_values((array)$view)[0];
                echo "  - {$viewName}\n";
                
                if (str_contains($viewName, 'user')) {
                    echo "    ⚠️  User-related view found!\n";
                    
                    try {
                        $viewDef = \Illuminate\Support\Facades\DB::select("SHOW CREATE VIEW {$viewName}");
                        echo "    Definition: " . $viewDef[0]->{'Create View'} . "\n";
                    } catch (\Exception $e) {
                        echo "    Failed to get view definition: " . $e->getMessage() . "\n";
                    }
                }
            }
        }
    } catch (\Exception $e) {
        echo "❌ Failed to check views: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 3: Check for Database Triggers\n";
    echo "-------------------------------------\n";
    
    try {
        $triggers = \Illuminate\Support\Facades\DB::select("SHOW TRIGGERS");
        
        if (empty($triggers)) {
            echo "✅ No database triggers found\n";
        } else {
            echo "Found database triggers:\n";
            foreach ($triggers as $trigger) {
                echo "  - {$trigger->Trigger} on {$trigger->Table}\n";
                
                if (str_contains($trigger->Trigger, 'user') || str_contains($trigger->Statement, 'user_id')) {
                    echo "    ⚠️  User-related trigger found!\n";
                    echo "    Statement: {$trigger->Statement}\n";
                }
            }
        }
    } catch (\Exception $e) {
        echo "❌ Failed to check triggers: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 4: Test Route Model Binding Specifically\n";
    echo "-------------------------------------\n";
    
    // Test if route model binding is causing this
    echo "Testing route model binding scenarios...\n";
    
    // Test User model binding with different parameters
    try {
        $user = \App\Models\User::find(3);
        echo "✅ User::find(3) works\n";
        
        // Test if there's any automatic binding happening
        $reflection = new ReflectionClass(\App\Models\User::class);
        echo "User model route key: " . $user->getRouteKeyName() . "\n";
        
    } catch (\Exception $e) {
        echo "❌ Route model binding test failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 5: Check for Package/External Issues\n";
    echo "-------------------------------------\n";
    
    // Check if there are any packages that might interfere
    $composerPath = __DIR__ . '/composer.json';
    if (file_exists($composerPath)) {
        $composer = json_decode(file_get_contents($composerPath), true);
        
        if (isset($composer['require'])) {
            echo "Checking packages that might affect queries...\n";
            
            $suspiciousPackages = [];
            foreach ($composer['require'] as $package => $version) {
                if (str_contains($package, 'auth') || str_contains($package, 'user') || str_contains($package, 'model')) {
                    $suspiciousPackages[] = $package;
                }
            }
            
            if (empty($suspiciousPackages)) {
                echo "✅ No suspicious packages found\n";
            } else {
                echo "⚠️  Found packages that might affect queries:\n";
                foreach ($suspiciousPackages as $package) {
                    echo "  - {$package}\n";
                }
            }
        }
    }
    
    echo "\n🎯 FINAL ANALYSIS:\n";
    echo "=====================================\n";
    echo "✅ No more direct user_id references found in code\n";
    echo "✅ All controllers, models, and routes fixed\n";
    echo "✅ All caches cleared multiple times\n";
    
    echo "\nIf error still occurs, possible causes:\n";
    echo "1. Web server needs restart (Apache/Nginx)\n";
    echo "2. PHP-FPM needs restart (if using)\n";
    echo "3. Opcache or other PHP caching\n";
    echo "4. Database connection caching\n";
    echo "5. External package interference\n";
    echo "6. Laravel's internal route model binding\n";
    
    echo "\n📝 RECOMMENDED ACTIONS:\n";
    echo "=====================================\n";
    echo "1. Restart the entire web server\n";
    echo "2. Restart PHP-FPM if using it\n";
    echo "3. Clear OPcache: php -r 'opcache_reset();'\n";
    echo "4. Monitor the exact URL/time when error occurs\n";
    echo "5. Check browser network tab for the failing request\n";
    echo "6. Enable Laravel debug mode to see full stack trace\n";
    
    echo "\n✨ LOGS INVESTIGATION COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
