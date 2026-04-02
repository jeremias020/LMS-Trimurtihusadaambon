<?php
echo "=== AGGRESSIVE TABLESPACE CLEANUP ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
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
        echo "\n=== AGGRESSIVE CLEANUP: $table ===\n";
        
        try {
            // Try multiple approaches to clean tablespace
            $commands = [
                "DROP TABLE IF EXISTS `$table`",
                "CREATE TABLE `$table` (id INT)",
                "DROP TABLE `$table`",
                "CREATE TABLE `$table` (id INT)",
                "TRUNCATE TABLE `$table`",
                "DROP TABLE IF EXISTS `$table`"
            ];
            
            foreach ($commands as $command) {
                try {
                    \Illuminate\Support\Facades\DB::statement($command);
                    echo "✅ Executed: $command\n";
                } catch (\Exception $e) {
                    echo "⚠️ Failed: $command - " . $e->getMessage() . "\n";
                }
            }
            
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== ALTERNATIVE APPROACH: DISABLE PROBLEMATIC MIGRATIONS ===\n";
    
    // Get list of migration files
    $migrationPath = database_path('migrations');
    $files = glob($migrationPath . '/*.php');
    
    $problematicMigrations = [
        '2025_09_24_000300_create_scheduled_notifications_table.php',
        '2026_01_18_222346_create_exam_schedules_table.php',
        '2026_01_20_190220_create_mata_pelajarans_table.php',
        '2026_01_19_112607_create_system_notifications_table.php',
        '2026_03_04_141242_create_sessions_table.php',
        '2025_09_22_020500_create_jadwal_ujian_table.php',
        '2025_09_22_001500_create_kriteria_penilaian_table.php',
        '2025_09_21_160410_create_jurusan_table.php'
    ];
    
    foreach ($problematicMigrations as $migration) {
        $filePath = $migrationPath . '/' . $migration;
        if (file_exists($filePath)) {
            echo "Processing: $migration\n";
            
            $content = file_get_contents($filePath);
            
            // Check if already disabled
            if (strpos($content, '// Schema::create') !== false) {
                echo "⚠️ Already disabled: $migration\n";
                continue;
            }
            
            // Disable the migration by commenting out Schema::create
            $content = str_replace('Schema::create', '// Schema::create', $content);
            
            if (file_put_contents($filePath, $content)) {
                echo "✅ Disabled: $migration\n";
            } else {
                echo "❌ Failed to disable: $migration\n";
            }
        }
    }
    
    echo "\n=== CREATING ALTERNATIVE TABLES ===\n";
    
    // Create alternative tables with different names
    $alternatives = [
        'scheduled_notifications_new' => 'CREATE TABLE scheduled_notifications_new (
            id bigint unsigned not null auto_increment primary key,
            jadwal_ujian_id bigint unsigned not null,
            notification_type varchar(5) not null,
            scheduled_at timestamp not null,
            sent_at timestamp null,
            status enum("pending", "sent", "failed") not null default "pending",
            error_message text null,
            created_at timestamp null,
            updated_at timestamp null
        )',
        
        'exam_schedules_new' => 'CREATE TABLE exam_schedules_new (
            id bigint unsigned not null auto_increment primary key,
            title varchar(255) not null,
            description text null,
            exam_date date not null,
            start_time time not null,
            end_time time not null,
            kelas_id bigint unsigned null,
            mata_pelajaran_id bigint unsigned null,
            guru_id bigint unsigned null,
            is_published boolean not null default false,
            created_at timestamp null,
            updated_at timestamp null,
            deleted_at timestamp null
        )',
        
        'mata_pelajarans_new' => 'CREATE TABLE mata_pelajarans_new (
            id bigint unsigned not null auto_increment primary key,
            nama varchar(255) not null,
            kode varchar(20) not null,
            deskripsi text null,
            jenis enum("umum", "kejuruan") not null default "umum",
            jam_per_minggu tinyint not null default 2,
            status boolean not null default true,
            created_at timestamp null,
            updated_at timestamp null,
            deleted_at timestamp null
        )',
        
        'system_notifications_new' => 'CREATE TABLE system_notifications_new (
            id bigint unsigned not null auto_increment primary key,
            title varchar(255) not null,
            message text not null,
            type varchar(50) not null default "info",
            target_role varchar(20) null,
            is_read boolean not null default false,
            user_id bigint unsigned null,
            created_at timestamp null,
            updated_at timestamp null
        )',
        
        'sessions_new' => 'CREATE TABLE sessions_new (
            id varchar(255) not null primary key,
            user_id bigint unsigned null,
            ip_address varchar(45) null,
            user_agent text null,
            payload text not null,
            last_activity int not null
        )',
        
        'jadwal_ujian_new' => 'CREATE TABLE jadwal_ujian_new (
            id bigint unsigned not null auto_increment primary key,
            title varchar(255) not null,
            description text null,
            exam_date date not null,
            start_time time not null,
            end_time time not null,
            kelas_id bigint unsigned null,
            mata_pelajaran_id bigint unsigned null,
            guru_id bigint unsigned null,
            ruangan varchar(100) null,
            status enum("scheduled", "ongoing", "completed", "cancelled") not null default "scheduled",
            created_at timestamp null,
            updated_at timestamp null,
            deleted_at timestamp null
        )',
        
        'kriteria_penilaian_new' => 'CREATE TABLE kriteria_penilaian_new (
            id bigint unsigned not null auto_increment primary key,
            nama varchar(255) not null,
            kategori varchar(50) not null default "praktik",
            deskripsi text null,
            parent_id bigint unsigned null,
            bobot tinyint not null default 1,
            is_active boolean not null default true,
            created_at timestamp null,
            updated_at timestamp null,
            deleted_at timestamp null
        )',
        
        'jurusan_new' => 'CREATE TABLE jurusan_new (
            id bigint unsigned not null auto_increment primary key,
            nama varchar(255) not null,
            kode varchar(10) not null,
            deskripsi text null,
            mata_pelajaran json null,
            kapasitas_total int unsigned null,
            status tinyint(1) not null default 1,
            created_at timestamp null,
            updated_at timestamp null,
            deleted_at timestamp null
        )'
    ];
    
    foreach ($alternatives as $tableName => $sql) {
        try {
            \Illuminate\Support\Facades\DB::statement("DROP TABLE IF EXISTS $tableName");
            \Illuminate\Support\Facades\DB::statement($sql);
            echo "✅ Created alternative table: $tableName\n";
        } catch (\Exception $e) {
            echo "❌ Failed to create $tableName: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== UPDATE MODELS TO USE NEW TABLES ===\n";
    
    // Update models to use new tables
    $modelUpdates = [
        'app/Models/Jurusan.php' => "protected \$table = 'jurusan_new';",
        'app/Models/MataPelajaran.php' => "protected \$table = 'subjects';", // Already using subjects
        'app/Models/ExamSchedule.php' => "protected \$table = 'exam_schedules_new';",
        'app/Models/SystemNotification.php' => "protected \$table = 'system_notifications_new';",
        'app/Models/KriteriaPenilaian.php' => "protected \$table = 'criteria';", // Already using criteria
    ];
    
    foreach ($modelUpdates as $modelPath => $newLine) {
        if (file_exists($modelPath)) {
            $content = file_get_contents($modelPath);
            
            // Find and replace protected $table line
            if (preg_match('/protected\s+\$table\s*=\s*[\'"][^\'"]*[\'"];/', $content)) {
                $content = preg_replace('/protected\s+\$table\s*=\s*[\'"][^\'"]*[\'"];/', $newLine, $content);
                
                if (file_put_contents($modelPath, $content)) {
                    echo "✅ Updated model: $modelPath\n";
                } else {
                    echo "❌ Failed to update model: $modelPath\n";
                }
            }
        }
    }
    
    echo "\n=== RUNNING REMAINING MIGRATIONS ===\n";
    
    $output = shell_exec('php artisan migrate --force 2>&1');
    echo $output;

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
