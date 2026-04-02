<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECKING CORRECT TABLE STRUCTURE ===\n\n";

echo "Step 1: Understanding the table relationship...\n";
echo "User model uses: users_central table\n";
echo "Student model uses: users table\n";
echo "Error occurs because Student model is looking for user_id in users table\n\n";

echo "Step 2: Checking if there's a students table...\n";
try {
    $tables = \Schema::getTableListing();
    $studentsTableExists = in_array('students', $tables);
    echo "Students table exists: " . ($studentsTableExists ? 'Yes' : 'No') . "\n";
    
    if ($studentsTableExists) {
        echo "Students table columns:\n";
        $studentsColumns = \Schema::getColumnListing('students');
        foreach ($studentsColumns as $column) {
            echo "  - {$column}\n";
        }
    }
} catch (Exception $e) {
    echo "Error checking tables: " . $e->getMessage() . "\n";
}

echo "\nStep 3: Checking current Student model configuration...\n";
$studentModel = new \App\Models\Student();
echo "Student table: " . $studentModel->getTable() . "\n";
echo "Student fillable fields: " . implode(', ', $studentModel->getFillable()) . "\n";

echo "\nStep 4: The real issue...\n";
echo "Student model extends Authenticatable and uses 'users' table\n";
echo "But it should have a separate 'students' table with user_id foreign key\n";
echo "OR the relationship should be different\n\n";

echo "Step 5: Testing current setup...\n";
try {
    $user = \App\Models\User::find(3);
    echo "Found user: {$user->name}\n";
    
    // Try to get student with current relationship
    $student = $user->siswa;
    if ($student) {
        echo "✓ Student relationship works\n";
    } else {
        echo "✗ Student relationship returns null\n";
        
        // Try to find student directly
        $directStudent = \App\Models\Student::where('id', 3)->first();
        if ($directStudent) {
            echo "✓ Found student directly with ID 3: {$directStudent->name}\n";
        } else {
            echo "✗ No student found with ID 3\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== SOLUTION ===\n";
echo "Option 1: Fix the relationship to use correct foreign key\n";
echo "Option 2: Create proper students table with user_id column\n";
echo "Option 3: Simplify the relationship structure\n";
?>
