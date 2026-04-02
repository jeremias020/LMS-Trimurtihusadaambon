<?php
echo "=== CHECKING WEIGHT COLUMN TYPE ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Check column types
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('criteria');
    echo "Columns in criteria table:\n";
    foreach ($columns as $col) {
        echo "- $col\n";
    }
    
    echo "\n=== TESTING WEIGHT VALUES ===\n";
    
    // Test different weight values
    $testWeights = [1, 2, 3, 5, 10, 50, 100];
    
    foreach ($testWeights as $weight) {
        try {
            \Illuminate\Support\Facades\DB::table('criteria')
                ->where('name', 'like', '%Aduk dan goyangkan%')
                ->update(['weight' => $weight]);
            echo "✅ Weight $weight: OK\n";
            break;
        } catch (\Exception $e) {
            echo "❌ Weight $weight: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== CREATING MAIN CATEGORIES WITH CORRECT WEIGHT ===\n";
    
    // Use weight 5 for main categories
    $mainData = [
        [
            'name' => 'Pemasangan Infus',
            'description' => 'Prosedur pemasangan infus pada pasien',
            'weight' => 5,
            'max_score' => 100,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'Pemeriksaan Golongan Darah',
            'description' => 'Prosedur pemeriksaan golongan darah (TLM)',
            'weight' => 5,
            'max_score' => 100,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];
    
    foreach ($mainData as $data) {
        try {
            \Illuminate\Support\Facades\DB::table('criteria')->insert($data);
            echo "✅ Created: {$data['name']} (weight: {$data['weight']})\n";
        } catch (\Exception $e) {
            echo "❌ Failed: {$data['name']}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== FINAL VERIFICATION ===\n";
    
    $allCriteria = \Illuminate\Support\Facades\DB::table('criteria')
        ->orderBy('weight', 'desc')
        ->orderBy('name')
        ->get();
    
    echo "Total criteria: " . $allCriteria->count() . "\n\n";
    
    echo "Main categories (weight 5):\n";
    $mainCategories = $allCriteria->where('weight', 5);
    foreach ($mainCategories as $cat) {
        echo "- {$cat->name} (ID: {$cat->id}, Weight: {$cat->weight})\n";
    }
    
    echo "\nSample sub-criteria (weight 1-3):\n";
    $subCriteria = $allCriteria->where('weight', '<', 5)->take(5);
    foreach ($subCriteria as $cat) {
        echo "- {$cat->name} (Weight: {$cat->weight})\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
