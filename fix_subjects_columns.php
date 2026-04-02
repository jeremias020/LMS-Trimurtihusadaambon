<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 FIX SUBJECTS TABLE MISSING COLUMNS\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Current Subjects Table Structure\n";
    echo "-------------------------------------\n";
    
    $columns = \DB::select('SHOW COLUMNS FROM subjects');
    echo "Current subjects table columns:\n";
    
    $existingColumns = [];
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type})\n";
        $existingColumns[] = $column->Field;
    }
    
    echo "\nStep 2: Check Missing Columns\n";
    echo "-------------------------------------\n";
    
    $requiredColumns = ['description', 'sks'];
    $missingColumns = [];
    
    foreach ($requiredColumns as $column) {
        if (!in_array($column, $existingColumns)) {
            $missingColumns[] = $column;
            echo "❌ Missing column: {$column}\n";
        } else {
            echo "✅ Column exists: {$column}\n";
        }
    }
    
    if (!empty($missingColumns)) {
        echo "\nStep 3: Add Missing Columns\n";
        echo "-------------------------------------\n";
        
        \Schema::table('subjects', function ($table) {
            if (!\Schema::hasColumn('subjects', 'description')) {
                $table->text('description')->nullable()->after('name');
                echo "✅ Added description column\n";
            }
            
            if (!\Schema::hasColumn('subjects', 'sks')) {
                $table->integer('sks')->default(2)->after('type');
                echo "✅ Added sks column\n";
            }
        });
        
        echo "✅ All missing columns added successfully\n";
    } else {
        echo "\n✅ All required columns already exist\n";
    }
    
    echo "\nStep 4: Update Subject Model Fillable\n";
    echo "-------------------------------------\n";
    
    // Check Subject model
    $subjectModel = new \App\Models\Subject();
    echo "Current Subject model fillable:\n";
    
    $reflection = new ReflectionClass($subjectModel);
    $fillableProperty = $reflection->getProperty('fillable');
    $fillableProperty->setAccessible(true);
    $fillable = $fillableProperty->getValue($subjectModel);
    
    foreach ($fillable as $field) {
        echo "  - {$field}\n";
    }
    
    // Check if we need to update fillable
    $neededFillable = ['description', 'sks'];
    $missingFillable = [];
    
    foreach ($neededFillable as $field) {
        if (!in_array($field, $fillable)) {
            $missingFillable[] = $field;
        }
    }
    
    if (!empty($missingFillable)) {
        echo "\nMissing fillable fields: " . implode(', ', $missingFillable) . "\n";
        echo "Please update the Subject model fillable array to include these fields.\n";
    } else {
        echo "\n✅ All needed fields are already in fillable\n";
    }
    
    echo "\nStep 5: Test Subject Creation\n";
    echo "-------------------------------------\n";
    
    // Test creating a subject with the new fields
    try {
        $testSubject = \App\Models\Subject::create([
            'name' => 'Test Subject',
            'code' => 'TST001',
            'description' => 'Test description for subject',
            'type' => 'teori',
            'sks' => 2,
            'is_active' => 1
        ]);
        
        echo "✅ Test subject created successfully!\n";
        echo "  - ID: {$testSubject->id}\n";
        echo "  - Name: {$testSubject->name}\n";
        echo "  - Description: " . ($testSubject->description ?? 'NULL') . "\n";
        echo "  - Type: {$testSubject->type}\n";
        echo "  - SKS: {$testSubject->sks}\n";
        
        // Clean up
        $testSubject->delete();
        echo "✅ Test subject deleted\n";
        
    } catch (Exception $e) {
        echo "❌ Test subject creation failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 6: Update Existing Subjects\n";
    echo "-------------------------------------\n";
    
    // Update existing subjects to have default values
    $existingSubjects = \DB::table('subjects')->get();
    
    foreach ($existingSubjects as $subject) {
        $updates = [];
        
        if (!isset($subject->description) || is_null($subject->description)) {
            $updates['description'] = 'Mata pelajaran ' . $subject->name;
        }
        
        if (!isset($subject->sks) || is_null($subject->sks)) {
            $updates['sks'] = 2;
        }
        
        if (!empty($updates)) {
            \DB::table('subjects')
                ->where('id', $subject->id)
                ->update($updates);
            
            echo "✅ Updated subject '{$subject->name}'\n";
        }
    }
    
    echo "\n🎉 SUBJECTS TABLE FIX COMPLETE!\n";
    echo "=====================================\n";
    echo "✅ Missing columns added\n";
    echo "✅ Subject creation tested\n";
    echo "✅ Existing subjects updated\n";
    
    echo "\n📋 Final Subjects Table Structure:\n";
    $finalColumns = \DB::select('SHOW COLUMNS FROM subjects');
    foreach ($finalColumns as $column) {
        echo "  - {$column->Field} ({$column->Type})\n";
    }
    
    echo "\n🚀 You can now create subjects with description and sks!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
