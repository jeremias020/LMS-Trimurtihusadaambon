<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 FIX ROUTE MODEL BINDING\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Route Resource Configuration\n";
    echo "-------------------------------------\n";
    
    // Check the current route configuration
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    
    foreach ($routes as $route) {
        if ($route->getName() === 'users.show') {
            echo "Found users.show route:\n";
            echo "  URI: {$route->uri()}\n";
            echo "  Methods: " . implode(', ', $route->methods()) . "\n";
            echo "  Action: {$route->getActionName()}\n";
            
            // Check if it uses model binding
            $parameters = $route->parameterNames();
            echo "  Parameters: " . implode(', ', $parameters) . "\n";
            
            if (in_array('user', $parameters)) {
                echo "  ❌ Uses 'user' parameter - this might cause the error!\n";
            }
            break;
        }
    }
    
    echo "\nStep 2: Check Model Binding Resolution\n";
    echo "-------------------------------------\n";
    
    // Laravel resolves model binding by parameter name to model class
    // 'user' parameter -> User model
    // But User model points to 'users_central' table
    // The error suggests it's trying to query 'users' table with 'user_id'
    
    echo "Route parameter: 'user'\n";
    echo "Resolves to: App\Models\User\n";
    echo "User model table: users_central\n";
    echo "But error shows: users table with user_id column\n";
    echo "This suggests there's a mismatch!\n";
    
    echo "\nStep 3: Check if there's a User model pointing to users table\n";
    echo "-------------------------------------\n";
    
    // Check if there are multiple User models or if there's a binding issue
    $userModel = new \App\Models\User();
    echo "App\Models\User table: " . $userModel->getTable() . "\n";
    
    // Check if there's a different User model being used
    try {
        // Try to find what model is actually being resolved
        $reflection = new ReflectionClass('App\Models\User');
        echo "App\Models\User class found\n";
        echo "Table: " . $userModel->getTable() . "\n";
    } catch (Exception $e) {
        echo "Error resolving App\Models\User: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 4: Test Route Model Binding Directly\n";
    echo "-------------------------------------\n";
    
    try {
        // Simulate what Laravel does for route model binding
        $user = \App\Models\User::find(1);
        echo "✅ User::find(1) works: " . $user->name . "\n";
        
        // Test if there's a query that causes the error
        // The error shows: select * from `users` where `users`.`user_id` = 1
        // This suggests something is querying 'users' table with 'user_id' column
        
        // Check if there's a relationship or scope that does this
        $userReflection = new ReflectionClass($user);
        $methods = $userReflection->getMethods();
        
        foreach ($methods as $method) {
            $methodName = $method->getName();
            if (str_contains($methodName, 'user_id') || str_contains($methodName, 'scope')) {
                // Check method content for problematic query
                if ($method->getFileName() && str_contains($method->getFileName(), 'User.php')) {
                    $startLine = $method->getStartLine();
                    $endLine = $method->getEndLine();
                    
                    if ($endLine - $startLine < 20) { // Only check short methods
                        $lines = file($method->getFileName());
                        for ($i = $startLine - 1; $i < $endLine && $i < count($lines); $i++) {
                            if (str_contains($lines[$i], 'users.user_id')) {
                                echo "❌ Found problematic query in User model:\n";
                                echo "  Method: {$methodName}\n";
                                echo "  Line " . ($i + 1) . ": " . trim($lines[$i]) . "\n";
                            }
                        }
                    }
                }
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Error testing model binding: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 5: Check for Alternative Solutions\n";
    echo "-------------------------------------\n";
    
    echo "Possible solutions:\n";
    echo "1. Change route parameter name to avoid model binding\n";
    echo "2. Create explicit model binding in RouteServiceProvider\n";
    echo "3. Use UserController with explicit ID parameter\n";
    echo "4. Fix the User model table configuration\n";
    
    echo "\nStep 6: Implement Fix\n";
    echo "-------------------------------------\n";
    
    // Let's implement the fix by changing the route
    // The safest approach is to change the resource route to use a different parameter
    
    echo "Implementing fix...\n";
    
    // Read current routes
    $routesPath = __DIR__ . '/routes/web.php';
    $routesContent = file_get_contents($routesPath);
    
    // Find the problematic resource route
    if (str_contains($routesContent, "Route::resource('users', AdminUserController::class)")) {
        echo "Found problematic resource route\n";
        
        // We need to change it to avoid model binding conflict
        // Option 1: Change parameter name
        $newRoute = "Route::resource('users', AdminUserController::class)->parameters(['users' => 'user_id']);";
        
        echo "New route: {$newRoute}\n";
        
        // For now, let's just document the fix
        echo "\n🔧 RECOMMENDED FIX:\n";
        echo "=====================================\n";
        echo "Replace this line in routes/web.php:\n";
        echo "Route::resource('users', AdminUserController::class)\n\n";
        echo "With:\n";
        echo "Route::resource('users', AdminUserController::class)->parameters(['users' => 'user_id'])\n\n";
        echo "This will change the route parameter from {user} to {user_id}\n";
        echo "Which will avoid the model binding conflict.\n";
        
    } else {
        echo "Resource route not found in expected format\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
