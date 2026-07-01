<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 CHECK DATABASE TABLES\n";
echo "=====================================\n";

try {
    $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
    
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "  - {$tableName}\n";
    }
    
    // Check specifically for siswa table
    $siswaExists = \Illuminate\Support\Facades\Schema::hasTable('siswa');
    echo "\nSiswa table exists: " . ($siswaExists ? "✅ Yes" : "❌ No") . "\n";
    
    if ($siswaExists) {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('siswa');
        echo "Columns in siswa table:\n";
        foreach ($columns as $column) {
            echo "  - {$column}\n";
        }
    }
    
    // Check users table
    $usersExists = \Illuminate\Support\Facades\Schema::hasTable('users');
    echo "\nUsers table exists: " . ($usersExists ? "✅ Yes" : "❌ No") . "\n";
    
    if ($usersExists) {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
        echo "Columns in users table:\n";
        foreach ($columns as $column) {
            echo "  - {$column}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
