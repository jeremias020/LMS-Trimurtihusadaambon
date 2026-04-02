<?php
echo "=== FIXING ALL MIGRATION SYNTAX ERRORS ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $problematicMigrations = [
        '2025_09_24_000300_create_scheduled_notifications_table.php',
        '2026_01_18_222346_create_exam_schedules_table.php',
        '2026_01_20_190220_create_mata_pelajarans_table.php',
        '2026_01_19_112607_create_system_notifications_table.php',
        '2026_03_04_141242_create_sessions_table.php',
        '2025_09_22_020500_create_jadwal_ujian_table.php',
        '2025_09_22_001500_create_kriteria_penilaian_table.php'
    ];
    
    $migrationPath = database_path('migrations');
    
    foreach ($problematicMigrations as $migration) {
        $filePath = $migrationPath . '/' . $migration;
        
        if (file_exists($filePath)) {
            echo "Fixing: $migration\n";
            
            $content = file_get_contents($filePath);
            
            // Check if Schema::create is commented but the closing brace is not
            if (strpos($content, '// Schema::create') !== false && strpos($content, '});') !== false) {
                // Find the Schema::create block and comment it properly
                $pattern = '/(\/\/ Schema::create\([^)]+\, function \(Blueprint \$table\) \{)(.*?)(\});/s';
                
                if (preg_match($pattern, $content, $matches)) {
                    $newBlock = $matches[1] . "\n";
                    
                    // Add commented lines for each line in the original block
                    $lines = explode("\n", $matches[2]);
                    foreach ($lines as $line) {
                        $trimmedLine = trim($line);
                        if ($trimmedLine && !str_starts_with($trimmedLine, '//')) {
                            $newBlock .= "        //     " . $trimmedLine . "\n";
                        } elseif ($trimmedLine) {
                            $newBlock .= "        " . $trimmedLine . "\n";
                        }
                    }
                    
                    $newBlock .= "        // " . $matches[3] . "\n";
                    
                    $content = preg_replace($pattern, $newBlock, $content);
                    
                    if (file_put_contents($filePath, $content)) {
                        echo "✅ Fixed syntax: $migration\n";
                    } else {
                        echo "❌ Failed to fix syntax: $migration\n";
                    }
                } else {
                    echo "⚠️ Pattern not found in: $migration\n";
                }
            } else {
                echo "✅ Already properly disabled: $migration\n";
            }
        } else {
            echo "❌ File not found: $migration\n";
        }
    }
    
    echo "\n=== TESTING MIGRATION ===\n";
    
    $output = shell_exec('php artisan migrate --force 2>&1');
    echo $output;
    
    echo "\n=== MIGRATION STATUS ===\n";
    
    $statusOutput = shell_exec('php artisan migrate:status 2>&1');
    echo $statusOutput;

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
