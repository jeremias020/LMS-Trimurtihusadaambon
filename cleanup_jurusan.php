<?php
echo "=== CLEANING JURUSAN TABLESPACE ===\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=lms_trimurti', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Drop existing tablespace
    echo "Dropping jurusan table...\n";
    try {
        $pdo->exec("DROP TABLE IF EXISTS `jurusan`");
        echo "✅ Dropped existing table\n";
    } catch (PDOException $e) {
        echo "⚠️  Could not drop: " . $e->getMessage() . "\n";
    }
    
    // Try to discard tablespace
    try {
        $pdo->exec("ALTER TABLE `jurusan` DISCARD TABLESPACE");
        echo "✅ Discarded tablespace\n";
        $pdo->exec("DROP TABLE `jurusan`");
        echo "✅ Dropped after discarding\n";
    } catch (PDOException $e) {
        echo "ℹ️  No tablespace to discard\n";
    }

} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
