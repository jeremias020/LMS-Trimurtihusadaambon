<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 CHECK JURUSAN TABLE NAME\n";
echo "=====================================\n";

$tables = \DB::select('SHOW TABLES');
echo "Tables containing 'jurus':\n";

foreach ($tables as $table) {
    $tableName = $table->{'Tables_in_lms_trimurti'};
    if (str_contains($tableName, 'jurus')) {
        echo "  - {$tableName}\n";
    }
}

echo "\nAll tables:\n";
foreach ($tables as $table) {
    $tableName = $table->{'Tables_in_lms_trimurti'};
    echo "  - {$tableName}\n";
}
?>
