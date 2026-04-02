<?php
echo "=== TESTING EXAM_SCHEDULES QUERY ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Test the exact query that was failing
    echo "Testing: SELECT * FROM exams WHERE is_published = 1 AND start_time > NOW() ORDER BY start_time ASC LIMIT 5\n";
    
    $start = microtime(true);
    $result = \Illuminate\Support\Facades\DB::table('exams')
        ->where('is_published', 1)
        ->where('start_time', '>', now())
        ->whereNull('deleted_at')
        ->orderBy('start_time', 'asc')
        ->limit(5)
        ->get();
    $end = microtime(true);
    
    echo "✅ Query SUCCESS\n";
    echo "⏱️  Query time: " . round(($end - $start) * 1000, 2) . " ms\n";
    echo "📊 Result: " . $result->count() . " records\n";
    
    // Test with Eloquent
    echo "\nTesting with Eloquent...\n";
    $start = microtime(true);
    $count = \App\Models\ExamSchedule::published()->upcoming()->count();
    $end = microtime(true);
    
    echo "✅ Eloquent Query SUCCESS\n";
    echo "⏱️  Eloquent time: " . round(($end - $start) * 1000, 2) . " ms\n";
    echo "📊 Eloquent Result: $count records\n";

} catch (Exception $e) {
    echo "❌ Query FAILED\n";
    echo "🔍 Error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
