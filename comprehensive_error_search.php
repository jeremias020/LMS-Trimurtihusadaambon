<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 COMPREHENSIVE ERROR SEARCH\n";
echo "=====================================\n\n";

try {
    // Check all files that might contain the problematic query
    echo "📊 Searching for problematic patterns:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $directories = [
        'app/Http/Controllers',
        'app/Models',
        'app/Observers',
        'app/Console/Commands'
    ];
    
    $problematicPatterns = [
        'Student::where(\'user_id\'',
        'Student::where("user_id"',
        'students.user_id',
        '->where(\'user_id\'',
        '->where("user_id"'
    ];
    
    $foundIssues = [];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) continue;
        
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                
                foreach ($problematicPatterns as $pattern) {
                    if (strpos($content, $pattern) !== false) {
                        $foundIssues[] = [
                            'file' => $file->getPathname(),
                            'pattern' => $pattern,
                            'line' => $this->findLineNumber($content, $pattern)
                        ];
                    }
                }
            }
        }
    }
    
    if (empty($foundIssues)) {
        echo "✅ No problematic patterns found in PHP files\n";
    } else {
        echo "❌ Found problematic patterns:\n";
        foreach ($foundIssues as $issue) {
            echo "- File: {$issue['file']}\n";
            echo "  Pattern: {$issue['pattern']}\n";
            echo "  Line: {$issue['line']}\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        }
    }
    
    // Check specific controllers that might be causing the error
    echo "\n🔍 Testing specific controllers:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $testControllers = [
        'Siswa\\ProfileController',
        'Siswa\\ProfileControllerNew',
        'Siswa\\MaterialController',
        'Siswa\\PracticalController',
        'Siswa\\MaterialTrackingController'
    ];
    
    foreach ($testControllers as $controller) {
        $controllerClass = "App\\Http\\Controllers\\{$controller}";
        if (class_exists($controllerClass)) {
            echo "✅ Controller {$controller} exists\n";
            
            // Test if controller methods would work
            try {
                $reflection = new ReflectionClass($controllerClass);
                $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
                
                foreach ($methods as $method) {
                    if ($method->getName() !== '__construct') {
                        echo "  - Method: {$method->getName()}\n";
                    }
                }
            } catch (\Exception $e) {
                echo "  ❌ Error reflecting controller: " . $e->getMessage() . "\n";
            }
        } else {
            echo "❌ Controller {$controller} not found\n";
        }
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Test the actual error scenario
    echo "🔍 Testing error scenario:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Simulate what happens when a siswa logs in
    try {
        // Get a siswa user
        $siswaUser = \App\Models\User::where('role', 'siswa')->first();
        
        if ($siswaUser) {
            echo "✅ Found siswa user: {$siswaUser->name} (ID: {$siswaUser->id})\n";
            
            // Test the problematic query pattern
            try {
                $student = \App\Models\Student::where('id', $siswaUser->id)->first();
                if ($student) {
                    echo "✅ Student found using correct query (Student::where('id', {$siswaUser->id}))\n";
                } else {
                    echo "⚠️  No student found for user ID {$siswaUser->id}\n";
                }
            } catch (\Exception $e) {
                echo "❌ Error in correct query: " . $e->getMessage() . "\n";
            }
            
            // Test if there's still any code using user_id
            try {
                $badQuery = \App\Models\Student::where('user_id', $siswaUser->id)->first();
                echo "⚠️  Bad query succeeded (unexpected)\n";
            } catch (\Exception $e) {
                echo "✅ Bad query correctly fails: " . $e->getMessage() . "\n";
            }
        } else {
            echo "❌ No siswa user found\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Error in test scenario: " . $e->getMessage() . "\n";
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n✅ Search selesai\n";

function findLineNumber($content, $pattern) {
    $lines = explode("\n", $content);
    foreach ($lines as $lineNumber => $line) {
        if (strpos($line, $pattern) !== false) {
            return $lineNumber + 1;
        }
    }
    return 'Unknown';
}
?>
