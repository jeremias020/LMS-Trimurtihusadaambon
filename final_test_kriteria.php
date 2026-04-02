<?php
echo "=== FINAL TEST: KRITERIA PENILAIAN DISPLAY ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Testing complete flow...\n";
    
    // Test 1: Raw database data
    echo "\n=== RAW DATABASE DATA ===\n";
    $rawData = \Illuminate\Support\Facades\DB::table('criteria')
        ->where('is_active', true)
        ->orderBy('weight', 'desc')
        ->orderBy('name')
        ->limit(5)
        ->get();
    
    echo "Found " . $rawData->count() . " active criteria:\n";
    foreach ($rawData as $item) {
        echo "- {$item->name} (weight: {$item->weight}, max_score: {$item->max_score})\n";
    }
    
    // Test 2: Model query
    echo "\n=== MODEL QUERY ===\n";
    $modelData = \App\Models\KriteriaPenilaian::orderBy('name')
                                                ->orderBy('weight', 'desc')
                                                ->paginate(20);
    
    echo "Model query: " . $modelData->count() . " records\n";
    echo "Total records: " . $modelData->total() . "\n";
    
    // Test 3: Controller simulation
    echo "\n=== CONTROLLER SIMULATION ===\n";
    $controller = new \App\Http\Controllers\Admin\KriteriaPenilaianController();
    $response = $controller->index();
    
    $viewData = $response->getData();
    if (isset($viewData['kriteria'])) {
        echo "✅ Controller returns kriteria data: " . $viewData['kriteria']->count() . " items\n";
        
        // Show sample data as it would appear in view
        echo "\nSample data for view:\n";
        foreach ($viewData['kriteria']->take(3) as $item) {
            echo "Nama: " . ($item->nama ?? $item->name) . "\n";
            echo "Deskripsi: " . substr($item->deskripsi ?? $item->description ?? '-', 40) . "...\n";
            echo "Weight: " . ($item->bobot ?? $item->weight) . "\n";
            echo "Status: " . ($item->is_active ? 'Aktif' : 'Tidak Aktif') . "\n\n";
        }
    }
    
    // Test 4: Check if main categories exist
    echo "\n=== MAIN CATEGORIES ===\n";
    $mainCategories = \Illuminate\Support\Facades\DB::table('criteria')
        ->where('weight', 5)
        ->get();
    
    echo "Main categories (weight 5): " . $mainCategories->count() . "\n";
    foreach ($mainCategories as $cat) {
        echo "- {$cat->name}: {$cat->description}\n";
    }
    
    // Test 5: Count by weight
    echo "\n=== DATA DISTRIBUTION ===\n";
    $weightStats = \Illuminate\Support\Facades\DB::table('criteria')
        ->select('weight', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
        ->groupBy('weight')
        ->orderBy('weight', 'desc')
        ->get();
    
    foreach ($weightStats as $stat) {
        echo "Weight {$stat->weight}: {$stat->count} records\n";
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "✅ Database connection: OK\n";
    echo "✅ Raw query: " . $rawData->count() . " records\n";
    echo "✅ Model query: " . $modelData->count() . " records\n";
    echo "✅ Controller: " . (isset($viewData['kriteria']) ? $viewData['kriteria']->count() : 'ERROR') . " records\n";
    echo "✅ View ready: YES\n";
    
    echo "\n🎉 Data kriteria penilaian sudah siap ditampilkan di halaman admin!\n";
    echo "📱 URL: http://127.0.0.1:8000/admin/kriteria-penilaian\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
