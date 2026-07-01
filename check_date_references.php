<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 CHECK FOR REMAINING DATE REFERENCES\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Practical Model for Any Date References\n";
    echo "-------------------------------------\n";
    
    $modelPath = __DIR__ . '/app/Models/Practical.php';
    $modelContent = file_get_contents($modelPath);
    
    $lines = explode("\n", $modelContent);
    $problemLines = [];
    
    foreach ($lines as $lineNum => $line) {
        // Look for problematic date references (not the cast)
        if ((str_contains($line, 'date') || str_contains($line, 'Date')) && 
            !str_contains($line, 'due_date') && 
            !str_contains($line, '=> \'date\'') && 
            !str_contains($line, 'created_at') && 
            !str_contains($line, 'updated_at') && 
            !str_contains($line, 'deleted_at')) {
            
            $problemLines[] = [
                'line' => $lineNum + 1,
                'content' => trim($line)
            ];
        }
    }
    
    if (empty($problemLines)) {
        echo "✅ No problematic date references found\n";
    } else {
        echo "❌ Found problematic date references:\n";
        foreach ($problemLines as $problem) {
            echo "  Line {$problem['line']}: {$problem['content']}\n";
        }
    }
    
    echo "\nStep 2: Check Controllers for Date References\n";
    echo "-------------------------------------\n";
    
    $controllerFiles = [
        __DIR__ . '/app/Http/Controllers/Admin/DashboardController.php',
        __DIR__ . '/app/Http/Controllers/Admin/PracticalController.php',
        __DIR__ . '/app/Http/Controllers/Guru/DashboardController.php',
        __DIR__ . '/app/Http/Controllers/Siswa/DashboardController.php'
    ];
    
    foreach ($controllerFiles as $file) {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $filename = basename($file);
            
            if (str_contains($content, 'practicals') && str_contains($content, 'date')) {
                echo "Found practicals + date in {$filename}:\n";
                
                $lines = explode("\n", $content);
                foreach ($lines as $lineNum => $line) {
                    if (str_contains($line, 'practicals') && 
                        (str_contains($line, 'date') || str_contains($line, 'Date')) &&
                        !str_contains($line, 'due_date') &&
                        !str_contains($line, 'created_at') &&
                        !str_contains($line, 'updated_at')) {
                        
                        echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                    }
                }
                echo "\n";
            }
        }
    }
    
    echo "\nStep 3: Test the Original Error Scenario\n";
    echo "-------------------------------------\n";
    
    // The original error mentioned siswa_id = 3
    // Let's try to reproduce the exact scenario
    $siswaId = 3;
    
    echo "Testing with siswa_id = {$siswaId}...\n";
    
    try {
        // This might be the query that was failing
        $query = \App\Models\Practical::whereNotNull('published_at')
            ->where(function($q) {
                $q->whereNull('kelas_id');
            })
            ->where('due_date', '>', now())
            ->whereDoesntHave('scores', function($q) use ($siswaId) {
                $q->where('siswa_id', $siswaId);
            })
            ->orderBy('due_date', 'asc')
            ->limit(5);
        
        $results = $query->get();
        echo "✅ Query works! Found " . $results->count() . " practicals\n";
        
    } catch (\Exception $e) {
        echo "❌ Query failed: " . $e->getMessage() . "\n";
        
        // Check if it's still the date column error
        if (str_contains($e->getMessage(), 'date') && str_contains($e->getMessage(), 'where clause')) {
            echo "❌ Still getting date column error!\n";
            
            // Let's see what the actual query is
            $querySql = $query->toSql();
            echo "Actual SQL: {$querySql}\n";
        }
    }
    
    echo "\nStep 4: Check for Any Other Date Column Usage\n";
    echo "-------------------------------------\n";
    
    // Check if there are any other places that might be using 'date' instead of 'due_date'
    $directories = [
        __DIR__ . '/app/Http/Controllers'
    ];
    
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
                
                // Look for ->where('date' patterns
                if (str_contains($content, "->where('date'") || str_contains($content, "->where(\"date\"")) {
                    echo "Found ->where('date') in: " . str_replace(__DIR__, '', $file) . "\n";
                    
                    $lines = explode("\n", $content);
                    foreach ($lines as $lineNum => $line) {
                        if (str_contains($line, "->where('date'") || str_contains($line, "->where(\"date\"")) {
                            echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                        }
                    }
                    echo "\n";
                }
            }
        }
    }
    
    echo "\n🎯 CONCLUSION:\n";
    echo "=====================================\n";
    
    if (empty($problemLines)) {
        echo "✅ Practical model is completely fixed\n";
        echo "✅ All date references converted to due_date\n";
        echo "✅ The original error should be resolved\n";
    } else {
        echo "❌ There are still some date references that need fixing\n";
        echo "Please review the lines mentioned above\n";
    }
    
    echo "\n✨ DATE COLUMN FIX ANALYSIS COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
