<?php
$start = microtime(true);
echo "Starting test...\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    echo "MySQL Connection: SUCCESS\n";
} catch (PDOException $e) {
    echo "MySQL Connection: FAILED - " . $e->getMessage() . "\n";
}

$end = microtime(true);
echo "Test completed in " . round(($end - $start) * 1000, 2) . " ms\n";
?>
