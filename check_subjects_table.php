<?php
echo "=== SUBJECTS TABLE STRUCTURE ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Columns in subjects table:\n";
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('subjects');
    foreach ($columns as $col) {
        echo "- $col\n";
    }
    
    echo "\n=== SAMPLE DATA ===\n";
    $data = \Illuminate\Support\Facades\DB::table('subjects')->limit(3)->get();
    foreach ($data as $row) {
        echo "ID: " . $row->id . ", Name: " . ($row->name ?? 'NULL') . ", Code: " . ($row->code ?? 'NULL') . "\n";
    }
    
    echo "\n=== MATA PELAJARAN MODEL ===\n";
    echo "Table: " . (new \App\Models\MataPelajaran())->getTable() . "\n";
    
    echo "\n=== TESTING QUERY ===\n";
    try {
        $result = \App\Models\MataPelajaran::orderBy('nama')->get();
        echo "✅ Query with 'nama' succeeded: " . $result->count() . " records\n";
    } catch (Exception $e) {
        echo "❌ Query with 'nama' failed: " . $e->getMessage() . "\n";
        
        echo "\nTrying with 'name' column...\n";
        try {
            $result = \App\Models\MataPelajaran::orderBy('name')->get();
            echo "✅ Query with 'name' succeeded: " . $result->count() . " records\n";
        } catch (Exception $e2) {
            echo "❌ Query with 'name' also failed: " . $e2->getMessage() . "\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
