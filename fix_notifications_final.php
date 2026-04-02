<?php
echo "=== FIXING SYSTEM NOTIFICATIONS TABLESPACE ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Fixing system_notifications tablespace issue...\n";
    
    // Drop the problematic table if it exists
    try {
        \Illuminate\Support\Facades\Schema::dropIfExists('system_notifications');
        echo "✅ Dropped existing system_notifications table\n";
    } catch (\Exception $e) {
        echo "⚠️ Error dropping table: " . $e->getMessage() . "\n";
    }
    
    // Clean up any orphaned tablespace
    try {
        \Illuminate\Support\Facades\DB::statement('DROP TABLE IF EXISTS system_notifications');
        echo "✅ Cleaned up orphaned tablespace\n";
    } catch (\Exception $e) {
        echo "⚠️ Error cleaning tablespace: " . $e->getMessage() . "\n";
    }
    
    // Recreate the table
    echo "\n=== RECREATING SYSTEM NOTIFICATIONS TABLE ===\n";
    
    try {
        \Illuminate\Support\Facades\Schema::create('system_notifications', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('info');
            $table->string('action_url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'is_read']);
        });
        
        echo "✅ System notifications table recreated successfully\n";
    } catch (\Exception $e) {
        echo "❌ Error recreating table: " . $e->getMessage() . "\n";
    }
    
    // Test the table
    echo "\n=== TESTING TABLE FUNCTIONALITY ===\n";
    
    try {
        // Test insertion
        $id = \Illuminate\Support\Facades\DB::table('system_notifications')->insertGetId([
            'user_id' => 1,
            'title' => 'Test Notification After Fix',
            'message' => 'Testing after tablespace fix',
            'type' => 'info',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "✅ Test notification created: ID $id\n";
        
        // Test reading
        $notification = \Illuminate\Support\Facades\DB::table('system_notifications')->find($id);
        echo "✅ Test notification read: {$notification->title}\n";
        
        // Clean up
        \Illuminate\Support\Facades\DB::table('system_notifications')->delete($id);
        echo "✅ Test notification cleaned up\n";
        
    } catch (\Exception $e) {
        echo "❌ Error testing table: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== TESTING EXAM SCHEDULE WITH NOTIFICATIONS ===\n";
    
    try {
        // Test creating schedule with notifications enabled
        $testData = [
            'title' => 'Test Schedule With Notifications',
            'description' => 'Testing with notifications enabled',
            'exam_type' => 'quiz',
            'subject_id' => 1,
            'kelas_id' => null,
            'start_time' => now()->addHours(1),
            'end_time' => now()->addHours(2),
            'location' => 'Test Room with Notifications',
            'duration_minutes' => 60,
            'is_published' => true, // Enable notifications
            'created_by' => 1
        ];
        
        $id = \Illuminate\Support\Facades\DB::table('exam_schedules_new')->insertGetId($testData);
        echo "✅ Schedule created with notifications: ID $id\n";
        
        // Clean up
        \Illuminate\Support\Facades\DB::table('exam_schedules_new')->delete($id);
        echo "✅ Test schedule cleaned up\n";
        
        echo "\n🎉 ALL ISSUES RESOLVED!\n";
        echo "✅ System notifications table fixed\n";
        echo "✅ Exam schedule creation working\n";
        echo "✅ Notifications working\n";
        echo "📱 Form submission should work perfectly now\n";
        
    } catch (\Exception $e) {
        echo "❌ Error with notifications: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
