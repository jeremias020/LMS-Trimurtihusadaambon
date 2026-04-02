<?php
echo "=== MEMPERBAIKI WEIGHT KRITERIA UTAMA ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Perbaiki weight untuk Pemasangan Infus
    \Illuminate\Support\Facades\DB::table('criteria')
        ->where('name', 'Pemasangan Infus')
        ->update(['weight' => 10]);
    
    echo "✅ Weight untuk 'Pemasangan Infus' diperbaiki menjadi 10\n";
    
    // Perbaiki weight untuk Pemeriksaan Golongan Darah
    \Illuminate\Support\Facades\DB::table('criteria')
        ->where('name', 'Pemeriksaan Golongan Darah')
        ->update(['weight' => 10]);
    
    echo "✅ Weight untuk 'Pemeriksaan Golongan Darah' diperbaiki menjadi 10\n";
    
    echo "\n=== VERIFICATION ===\n";
    
    // Tampilkan data utama
    $mainCriteria = \Illuminate\Support\Facades\DB::table('criteria')
        ->whereIn('name', ['Pemasangan Infus', 'Pemeriksaan Golongan Darah'])
        ->get();
    
    foreach ($mainCriteria as $criteria) {
        echo "- {$criteria->name}: weight={$criteria->weight}, max_score={$criteria->max_score}\n";
    }
    
    // Tampilkan beberapa data detail
    echo "\nSample detail criteria:\n";
    $sampleCriteria = \Illuminate\Support\Facades\DB::table('criteria')
        ->where('name', 'like', '%Mengecek Catatan%')
        ->orWhere('name', 'like', '%Aliran dan tetesan%')
        ->orWhere('name', 'like', '%Golongan darah A%')
        ->limit(3)
        ->get();
    
    foreach ($sampleCriteria as $criteria) {
        echo "- {$criteria->name}: weight={$criteria->weight}, max_score={$criteria->max_score}\n";
    }
    
    echo "\nTotal kriteria: " . \Illuminate\Support\Facades\DB::table('criteria')->count() . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
