<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 FIX MAJOR_ID DEFAULT VALUE ISSUE\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Subjects Table Constraints\n";
    echo "-------------------------------------\n";
    
    $constraints = \DB::select("
        SELECT COLUMN_NAME, IS_NULLABLE, COLUMN_DEFAULT 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = 'lms_trimurti' 
        AND TABLE_NAME = 'subjects' 
        AND COLUMN_NAME = 'major_id'
    ");
    
    foreach ($constraints as $constraint) {
        echo "Column: {$constraint->COLUMN_NAME}\n";
        echo "Is Nullable: {$constraint->IS_NULLABLE}\n";
        echo "Default Value: " . ($constraint->COLUMN_DEFAULT ?? 'NULL') . "\n";
    }
    
    echo "\nStep 2: Fix major_id Column\n";
    echo "-------------------------------------\n";
    
    // Make major_id nullable
    \Schema::table('subjects', function ($table) {
        $table->unsignedBigInteger('major_id')->nullable()->change();
    });
    
    echo "✅ major_id column made nullable\n";
    
    echo "\nStep 3: Test Subject Creation Again\n";
    echo "-------------------------------------\n";
    
    // Test the exact data that was failing
    $subjectData = [
        'name' => 'Analisis keperawatan',
        'code' => 'AKP',
        'description' => 'contoh',
        'type' => 'teori',
        'sks' => 2,
        'is_active' => 1
        // Note: major_id is now nullable, so we don't need to provide it
    ];
    
    echo "Creating subject with data:\n";
    foreach ($subjectData as $key => $value) {
        echo "  - {$key}: {$value}\n";
    }
    
    $subject = \App\Models\Subject::create($subjectData);
    
    echo "✅ Subject created successfully!\n";
    echo "  - ID: {$subject->id}\n";
    echo "  - Name: {$subject->name}\n";
    echo "  - Code: {$subject->code}\n";
    echo "  - Description: {$subject->description}\n";
    echo "  - Type: {$subject->type}\n";
    echo "  - SKS: {$subject->sks}\n";
    echo "  - Is Active: {$subject->is_active}\n";
    echo "  - Major ID: " . ($subject->major_id ?? 'NULL') . "\n";
    
    echo "\nStep 4: Test with Optional Fields\n";
    echo "-------------------------------------\n";
    
    // Test with all optional fields
    $subjectWithAllFields = \App\Models\Subject::create([
        'name' => 'Biologi Dasar',
        'code' => 'BIO001',
        'description' => 'Mata pelajaran biologi dasar untuk keperawatan',
        'type' => 'teori_praktik',
        'sks' => 3,
        'major_id' => 1,
        'jurusan_id' => 1,
        'guru_id' => 2,
        'kelas_id' => 1,
        'is_active' => 1
    ]);
    
    echo "✅ Subject with all fields created!\n";
    echo "  - ID: {$subjectWithAllFields->id}\n";
    echo "  - Name: {$subjectWithAllFields->name}\n";
    echo "  - Type: {$subjectWithAllFields->type}\n";
    echo "  - Major ID: {$subjectWithAllFields->major_id}\n";
    echo "  - Jurusan ID: {$subjectWithAllFields->jurusan_id}\n";
    echo "  - Guru ID: {$subjectWithAllFields->guru_id}\n";
    
    echo "\nStep 5: Test Subject Update\n";
    echo "-------------------------------------\n";
    
    $subject->update([
        'description' => 'Updated description for analisis keperawatan',
        'sks' => 3,
        'major_id' => 1
    ]);
    
    echo "✅ Subject updated successfully!\n";
    echo "  - New Description: {$subject->description}\n";
    echo "  - New SKS: {$subject->sks}\n";
    echo "  - New Major ID: {$subject->major_id}\n";
    
    // Clean up test subjects
    $subject->forceDelete();
    $subjectWithAllFields->forceDelete();
    echo "✅ Test subjects cleaned up\n";
    
    echo "\nStep 6: Final Verification\n";
    echo "-------------------------------------\n";
    
    // Check final table structure
    $finalColumns = \DB::select("SHOW COLUMNS FROM subjects");
    echo "Final subjects table structure:\n";
    
    foreach ($finalColumns as $column) {
        $nullable = $column->Null === 'YES' ? 'NULLABLE' : 'NOT NULL';
        $default = $column->Default ?? 'NULL';
        echo "  - {$column->Field} ({$column->Type}) [{$nullable}] [Default: {$default}]\n";
    }
    
    echo "\n🎉 MAJOR_ID ISSUE FIXED!\n";
    echo "=====================================\n";
    echo "✅ major_id column made nullable\n";
    echo "✅ Subject creation working\n";
    echo "✅ Subject update working\n";
    echo "✅ All fields accessible\n";
    echo "✅ No more SQL errors\n";
    
    echo "\n📋 Problem Resolution:\n";
    echo "-------------------------------------\n";
    echo "❌ BEFORE: Field 'major_id' doesn't have a default value\n";
    echo "✅ AFTER: major_id made nullable, no default needed\n";
    
    echo "\n🚀 You can now create subjects freely!\n";
    echo "All required and optional fields are working properly.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
