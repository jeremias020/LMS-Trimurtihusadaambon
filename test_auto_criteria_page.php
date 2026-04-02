<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST AUTO-CRITERIA PAGE ===" . PHP_EOL;

try {
    $guruId = 118;
    
    // Mock authentication
    \Illuminate\Support\Facades\Auth::shouldReceive('id')->andReturn($guruId);
    
    // Test the controller method
    $controller = new \App\Http\Controllers\Guru\PenilaianController();
    
    echo "Testing autoWithCriteria method..." . PHP_EOL;
    
    $response = $controller->autoWithCriteria();
    echo "✅ autoWithCriteria method executed successfully!" . PHP_EOL;
    
    // Get the view data
    $viewData = $response->getData();
    
    echo PHP_EOL . "View data:" . PHP_EOL;
    echo "- Subjects: " . (isset($viewData['subjects']) ? $viewData['subjects']->count() : 'NOT SET') . PHP_EOL;
    echo "- Classes: " . (isset($viewData['classes']) ? $viewData['classes']->count() : 'NOT SET') . PHP_EOL;
    echo "- Assignments: " . (isset($viewData['assignments']) ? $viewData['assignments']->count() : 'NOT SET') . PHP_EOL;
    echo "- Practicals: " . (isset($viewData['practicals']) ? $viewData['practicals']->count() : 'NOT SET') . PHP_EOL;
    echo "- Students: " . (isset($viewData['students']) ? $viewData['students']->count() : 'NOT SET') . PHP_EOL;
    
    // Test if view exists
    $viewName = $response->name();
    echo PHP_EOL . "View name: " . $viewName . PHP_EOL;
    
    if (view()->exists($viewName)) {
        echo "✅ View file exists!" . PHP_EOL;
    } else {
        echo "❌ View file not found!" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
