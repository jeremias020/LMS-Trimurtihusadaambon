<?php
echo "=== TESTING JURUSAN ACCESS ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Test the exact query that was failing
    echo "Testing: select count(*) as aggregate from `jurusan` where `id` = 1\n";
    
    $count = \Illuminate\Support\Facades\DB::table('jurusan')->where('id', 1)->count();
    echo "✅ Query successful! Count: $count\n";
    
    // Test some other operations
    echo "\nTesting other operations...\n";
    
    $all = \Illuminate\Support\Facades\DB::table('jurusan')->get();
    echo "✅ Get all records: " . $all->count() . " records\n";
    
    $first = \Illuminate\Support\Facades\DB::table('jurusan')->first();
    if ($first) {
        echo "✅ First record: ID={$first->id}, Name={$first->nama}, Code={$first->kode}\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
