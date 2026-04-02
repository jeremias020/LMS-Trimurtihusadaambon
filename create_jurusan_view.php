<?php
echo "=== CREATING JURUSAN VIEW ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Create a view named jurusan that points to jurusan_temp
    echo "Creating jurusan view...\n";
    
    $sql = "CREATE OR REPLACE VIEW jurusan AS SELECT * FROM jurusan_temp";
    \Illuminate\Support\Facades\DB::statement($sql);
    
    echo "✅ jurusan view created successfully!\n";
    
    // Test the view
    echo "\nTesting jurusan view...\n";
    $count = \Illuminate\Support\Facades\DB::table('jurusan')->count();
    echo "📊 Total jurusan: $count\n";
    
    $jurusanList = \Illuminate\Support\Facades\DB::table('jurusan')->pluck('nama')->toArray();
    echo "📝 Jurusan: " . implode(', ', $jurusanList) . "\n";
    
    // Insert sample data if empty
    if ($count == 0) {
        echo "\nInserting sample data...\n";
        
        $sampleData = [
            ['nama' => 'Keperawatan', 'kode' => 'KEP', 'deskripsi' => 'Program studi Keperawatan'],
            ['nama' => 'Kebidanan', 'kode' => 'KBD', 'deskripsi' => 'Program studi Kebidanan'],
            ['nama' => 'Farmasi', 'kode' => 'FRM', 'deskripsi' => 'Program studi Farmasi'],
        ];
        
        foreach ($sampleData as $data) {
            \Illuminate\Support\Facades\DB::table('jurusan')->insert($data);
        }
        
        echo "✅ Sample data inserted\n";
        
        // Test again
        $count = \Illuminate\Support\Facades\DB::table('jurusan')->count();
        echo "📊 Total jurusan after insert: $count\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
