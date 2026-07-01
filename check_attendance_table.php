<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 CHECK ATTENDANCE TABLE STRUCTURE\n";
echo "=====================================\n";

try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('attendances');
    
    echo "Columns in attendances table:\n";
    foreach ($columns as $column) {
        echo "  - {$column}\n";
    }
    
    // Check column types
    echo "\nColumn details:\n";
    $columnDetails = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM attendances");
    foreach ($columnDetails as $column) {
        echo "  {$column->Field}: {$column->Type} (Null: {$column->Null}, Default: " . ($column->Default ?: 'NULL') . ")\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
