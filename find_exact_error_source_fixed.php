<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 FINDING EXACT SOURCE OF ERROR - FIXED\n";
echo "=====================================\n\n";

try {
    // Enable query logging
    \Illuminate\Support\Facades\DB::enableQueryLog();
    
    echo "📊 Testing all controller methods that might trigger error:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Test 1: ProfileController methods
    echo "Testing ProfileController methods:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    try {
        $profileController = new \App\Http\Controllers\Siswa\ProfileController();
        
        // Test edit method (this might be where error occurs)
        $request = new \Illuminate\Http\Request();
        
        // Mock authenticated user
        $user = \App\Models\User::where('role', 'siswa')->first();
        if ($user) {
            \Illuminate\Support\Facades\Auth::login($user);
            
            echo "✅ Logged in as: {$user->name} (ID: {$user->id})\n";
            
            // Clear query log
            \Illuminate\Support\Facades\DB::flushQueryLog();
            
            // Try to call edit method
            try {
                $response = $profileController->edit($request);
                echo "✅ ProfileController::edit() succeeded\n";
            } catch (\Exception $e) {
                echo "❌ ProfileController::edit() failed: " . $e->getMessage() . "\n";
                
                // Check if this is the error we're looking for
                if (strpos($e->getMessage(), 'user_id') !== false) {
                    echo "🎯 FOUND THE ERROR! This is the source of user_id error\n";
                }
            }
            
            // Check queries
            $queries = \Illuminate\Support\Facades\DB::getQueryLog();
            echo "Queries executed:\n";
            foreach ($queries as $query) {
                echo "- " . $query['query'] . "\n";
                if (strpos($query['query'], 'user_id') !== false) {
                    echo "  🚨 PROBLEMATIC QUERY FOUND!\n";
                }
            }
        }
        
    } catch (\Exception $e) {
        echo "❌ Error setting up ProfileController test: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // Test 2: ProfileControllerNew methods
    echo "Testing ProfileControllerNew methods:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    try {
        $profileControllerNew = new \App\Http\Controllers\Siswa\ProfileControllerNew();
        
        $user = \App\Models\User::where('role', 'siswa')->first();
        if ($user) {
            \Illuminate\Support\Facades\Auth::login($user);
            
            // Clear query log
            \Illuminate\Support\Facades\DB::flushQueryLog();
            
            try {
                $response = $profileControllerNew->edit($request);
                echo "✅ ProfileControllerNew::edit() succeeded\n";
            } catch (\Exception $e) {
                echo "❌ ProfileControllerNew::edit() failed: " . $e->getMessage() . "\n";
                
                if (strpos($e->getMessage(), 'user_id') !== false) {
                    echo "🎯 FOUND THE ERROR in ProfileControllerNew!\n";
                }
            }
            
            $queries = \Illuminate\Support\Facades\DB::getQueryLog();
            foreach ($queries as $query) {
                if (strpos($query['query'], 'user_id') !== false) {
                    echo "  🚨 PROBLEMATIC QUERY: " . $query['query'] . "\n";
                }
            }
        }
        
    } catch (\Exception $e) {
        echo "❌ Error setting up ProfileControllerNew test: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // Test 3: Direct simulation of error
    echo "Testing direct error simulation:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Check if there's any code that might be using user_id
    $user = \App\Models\User::where('role', 'siswa')->first();
    if ($user) {
        echo "Testing with user ID: {$user->id}\n";
        
        // Test all possible patterns that could cause error
        $studentModel = new \App\Models\Student();
        
        // Test correct query
        try {
            $result = $studentModel->where('id', $user->id)->first();
            echo "✅ Student::where('id', {$user->id}) succeeded\n";
        } catch (\Exception $e) {
            echo "❌ Student::where('id', {$user->id}) failed: " . $e->getMessage() . "\n";
        }
        
        // Test wrong query (should fail)
        try {
            $result = $studentModel->where('user_id', $user->id)->first();
            echo "⚠️  Student::where('user_id', {$user->id}) succeeded (unexpected)\n";
        } catch (\Exception $e) {
            echo "✅ Student::where('user_id', {$user->id}) correctly failed: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n";
    
    // Test 4: Check Laravel logs for the error
    echo "Checking Laravel logs:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $logFile = __DIR__ . '/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $logContent = file_get_contents($logFile);
        if (strpos($logContent, 'user_id') !== false) {
            echo "🎯 Found user_id error in Laravel logs!\n";
            
            // Extract relevant lines
            $lines = explode("\n", $logContent);
            foreach ($lines as $line) {
                if (strpos($line, 'user_id') !== false) {
                    echo "Log line: " . trim($line) . "\n";
                }
            }
        } else {
            echo "✅ No user_id errors found in Laravel logs\n";
        }
    } else {
        echo "⚠️  Laravel log file not found\n";
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n✅ Search selesai\n";
?>
