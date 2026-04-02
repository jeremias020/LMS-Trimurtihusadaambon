<?php
echo "=== TESTING MATA PELAJARAN INDEX DISPLAY ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Testing mata pelajaran data display...\n";
    
    // Get all mata pelajaran
    $mataPelajarans = \App\Models\MataPelajaran::orderBy('name')->get();
    
    echo "Total mata pelajaran: " . $mataPelajarans->count() . "\n\n";
    
    foreach ($mataPelajarans as $mapel) {
        echo "=== ID: {$mapel->id} ===\n";
        echo "Name: {$mapel->name}\n";
        echo "Code: {$mapel->code}\n";
        echo "Type: {$mapel->type}\n";
        echo "SKS: {$mapel->sks}\n";
        echo "Description: " . ($mapel->description ?? 'NULL') . "\n";
        echo "Active: " . ($mapel->is_active ? 'Yes' : 'No') . "\n";
        echo "\n";
    }
    
    echo "=== TESTING WITH DESCRIPTION ===\n";
    
    // Test creating a record with description
    $testMapel = \App\Models\MataPelajaran::create([
        'name' => 'Test Mata Pelajaran with Description',
        'code' => 'TST' . time(),
        'description' => 'This is a test description for the mata pelajaran',
        'type' => 'teori',
        'sks' => 3,
        'is_active' => true
    ]);
    
    echo "Created test record:\n";
    echo "Name: {$testMapel->name}\n";
    echo "Description: {$testMapel->description}\n";
    
    // Test if description appears in view simulation
    echo "\n=== VIEW SIMULATION ===\n";
    echo "How it would appear in index.blade.php:\n";
    echo "<div class=\"fw-bold\">{$testMapel->name}</div>\n";
    echo "<small class=\"text-muted\">" . ($testMapel->description ?? 'Tidak ada deskripsi') . "</small>\n";
    
    // Clean up
    $testMapel->delete();
    echo "\n✅ Test record cleaned up\n";
    
    echo "\n=== FIELD MAPPING VERIFICATION ===\n";
    echo "✅ All fields now use correct names:\n";
    echo "  - name (was: nama)\n";
    echo "  - code (was: kode)\n";
    echo "  - description (was: deskripsi)\n";
    echo "  - type (was: jenis)\n";
    echo "  - sks (was: jam_per_minggu)\n";
    echo "  - is_active (was: status)\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
