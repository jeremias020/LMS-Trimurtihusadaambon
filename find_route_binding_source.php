<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 FIND ROUTE MODEL BINDING SOURCE\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check if There's Still Route Model Binding\n";
    echo "-------------------------------------\n";
    
    // The error suggests there might still be automatic model binding happening
    // Let's check if there are any routes that still use {user} parameter
    
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $problemRoutes = [];
    
    foreach ($routes as $route) {
        $parameters = $route->parameterNames();
        
        if (in_array('user', $parameters)) {
            $problemRoutes[] = [
                'name' => $route->getName(),
                'uri' => $route->uri(),
                'methods' => implode(', ', $route->methods()),
                'action' => $route->getActionName()
            ];
        }
    }
    
    if (empty($problemRoutes)) {
        echo "✅ No routes found with {user} parameter\n";
    } else {
        echo "❌ Found routes with {user} parameter:\n";
        foreach ($problemRoutes as $route) {
            echo "  - {$route['name']}: {$route['uri']}\n";
        }
    }
    
    echo "\nStep 2: Check RouteServiceProvider\n";
    echo "-------------------------------------\n";
    
    // Check if there's any explicit model binding in RouteServiceProvider
    $routeServiceProviderPath = __DIR__ . '/app/Providers/RouteServiceProvider.php';
    
    if (file_exists($routeServiceProviderPath)) {
        $content = file_get_contents($routeServiceProviderPath);
        
        if (str_contains($content, 'User') && str_contains($content, 'bind')) {
            echo "Found User model binding in RouteServiceProvider:\n";
            
            $lines = explode("\n", $content);
            foreach ($lines as $lineNum => $line) {
                if (str_contains($line, 'User') && str_contains($line, 'bind')) {
                    echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                }
            }
        } else {
            echo "✅ No User model binding found in RouteServiceProvider\n";
        }
    } else {
        echo "❌ RouteServiceProvider not found\n";
    }
    
    echo "\nStep 3: Check for Implicit Model Binding\n";
    echo "-------------------------------------\n";
    
    // Laravel might still be doing implicit model binding
    // Let's check if any controller methods have User type-hinted parameters
    
    $controllerFiles = [
        __DIR__ . '/app/Http/Controllers/Admin/UserController.php',
        __DIR__ . '/app/Http/Controllers/Admin/ModernUserController.php'
    ];
    
    foreach ($controllerFiles as $file) {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $filename = basename($file);
            
            // Look for method signatures with User type hint
            if (preg_match('/public function \w+\s*\(\s*[^)]*User\s+\$user\s*[^)]*\)/', $content)) {
                echo "Found User type-hinted parameter in {$filename}:\n";
                
                $lines = explode("\n", $content);
                foreach ($lines as $lineNum => $line) {
                    if (preg_match('/public function \w+\s*\(\s*[^)]*User\s+\$user\s*[^)]*\)/', $line)) {
                        echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                    }
                }
                echo "\n";
            }
        }
    }
    
    echo "\nStep 4: Check for Global Scopes or Traits\n";
    echo "-------------------------------------\n";
    
    // Maybe there's a global scope or trait that's causing this
    $userModelPath = __DIR__ . '/app/Models/User.php';
    
    if (file_exists($userModelPath)) {
        $content = file_get_contents($userModelPath);
        
        // Check for any global scopes
        if (str_contains($content, 'booted') || str_contains($content, 'addGlobalScope')) {
            echo "Found potential global scope in User model:\n";
            
            $lines = explode("\n", $content);
            foreach ($lines as $lineNum => $line) {
                if (str_contains($line, 'booted') || str_contains($line, 'addGlobalScope')) {
                    echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                }
            }
        } else {
            echo "✅ No global scopes found in User model\n";
        }
    }
    
    echo "\nStep 5: Test Manual Route Resolution\n";
    echo "-------------------------------------\n";
    
    // Let's try to manually resolve what happens when we access a user route
    try {
        // Test the route resolution
        $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName('admin.users.show');
        
        if ($route) {
            echo "Found admin.users.show route\n";
            echo "URI: {$route->uri()}\n";
            
            // Try to generate URL
            $url = route('admin.users.show', ['user_id' => 3]);
            echo "Generated URL: {$url}\n";
            
            // The issue might be that Laravel is still trying to do model binding
            // even though we changed the parameter name
            
        } else {
            echo "❌ admin.users.show route not found\n";
        }
    } catch (\Exception $e) {
        echo "Error testing route: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 6: Check for Cached Routes\n";
    echo "-------------------------------------\n";
    
    // Maybe there are cached routes that still have the old configuration
    echo "Checking for cached routes...\n";
    
    $cachePath = __DIR__ . '/bootstrap/cache/routes.php';
    if (file_exists($cachePath)) {
        echo "❌ Found cached routes file\n";
        echo "This might be causing the old route binding to still be active\n";
        
        // Clear the cache
        unlink($cachePath);
        echo "✅ Deleted cached routes file\n";
    } else {
        echo "✅ No cached routes file found\n";
    }
    
    echo "\n🔧 LIKELY SOLUTION:\n";
    echo "=====================================\n";
    echo "The error suggests that Laravel is still trying to do\n";
    echo "automatic model binding for User model, even though we\n";
    echo "changed the route parameter to user_id.\n\n";
    
    echo "Possible causes:\n";
    echo "1. Cached routes with old configuration\n";
    echo "2. Implicit model binding in controller methods\n";
    echo "3. RouteServiceProvider with explicit binding\n";
    echo "4. Middleware or global scope interfering\n\n";
    
    echo "Solutions to try:\n";
    echo "1. Clear all caches (route, config, view)\n";
    echo "2. Check for User type-hinted parameters in controllers\n";
    echo "3. Restart the application server\n";
    
    echo "\n✨ NEED TO CLEAR ALL CACHES! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
