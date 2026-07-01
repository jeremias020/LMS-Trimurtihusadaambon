<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 CHECK STUDENTS TABLE\n";
echo "=====================================\n";

try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('students');
    
    echo "Columns in students table:\n";
    foreach ($columns as $column) {
        echo "  - {$column}\n";
    }
    
    // Check column details
    echo "\nColumn details:\n";
    $columnDetails = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM students");
    foreach ($columnDetails as $column) {
        echo "  {$column->Field}: {$column->Type} (Null: {$column->Null}, Default: " . ($column->Default ?: 'NULL') . ")\n";
    }
    
    // Check if there's any data
    $count = \Illuminate\Support\Facades\DB::table('students')->count();
    echo "\nTotal records in students: {$count}\n";
    
    if ($count > 0) {
        $sample = \Illuminate\Support\Facades\DB::table('students')->first();
        echo "Sample record:\n";
        foreach ($sample as $key => $value) {
            echo "  {$key}: {$value}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
