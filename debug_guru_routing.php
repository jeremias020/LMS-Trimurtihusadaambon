<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG GURU FORM ROUTING ISSUE\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Current Form Content\n";
    echo "-------------------------------------\n";
    
    $formPath = __DIR__ . '/resources/views/admin/users/create-guru.blade.php';
    $formContent = file_get_contents($formPath);
    
    // Extract form action
    if (preg_match('/<form[^>]*action="([^"]*)"[^>]*>/', $formContent, $matches)) {
        echo "Form action found: {$matches[1]}\n";
        
        // Check if it uses route() helper
        if (str_contains($matches[1], 'route(')) {
            if (preg_match('/route\([\'"]([^\'"]+)[\'"]\)/', $matches[1], $routeMatch)) {
                echo "Route name: {$routeMatch[1]}\n";
            }
        }
    }
    
    echo "\nStep 2: Check Controller Redirect\n";
    echo "-------------------------------------\n";
    
    $controllerPath = __DIR__ . '/app/Http/Controllers/Admin/ModernUserController.php';
    $controllerContent = file_get_contents($controllerPath);
    
    // Find the storeGuru method
    if (preg_match('/public function storeGuru.*?return redirect\(\)->route\([\'"]([^\'"]+)[\'"]\)/s', $controllerContent, $matches)) {
        echo "Controller redirect route: {$matches[1]}\n";
    }
    
    echo "\nStep 3: Test Route Resolution\n";
    echo "-------------------------------------\n";
    
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    
    $routesToCheck = [
        'admin.users.store.guru',
        'admin.users.guru',
        'users.store.guru',
        'users.guru'
    ];
    
    foreach ($routesToCheck as $routeName) {
        $found = false;
        foreach ($routes as $route) {
            if ($route->getName() === $routeName) {
                echo "✅ Route '{$routeName}' found: {$route->uri()}\n";
                $found = true;
                break;
            }
        }
        if (!$found) {
            echo "❌ Route '{$routeName}' NOT found\n";
        }
    }
    
    echo "\nStep 4: Simulate Route Generation\n";
    echo "-------------------------------------\n";
    
    try {
        // Test if routes can be generated
        $testRoutes = ['admin.users.store.guru', 'admin.users.guru'];
        
        foreach ($testRoutes as $routeName) {
            try {
                $url = route($routeName);
                echo "✅ route('{$routeName}') = {$url}\n";
            } catch (Exception $e) {
                echo "❌ route('{$routeName}') ERROR: {$e->getMessage()}\n";
            }
        }
    } catch (Exception $e) {
        echo "❌ Route generation error: {$e->getMessage()}\n";
    }
    
    echo "\nStep 5: Check for Validation Errors\n";
    echo "-------------------------------------\n";
    
    // Check if there might be validation errors causing redirect back
    $testData = [
        'name' => 'Test Guru ' . time(),
        'email' => 'testguru' . time() . '@example.com',
        'username' => 'testguru' . time(),
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'nip' => str_shuffle('1234567890'),
        'subject_id' => 1,
        'pendidikan_terakhir' => 'S1',
    ];
    
    $validator = \Illuminate\Support\Facades\Validator::make($testData, [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users_central,email',
        'username' => 'required|string|max:255|unique:users_central,username',
        'password' => 'required|string|min:8|confirmed',
        'subject_id' => 'required|exists:subjects,id',
        'nip' => 'required|string|max:50|unique:gurus,nip',
    ]);
    
    if ($validator->fails()) {
        echo "❌ Validation would fail:\n";
        foreach ($validator->errors()->all() as $error) {
            echo "  - {$error}\n";
        }
    } else {
        echo "✅ Validation would pass\n";
    }
    
    echo "\nStep 6: Check for Exception Handling\n";
    echo "-------------------------------------\n";
    
    // Look for try-catch blocks in storeGuru
    if (preg_match('/public function storeGuru.*?try\s*{(.*?)}\s*catch/s', $controllerContent, $matches)) {
        echo "✅ Try-catch block found in storeGuru\n";
        
        if (str_contains($matches[1], 'redirect()->back()')) {
            echo "✅ Exception redirects back (this could be the issue)\n";
        }
    }
    
    echo "\n🔍 ANALYSIS RESULTS:\n";
    echo "=====================================\n";
    
    // Re-check current form content
    if (str_contains($formContent, "route('admin.users.store.guru')")) {
        echo "✅ Form action is CORRECT: admin.users.store.guru\n";
    } else {
        echo "❌ Form action is WRONG\n";
        echo "Current content: " . substr($formContent, strpos($formContent, '<form'), 100) . "\n";
    }
    
    if (str_contains($controllerContent, "return redirect()->route('admin.users.guru')")) {
        echo "✅ Controller redirect is CORRECT: admin.users.guru\n";
    } else {
        echo "❌ Controller redirect is WRONG\n";
        echo "Current redirect: " . substr($controllerContent, strpos($controllerContent, 'return redirect()->route'), 100) . "\n";
    }
    
    echo "\n📋 POSSIBLE CAUSES:\n";
    echo "=====================================\n";
    echo "1. ❌ Form action still uses wrong route\n";
    echo "2. ❌ Controller redirect still uses wrong route\n";
    echo "3. ❌ Validation errors causing redirect back\n";
    echo "4. ❌ Exception being thrown and caught\n";
    echo "5. ❌ Route caching issue\n";
    
    echo "\n🔧 RECOMMENDED ACTIONS:\n";
    echo "=====================================\n";
    echo "1. Clear route cache: php artisan route:clear\n";
    echo "2. Clear view cache: php artisan view:clear\n";
    echo "3. Check browser network tab for actual request\n";
    echo "4. Check Laravel logs for errors\n";
    echo "5. Verify form submission data\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
