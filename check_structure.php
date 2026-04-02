<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 CHECKING ASSESSMENT_CRITERIA STRUCTURE\n";
echo "=====================================\n";

$columns = \DB::select('SHOW COLUMNS FROM assessment_criteria');
foreach ($columns as $column) {
    echo $column->Field . ' - ' . $column->Type . PHP_EOL;
}
?>
