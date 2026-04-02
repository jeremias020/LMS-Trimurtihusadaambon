<?php
echo "=== CREATING JURUSAN TABLE WITH RAW SQL ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Use raw SQL to create the table
    echo "Creating jurusan table with raw SQL...\n";
    
    $sql = "
    CREATE TABLE `jurusan` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `nama` varchar(255) NOT NULL,
        `kode` varchar(10) NOT NULL,
        `deskripsi` text NULL,
        `mata_pelajaran` json NULL,
        `kapasitas_total` int unsigned NULL,
        `status` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        `deleted_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `jurusan_kode_unique` (`kode`),
        KEY `jurusan_status_index` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    \Illuminate\Support\Facades\DB::unprepared($sql);
    
    echo "✅ jurusan table created successfully!\n";
    
    // Insert some sample data
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
