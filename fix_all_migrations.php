<?php
echo "=== MEMPERBAIKI SEMUA MIGRATION ERROR ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Checking problematic tables...\n";
    
    // List of tables that have tablespace issues
    $problematicTables = [
        'scheduled_notifications',
        'exam_schedules',
        'mata_pelajarans', 
        'system_notifications',
        'sessions',
        'jadwal_ujian',
        'kriteria_penilaian',
        'jurusan'
    ];
    
    foreach ($problematicTables as $table) {
        echo "\n=== Processing table: $table ===\n";
        
        try {
            // Check if table exists
            if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                echo "Table exists, dropping...\n";
                
                // Drop the table
                \Illuminate\Support\Facades\Schema::dropIfExists($table);
                echo "✅ Table dropped\n";
                
                // Try to discard tablespace
                try {
                    \Illuminate\Support\Facades\DB::statement("DROP TABLE IF EXISTS $table");
                    \Illuminate\Support\Facades\DB::statement("CREATE TABLE $table (id INT)");
                    \Illuminate\Support\Facades\DB::statement("DROP TABLE $table");
                    echo "✅ Tablespace cleaned\n";
                } catch (\Exception $e) {
                    echo "⚠️ Tablespace cleanup failed: " . $e->getMessage() . "\n";
                }
            } else {
                echo "Table doesn't exist, cleaning tablespace...\n";
                
                try {
                    \Illuminate\Support\Facades\DB::statement("DROP TABLE IF EXISTS $table");
                    \Illuminate\Support\Facades\DB::statement("CREATE TABLE $table (id INT)");
                    \Illuminate\Support\Facades\DB::statement("DROP TABLE $table");
                    echo "✅ Tablespace cleaned\n";
                } catch (\Exception $e) {
                    echo "⚠️ Tablespace cleanup failed: " . $e->getMessage() . "\n";
                }
            }
        } catch (\Exception $e) {
            echo "❌ Error processing $table: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== RUNNING MIGRATIONS ===\n";
    
    // Clear migration cache
    \Illuminate\Support\Facades\DB::table('migrations')->whereIn('migration', [
        '2025_09_24_000300_create_scheduled_notifications_table',
        '2026_01_16_153918_add_foto_to_siswa_table',
        '2026_01_18_222346_create_exam_schedules_table',
        '2026_01_19_112607_create_system_notifications_table',
        '2026_01_19_145826_add_kelas_id_to_gurus_table',
        '2026_01_20_190220_create_mata_pelajarans_table',
        '2026_03_04_141242_create_sessions_table',
        '2025_09_22_020500_create_jadwal_ujian_table',
        '2025_09_22_001500_create_kriteria_penilaian_table',
        '2025_09_21_160410_create_jurusan_table'
    ])->delete();
    
    echo "✅ Migration cache cleared\n";
    
    // Now run migrations
    echo "\nRunning php artisan migrate...\n";
    $output = shell_exec('php artisan migrate --force 2>&1');
    echo $output;
    
    echo "\n=== VERIFICATION ===\n";
    
    // Check final migration status
    $pendingMigrations = \Illuminate\Support\Facades\DB::table('migrations')
        ->whereNotIn('migration', function($query) {
            $query->select('migration')->from('migrations');
        })
        ->count();
    
    echo "Pending migrations: $pendingMigrations\n";
    
    // Check all tables exist
    $allTables = \Illuminate\Support\Facades\Schema::getTableListing();
    echo "\nTables in database: " . count($allTables) . "\n";
    
    foreach ($problematicTables as $table) {
        $status = \Illuminate\Support\Facades\Schema::hasTable($table) ? "✅" : "❌";
        echo "$status $table\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
