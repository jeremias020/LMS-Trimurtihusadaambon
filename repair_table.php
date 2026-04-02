<?php
echo "=== REPAIRING MATA_PELAJARANS TABLE ===\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=lms_trimurti', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Check table status
    echo "Checking table status...\n";
    $stmt = $pdo->query("SHOW TABLE STATUS LIKE 'mata_pelajarans'");
    $status = $stmt->fetch();
    
    if ($status) {
        echo "📊 Engine: " . $status['Engine'] . "\n";
        echo "📊 Rows: " . $status['Rows'] . "\n";
        echo "📊 Data_length: " . $status['Data_length'] . "\n";
        
        // Try to repair table
        echo "\nAttempting to repair table...\n";
        try {
            $pdo->exec("REPAIR TABLE mata_pelajarans");
            echo "✅ Table repaired\n";
        } catch (PDOException $e) {
            echo "❌ Repair failed: " . $e->getMessage() . "\n";
        }
        
        // Try to check table
        echo "\nChecking table...\n";
        try {
            $pdo->exec("CHECK TABLE mata_pelajarans");
            echo "✅ Table check completed\n";
        } catch (PDOException $e) {
            echo "❌ Check failed: " . $e->getMessage() . "\n";
        }
        
        // Try to optimize table
        echo "\nOptimizing table...\n";
        try {
            $pdo->exec("OPTIMIZE TABLE mata_pelajarans");
            echo "✅ Table optimized\n";
        } catch (PDOException $e) {
            echo "❌ Optimize failed: " . $e->getMessage() . "\n";
        }
        
        // Test query after repair
        echo "\nTesting query after repair...\n";
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM mata_pelajarans");
            $result = $stmt->fetch();
            echo "✅ Query SUCCESS: " . $result['count'] . " records\n";
        } catch (PDOException $e) {
            echo "❌ Query still failed: " . $e->getMessage() . "\n";
            
            // Last resort: drop and recreate
            echo "\nLast resort: dropping table to recreate...\n";
            try {
                // Get table structure first
                $stmt = $pdo->query("SHOW CREATE TABLE mata_pelajarans");
                $create = $stmt->fetch();
                echo "📝 Original structure saved\n";
                
                // Drop table
                $pdo->exec("DROP TABLE mata_pelajarans");
                echo "🗑️  Table dropped\n";
                
                // Recreate table
                $pdo->exec($create['Create Table']);
                echo "✅ Table recreated\n";
                
            } catch (PDOException $e2) {
                echo "❌ Recreation failed: " . $e2->getMessage() . "\n";
                echo "💡 You may need to run: php artisan migrate:fresh\n";
            }
        }
    } else {
        echo "❌ Table 'mata_pelajarans' not found\n";
    }

} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
