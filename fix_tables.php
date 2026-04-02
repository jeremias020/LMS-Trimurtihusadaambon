<?php
echo "=== TABLE INTEGRITY CHECK ===\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=lms_trimurti', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Check mata_pelajarans table
    echo "Checking mata_pelajarans table...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'mata_pelajarans'");
    $exists = $stmt->rowCount() > 0;
    
    if ($exists) {
        echo "✅ Table 'mata_pelajarans' exists\n";
        
        // Check table engine
        $stmt = $pdo->query("SHOW TABLE STATUS LIKE 'mata_pelajarans'");
        $status = $stmt->fetch();
        echo "🔧 Engine: " . $status['Engine'] . "\n";
        echo "📊 Rows: " . $status['Rows'] . "\n";
        
        // Test simple query
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM mata_pelajarans");
            $result = $stmt->fetch();
            echo "📈 Total records: " . $result['count'] . "\n";
        } catch (PDOException $e) {
            echo "❌ Query failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ Table 'mata_pelajarans' does not exist\n";
    }

    // Check for corrupted tables
    echo "\nChecking for corrupted tables...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        if (strpos($tableName, '#mysql50#') === 0) {
            echo "⚠️  Found corrupted table: $tableName\n";
            
            // Try to rename it
            $cleanName = str_replace('#mysql50#', '', $tableName);
            try {
                $pdo->exec("RENAME TABLE `$tableName` TO `$cleanName`");
                echo "✅ Renamed to: $cleanName\n";
            } catch (PDOException $e) {
                echo "❌ Failed to rename: " . $e->getMessage() . "\n";
            }
        }
    }

    // Check if mata_pelajarans exists after cleanup
    echo "\nFinal check for mata_pelajarans...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'mata_pelajarans'");
    $exists = $stmt->rowCount() > 0;
    
    if ($exists) {
        echo "✅ Table 'mata_pelajarans' is now accessible\n";
    } else {
        echo "❌ Table 'mata_pelajarans' still not found\n";
        echo "💡 You may need to run: php artisan migrate:fresh\n";
    }

} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
