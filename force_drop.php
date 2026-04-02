<?php
echo "=== FORCE DROP ALL TABLES ===\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=lms_trimurti', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    echo "🔓 Foreign key checks disabled\n";

    // Get all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    echo "Dropping " . count($tables) . " tables...\n";
    
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        try {
            $pdo->exec("DROP TABLE `$tableName`");
            echo "✅ Dropped: $tableName\n";
        } catch (PDOException $e) {
            echo "❌ Failed to drop $tableName: " . $e->getMessage() . "\n";
        }
    }

    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "🔒 Foreign key checks re-enabled\n";

} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
