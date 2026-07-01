<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG NEW USER_ID ERROR\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Analyze the New Error\n";
    echo "-------------------------------------\n";
    
    echo "Error: SQLSTATE[42S22]: Column not found: 1054 Unknown column 'user_id'\n";
    echo "Query: select * from `users` where `user_id` = 3 and `users`.`deleted_at` is null limit 1\n\n";
    
    echo "This is different from the previous error!\n";
    echo "Previous: select * from `users` where `users`.`user_id` = 1\n";
    echo "Current:  select * from `users` where `user_id` = 3\n\n";
    
    echo "The query is missing 'users.' prefix before 'user_id'\n";
    echo "This suggests a different source of the problem.\n";
    
    echo "\nStep 2: Check if This is Route Model Binding Again\n";
    echo "-------------------------------------\n";
    
    // Check if there are any routes that might cause this
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    
    foreach ($routes as $route) {
        $name = $route->getName();
        $uri = $route->uri();
        
        // Look for routes that might use user_id parameter
        if (str_contains($uri, '{user_id}') || str_contains($uri, '{user}')) {
            echo "Route with user parameter: {$name}\n";
            echo "  URI: {$uri}\n";
            echo "  Action: {$route->getActionName()}\n";
            
            $parameters = $route->parameterNames();
            echo "  Parameters: " . implode(', ', $parameters) . "\n\n";
        }
    }
    
    echo "\nStep 3: Test the Problematic Query Directly\n";
    echo "-------------------------------------\n";
    
    try {
        // This should fail (and that's expected)
        $result = \Illuminate\Support\Facades\DB::table('users')
            ->where('user_id', 3)
            ->whereNull('deleted_at')
            ->first();
        
        echo "❌ Unexpected: The problematic query worked!\n";
    } catch (\Exception $e) {
        echo "✅ Expected: The problematic query failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 4: Check for Manual User Queries\n";
    echo "-------------------------------------\n";
    
    // Search for queries that might use 'user_id' without table prefix
    $directories = [
        __DIR__ . '/app/Http/Controllers',
        __DIR__ . '/app/Models'
    ];
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
            $files = [];
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $files[] = $file->getPathname();
                }
            }
            
            foreach ($files as $file) {
                $content = file_get_contents($file);
                
                // Look for patterns like ->where('user_id'
                if (str_contains($content, "->where('user_id'") || str_contains($content, '->where("user_id"')) {
                    echo "Found ->where('user_id') in: " . str_replace(__DIR__, '', $file) . "\n";
                    
                    $lines = explode("\n", $content);
                    foreach ($lines as $lineNum => $line) {
                        if (str_contains($line, "->where('user_id'") || str_contains($line, '->where("user_id"')) {
                            echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                        }
                    }
                    echo "\n";
                }
            }
        }
    }
    
    echo "\nStep 5: Check for User Model Usage\n";
    echo "-------------------------------------\n";
    
    // The query looks like it's trying to find a user by user_id
    // Let's see if there are any places that do User::where('user_id', ...)
    
    try {
        // Test if User model has user_id column
        $userModel = new \App\Models\User();
        echo "User model table: " . $userModel->getTable() . "\n";
        
        $userColumns = \Illuminate\Support\Facades\Schema::getColumnListing($userModel->getTable());
        echo "User table columns:\n";
        foreach ($userColumns as $column) {
            echo "  - {$column}\n";
        }
        
        if (in_array('user_id', $userColumns)) {
            echo "✅ User table has user_id column\n";
        } else {
            echo "❌ User table does NOT have user_id column\n";
        }
        
    } catch (\Exception $e) {
        echo "Error checking User model: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 6: Check for Alternative User Tables\n";
    echo "-------------------------------------\n";
    
    // Maybe there's another 'users' table that has user_id column
    $allTables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
    
    $usersTables = [];
    foreach ($allTables as $table) {
        $tableName = array_values((array)$table)[0];
        if (str_contains($tableName, 'user')) {
            $usersTables[] = $tableName;
        }
    }
    
    echo "Tables with 'user' in name:\n";
    foreach ($usersTables as $table) {
        echo "  - {$table}\n";
        
        // Check if this table has user_id column
        try {
            $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
            if (in_array('user_id', $columns)) {
                echo "    ✅ Has user_id column\n";
            }
        } catch (\Exception $e) {
            echo "    ❌ Could not check columns\n";
        }
    }
    
    echo "\nStep 7: Test the Correct Query\n";
    echo "-------------------------------------\n";
    
    // Test what the correct query should be
    try {
        $result = \Illuminate\Support\Facades\DB::table('users')
            ->where('id', 3)
            ->whereNull('deleted_at')
            ->first();
        
        echo "✅ Correct query works: " . ($result ? "Found user: {$result->name}" : "No user found") . "\n";
    } catch (\Exception $e) {
        echo "❌ Correct query failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n🔧 SOLUTION:\n";
    echo "=====================================\n";
    echo "The error indicates that somewhere in the code,\n";
    echo "there's a query trying to use 'user_id' column\n";
    echo "in the 'users' table, but this column doesn't exist.\n\n";
    
    echo "This is likely caused by:\n";
    echo "1. Manual query using ->where('user_id', \$id)\n";
    echo "2. Incorrect model relationship\n";
    echo "3. Wrong column reference in controller\n\n";
    
    echo "Need to find and fix the source of this query.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
