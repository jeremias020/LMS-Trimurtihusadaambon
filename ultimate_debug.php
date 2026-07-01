<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 ULTIMATE USER_ID DEBUG\n";
echo "=====================================\n";

// Check for ANY user_id references in the entire app
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/app'));
$found = [];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        if (preg_match("/user_id/", $content)) {
            $found[] = str_replace(__DIR__, '', $file->getPathname());
        }
    }
}

echo "Files with user_id references:\n";
foreach ($found as $file) {
    echo "- {$file}\n";
}

echo "\n✨ DEBUG COMPLETE! ✨\n";
?>
