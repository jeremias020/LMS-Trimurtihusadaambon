<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    echo "MySQL Connection: SUCCESS\n";
} catch (PDOException $e) {
    echo "MySQL Connection: FAILED - " . $e->getMessage() . "\n";
}

try {
    $pdo = new PDO('sqlite:' . database_path('database.sqlite'));
    echo "SQLite Connection: SUCCESS\n";
} catch (PDOException $e) {
    echo "SQLite Connection: FAILED - " . $e->getMessage() . "\n";
}
?>
