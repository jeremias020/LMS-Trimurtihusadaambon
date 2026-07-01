<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 SYSTEM NOTIFICATION TABLE ANALYSIS\n";
echo "=====================================\n";

try {
    echo "Step 1: Check all columns in system_notifications_new\n";
    echo "-------------------------------------\n";
    
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('system_notifications_new');
    echo "All columns:\n";
    foreach ($columns as $column) {
        echo "  - {$column}\n";
    }
    
    echo "\nStep 2: Look for user-related columns\n";
    echo "-------------------------------------\n";
    
    $userColumns = [];
    foreach ($columns as $column) {
        if (str_contains($column, 'user') || str_contains($column, 'siswa') || str_contains($column, 'guru')) {
            $userColumns[] = $column;
        }
    }
    
    if (!empty($userColumns)) {
        echo "Found user-related columns:\n";
        foreach ($userColumns as $column) {
            echo "  - {$column}\n";
        }
    } else {
        echo "❌ No user-related columns found\n";
    }
    
    echo "\nStep 3: Check if there's an ID column\n";
    echo "-------------------------------------\n";
    
    if (in_array('id', $columns)) {
        echo "✅ Found 'id' column\n";
    } else {
        echo "❌ No 'id' column found\n";
    }
    
    echo "\nStep 4: Test different column names\n";
    echo "-------------------------------------\n";
    
    // Test if there are any records
    try {
        $count = \Illuminate\Support\Facades\DB::table('system_notifications_new')->count();
        echo "Total records: {$count}\n";
        
        if ($count > 0) {
            $record = \Illuminate\Support\Facades\DB::table('system_notifications_new')->first();
            echo "Sample record:\n";
            foreach ($record as $key => $value) {
                echo "  {$key}: {$value}\n";
            }
        }
    } catch (\Exception $e) {
        echo "❌ Failed to query table: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎯 ANALYSIS:\n";
    echo "=====================================\n";
    
    if (in_array('user_id', $columns)) {
        echo "✅ user_id column exists - NotificationController is correct\n";
    } else {
        echo "❌ user_id column does not exist\n";
        echo "❌ NotificationController needs to be fixed\n";
        
        if (in_array('id', $columns)) {
            echo "✅ Should use 'id' column instead of 'user_id'\n";
        } else {
            echo "❌ No suitable ID column found\n";
        }
    }
    
    echo "\n✨ ANALYSIS COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
