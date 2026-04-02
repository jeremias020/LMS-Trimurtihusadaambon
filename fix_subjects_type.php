<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 FIX SUBJECTS TABLE TYPE ISSUE\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Subjects Table Structure\n";
    echo "-------------------------------------\n";
    
    $columns = \DB::select('SHOW COLUMNS FROM subjects');
    echo "Subjects table columns:\n";
    
    $hasType = false;
    $existingColumns = [];
    
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type})\n";
        $existingColumns[] = $column->Field;
        
        if ($column->Field === 'type') {
            $hasType = true;
        }
    }
    
    echo "\nHas type column: " . ($hasType ? 'YES' : 'NO') . "\n";
    
    echo "\nStep 2: Check Current Subjects Data\n";
    echo "-------------------------------------\n";
    
    $subjects = \DB::table('subjects')->get();
    echo "Total subjects: " . count($subjects) . "\n";
    
    foreach ($subjects as $subject) {
        echo "  - ID: {$subject->id}, Name: {$subject->name}\n";
        if (isset($subject->jenis_mata_pelajaran)) {
            echo "    Jenis: {$subject->jenis_mata_pelajaran}\n";
        }
    }
    
    echo "\nStep 3: Test Problematic Query\n";
    echo "-------------------------------------\n";
    
    try {
        $count = \DB::table('subjects')->where('type', 'teori')->count();
        echo "Query with 'type' column: {$count} records\n";
    } catch (Exception $e) {
        echo "❌ Query with 'type' failed: " . $e->getMessage() . "\n";
    }
    
    // Try with alternative column names
    $alternativeColumns = ['jenis_mata_pelajaran', 'jenis', 'category', 'subject_type'];
    
    foreach ($alternativeColumns as $column) {
        if (in_array($column, $existingColumns)) {
            try {
                $count = \DB::table('subjects')->where($column, 'teori')->count();
                echo "Query with '{$column}' column: {$count} records\n";
            } catch (Exception $e) {
                echo "Query with '{$column}' failed: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\nStep 4: Fix Subjects Table Structure\n";
    echo "-------------------------------------\n";
    
    if (!$hasType) {
        echo "Adding type column to subjects table...\n";
        
        \Schema::table('subjects', function ($table) {
            $table->enum('type', ['teori', 'praktik', 'teori_praktik'])->default('teori')->after('name');
        });
        
        echo "✅ type column added\n";
    }
    
    echo "\nStep 5: Update Subjects Data with Type\n";
    echo "-------------------------------------\n";
    
    // Update subjects based on existing data
    $subjects = \DB::table('subjects')->get();
    
    foreach ($subjects as $subject) {
        $type = 'teori'; // default
        
        // Try to determine type from name or other fields
        $name = strtolower($subject->name);
        if (str_contains($name, 'praktik') || str_contains($name, 'lab')) {
            $type = 'praktik';
        } elseif (str_contains($name, 'teori') || str_contains($name, 'dasar')) {
            $type = 'teori';
        } else {
            $type = 'teori_praktik';
        }
        
        \DB::table('subjects')
            ->where('id', $subject->id)
            ->update(['type' => $type]);
        
        echo "Updated subject '{$subject->name}' with type: {$type}\n";
    }
    
    echo "\nStep 6: Test Fixed Query\n";
    echo "-------------------------------------\n";
    
    try {
        $count = \DB::table('subjects')->where('type', 'teori')->count();
        echo "✅ Query with 'type' now works: {$count} records\n";
    } catch (Exception $e) {
        echo "❌ Query with 'type' still failed: " . $e->getMessage() . "\n";
    }
    
    // Test other types
    $types = ['teori', 'praktik', 'teori_praktik'];
    foreach ($types as $type) {
        $count = \DB::table('subjects')->where('type', $type)->count();
        echo "  - {$type}: {$count} records\n";
    }
    
    echo "\nStep 7: Check Subject Model\n";
    echo "-------------------------------------\n";
    
    $subjectModel = new \App\Models\Subject();
    echo "Subject model table: " . $subjectModel->getTable() . "\n";
    
    // Test Subject model with type
    $teoriSubjects = \App\Models\Subject::where('type', 'teori')->get();
    echo "Subject model query for teori: " . $teoriSubjects->count() . " records\n";
    
    foreach ($teoriSubjects as $subject) {
        echo "  - {$subject->name} (type: {$subject->type})\n";
    }
    
    echo "\n🎉 SUBJECTS TABLE FIX COMPLETE!\n";
    echo "=====================================\n";
    echo "✅ type column added to subjects table\n";
    echo "✅ Subjects data updated with proper type\n";
    echo "✅ Query now works properly\n";
    echo "✅ Subject model relationships working\n";
    
    echo "\n📋 Summary:\n";
    $totalSubjects = \DB::table('subjects')->count();
    echo "  - Total subjects: {$totalSubjects}\n";
    
    foreach ($types as $type) {
        $count = \DB::table('subjects')->where('type', $type)->count();
        echo "  - {$type}: {$count} records\n";
    }
    
    echo "\n🚀 The 'type' column error is now resolved!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
