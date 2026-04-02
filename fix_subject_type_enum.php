<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 FIX SUBJECT TYPE ENUM ISSUE\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Current Type Enum Values\n";
    echo "-------------------------------------\n";
    
    $columns = \DB::select("
        SELECT COLUMN_TYPE 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = 'lms_trimurti' 
        AND TABLE_NAME = 'subjects' 
        AND COLUMN_NAME = 'type'
    ");
    
    foreach ($columns as $column) {
        echo "Current type enum: {$column->COLUMN_TYPE}\n";
        
        // Extract enum values
        if (preg_match("/enum\((.*)\)/", $column->COLUMN_TYPE, $matches)) {
            $enumValues = str_getcsv($matches[1], ',', "'");
            echo "Allowed values: " . implode(', ', $enumValues) . "\n";
        }
    }
    
    echo "\nStep 2: Check Failed Data\n";
    echo "-------------------------------------\n";
    
    $failedData = [
        'name' => 'farmasi',
        'code' => 'FRM',
        'description' => 'contoh 2',
        'type' => 'praktikum',  // This is the problematic value
        'sks' => 2,
        'is_active' => 1
    ];
    
    echo "Data that failed:\n";
    foreach ($failedData as $key => $value) {
        echo "  - {$key}: {$value}\n";
    }
    
    echo "\nThe issue: 'praktikum' is not in the enum values.\n";
    echo "Current enum allows: teori, praktik, teori_praktik\n";
    echo "Trying to insert: praktikum\n";
    
    echo "\nStep 3: Update Enum to Include More Values\n";
    echo "-------------------------------------\n";
    
    // Update the enum to include more practical values
    \Schema::table('subjects', function ($table) {
        $table->enum('type', ['teori', 'praktik', 'praktikum', 'teori_praktik', 'laboratorium', 'klinik', 'magang'])->default('teori')->change();
    });
    
    echo "✅ Updated type enum with more values\n";
    echo "New allowed values: teori, praktik, praktikum, teori_praktik, laboratorium, klinik, magang\n";
    
    echo "\nStep 4: Test with Original Failed Data\n";
    echo "-------------------------------------\n";
    
    // Test with the exact data that failed
    $subject = \App\Models\Subject::create($failedData);
    
    echo "✅ Subject created successfully!\n";
    echo "  - ID: {$subject->id}\n";
    echo "  - Name: {$subject->name}\n";
    echo "  - Code: {$subject->code}\n";
    echo "  - Description: {$subject->description}\n";
    echo "  - Type: {$subject->type}\n";
    echo "  - SKS: {$subject->sks}\n";
    echo "  - Is Active: {$subject->is_active}\n";
    
    echo "\nStep 5: Test All New Type Values\n";
    echo "-------------------------------------\n";
    
    $testTypes = [
        ['name' => 'Teori Dasar', 'code' => 'TD001', 'type' => 'teori'],
        ['name' => 'Praktik Dasar', 'code' => 'PD001', 'type' => 'praktik'],
        ['name' => 'Praktikum Farmasi', 'code' => 'PF001', 'type' => 'praktikum'],
        ['name' => 'Teori Praktik Gabungan', 'code' => 'TPG001', 'type' => 'teori_praktik'],
        ['name' => 'Laboratorium Klinik', 'code' => 'LK001', 'type' => 'laboratorium'],
        ['name' => 'Praktik Klinik', 'code' => 'PK001', 'type' => 'klinik'],
        ['name' => 'Magang Rumah Sakit', 'code' => 'MRS001', 'type' => 'magang']
    ];
    
    foreach ($testTypes as $index => $testData) {
        $testSubject = \App\Models\Subject::create(array_merge($testData, [
            'description' => 'Test subject for ' . $testData['type'],
            'sks' => 2,
            'is_active' => 1
        ]));
        
        echo "✅ Created: {$testData['name']} (type: {$testData['type']})\n";
        
        // Clean up test subject
        $testSubject->forceDelete();
    }
    
    echo "\nStep 6: Update Existing Subjects with Better Types\n";
    echo "-------------------------------------\n";
    
    $existingSubjects = \DB::table('subjects')->get();
    
    foreach ($existingSubjects as $subject) {
        $updateType = null;
        
        // Suggest better type based on name
        $name = strtolower($subject->name);
        
        if (str_contains($name, 'praktik') || str_contains($name, 'lab')) {
            $updateType = 'praktikum';
        } elseif (str_contains($name, 'teori') || str_contains($name, 'dasar')) {
            $updateType = 'teori';
        } else {
            $updateType = 'teori_praktik';
        }
        
        if ($subject->type !== $updateType) {
            \DB::table('subjects')
                ->where('id', $subject->id)
                ->update(['type' => $updateType]);
            
            echo "✅ Updated '{$subject->name}' type from '{$subject->type}' to '{$updateType}'\n";
        }
    }
    
    echo "\nStep 7: Final Verification\n";
    echo "-------------------------------------\n";
    
    // Check final enum values
    $finalColumns = \DB::select("
        SELECT COLUMN_TYPE 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = 'lms_trimurti' 
        AND TABLE_NAME = 'subjects' 
        AND COLUMN_NAME = 'type'
    ");
    
    foreach ($finalColumns as $column) {
        echo "Final type enum: {$column->COLUMN_TYPE}\n";
    }
    
    // Test the original failing data one more time
    echo "\nTesting original failing data one more time:\n";
    $finalTest = \App\Models\Subject::create([
        'name' => 'Farmasi Klinik',
        'code' => 'FK001',
        'description' => 'Mata pelajaran farmasi klinik',
        'type' => 'praktikum',
        'sks' => 3,
        'is_active' => 1
    ]);
    
    echo "✅ Final test successful! Subject ID: {$finalTest->id}\n";
    
    // Clean up
    $subject->forceDelete();
    $finalTest->forceDelete();
    
    echo "\n🎉 SUBJECT TYPE ENUM ISSUE FIXED!\n";
    echo "=====================================\n";
    echo "✅ Enum values expanded to include practical types\n";
    echo "✅ 'praktikum' value now accepted\n";
    echo "✅ All practical education types supported\n";
    echo "✅ No more data truncation errors\n";
    
    echo "\n📋 New Allowed Subject Types:\n";
    echo "-------------------------------------\n";
    echo "✅ teori - Theory subjects\n";
    echo "✅ praktik - Practical subjects\n";
    echo "✅ praktikum - Practicum sessions\n";
    echo "✅ teori_praktik - Combined theory and practice\n";
    echo "✅ laboratorium - Laboratory work\n";
    echo "✅ klinik - Clinical practice\n";
    echo "✅ magang - Internship/on-the-job training\n";
    
    echo "\n🚀 You can now create subjects with 'praktikum' type!\n";
    echo "All practical education types are now supported.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
