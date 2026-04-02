<?php
echo "=== TESTING MATA_PELAJARANS QUERY ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Test the exact query that was failing
    echo "Testing: SELECT COUNT(*) as aggregate FROM subjects WHERE deleted_at IS NULL\n";
    
    $start = microtime(true);
    $result = \Illuminate\Support\Facades\DB::select("SELECT COUNT(*) as aggregate FROM `subjects` WHERE `subjects`.`deleted_at` is null");
    $end = microtime(true);
    
    echo "✅ Query SUCCESS\n";
    echo "⏱️  Query time: " . round(($end - $start) * 1000, 2) . " ms\n";
    echo "📊 Result: " . $result[0]->aggregate . " records\n";
    
    // Test with Eloquent
    echo "\nTesting with Eloquent...\n";
    $start = microtime(true);
    $count = \App\Models\MataPelajaran::count();
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
