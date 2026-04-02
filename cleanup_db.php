<?php
echo "=== DATABASE CLEANUP ===\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=lms_trimurti', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Drop all corrupted tables
    echo "Dropping corrupted tables...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    $dropped = 0;
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        if (strpos($tableName, '#mysql50#') === 0) {
            try {
                $pdo->exec("DROP TABLE `$tableName`");
                echo "✅ Dropped: $tableName\n";
                $dropped++;
            } catch (PDOException $e) {
                echo "❌ Failed to drop $tableName: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "📊 Dropped $dropped corrupted tables\n";
    
    // Check remaining tables
    echo "\nRemaining tables:\n";
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
