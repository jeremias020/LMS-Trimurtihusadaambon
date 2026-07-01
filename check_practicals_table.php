<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 CHECKING PRACTICALS TABLE STRUCTURE\n";
echo "=====================================\n";

try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('practicals');
    echo "Columns in practicals table:\n";
    foreach ($columns as $column) {
        echo "  - {$column}\n";
    }
    
    echo "\nChecking for title column:\n";
    $hasTitle = in_array('title', $columns);
    echo "  " . ($hasTitle ? "✅" : "❌") . " title column: " . ($hasTitle ? "Exists" : "Missing") . "\n";
    
    echo "\nChecking for judul column:\n";
    $hasJudul = in_array('judul', $columns);
    echo "  " . ($hasJudul ? "✅" : "❌") . " judul column: " . ($hasJudul ? "Exists" : "Missing") . "\n";
    
    // Check if there are any practical records
    $practicals = \Illuminate\Support\Facades\DB::table('practicals')->limit(1)->get();
    echo "\nSample practical record:\n";
    if ($practicals->count() > 0) {
        $practical = $practicals->first();
        foreach ((array)$practical as $key => $value) {
            echo "  {$key}: {$value}\n";
        }
    } else {
        echo "  No records found\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n✅ Practical table check complete\n";
?>
