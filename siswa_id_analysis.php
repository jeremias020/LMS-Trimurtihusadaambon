<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 SISWA_ID COLUMN ANALYSIS\n";
echo "=====================================\n";

try {
    echo "=== Testing if siswa_id column exists ===\n";
    
    // Check if siswa_id column exists in users table
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    if (in_array('siswa_id', $columns)) {
        echo "✅ siswa_id column exists in users table\n";
        
        // Test query with siswa_id
        try {
            $result = \Illuminate\Support\Facades\DB::table('users')
                ->where('siswa_id', 3)
                ->whereNull('deleted_at')
                ->first();
            
            echo "✅ Query with siswa_id works: " . ($result ? $result->name : 'Not found') . "\n";
        } catch (\Exception $e) {
            echo "❌ Query with siswa_id failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ siswa_id column does not exist in users table\n";
    }
    
    echo "\n=== Testing User Model with siswa_id ===\n";
    
    // Check if User model has siswa_id in fillable
    $userModel = new \App\Models\User();
    $fillable = $userModel->getFillable();
    
    if (in_array('siswa_id', $fillable)) {
        echo "✅ siswa_id is in User model fillable\n";
    } else {
        echo "❌ siswa_id is not in User model fillable\n";
    }
    
    echo "\n=== Testing Student Model Relations ===\n";
    
    // Check if Student model should have different relation
    echo "Current Student model table: users\n";
    echo "Student model fillable:\n";
    $studentModel = new \App\Models\Student();
    $studentFillable = $studentModel->getFillable();
    foreach ($studentFillable as $field) {
        echo "  - {$field}\n";
    }
    
    echo "\n=== Testing if Student should use siswa table ===\n";
    
    // Check if there's a siswa table
    $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
    $siswaTableExists = false;
    
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        if ($tableName === 'siswa') {
            $siswaTableExists = true;
            break;
        }
    }
    
    if ($siswaTableExists) {
        echo "✅ siswa table exists\n";
        
        $siswaColumns = \Illuminate\Support\Facades\Schema::getColumnListing('siswa');
        echo "siswa table columns:\n";
        foreach ($siswaColumns as $column) {
            echo "  - {$column}\n";
        }
    } else {
        echo "❌ siswa table does not exist\n";
    }
    
    echo "\n=== Testing the Exact Error Query ===\n";
    
    // The error: select * from `users` where `user_id` = 3 and `users`.`deleted_at` is null limit 1
    echo "Testing: SELECT * FROM users WHERE user_id = 3 AND deleted_at IS NULL LIMIT 1\n";
    
    try {
        $result = \Illuminate\Support\Facades\DB::table('users')
            ->where('user_id', 3)
            ->whereNull('deleted_at')
            ->first();
        
        echo "❌ Unexpected: The error query worked!\n";
        echo "This means there's actually a user_id column\n";
        
        // Double-check table structure
        $allColumns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
        echo "All users table columns:\n";
        foreach ($allColumns as $column) {
            if (str_contains($column, 'user')) {
                echo "  - {$column} ⭐ (contains 'user')\n";
            } else {
                echo "  - {$column}\n";
            }
        }
        
    } catch (\Exception $e) {
        echo "✅ Expected: Error query fails\n";
        echo "Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎯 ANALYSIS:\n";
    echo "=====================================\n";
    echo "The error suggests:\n";
    echo "1. There's a query somewhere using 'user_id' column\n";
    echo "2. The 'users' table might have both 'id' and 'user_id' columns\n";
    echo "3. Or there's cached query information\n";
    echo "4. Or there's a relationship causing this\n\n";
    
    echo "Next steps:\n";
    echo "1. Check if users table actually has user_id column\n";
    echo "2. Look for any remaining user_id references\n";
    echo "3. Check database views or triggers\n";
    echo "4. Restart web server completely\n";
    
    echo "\n✨ ANALYSIS COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
