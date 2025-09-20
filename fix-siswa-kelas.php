<?php

/**
 * Fix siswa kelas_id assignment
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 Fixing Siswa Kelas Assignment...\n\n";

// Check kelas table structure
try {
    $kelas = DB::table('kelas')->first();
    if ($kelas) {
        echo "Kelas table sample:\n";
        foreach ((array)$kelas as $field => $value) {
            echo "- $field: $value\n";
        }
    }
} catch (Exception $e) {
    echo "Error checking kelas: " . $e->getMessage() . "\n";
}

// Get all siswa without kelas_id and assign them to first available kelas
echo "\n=== Updating Siswa Kelas Assignment ===\n";

try {
    // Get first kelas
    $firstKelas = DB::table('kelas')->first();
    if (!$firstKelas) {
        echo "No kelas found, creating default kelas...\n";
        
        DB::table('kelas')->insert([
            'kode' => 'X-RPL-1',
            'tingkat' => 'X',
            'jurusan' => 'RPL',
            'rombel' => 1,
            'tahun_ajaran' => '2024/2025',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $firstKelas = DB::table('kelas')->first();
        echo "Default kelas created with ID: {$firstKelas->id}\n";
    }
    
    // Update siswa without kelas_id
    $siswaWithoutKelas = DB::table('users')
        ->where('role', 'siswa')
        ->whereNull('kelas_id')
        ->get();
    
    echo "Found " . count($siswaWithoutKelas) . " siswa without kelas_id\n";
    
    foreach ($siswaWithoutKelas as $siswa) {
        DB::table('users')
            ->where('id', $siswa->id)
            ->update(['kelas_id' => $firstKelas->id]);
        
        echo "Updated siswa: {$siswa->name} -> Kelas ID: {$firstKelas->id}\n";
    }
    
    // Also update siswa table if exists
    $siswaRecords = DB::table('siswa')->whereNull('kelas_id')->get();
    echo "Found " . count($siswaRecords) . " siswa records without kelas_id\n";
    
    foreach ($siswaRecords as $siswa) {
        DB::table('siswa')
            ->where('id', $siswa->id)
            ->update(['kelas_id' => $firstKelas->id]);
        
        echo "Updated siswa record ID: {$siswa->id} -> Kelas ID: {$firstKelas->id}\n";
    }
    
} catch (Exception $e) {
    echo "Error updating siswa kelas: " . $e->getMessage() . "\n";
}

echo "\n✅ Siswa kelas assignment completed!\n";
echo "\n💡 Now try logging in as a siswa user to test the dashboard.\n";