<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 NOTIFICATIONS TABLE ANALYSIS\n";
echo "=====================================\n";

try {
    echo "Step 1: Check notifications table structure\n";
    echo "-------------------------------------\n";
    
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('notifications');
    echo "notifications table columns:\n";
    foreach ($columns as $column) {
        echo "  - {$column}\n";
    }
    
    echo "\nStep 2: Look for user-related columns\n";
    echo "-------------------------------------\n";
    
    $userColumns = [];
    foreach ($columns as $column) {
        if (str_contains($column, 'user') || str_contains($column, 'notifiable')) {
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
    
    echo "\nStep 3: Test different column names\n";
    echo "-------------------------------------\n";
    
    // Test if there are any records
    try {
        $count = \Illuminate\Support\Facades\DB::table('notifications')->count();
        echo "Total records: {$count}\n";
        
        if ($count > 0) {
            $record = \Illuminate\Support\Facades\DB::table('notifications')->first();
            echo "Sample record:\n";
            foreach ($record as $key => $value) {
                echo "  {$key}: {$value}\n";
            }
        }
    } catch (\Exception $e) {
        echo "❌ Failed to query table: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 4: Test with notifiable_id\n";
    echo "-------------------------------------\n";
    
    if (in_array('notifiable_id', $columns)) {
        echo "✅ Found notifiable_id column\n";
        
        try {
            $notifications = \Illuminate\Support\Facades\DB::table('notifications')
                ->where('notifiable_id', 1)
                ->get();
            echo "✅ Query with notifiable_id works: " . $notifications->count() . " notifications\n";
        } catch (\Exception $e) {
            echo "❌ Query with notifiable_id failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ notifiable_id column not found\n";
    }
    
    echo "\n🎯 ANALYSIS:\n";
    echo "=====================================\n";
    
    if (in_array('user_id', $columns)) {
        echo "✅ user_id column exists in notifications table\n";
        echo "✅ NotificationController should work\n";
    } else {
        echo "❌ user_id column does not exist in notifications table\n";
        echo "❌ NotificationController needs to use different column\n";
        
        if (in_array('notifiable_id', $columns)) {
            echo "✅ Should use notifiable_id instead of user_id\n";
        }
    }
    
    echo "\n✨ ANALYSIS COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
