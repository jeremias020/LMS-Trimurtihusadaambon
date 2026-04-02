<?php
echo "=== MANUAL TABLESPACE CLEANUP ===\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=lms_trimurti', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Drop problematic tables manually
    $problemTables = [
        'practice_schedules',
        'exam_schedules',
        'jadwal_ujian',
        'detail_penilaian',
        'kriteria_penilaian',
        'mata_pelajarans',
        'jurusan',
        'nilai_praktik',
        'practice_schedule_participants',
        'scheduled_notifications',
        'system_notifications'
    ];

    foreach ($problemTables as $table) {
        echo "Processing table: $table\n";
        
        // Check if table exists
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            try {
                // Try to drop tablespace first
                $pdo->exec("DROP TABLE IF EXISTS `$table`");
                echo "✅ Dropped: $table\n";
            } catch (PDOException $e) {
                echo "⚠️  Could not drop $table: " . $e->getMessage() . "\n";
                
                // Try to discard tablespace
                try {
                    $pdo->exec("ALTER TABLE `$table` DISCARD TABLESPACE");
                    echo "🗑️  Discarded tablespace: $table\n";
                    $pdo->exec("DROP TABLE `$table`");
                    echo "✅ Dropped after discard: $table\n";
                } catch (PDOException $e2) {
                    echo "❌ Failed to discard/drop $table\n";
                }
            }
        } else {
            echo "ℹ️  Table $table does not exist\n";
        }
    }

    // Clear all tables and start fresh
    echo "\nDropping all remaining tables...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        try {
            $pdo->exec("DROP TABLE `$tableName`");
            echo "✅ Dropped: $tableName\n";
        } catch (PDOException $e) {
            echo "❌ Failed to drop $tableName: " . $e->getMessage() . "\n";
        }
    }

} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
