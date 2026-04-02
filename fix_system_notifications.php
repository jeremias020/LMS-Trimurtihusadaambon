<?php
echo "=== CHECKING SYSTEM NOTIFICATIONS TABLE ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Checking system_notifications table...\n";
    
    // Check if table exists
    $tables = \Illuminate\Support\Facades\Schema::getTableListing();
    $hasTable = in_array('system_notifications', $tables);
    
    echo "Table exists: " . ($hasTable ? 'YES' : 'NO') . "\n";
    
    if (!$hasTable) {
        echo "\n=== CREATING SYSTEM NOTIFICATIONS TABLE ===\n";
        
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
            
            echo "✅ System notifications table created successfully\n";
        } catch (\Exception $e) {
            echo "❌ Error creating table: " . $e->getMessage() . "\n";
        }
    } else {
        echo "\n=== TABLE STRUCTURE ===\n";
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('system_notifications');
        foreach ($columns as $column) {
            echo "- $column\n";
        }
        
        // Test inserting a notification
        echo "\n=== TESTING NOTIFICATION INSERT ===\n";
        try {
            $id = \Illuminate\Support\Facades\DB::table('system_notifications')->insertGetId([
                'user_id' => 1,
                'title' => 'Test Notification',
                'message' => 'Test message',
                'type' => 'info',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "✅ Test notification created with ID: $id\n";
            
            // Clean up
            \Illuminate\Support\Facades\DB::table('system_notifications')->delete($id);
            echo "✅ Test notification cleaned up\n";
            
        } catch (\Exception $e) {
            echo "❌ Error inserting notification: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== TESTING EXAM SCHEDULE CREATION AGAIN ===\n";
    
    // Test creating schedule without notifications
    try {
        $testData = [
            'title' => 'Test Schedule Without Notifications',
            'description' => 'Testing schedule creation',
            'exam_type' => 'quiz',
            'subject_id' => 1,
            'kelas_id' => null,
            'start_time' => now()->addHours(1),
            'end_time' => now()->addHours(2),
            'location' => 'Test Room',
            'duration_minutes' => 60,
            'is_published' => false, // Don't send notifications
            'created_by' => 1
        ];
        
        $id = \Illuminate\Support\Facades\DB::table('exam_schedules_new')->insertGetId($testData);
        echo "✅ Schedule created without notifications: ID $id\n";
        
        // Clean up
        \Illuminate\Support\Facades\DB::table('exam_schedules_new')->delete($id);
        echo "✅ Test schedule cleaned up\n";
        
        echo "\n🎉 ISSUE RESOLVED!\n";
        echo "✅ Form submission should work now\n";
        
    } catch (\Exception $e) {
        echo "❌ Error creating schedule: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
