<?php
echo "=== CHECKING MAIN CATEGORIES ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Check for main categories
    $mainCategories = \Illuminate\Support\Facades\DB::table('criteria')
        ->where('weight', 10)
        ->get();
    
    echo "Main categories found: " . $mainCategories->count() . "\n";
    foreach ($mainCategories as $cat) {
        echo "- {$cat->name} (ID: {$cat->id})\n";
    }
    
    echo "\n=== RE-CREATING MAIN CATEGORIES ===\n";
    
    // Delete existing main categories if they exist
    \Illuminate\Support\Facades\DB::table('criteria')
        ->whereIn('name', ['Pemasangan Infus', 'Pemeriksaan Golongan Darah'])
        ->delete();
    
    echo "✅ Deleted existing main categories\n";
    
    // Re-create main categories
    $mainData = [
        [
            'name' => 'Pemasangan Infus',
            'description' => 'Prosedur pemasangan infus pada pasien',
            'weight' => 10,
            'max_score' => 100,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'Pemeriksaan Golongan Darah',
            'description' => 'Prosedur pemeriksaan golongan darah (TLM)',
            'weight' => 10,
            'max_score' => 100,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];
    
    foreach ($mainData as $data) {
        \Illuminate\Support\Facades\DB::table('criteria')->insert($data);
        echo "✅ Re-created: {$data['name']}\n";
    }
    
    echo "\n=== VERIFICATION ===\n";
    
    $newMainCategories = \Illuminate\Support\Facades\DB::table('criteria')
        ->where('weight', 10)
        ->get();
    
    echo "Main categories after re-creation: " . $newMainCategories->count() . "\n";
    foreach ($newMainCategories as $cat) {
        echo "- {$cat->name} (ID: {$cat->id}, Weight: {$cat->weight})\n";
    }
    
    echo "\nTotal criteria: " . \Illuminate\Support\Facades\DB::table('criteria')->count() . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
