<?php
echo "=== FIXING NOTIFICATION SYSTEM ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Fixing notification system...\n";
    
    // 1. Drop problematic tables
    echo "\n=== 1. CLEANING UP TABLES ===\n";
    
    try {
        \Illuminate\Support\Facades\Schema::dropIfExists('system_notifications');
        echo "✅ Dropped system_notifications\n";
    } catch (Exception $e) {
        echo "⚠️ Error dropping table: " . $e->getMessage() . "\n";
    }
    
    try {
        \Illuminate\Support\Facades\DB::statement("DROP TABLE IF EXISTS system_notifications");
        echo "✅ Force dropped system_notifications\n";
    } catch (Exception $e) {
        echo "⚠️ Error force dropping: " . $e->getMessage() . "\n";
    }
    
    // 2. Create proper table
    echo "\n=== 2. CREATING TABLE ===\n";
    
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
        
        echo "✅ System notifications table created\n";
    } catch (Exception $e) {
        echo "❌ Error creating table: " . $e->getMessage() . "\n";
    }
    
    // 3. Test functionality
    echo "\n=== 3. TESTING FUNCTIONALITY ===\n";
    
    try {
        $testData = [
            'user_id' => 1,
            'title' => 'Test Notification',
            'message' => 'System is working!',
            'type' => 'success',
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        $id = \Illuminate\Support\Facades\DB::table('system_notifications')->insertGetId($testData);
        echo "✅ Test notification created: ID $id\n";
        
        $notification = \Illuminate\Support\Facades\DB::table('system_notifications')->find($id);
        echo "✅ Notification read: {$notification->title}\n";
        
        \Illuminate\Support\Facades\DB::table('system_notifications')->delete($id);
        echo "✅ Test notification cleaned up\n";
        
        echo "\n🎉 NOTIFICATION SYSTEM IS WORKING!\n";
        
    } catch (Exception $e) {
        echo "❌ Test failed: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
