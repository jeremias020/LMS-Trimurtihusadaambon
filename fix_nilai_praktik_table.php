<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECK EXISTING TABLES ===" . PHP_EOL;

try {
    $tables = DB::select('SHOW TABLES');
    
    echo "Tables containing 'nilai' or 'praktik':" . PHP_EOL;
    
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        if (strpos($tableName, 'nilai') !== false || strpos($tableName, 'praktik') !== false) {
            echo "- " . $tableName . PHP_EOL;
            
            // Check structure
            if ($tableName === 'nilai_praktik_new') {
                echo PHP_EOL . "=== STRUCTURE OF nilai_praktik_new ===" . PHP_EOL;
                $columns = DB::select("DESCRIBE `nilai_praktik_new`");
                foreach ($columns as $column) {
                    echo "- " . $column->Field . " (" . $column->Type . ")" . PHP_EOL;
                }
                
                // Check if we can rename it
                echo PHP_EOL . "=== ATTEMPTING TO RENAME TABLE ===" . PHP_EOL;
                try {
                    DB::statement("RENAME TABLE `nilai_praktik_new` TO `nilai_praktik`");
                    echo "✅ Successfully renamed nilai_praktik_new to nilai_praktik!" . PHP_EOL;
                } catch (Exception $e) {
                    echo "❌ Failed to rename: " . $e->getMessage() . PHP_EOL;
                    
                    // Try to drop and recreate
                    echo PHP_EOL . "=== ATTEMPTING TO DROP AND RECREATE ===" . PHP_EOL;
                    try {
                        DB::statement("DROP TABLE IF EXISTS `nilai_praktik`");
                        echo "✅ Dropped existing nilai_praktik table" . PHP_EOL;
                        
                        DB::statement("RENAME TABLE `nilai_praktik_new` TO `nilai_praktik`");
                        echo "✅ Successfully renamed nilai_praktik_new to nilai_praktik!" . PHP_EOL;
                    } catch (Exception $e2) {
                        echo "❌ Failed to drop and recreate: " . $e2->getMessage() . PHP_EOL;
                    }
                }
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
}
