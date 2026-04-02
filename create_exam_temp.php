<?php
echo "=== CREATING TEMP EXAM_SCHEDULES ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Create table with temporary name
    echo "Creating exam_schedules_new table...\n";
    
    \Illuminate\Support\Facades\Schema::create('exam_schedules_new', function ($table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->enum('exam_type', ['uts', 'uas', 'quiz', 'praktikum', 'lainnya']);
        $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
        $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('cascade');
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        $table->datetime('start_time');
        $table->datetime('end_time');
        $table->string('location')->nullable();
        $table->integer('duration_minutes')->default(60);
        $table->boolean('is_published')->default(false);
        $table->timestamps();
        $table->softDeletes();
        
        $table->index(['start_time', 'end_time']);
        $table->index(['kelas_id', 'is_published']);
    });
    
    echo "✅ exam_schedules_new table created successfully\n";
    
    // Rename to original name
    echo "Renaming to exam_schedules...\n";
    \Illuminate\Support\Facades\Schema::rename('exam_schedules_new', 'exam_schedules');
    echo "✅ Renamed successfully\n";
    
    // Test the table
    echo "\nTesting exam_schedules table...\n";
    $count = \Illuminate\Support\Facades\DB::table('exam_schedules')->count();
    echo "📊 Current records: $count\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
