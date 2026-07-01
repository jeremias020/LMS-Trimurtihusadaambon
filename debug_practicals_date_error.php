<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG PRACTICALS DATE COLUMN ERROR\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Practicals Table Structure\n";
    echo "-------------------------------------\n";
    
    $practicalsColumns = \Illuminate\Support\Facades\Schema::getColumnListing('practicals');
    echo "Practicals table columns:\n";
    foreach ($practicalsColumns as $column) {
        echo "  - {$column}\n";
    }
    
    echo "\nStep 2: Analyze the Problematic Query\n";
    echo "-------------------------------------\n";
    
    echo "Problematic query parts:\n";
    echo "  - WHERE `date` > 2026-04-02 14:32:35\n";
    echo "  - ORDER BY `date` asc\n\n";
    
    echo "The query is looking for 'date' column but it doesn't exist.\n";
    
    // Check if there are date-related columns
    $dateColumns = [];
    foreach ($practicalsColumns as $column) {
        if (str_contains($column, 'date') || str_contains($column, 'time') || str_contains($column, 'at')) {
            $dateColumns[] = $column;
        }
    }
    
    echo "Potential date/time columns found:\n";
    foreach ($dateColumns as $column) {
        echo "  - {$column}\n";
    }
    
    if (empty($dateColumns)) {
        echo "  No date/time columns found!\n";
    }
    
    echo "\nStep 3: Find the Source of This Query\n";
    echo "-------------------------------------\n";
    
    // Search for the query in the codebase
    $directories = [
        __DIR__ . '/app/Http/Controllers',
        __DIR__ . '/app/Models',
        __DIR__ . '/app'
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
                
                // Look for practicals queries with date
                if (str_contains($content, 'practicals') && str_contains($content, 'date')) {
                    echo "Found practicals + date in: " . str_replace(__DIR__, '', $file) . "\n";
                    
                    $lines = explode("\n", $content);
                    foreach ($lines as $lineNum => $line) {
                        if (str_contains($line, 'practicals') && (str_contains($line, 'date') || str_contains($line, 'Date'))) {
                            echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                        }
                    }
                    echo "\n";
                }
            }
        }
    }
    
    echo "\nStep 4: Check Practical Model\n";
    echo "-------------------------------------\n";
    
    $practicalModel = new \App\Models\Practical();
    echo "Practical model table: " . $practicalModel->getTable() . "\n";
    
    // Check if there are any scopes or methods that use 'date'
    $reflection = new ReflectionClass($practicalModel);
    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
    
    foreach ($methods as $method) {
        $methodName = $method->getName();
        if (str_contains($methodName, 'date') || str_contains($methodName, 'scope')) {
            echo "Found method: {$methodName}\n";
        }
    }
    
    echo "\nStep 5: Check for Published Scope\n";
    echo "-------------------------------------\n";
    
    // The query mentions 'published_at' so let's check if there's a published scope
    if (in_array('published_at', $practicalsColumns)) {
        echo "✅ published_at column exists\n";
        
        // Check if there's a published scope
        try {
            $testQuery = \App\Models\Practical::whereNotNull('published_at');
            echo "✅ Basic published_at query works\n";
        } catch (\Exception $e) {
            echo "❌ published_at query failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ published_at column does not exist\n";
    }
    
    echo "\nStep 6: Identify the Correct Date Column\n";
    echo "-------------------------------------\n";
    
    // Let's check what the actual date column should be
    echo "Looking for the correct date column...\n";
    
    $possibleDateColumns = ['tanggal', 'start_date', 'end_date', 'created_at', 'updated_at', 'schedule_date', 'due_date'];
    
    foreach ($possibleDateColumns as $column) {
        if (in_array($column, $practicalsColumns)) {
            echo "✅ Found: {$column}\n";
        }
    }
    
    echo "\nStep 7: Test with Correct Column Name\n";
    echo "-------------------------------------\n";
    
    // Try to identify which column should be used for date filtering
    if (in_array('tanggal', $practicalsColumns)) {
        echo "Testing with 'tanggal' column...\n";
        
        try {
            $result = \Illuminate\Support\Facades\DB::table('practicals')
                ->whereNotNull('published_at')
                ->where('tanggal', '>', '2026-04-02 14:32:35')
                ->first();
            
            echo "✅ Query with 'tanggal' works\n";
        } catch (\Exception $e) {
            echo "❌ Query with 'tanggal' failed: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🔧 SOLUTION:\n";
    echo "=====================================\n";
    echo "The error indicates that somewhere in the code,\n";
    echo "there's a query trying to use 'date' column\n";
    echo "in the practicals table, but this column doesn't exist.\n\n";
    
    echo "Likely solutions:\n";
    echo "1. Find and replace 'date' with the correct column name\n";
    echo "2. Add a 'date' column to the practicals table\n";
    echo "3. Update the query to use an existing date column\n\n";
    
    echo "Need to identify the exact location and fix it.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
