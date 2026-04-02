<?php
echo "=== COMPREHENSIVE NOTIFICATION SYSTEM CHECK ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Checking notification system status...\n";
    
    // 1. Check notification tables
    echo "\n=== 1. NOTIFICATION TABLES CHECK ===\n";
    
    $tables = \Illuminate\Support\Facades\Schema::getTableListing();
    $notificationTables = array_filter($tables, function($table) {
        return strpos($table, 'notification') !== false;
    });
    
    echo "Found notification tables:\n";
    foreach ($notificationTables as $table) {
        echo "- $table\n";
        
        // Check table structure
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
        echo "  Columns: " . implode(', ', $columns) . "\n";
        
        // Check record count
        try {
            $count = \Illuminate\Support\Facades\DB::table($table)->count();
            echo "  Records: $count\n";
        } catch (\Exception $e) {
            echo "  Error counting records: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
    
    // 2. Check notification models
    echo "=== 2. NOTIFICATION MODELS CHECK ===\n";
    
    $modelFiles = [
        'SystemNotification' => 'app/Models/SystemNotification.php',
        'ScheduledNotification' => 'app/Models/ScheduledNotification.php',
        'Notification' => 'app/Models/Notification.php'
    ];
    
    foreach ($modelFiles as $modelName => $filePath) {
        if (file_exists($filePath)) {
            echo "✅ $modelName model exists\n";
            
            // Check model structure
            $modelClass = "App\\Models\\{$modelName}";
            if (class_exists($modelClass)) {
                $model = new $modelClass();
                echo "  - Table: " . $model->getTable() . "\n";
                echo "  - Fillable: " . implode(', ', $model->getFillable()) . "\n";
            }
        } else {
            echo "❌ $modelName model missing\n";
        }
        echo "\n";
    }
    
    // 3. Test notification functionality
    echo "=== 3. NOTIFICATION FUNCTIONALITY TEST ===\n";
    
    // Test SystemNotification
    if (in_array('system_notifications', $notificationTables)) {
        echo "Testing SystemNotification...\n";
        try {
            $testNotification = [
                'user_id' => 1,
                'title' => 'Test Notification',
                'message' => 'This is a test notification',
                'type' => 'info',
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            $id = \Illuminate\Support\Facades\DB::table('system_notifications')->insertGetId($testNotification);
            echo "✅ SystemNotification created: ID $id\n";
            
            // Test reading
            $notification = \Illuminate\Support\Facades\DB::table('system_notifications')->find($id);
            echo "✅ SystemNotification read: {$notification->title}\n";
            
            // Clean up
            \Illuminate\Support\Facades\DB::table('system_notifications')->delete($id);
            echo "✅ Test notification cleaned up\n";
            
        } catch (\Exception $e) {
            echo "❌ SystemNotification test failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ SystemNotification table not available\n";
    }
    
    // 4. Check notification in controllers
    echo "\n=== 4. CONTROLLER NOTIFICATION USAGE ===\n";
    
    $controllerFiles = [
        'ExamScheduleController' => 'app/Http/Controllers/Admin/ExamScheduleController.php',
        'UserController' => 'app/Http/Controllers/Admin/UserController.php'
    ];
    
    foreach ($controllerFiles as $controllerName => $filePath) {
        if (file_exists($filePath)) {
            echo "Checking $controllerName...\n";
            $content = file_get_contents($filePath);
            
            if (strpos($content, 'SystemNotification') !== false) {
                echo "✅ Uses SystemNotification\n";
            }
            
            if (strpos($content, 'sendExamNotifications') !== false) {
                echo "✅ Has notification method\n";
            }
            
            if (strpos($content, 'notification') !== false) {
                echo "✅ Contains notification logic\n";
            }
        }
        echo "\n";
    }
    
    // 5. Check user notification display
    echo "=== 5. USER NOTIFICATION DISPLAY ===\n";
    
    // Check if users have notifications
    try {
        $users = \App\Models\User::limit(5)->get();
        foreach ($users as $user) {
            echo "User: {$user->name} ({$user->email})\n";
            
            // Check system notifications
            if (in_array('system_notifications', $notificationTables)) {
                $notifCount = \Illuminate\Support\Facades\DB::table('system_notifications')
                    ->where('user_id', $user->id)
                    ->where('is_read', false)
                    ->count();
                echo "  - Unread notifications: $notifCount\n";
            }
        }
    } catch (\Exception $e) {
        echo "❌ Error checking user notifications: " . $e->getMessage() . "\n";
    }
    
    // 6. Check views for notification display
    echo "\n=== 6. VIEW NOTIFICATION DISPLAY ===\n";
    
    $viewPaths = [
        'layouts/admin' => 'resources/views/layouts/admin.blade.php',
        'admin/dashboard' => 'resources/views/admin/dashboard.blade.php'
    ];
    
    foreach ($viewPaths as $viewName => $filePath) {
        if (file_exists($filePath)) {
            echo "Checking $viewName view...\n";
            $content = file_get_contents($filePath);
            
            if (strpos($content, 'notification') !== false) {
                echo "✅ Contains notification display logic\n";
            }
            
            if (strpos($content, 'SystemNotification') !== false) {
                echo "✅ Uses SystemNotification model\n";
            }
        }
        echo "\n";
    }
    
    // 7. Summary and recommendations
    echo "=== 7. NOTIFICATION SYSTEM SUMMARY ===\n";
    
    $issues = [];
    $working = [];
    
    // Check tables
    if (!in_array('system_notifications', $notificationTables)) {
        $issues[] = "System notifications table missing";
    } else {
        $working[] = "System notifications table exists";
    }
    
    // Check models
    if (!class_exists('App\Models\SystemNotification')) {
        $issues[] = "SystemNotification model missing";
    } else {
        $working[] = "SystemNotification model available";
    }
    
    // Check functionality
    try {
        \Illuminate\Support\Facades\DB::table('system_notifications')->count();
        $working[] = "System notifications functional";
    } catch (\Exception $e) {
        $issues[] = "System notifications not functional: " . $e->getMessage();
    }
    
    echo "✅ Working Components:\n";
    foreach ($working as $item) {
        echo "  - $item\n";
    }
    
    if (!empty($issues)) {
        echo "\n❌ Issues Found:\n";
        foreach ($issues as $issue) {
            echo "  - $issue\n";
        }
    }
    
    echo "\n=== RECOMMENDATIONS ===\n";
    
    if (!in_array('system_notifications', $notificationTables)) {
        echo "1. Create system_notifications table\n";
        echo "2. Run migration for notifications\n";
    }
    
    if (!empty($issues)) {
        echo "3. Fix tablespace issues for notifications\n";
        echo "4. Test notification functionality\n";
    } else {
        echo "✅ Notification system is working properly\n";
        echo "✅ All components functional\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
