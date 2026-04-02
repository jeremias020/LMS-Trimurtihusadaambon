<?php
echo "=== CLEANING TABLESPACE FOR PRACTICE_SCHEDULES ===\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=lms_trimurti', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Check if practice_schedules exists in any form
    echo "Checking for practice_schedules table...\n";
    
    // Try to find and remove any remaining tablespace files
    try {
        $pdo->exec("DROP TABLE IF EXISTS `practice_schedules`");
        echo "✅ Attempted to drop practice_schedules\n";
    } catch (PDOException $e) {
        echo "⚠️  Drop failed: " . $e->getMessage() . "\n";
    }
    
    // Try to discard tablespace if it exists
    try {
        $pdo->exec("ALTER TABLE `practice_schedules` DISCARD TABLESPACE");
        echo "✅ Discarded tablespace\n";
        $pdo->exec("DROP TABLE `practice_schedules`");
        echo "✅ Dropped after discarding\n";
    } catch (PDOException $e) {
        echo "ℹ️  No tablespace to discard\n";
    }
    
    // Check what tables we have now
    echo "\nCurrent tables:\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        echo "✅ $tableName\n";
    }

} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
