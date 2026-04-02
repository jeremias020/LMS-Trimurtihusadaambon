<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECK DATABASE TABLES ===" . PHP_EOL;

try {
    $tables = DB::select('SHOW TABLES');
    echo "Available tables:" . PHP_EOL;
    
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "- " . $tableName . PHP_EOL;
    }
    
    echo PHP_EOL . "=== CHECK FOR nilai_praktik TABLE ===" . PHP_EOL;
    
    $nilaiPraktikExists = false;
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        if (strpos($tableName, 'nilai') !== false || strpos($tableName, 'praktik') !== false) {
            echo "Found related table: " . $tableName . PHP_EOL;
            if ($tableName === 'nilai_praktik') {
                $nilaiPraktikExists = true;
            }
        }
    }
    
    if (!$nilaiPraktikExists) {
        echo "Table 'nilai_praktik' NOT FOUND!" . PHP_EOL;
        echo PHP_EOL . "=== CREATING nilai_praktik TABLE ===" . PHP_EOL;
        
        // Create the table
        $createTableSQL = "
        CREATE TABLE IF NOT EXISTS `nilai_praktik` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `siswa_id` bigint(20) unsigned NOT NULL,
            `guru_id` bigint(20) unsigned NOT NULL,
            `mata_praktik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `tanggal_praktik` date NOT NULL,
            `total_nilai` decimal(5,2) NOT NULL DEFAULT '0.00',
            `grade` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
            `feedback_otomatis` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `catatan_guru` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `status` enum('draft','final') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `nilai_praktik_siswa_id_foreign` (`siswa_id`),
            KEY `nilai_praktik_guru_id_foreign` (`guru_id`),
            CONSTRAINT `nilai_praktik_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
            CONSTRAINT `nilai_praktik_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        DB::statement($createTableSQL);
        echo "✅ Table 'nilai_praktik' created successfully!" . PHP_EOL;
    } else {
        echo "✅ Table 'nilai_praktik' already exists!" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
}
