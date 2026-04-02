<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 CHECKING NOTIFICATIONS STRUCTURE\n";
echo "=====================================\n";

$columns = \DB::select('SHOW COLUMNS FROM notifications');
foreach ($columns as $column) {
    echo $column->Field . ' - ' . $column->Type . ($column->Null === 'NO' ? ' (Required)' : ' (Nullable)') . PHP_EOL;
}
?>
