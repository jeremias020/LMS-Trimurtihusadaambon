<?php
echo "=== CHECKING DATETIME AND TIMEZONE ===\n";

echo "Current datetime: " . date('Y-m-d H:i:s') . "\n";
echo "Timezone: " . date_default_timezone_get() . "\n";

// Check Laravel timezone
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Laravel timezone: " . config('app.timezone') . "\n";
echo "Laravel now: " . now()->format('Y-m-d H:i:s') . "\n";

echo "\n=== COMPLETE ===\n";
?>
