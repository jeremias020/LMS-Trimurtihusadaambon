<?php
echo "=== TESTING KRITERIA PENILAIAN DISPLAY ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Testing KriteriaPenilaian model...\n";
    
    try {
        // Test the model query that controller uses
        $kriteria = \App\Models\KriteriaPenilaian::orderBy('name')
                                                ->orderBy('weight', 'desc')
                                                ->paginate(20);
        
        echo "✅ Model query successful: " . $kriteria->count() . " records\n";
        
        // Show first few records with field aliases
        echo "\nFirst 5 records:\n";
        foreach ($kriteria->take(5) as $k) {
            echo "- ID: {$k->id}\n";
            echo "  Nama: " . ($k->nama ?? $k->name) . "\n";
            echo "  Deskripsi: " . substr($k->deskripsi ?? $k->description ?? 'NULL', 0, 50) . "...\n";
            echo "  Bobot: " . ($k->bobot ?? $k->weight) . "\n";
            echo "  Max Score: " . ($k->max_score ?? 'NULL') . "\n";
            echo "  Status: " . ($k->status ?? $k->is_active ? 'Aktif' : 'Tidak Aktif') . "\n\n";
        }
        
        echo "=== TESTING CONTROLLER INDEX METHOD ===\n";
        
        // Test controller index method
        $controller = new \App\Http\Controllers\Admin\KriteriaPenilaianController();
        $response = $controller->index();
        
        echo "✅ Controller index method executed successfully\n";
        
        // Check if view data contains kriteria
        $viewData = $response->getData();
        if (isset($viewData['kriteria'])) {
            echo "✅ View has 'kriteria' data: " . $viewData['kriteria']->count() . " items\n";
        } else {
            echo "❌ View missing 'kriteria' data\n";
        }
        
        if (isset($viewData['error'])) {
            echo "⚠️ View has error message: " . $viewData['error'] . "\n";
        } else {
            echo "✅ No error messages\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Model/Controller test failed: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
    
    echo "\n=== TESTING RAW DATABASE QUERY ===\n";
    
    // Test raw query
    $rawData = \Illuminate\Support\Facades\DB::table('criteria')
        ->where('is_active', true)
        ->orderBy('name')
        ->limit(5)
        ->get();
    
    echo "Raw query results: " . $rawData->count() . " records\n";
    foreach ($rawData as $record) {
        echo "- {$record->name} (weight: {$record->weight})\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
