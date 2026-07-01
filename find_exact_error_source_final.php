<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 FINDING EXACT SOURCE OF ERROR - FINAL\n";
echo "=====================================\n\n";

try {
    // The error is: "students.user_id" in where clause
    // This means there's a query like: where('students.user_id', ...)
    // Let's search for this specific pattern
    
    echo "📊 Searching for 'students.user_id' pattern:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Search in all PHP files
    $directories = [
        'app',
        'config',
        'database',
        'routes'
    ];
    
    $foundFiles = [];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) continue;
        
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                
                // Look for the specific pattern
                if (strpos($content, 'students.user_id') !== false) {
                    $lines = explode("\n", $content);
                    foreach ($lines as $lineNumber => $line) {
                        if (strpos($line, 'students.user_id') !== false) {
                            $foundFiles[] = [
                                'file' => $file->getPathname(),
                                'line' => $lineNumber + 1,
                                'content' => trim($line)
                            ];
                        }
                    }
                }
            }
        }
    }
    
    if (empty($foundFiles)) {
        echo "✅ No 'students.user_id' pattern found in PHP files\n";
    } else {
        echo "❌ Found 'students.user_id' pattern:\n";
        foreach ($foundFiles as $found) {
            echo "File: {$found['file']}\n";
            echo "Line: {$found['line']}\n";
            echo "Content: {$found['content']}\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        }
    }
    
    echo "\n";
    
    // Search for Laravel relationships that might cause this
    echo "🔍 Searching for relationship definitions:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Look for belongsTo relationships with students table
    $relationshipFiles = [];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) continue;
        
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                
                // Look for belongsTo relationships with students
                if (strpos($content, 'belongsTo') !== false && strpos($content, 'Student') !== false) {
                    $lines = explode("\n", $content);
                    foreach ($lines as $lineNumber => $line) {
                        if (strpos($line, 'belongsTo') !== false && strpos($line, 'Student') !== false) {
                            $relationshipFiles[] = [
                                'file' => $file->getPathname(),
                                'line' => $lineNumber + 1,
                                'content' => trim($line)
                            ];
                        }
                    }
                }
            }
        }
    }
    
    if (empty($relationshipFiles)) {
        echo "✅ No Student relationships found\n";
    } else {
        echo "Found Student relationships:\n";
        foreach ($relationshipFiles as $rel) {
            echo "File: {$rel['file']}\n";
            echo "Line: {$rel['line']}\n";
            echo "Content: {$rel['content']}\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        }
    }
    
    echo "\n";
    
    // Check User model for relationships to Student
    echo "🔍 Checking User model relationships:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $userModelFile = __DIR__ . '/app/Models/User.php';
    if (file_exists($userModelFile)) {
        $userContent = file_get_contents($userModelFile);
        
        // Look for siswa relationship
        if (strpos($userContent, 'siswa()') !== false) {
            echo "Found siswa() relationship in User model\n";
            
            $lines = explode("\n", $userContent);
            foreach ($lines as $lineNumber => $line) {
                if (strpos($line, 'siswa()') !== false) {
                    echo "Line " . ($lineNumber + 1) . ": " . trim($line) . "\n";
                    
                    // Get the full relationship method
                    $methodContent = '';
                    for ($i = $lineNumber; $i < count($lines); $i++) {
                        $methodContent .= $lines[$i] . "\n";
                        if (strpos($lines[$i], '}') !== false && strpos($lines[$i], 'function') === false) {
                            break;
                        }
                    }
                    echo "Full method:\n" . $methodContent . "\n";
                    break;
                }
            }
        } else {
            echo "❌ No siswa() relationship found in User model\n";
        }
    } else {
        echo "❌ User model file not found\n";
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n✅ Search selesai\n";
?>
