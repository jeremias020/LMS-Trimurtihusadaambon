<?php
echo "=== TESTING JURUSAN MODEL ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Testing Jurusan model...\n";
    
    // Test basic query
    $count = \App\Models\Jurusan::count();
    echo "✅ Jurusan count: $count\n";
    
    // Test with relationships
    $jurusan = \App\Models\Jurusan::withCount(['kelas', 'siswa'])->get();
    echo "✅ Jurusan with relationships loaded\n";
    
    foreach ($jurusan as $j) {
        echo "  - {$j->nama} ({$j->kode}): {$j->kelas_count} kelas, {$j->siswa_count} siswa\n";
    }
    
    echo "\n✅ Jurusan model working correctly!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
