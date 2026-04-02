<?php
echo "=== TESTING KRITERIA PENILAIAN DISPLAY ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Testing kriteria penilaian data...\n";
    
    // Get all criteria
    $allCriteria = \Illuminate\Support\Facades\DB::table('criteria')
        ->where('is_active', true)
        ->orderBy('name')
        ->get();
    
    echo "Total active criteria: " . $allCriteria->count() . "\n\n";
    
    // Group by main categories
    $mainCategories = $allCriteria->where('weight', 10);
    $subCriteria = $allCriteria->where('weight', '<', 10);
    
    echo "=== MAIN CATEGORIES ===\n";
    foreach ($mainCategories as $category) {
        echo "📋 {$category->name}\n";
        echo "   Description: {$category->description}\n";
        echo "   Weight: {$category->weight}, Max Score: {$category->max_score}\n\n";
    }
    
    echo "=== SAMPLE SUB-CRITERIA ===\n";
    $sampleSubCriteria = $subCriteria->take(10);
    foreach ($sampleSubCriteria as $criteria) {
        echo "✓ {$criteria->name}\n";
        echo "   Weight: {$criteria->weight}, Max Score: {$criteria->max_score}\n";
        if ($criteria->description) {
            echo "   Description: " . substr($criteria->description, 0, 60) . "...\n";
        }
        echo "\n";
    }
    
    echo "=== STATISTICS ===\n";
    echo "Main categories: " . $mainCategories->count() . "\n";
    echo "Sub-criteria: " . $subCriteria->count() . "\n";
    echo "Total: " . $allCriteria->count() . "\n";
    
    // Test if controller can access the data
    echo "\n=== CONTROLLER SIMULATION ===\n";
    try {
        // Simulate what the controller would do
        $kriteriaPenilaians = \Illuminate\Support\Facades\DB::table('criteria')
            ->where('deleted_at', null)
            ->orderBy('name')
            ->get();
        
        echo "✅ Controller query successful: " . $kriteriaPenilaians->count() . " records\n";
        
        // Show first few records as they would appear in view
        echo "\nHow it would appear in index.blade.php:\n";
        foreach ($kriteriaPenilaians->take(5) as $kriteria) {
            echo "- {$kriteria->name} (Weight: {$kriteria->weight}, Max: {$kriteria->max_score})\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Controller simulation failed: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
