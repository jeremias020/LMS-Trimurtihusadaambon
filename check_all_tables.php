<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECKING ALL TABLES ===\n\n";

try {
    echo "Available tables:\n";
    $tables = \Schema::getTableListing();
    foreach ($tables as $table) {
        echo "  - {$table}\n";
    }
    
    echo "\nChecking for class-related tables:\n";
    foreach ($tables as $table) {
        if (strpos($table, 'class') !== false) {
            echo "  Found: {$table}\n";
            $columns = \Schema::getColumnListing($table);
            echo "    Columns: " . implode(', ', $columns) . "\n";
        }
    }
    
    echo "\nChecking for subject-related tables:\n";
    foreach ($tables as $table) {
        if (strpos($table, 'subject') !== false) {
            echo "  Found: {$table}\n";
            $columns = \Schema::getColumnListing($table);
            echo "    Columns: " . implode(', ', $columns) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
