<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking FK on siswa table...\n";

$fks = DB::select("
    SELECT CONSTRAINT_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'siswa'
      AND COLUMN_NAME = 'user_id'
      AND REFERENCED_TABLE_NAME IS NOT NULL
");

DB::statement('SET FOREIGN_KEY_CHECKS=0');

foreach ($fks as $fk) {
    DB::statement("ALTER TABLE `siswa` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
    echo "Dropped FK: {$fk->CONSTRAINT_NAME}\n";
}

// Re-add FK tapi dengan deferred style — pakai MATCH SIMPLE agar lebih toleran
try {
    DB::statement("ALTER TABLE `siswa` ADD CONSTRAINT `siswa_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users_central`(`id`) ON DELETE CASCADE ON UPDATE CASCADE");
    echo "Re-added FK siswa_user_id_fk\n";
} catch (\Exception $e) {
    echo "FK add info: " . $e->getMessage() . "\n";
}

DB::statement('SET FOREIGN_KEY_CHECKS=1');

// Verify
$fks2 = DB::select("
    SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'siswa'
      AND REFERENCED_TABLE_NAME IS NOT NULL
");
echo "\nCurrent FKs on siswa:\n";
foreach ($fks2 as $fk) {
    echo "  {$fk->CONSTRAINT_NAME}: {$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}\n";
}

// Test insert
echo "\nTesting insert with transaction...\n";
try {
    DB::beginTransaction();
    $uid = DB::table('users_central')->insertGetId([
        'name' => '__test_user__',
        'email' => 'test_' . time() . '@test.com',
        'username' => 'test_' . time(),
        'password' => bcrypt('test'),
        'role' => 'siswa',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::commit();
    echo "User inserted (committed): ID=$uid\n";

    // Now insert siswa
    DB::table('siswa')->insert([
        'user_id' => $uid,
        'nis' => 'TEST' . time(),
        'nisn' => '999' . time(),
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Test',
        'tanggal_lahir' => '2000-01-01',
        'alamat' => 'Test',
        'no_telepon' => '000',
        'status' => 'aktif',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Siswa profile inserted OK\n";

    // Cleanup
    DB::table('siswa')->where('user_id', $uid)->delete();
    DB::table('users_central')->where('id', $uid)->delete();
    echo "Cleanup done\n";

} catch (\Exception $e) {
    DB::rollback();
    echo "Test FAILED: " . $e->getMessage() . "\n";
}
