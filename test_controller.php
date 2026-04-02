<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST PENILAIIAN CONTROLLER ===" . PHP_EOL;

try {
    // Test if controller can be instantiated
    $controller = new \App\Http\Controllers\Guru\PenilaianController();
    echo "✅ Controller loaded successfully!" . PHP_EOL;
    
    // Test if trait methods exist
    if (method_exists($controller, 'autoWithCriteria')) {
        echo "✅ autoWithCriteria method exists!" . PHP_EOL;
    } else {
        echo "❌ autoWithCriteria method missing!" . PHP_EOL;
    }
    
    if (method_exists($controller, 'saveAutoAssessmentWithCriteria')) {
        echo "✅ saveAutoAssessmentWithCriteria method exists!" . PHP_EOL;
    } else {
        echo "❌ saveAutoAssessmentWithCriteria method missing!" . PHP_EOL;
    }
    
    echo PHP_EOL . "🎉 Controller test passed!" . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
