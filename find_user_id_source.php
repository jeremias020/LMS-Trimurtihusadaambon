<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 FIND USER_ID QUERY SOURCE\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check User Model Configuration\n";
    echo "-------------------------------------\n";
    
    $userModel = new \App\Models\User();
    echo "User model table: " . $userModel->getTable() . "\n";
    
    // Check if User model has any relationships that might cause this
    $reflection = new ReflectionClass($userModel);
    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
    
    echo "User model public methods:\n";
    foreach ($methods as $method) {
        if (!str_starts_with($method->getName(), '__') && $method->getDeclaringClass()->getName() === 'App\Models\User') {
            echo "  - " . $method->getName() . "\n";
        }
    }
    
    echo "\nStep 2: Check for Eager Loading Issues\n";
    echo "-------------------------------------\n";
    
    // The error suggests there might be an eager loading issue
    // Let's check if there are any relationships that might cause this
    
    // Check if there's a relationship that's trying to load from users table with user_id
    try {
        // Test if there's a guru relationship that's causing this
        $testUser = \App\Models\User::find(1);
        if ($testUser) {
            echo "Found user with ID 1: {$testUser->name}\n";
            
            // Try to load relationships
            try {
                $guru = $testUser->guru;
                echo "✅ User->guru relationship works\n";
            } catch (\Exception $e) {
                echo "❌ User->guru relationship failed: " . $e->getMessage() . "\n";
            }
        }
    } catch (\Exception $e) {
        echo "Error finding user: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 3: Search for Problematic Code\n";
    echo "-------------------------------------\n";
    
    // Search in controller files for the issue
    $controllerDir = __DIR__ . '/app/Http/Controllers';
    if (is_dir($controllerDir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($controllerDir));
        $files = [];
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            
            // Look for patterns that might cause this error
            if (str_contains($content, 'users.user_id')) {
                echo "Found 'users.user_id' in: " . str_replace(__DIR__, '', $file) . "\n";
                
                $lines = explode("\n", $content);
                foreach ($lines as $lineNum => $line) {
                    if (str_contains($line, 'users.user_id')) {
                        echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                    }
                }
                echo "\n";
            }
            
            // Also check for where clauses that might be problematic
            if (str_contains($content, 'where(\'users.user_id\'')) {
                echo "Found where('users.user_id') in: " . str_replace(__DIR__, '', $file) . "\n";
                
                $lines = explode("\n", $content);
                foreach ($lines as $lineNum => $line) {
                    if (str_contains($line, 'where(\'users.user_id\'')) {
                        echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                    }
                }
                echo "\n";
            }
        }
    }
    
    echo "\nStep 4: Check for Route Model Binding Issues\n";
    echo "-------------------------------------\n";
    
    // The error might be coming from route model binding
    // Let's check the routes
    
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    
    foreach ($routes as $route) {
        $uri = $route->uri();
        
        // Look for routes that might use User model
        if (str_contains($uri, '{user}') || str_contains($uri, '{guru}')) {
            echo "Route with model binding: {$route->methods()[0]} {$uri}\n";
            echo "  Action: {$route->getActionName()}\n";
        }
    }
    
    echo "\nStep 5: Check Guru Model User Relationship\n";
    echo "-------------------------------------\n";
    
    $guruModel = new \App\Models\Guru();
    
    // Check the user relationship
    try {
        $guru = \App\Models\Guru::first();
        if ($guru) {
            echo "Found guru: {$guru->name}\n";
            
            try {
                $user = $guru->user;
                echo "✅ Guru->user relationship works: {$user->name}\n";
            } catch (\Exception $e) {
                echo "❌ Guru->user relationship failed: " . $e->getMessage() . "\n";
                echo "This might be the source of the error!\n";
            }
        }
    } catch (\Exception $e) {
        echo "Error finding guru: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 6: Check for Soft Deletes Issues\n";
    echo "-------------------------------------\n";
    
    // The error includes deleted_at, so it might be related to soft deletes
    // Let's check if User model has soft deletes
    
    if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses('App\Models\User'))) {
        echo "✅ User model uses SoftDeletes\n";
    } else {
        echo "❌ User model does NOT use SoftDeletes\n";
    }
    
    // Check users table structure for deleted_at
    $usersColumns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    if (in_array('deleted_at', $usersColumns)) {
        echo "✅ Users table has deleted_at column\n";
    } else {
        echo "❌ Users table does NOT have deleted_at column\n";
    }
    
    echo "\n🔧 LIKELY SOLUTION:\n";
    echo "=====================================\n";
    echo "The error suggests that somewhere in the code,\n";
    echo "there's a query trying to find a user from the 'users'\n";
    echo "table using 'user_id' column instead of 'id'.\n\n";
    
    echo "Most likely causes:\n";
    echo "1. Route model binding with wrong table\n";
    echo "2. Relationship definition pointing to wrong table\n";
    echo "3. Query builder with wrong column reference\n\n";
    
    echo "Recommendations:\n";
    echo "1. Check if there are any routes using {user} parameter\n";
    echo "2. Verify User model table configuration\n";
    echo "3. Check relationship definitions in other models\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
