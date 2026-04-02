<?php
echo "=== CREATING JURUSAN TEMP TABLE ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Create table with temporary name
    echo "Creating jurusan_new table...\n";
    
    \Illuminate\Support\Facades\Schema::create('jurusan_new', function ($table) {
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
    
    echo "✅ jurusan_new table created successfully\n";
    
    // Seed default data
    echo "\nSeeding default jurusan...\n";
    
    $defaultJurusan = [
        [
            'nama' => 'Keperawatan',
            'kode' => 'KPR',
            'deskripsi' => 'Program Keahlian Keperawatan',
            'mata_pelajaran' => json_encode([
                'Anatomi Fisiologi',
                'Patologi',
                'Farmakologi',
                'Keperawatan Dasar',
                'Keperawatan Medikal Bedah',
                'Keperawatan Anak',
                'Keperawatan Maternitas',
                'Keperawatan Jiwa',
                'Keperawatan Komunitas'
            ]),
            'kapasitas_total' => 120,
            'status' => true
        ],
        [
            'nama' => 'Farmasi',
            'kode' => 'FAR',
            'deskripsi' => 'Program Keahlian Farmasi Klinis dan Komunitas',
            'mata_pelajaran' => json_encode([
                'Kimia Farmasi',
                'Farmakologi',
                'Farmasetika',
                'Farmakognosi',
                'Farmasi Klinik',
                'Managemen Farmasi',
                'Kimia Analisis',
                'Biologi Farmasi'
            ]),
            'kapasitas_total' => 80,
            'status' => true
        ],
        [
            'nama' => 'Teknologi Laboratorium Medik',
            'kode' => 'TLM',
            'deskripsi' => 'Program Keahlian Analis Kesehatan',
            'mata_pelajaran' => json_encode([
                'Hematologi',
                'Kimia Klinik',
                'Mikrobiologi',
                'Parasitologi',
                'Imunologi',
                'Urinalisis',
                'Histopatologi',
                'Toksikologi'
            ]),
            'kapasitas_total' => 60,
            'status' => true
        ]
    ];
    
    foreach ($defaultJurusan as $jurusan) {
        \Illuminate\Support\Facades\DB::table('jurusan_new')->insert($jurusan);
    }
    
    echo "✅ Default jurusan seeded\n";
    
    // Test the table
    echo "\nTesting jurusan_new table...\n";
    $count = \Illuminate\Support\Facades\DB::table('jurusan_new')->count();
    echo "📊 Total jurusan: $count\n";
    
    // Try to rename
    echo "\nRenaming to jurusan...\n";
    \Illuminate\Support\Facades\Schema::rename('jurusan_new', 'jurusan');
    echo "✅ Renamed successfully\n";
    
    // Test final table
    echo "\nTesting final jurusan table...\n";
    $finalCount = \Illuminate\Support\Facades\DB::table('jurusan')->count();
    echo "📊 Final total jurusan: $finalCount\n";
    
    $jurusanList = \Illuminate\Support\Facades\DB::table('jurusan')->pluck('nama')->toArray();
    echo "📝 Jurusan: " . implode(', ', $jurusanList) . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
