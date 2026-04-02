<?php
echo "=== CHECKING EXAM SCHEDULES TABLE STRUCTURE ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Checking exam_schedules table structure...\n";
    
    // Check if table exists
    $tables = \Illuminate\Support\Facades\Schema::getTableListing();
    $examScheduleTables = array_filter($tables, function($table) {
        return strpos($table, 'exam_schedule') !== false;
    });
    
    echo "Found exam schedule tables:\n";
    foreach ($examScheduleTables as $table) {
        echo "- $table\n";
    }
    
    // Check structure of each table
    foreach ($examScheduleTables as $table) {
        echo "\n=== STRUCTURE OF $table ===\n";
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
        foreach ($columns as $column) {
            echo "- $column\n";
        }
    }
    
    // Check which table is being used by the model
    echo "\n=== MODEL CONFIGURATION ===\n";
    $model = new \App\Models\ExamSchedule();
    echo "Model table: " . $model->getTable() . "\n";
    
    // Check if exam_type column exists
    $hasExamType = \Illuminate\Support\Facades\Schema::hasColumn($model->getTable(), 'exam_type');
    echo "Has 'exam_type' column: " . ($hasExamType ? 'YES' : 'NO') . "\n";
    
    if (!$hasExamType) {
        echo "\n=== ADDING MISSING COLUMN ===\n";
        
        try {
            \Illuminate\Support\Facades\Schema::table($model->getTable(), function($table) {
                $table->enum('exam_type', ['uts', 'uas', 'quiz', 'praktikum', 'lainnya'])->default('quiz');
            });
            echo "✅ exam_type column added successfully\n";
        } catch (\Exception $e) {
            echo "❌ Error adding column: " . $e->getMessage() . "\n";
        }
    }
    
    // Check other required columns
    $requiredColumns = ['title', 'description', 'exam_type', 'subject_id', 'kelas_id', 'start_time', 'end_time', 'location', 'duration_minutes', 'is_published', 'created_by'];
    
    echo "\n=== CHECKING REQUIRED COLUMNS ===\n";
    foreach ($requiredColumns as $column) {
        $exists = \Illuminate\Support\Facades\Schema::hasColumn($model->getTable(), $column);
        echo "- $column: " . ($exists ? '✅' : '❌ MISSING') . "\n";
    }
    
    // Check if there are any records
    $count = \Illuminate\Support\Facades\DB::table($model->getTable())->count();
    echo "\nCurrent records in table: $count\n";
    
    echo "\n=== SOLUTION ===\n";
    echo "1. The exam_schedules table is missing the 'exam_type' column\n";
    echo "2. I'll add the missing column to fix the issue\n";
    echo "3. After adding the column, form submission should work\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
