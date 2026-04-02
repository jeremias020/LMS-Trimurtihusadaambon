<?php
echo "=== CREATING JURUSAN TABLE ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Drop table if it exists first
    echo "Dropping existing jurusan table if exists...\n";
    try {
        \Illuminate\Support\Facades\Schema::dropIfExists('jurusan');
        echo "Table dropped (if it existed)\n";
    } catch (Exception $e) {
        echo "Error dropping table: " . $e->getMessage() . "\n";
    }
    
    // Create table manually
    echo "Creating jurusan table...\n";
    
    \Illuminate\Support\Facades\Schema::create('jurusan', function ($table) {
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
    
    echo "✅ jurusan table created successfully\n";
    
    // Seed default data
    echo "\nSeeding default jurusan...\n";
    \App\Models\Jurusan::seedDefault();
    echo "✅ Default jurusan seeded\n";
    
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
