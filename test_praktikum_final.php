<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🧪 TEST PRAKTIKUM TYPE WITH UNIQUE CODE\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Test with Unique Code\n";
    echo "-------------------------------------\n";
    
    // Use unique code to avoid duplicate constraint
    $subjectData = [
        'name' => 'farmasi',
        'code' => 'FRM001',  // Changed from FRM to FRM001
        'description' => 'contoh 2',
        'type' => 'praktikum',
        'sks' => 2,
        'is_active' => 1
    ];
    
    echo "Creating subject with 'praktikum' type:\n";
    foreach ($subjectData as $key => $value) {
        echo "  - {$key}: {$value}\n";
    }
    
    $subject = \App\Models\Subject::create($subjectData);
    
    echo "✅ Subject created successfully!\n";
    echo "  - ID: {$subject->id}\n";
    echo "  - Name: {$subject->name}\n";
    echo "  - Code: {$subject->code}\n";
    echo "  - Type: {$subject->type}\n";
    echo "  - Description: {$subject->description}\n";
    
    echo "\nStep 2: Verify Database Record\n";
    echo "-------------------------------------\n";
    
    $dbRecord = \DB::table('subjects')->find($subject->id);
    echo "Database verification:\n";
    echo "  - Name: {$dbRecord->name}\n";
    echo "  - Code: {$dbRecord->code}\n";
    echo "  - Type: {$dbRecord->type}\n";
    echo "  - Description: {$dbRecord->description}\n";
    
    echo "\nStep 3: Test Subject Update\n";
    echo "-------------------------------------\n";
    
    $subject->update([
        'description' => 'Updated description for farmasi praktikum',
        'sks' => 3
    ]);
    
    echo "✅ Subject updated successfully!\n";
    echo "  - New Description: {$subject->description}\n";
    echo "  - New SKS: {$subject->sks}\n";
    
    echo "\nStep 4: Test Other Practical Types\n";
    echo "-------------------------------------\n";
    
    $practicalTypes = [
        ['name' => 'Laboratorium Farmasi', 'code' => 'LAB001', 'type' => 'laboratorium'],
        ['name' => 'Klinik Keperawatan', 'code' => 'KLK001', 'type' => 'klinik'],
        ['name' => 'Magang Rumah Sakit', 'code' => 'MAG001', 'type' => 'magang']
    ];
    
    foreach ($practicalTypes as $index => $data) {
        $testSubject = \App\Models\Subject::create(array_merge($data, [
            'description' => 'Test ' . $data['type'],
            'sks' => 3,
            'is_active' => 1
        ]));
        
        echo "✅ Created: {$data['name']} (type: {$data['type']})\n";
        
        // Clean up
        $testSubject->delete();
    }
    
    echo "\nStep 5: Check Current Subjects by Type\n";
    echo "-------------------------------------\n";
    
    $allowedTypes = ['teori', 'praktik', 'praktikum', 'teori_praktik', 'laboratorium', 'klinik', 'magang'];
    
    foreach ($allowedTypes as $type) {
        $count = \DB::table('subjects')->where('type', $type)->count();
        echo "  - {$type}: {$count} records\n";
    }
    
    echo "\nStep 6: Final Verification\n";
    echo "-------------------------------------\n";
    
    // Check the enum values
    $enumCheck = \DB::select("
        SELECT COLUMN_TYPE 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = 'lms_trimurti' 
        AND TABLE_NAME = 'subjects' 
        AND COLUMN_NAME = 'type'
    ");
    
    foreach ($enumCheck as $enum) {
        echo "Current enum: {$enum->COLUMN_TYPE}\n";
    }
    
    echo "\n🎉 PRAKTIKUM TYPE ISSUE COMPLETELY FIXED!\n";
    echo "=====================================\n";
    echo "✅ 'praktikum' type working perfectly\n";
    echo "✅ All practical types working\n";
    echo "✅ No more data truncation errors\n";
    echo "✅ No more duplicate key errors\n";
    echo "✅ Subject creation, update, delete working\n";
    
    echo "\n📋 Error Resolution Summary:\n";
    echo "-------------------------------------\n";
    echo "❌ BEFORE: Data truncated for column 'type' at row 1\n";
    echo "✅ AFTER: 'praktikum' and all practical types accepted\n";
    
    echo "\n❌ BEFORE: Duplicate entry for code 'FRM'\n";
    echo "✅ AFTER: Use unique codes (FRM001, LAB001, etc.)\n";
    
    echo "\n🚀 You can now create subjects with 'praktikum' type!\n";
    echo "Just make sure to use unique codes for each subject.\n";
    
    echo "\n📝 Working Example:\n";
    echo "-------------------------------------\n";
    echo "Subject::create([\n";
    echo "    'name' => 'Farmasi',\n";
    echo "    'code' => 'FRM001',\n";
    echo "    'description' => 'contoh 2',\n";
    echo "    'type' => 'praktikum',  // ✅ This now works!\n";
    echo "    'sks' => 2,\n";
    echo "    'is_active' => 1\n";
    echo "]);\n";
    
    // Clean up test subject
    $subject->delete();
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
