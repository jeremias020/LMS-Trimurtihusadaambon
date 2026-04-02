<?php
echo "=== FIXING EXAM SCHEDULES TABLE STRUCTURE ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Fixing exam_schedules_new table...\n";
    
    $tableName = 'exam_schedules_new';
    
    // Add missing columns
    $columnsToAdd = [
        'subject_id' => 'bigint unsigned after description',
        'location' => 'string(255) nullable after end_time', 
        'duration_minutes' => 'integer nullable after location',
        'created_by' => 'bigint unsigned nullable after duration_minutes'
    ];
    
    foreach ($columnsToAdd as $column => $definition) {
        if (!\Illuminate\Support\Facades\Schema::hasColumn($tableName, $column)) {
            echo "Adding column: $column\n";
            \Illuminate\Support\Facades\Schema::table($tableName, function($table) use ($column, $definition) {
                if ($column === 'subject_id') {
                    $table->unsignedBigInteger($column)->nullable()->after('description');
                } elseif ($column === 'location') {
                    $table->string($column, 255)->nullable()->after('end_time');
                } elseif ($column === 'duration_minutes') {
                    $table->integer($column)->nullable()->after('location');
                } elseif ($column === 'created_by') {
                    $table->unsignedBigInteger($column)->nullable()->after('duration_minutes');
                }
            });
            echo "✅ $column added\n";
        } else {
            echo "✅ $column already exists\n";
        }
    }
    
    // Check if we need to migrate data from old columns
    echo "\n=== CHECKING DATA MIGRATION ===\n";
    
    // Check if old columns exist and need to be migrated
    $oldColumns = ['mata_pelajaran_id', 'guru_id'];
    $newColumns = ['subject_id', 'created_by'];
    
    for ($i = 0; $i < count($oldColumns); $i++) {
        $oldCol = $oldColumns[$i];
        $newCol = $newColumns[$i];
        
        if (\Illuminate\Support\Facades\Schema::hasColumn($tableName, $oldCol)) {
            echo "Found old column: $oldCol\n";
            
            // Migrate data if new column is empty
            $oldData = \Illuminate\Support\Facades\DB::table($tableName)->whereNotNull($oldCol)->get();
            if ($oldData->isNotEmpty()) {
                echo "Migrating data from $oldCol to $newCol...\n";
                foreach ($oldData as $row) {
                    \Illuminate\Support\Facades\DB::table($tableName)
                        ->where('id', $row->id)
                        ->update([$newCol => $row->$oldCol]);
                }
                echo "✅ Data migrated from $oldCol to $newCol\n";
            }
            
            // Optionally drop old column (commented out for safety)
            // \Illuminate\Support\Facades\Schema::table($tableName, function($table) use ($oldCol) {
            //     $table->dropColumn($oldCol);
            // });
            // echo "🗑️ Old column $oldCol dropped\n";
        }
    }
    
    echo "\n=== FINAL STRUCTURE CHECK ===\n";
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing($tableName);
    echo "Table $tableName columns:\n";
    foreach ($columns as $column) {
        echo "- $column\n";
    }
    
    // Check all required columns exist
    $requiredColumns = ['title', 'description', 'exam_type', 'subject_id', 'kelas_id', 'start_time', 'end_time', 'location', 'duration_minutes', 'is_published', 'created_by'];
    
    echo "\n=== REQUIRED COLUMNS CHECK ===\n";
    $allGood = true;
    foreach ($requiredColumns as $column) {
        $exists = \Illuminate\Support\Facades\Schema::hasColumn($tableName, $column);
        echo "- $column: " . ($exists ? '✅' : '❌ MISSING') . "\n";
        if (!$exists) $allGood = false;
    }
    
    if ($allGood) {
        echo "\n🎉 All required columns are present!\n";
        echo "✅ Form submission should now work correctly\n";
    } else {
        echo "\n❌ Some columns are still missing\n";
    }
    
    echo "\n=== TESTING FORM SUBMISSION ===\n";
    
    // Test creating a schedule
    $testData = [
        'title' => 'Test Schedule Fix',
        'description' => 'Testing after table fix',
        'exam_type' => 'quiz',
        'subject_id' => 1,
        'kelas_id' => 1,
        'start_time' => now()->addHours(1),
        'end_time' => now()->addHours(2),
        'location' => 'Test Room',
        'duration_minutes' => 60,
        'is_published' => false,
        'created_by' => 1
    ];
    
    try {
        $id = \Illuminate\Support\Facades\DB::table($tableName)->insertGetId($testData);
        echo "✅ Test record created with ID: $id\n";
        
        // Clean up test record
        \Illuminate\Support\Facades\DB::table($tableName)->delete($id);
        echo "✅ Test record cleaned up\n";
        
        echo "\n🎉 TABLE FIX COMPLETE!\n";
        echo "📱 Form submission should now work properly\n";
        
    } catch (\Exception $e) {
        echo "❌ Test insertion failed: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
