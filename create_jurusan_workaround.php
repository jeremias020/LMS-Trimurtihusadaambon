<?php
echo "=== CREATING JURUSAN TABLE WITH WORKAROUND ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Create with temporary name
    echo "Creating jurusan table with temporary name...\n";
    
    \Illuminate\Support\Facades\Schema::create('jurusan_temp', function ($table) {
        $table->id();
        $table->string('nama');
        $table->string('kode', 10);
        $table->text('deskripsi')->nullable();
        $table->json('mata_pelajaran')->nullable();
        $table->unsignedInteger('kapasitas_total')->nullable();
        $table->boolean('status')->default(true);
        $table->timestamps();
        $table->softDeletes();
        
        $table->unique('kode');
        $table->index('status');
    });
    
    echo "✅ Temporary table created successfully!\n";
    
    // Rename the table
    echo "\nRenaming table to jurusan...\n";
    \Illuminate\Support\Facades\DB::statement('RENAME TABLE jurusan_temp TO jurusan');
    
    echo "✅ Table renamed to jurusan!\n";
    
    // Insert sample data
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
    
    // Test the table
    echo "\nTesting jurusan table...\n";
    $count = \Illuminate\Support\Facades\DB::table('jurusan')->count();
    echo "📊 Total jurusan: $count\n";
    
    $jurusanList = \Illuminate\Support\Facades\DB::table('jurusan')->pluck('nama')->toArray();
    echo "📝 Jurusan: " . implode(', ', $jurusanList) . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
