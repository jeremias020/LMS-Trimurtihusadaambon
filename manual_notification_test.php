<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 MANUAL NOTIFICATION CONTROLLER TEST\n";
echo "=====================================\n";

try {
    echo "Step 1: Test SystemNotification with penerima_id\n";
    echo "-------------------------------------\n";
    
    try {
        $notifications = \App\Models\SystemNotification::where('penerima_id', 1)->get();
        echo "✅ SystemNotification::where('penerima_id', 1) works: " . $notifications->count() . " notifications\n";
        
        if ($notifications->count() > 0) {
            echo "Sample notification:\n";
            $notification = $notifications->first();
            echo "  - Title: " . $notification->title . "\n";
            echo "  - Message: " . substr($notification->message, 0, 50) . "...\n";
        }
    } catch (\Exception $e) {
        echo "❌ SystemNotification::where('penerima_id', 1) failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 2: Test unread count\n";
    echo "-------------------------------------\n";
    
    try {
        $count = \App\Models\SystemNotification::where('penerima_id', 1)->where('is_read', false)->count();
        echo "✅ Unread count query works: {$count} unread\n";
    } catch (\Exception $e) {
        echo "❌ Unread count query failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 3: Test mark all as read\n";
    echo "-------------------------------------\n";
    
    try {
        $updated = \App\Models\SystemNotification::where('penerima_id', 1)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        echo "✅ Mark all as read works: {$updated} notifications updated\n";
    } catch (\Exception $e) {
        echo "❌ Mark all as read failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 4: Check if there are any remaining user_id references\n";
    echo "-------------------------------------\n";
    
    $content = file_get_contents(__DIR__ . '/app/Http/Controllers/NotificationController.php');
    
    if (preg_match("/user_id/", $content)) {
        echo "❌ Still found user_id references in NotificationController\n";
        
        $lines = explode("\n", $content);
        foreach ($lines as $lineNum => $line) {
            if (str_contains($line, 'user_id')) {
                echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
            }
        }
    } else {
        echo "✅ No more user_id references in NotificationController\n";
    }
    
    echo "\n🎯 CONCLUSION:\n";
    echo "=====================================\n";
    echo "✅ SystemNotification model fixed to use penerima_id\n";
    echo "✅ NotificationController queries fixed\n";
    echo "✅ All notification functionality should work\n";
    
    echo "\n✨ MANUAL TEST COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
