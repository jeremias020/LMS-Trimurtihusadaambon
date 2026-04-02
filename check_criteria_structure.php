<?php
echo "=== CHECKING CRITERIA TABLE STRUCTURE ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Checking criteria table structure...\n";
    
    // Get table structure
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('criteria');
    echo "Columns in criteria table:\n";
    foreach ($columns as $col) {
        echo "- $col\n";
    }
    
    echo "\n=== CHECKING SAMPLE DATA ===\n";
    
    // Get sample data
    $sampleData = \Illuminate\Support\Facades\DB::table('criteria')
        ->limit(5)
        ->get();
    
    echo "Sample records:\n";
    foreach ($sampleData as $record) {
        echo "ID: {$record->id}, Name: {$record->name}, Weight: {$record->weight}, Max Score: {$record->max_score}\n";
        echo "Description: " . substr($record->description ?? 'NULL', 0, 50) . "...\n";
        echo "Subject ID: " . ($record->subject_id ?? 'NULL') . "\n";
        echo "Active: " . ($record->is_active ? 'Yes' : 'No') . "\n\n";
    }
    
    echo "\n=== TESTING MODEL QUERY ===\n";
    
    try {
        // Test the model query that controller uses
        $kriteria = \App\Models\KriteriaPenilaian::orderBy('mata_praktik')
                                                ->orderBy('tingkat_kelas')
                                                ->orderBy('kategori')
                                                ->paginate(20);
        
        echo "Model query successful: " . $kriteria->count() . " records\n";
        
        // Show first few records
        foreach ($kriteria->take(3) as $k) {
            echo "- {$k->nama} (mata_praktik: " . ($k->mata_praktik ?? 'NULL') . ")\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Model query failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== FIELD MAPPING ANALYSIS ===\n";
    echo "Controller expects fields:\n";
    echo "- mata_praktik (NOT in criteria table)\n";
    echo "- tingkat_kelas (NOT in criteria table)\n";
    echo "- kategori (NOT in criteria table)\n";
    echo "- bobot (NOT in criteria table)\n";
    echo "- sop_checklist (NOT in criteria table)\n";
    echo "- status (NOT in criteria table)\n";
    
    echo "\nCriteria table has:\n";
    echo "- name (✅ used as nama)\n";
    echo "- description (✅ used as deskripsi)\n";
    echo "- weight (❌ should be bobot)\n";
    echo "- max_score (❌ not used)\n";
    echo "- subject_id (❌ not used)\n";
    echo "- is_active (✅ used as status)\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
