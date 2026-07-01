<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 FIND CORRECT NOTIFICATION TABLE\n";
echo "=====================================\n";

try {
    echo "Step 1: List all notification-related tables\n";
    echo "-------------------------------------\n";
    
    $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES LIKE "%notification%"');
    
    if (empty($tables)) {
        echo "❌ No notification tables found\n";
    } else {
        echo "Found notification tables:\n";
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            echo "  - {$tableName}\n";
        }
    }
    
    echo "\nStep 2: Check SystemNotification model table\n";
    echo "-------------------------------------\n";
    
    $model = new \App\Models\SystemNotification();
    echo "SystemNotification model table: " . $model->getTable() . "\n";
    
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing($model->getTable());
    echo "Table columns:\n";
    foreach ($columns as $column) {
        echo "  - {$column}\n";
    }
    
    if (in_array('user_id', $columns)) {
        echo "✅ user_id column exists\n";
        
        echo "\nStep 3: Test SystemNotification queries\n";
        echo "-------------------------------------\n";
        
        try {
            $notifications = \App\Models\SystemNotification::where('user_id', 1)->get();
            echo "✅ SystemNotification::where('user_id', 1) works: " . $notifications->count() . " notifications\n";
        } catch (\Exception $e) {
            echo "❌ SystemNotification::where('user_id', 1) failed: " . $e->getMessage() . "\n";
        }
        
    } else {
        echo "❌ user_id column does not exist\n";
        
        // Look for alternative columns
        echo "\nLooking for user-related columns:\n";
        foreach ($columns as $column) {
            if (str_contains($column, 'user') || str_contains($column, 'siswa') || str_contains($column, 'guru')) {
                echo "  - {$column}\n";
            }
        }
    }
    
    echo "\n✨ ANALYSIS COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
