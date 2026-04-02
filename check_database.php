<?php
echo "=== DATABASE CONNECTION TEST ===\n";
echo "Testing MySQL connection...\n";

try {
    $start = microtime(true);
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=lms_trimurti', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $end = microtime(true);
    
    echo "✅ MySQL Connection: SUCCESS\n";
    echo "⏱️  Connection time: " . round(($end - $start) * 1000, 2) . " ms\n";
    
    // Test database exists
    $stmt = $pdo->query("SELECT DATABASE() as current_db");
    $result = $stmt->fetch();
    echo "📊 Current database: " . $result['current_db'] . "\n";
    
    // Test tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    echo "📋 Tables count: " . count($tables) . "\n";
    
    if (count($tables) > 0) {
        echo "📝 Tables:\n";
        foreach ($tables as $table) {
            $tableName = $table['Tables_in_lms_trimurti'];
            echo "   - $tableName\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ MySQL Connection: FAILED\n";
    echo "🔍 Error: " . $e->getMessage() . "\n";
    
    // Try without database first
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        echo "✅ MySQL Server Connection: SUCCESS\n";
        
        // Check if database exists
        $stmt = $pdo->query("SHOW DATABASES LIKE 'lms_trimurti'");
        $exists = $stmt->rowCount() > 0;
        
        if (!$exists) {
            echo "❌ Database 'lms_trimurti' does not exist\n";
            echo "💡 You need to create the database first\n";
        } else {
            echo "✅ Database 'lms_trimurti' exists\n";
        }
        
    } catch (PDOException $e2) {
        echo "❌ MySQL Server Connection: FAILED\n";
        echo "🔍 Error: " . $e2->getMessage() . "\n";
        echo "💡 Make sure MySQL/XAMPP is running\n";
    }
}

echo "\n=== LARAVEL DB TEST ===\n";
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $start = microtime(true);
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    $end = microtime(true);
    
    echo "✅ Laravel DB Connection: SUCCESS\n";
    echo "⏱️  Laravel connection time: " . round(($end - $start) * 1000, 2) . " ms\n";
    
} catch (Exception $e) {
    echo "❌ Laravel DB Connection: FAILED\n";
    echo "🔍 Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
