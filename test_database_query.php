<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DATABASE QUERY ===" . PHP_EOL;

try {
    // Test basic query
    echo "Testing query on nilai_praktik_new table..." . PHP_EOL;
    
    $results = DB::table('nilai_praktik_new')
        ->select('*')
        ->limit(5)
        ->get();
    
    echo "✅ Query successful! Found " . $results->count() . " records" . PHP_EOL;
    
    if ($results->count() > 0) {
        echo PHP_EOL . "Sample records:" . PHP_EOL;
        foreach ($results as $result) {
            echo "- ID: " . $result->id . ", Siswa: " . $result->siswa_id . ", Nilai: " . $result->total_nilai . PHP_EOL;
        }
    }
    
    // Test with guru_id filter
    echo PHP_EOL . "Testing query with guru_id filter..." . PHP_EOL;
    
    $guruResults = DB::table('nilai_praktik_new')
        ->where('guru_id', 118)
        ->orderBy('tanggal_praktik', 'desc')
        ->limit(3)
        ->get();
    
    echo "✅ Query with guru_id successful! Found " . $guruResults->count() . " records for guru_id 118" . PHP_EOL;
    
    // Test model
    echo PHP_EOL . "Testing NilaiPraktik model..." . PHP_EOL;
    
    $modelResults = \App\Models\NilaiPraktik::where('guru_id', 118)
        ->orderBy('tanggal_praktik', 'desc')
        ->limit(3)
        ->get();
    
    echo "✅ Model query successful! Found " . $modelResults->count() . " records" . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
