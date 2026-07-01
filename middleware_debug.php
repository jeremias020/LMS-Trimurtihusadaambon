<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 MIDDLEWARE AND SERVICE DEBUG\n";
echo "=====================================\n";

// Check middleware that might be causing this
$middlewareFiles = [
    'app/Http/Middleware/ActiveStudentMiddleware.php',
    'app/Http/Middleware/CheckPermission.php',
    'app/Http/Middleware/CheckRole.php',
    'app/Http/Middleware/LogActivityMiddleware.php'
];

foreach ($middlewareFiles as $file) {
    $filePath = __DIR__ . '/' . $file;
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        echo "=== " . basename($file) . " ===\n";
        
        // Look for any queries that might use user_id
        if (preg_match("/User::.*user_id/", $content)) {
            echo "❌ Found User:: with user_id\n";
            preg_match_all("/User::.*user_id.*/", $content, $matches);
            foreach ($matches[0] as $match) {
                echo "  - {$match}\n";
            }
        }
        
        if (preg_match("/Student::.*user_id/", $content)) {
            echo "❌ Found Student:: with user_id\n";
            preg_match_all("/Student::.*user_id.*/", $content, $matches);
            foreach ($matches[0] as $match) {
                echo "  - {$match}\n";
            }
        }
        
        echo "\n";
    }
}

// Check AuthService
$authServicePath = __DIR__ . '/app/Services/AuthService.php';
if (file_exists($authServicePath)) {
    echo "=== AuthService.php ===\n";
    $content = file_get_contents($authServicePath);
    
    if (preg_match("/User::.*user_id/", $content)) {
        echo "❌ Found User:: with user_id in AuthService\n";
        preg_match_all("/User::.*user_id.*/", $content, $matches);
        foreach ($matches[0] as $match) {
            echo "  - {$match}\n";
        }
    }
    
    if (preg_match("/Student::.*user_id/", $content)) {
        echo "❌ Found Student:: with user_id in AuthService\n";
        preg_match_all("/Student::.*user_id.*/", $content, $matches);
        foreach ($matches[0] as $match) {
            echo "  - {$match}\n";
        }
    }
}

echo "\n✨ MIDDLEWARE DEBUG COMPLETE! ✨\n";
?>
