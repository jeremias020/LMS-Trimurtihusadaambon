<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG STUDENT MODEL FIX\n";
echo "=====================================\n\n";

try {
    // Test Student model
    echo "📊 Testing Student Model:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Get a sample student
    $student = \App\Models\Student::first();
    
    if ($student) {
        echo "✅ Student found:\n";
        echo "ID: {$student->id}\n";
        echo "Name: {$student->name}\n";
        echo "Email: {$student->email}\n";
        echo "NIS: {$student->nis}\n";
        echo "Kelas ID: {$student->kelas_id}\n";
        echo "Jurusan ID: {$student->jurusan_id}\n";
        echo "Gender: {$student->gender}\n";
        echo "Birth Date: {$student->birth_date}\n";
        echo "Is Active: " . ($student->is_active ? 'Yes' : 'No') . "\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Test accessors
        echo "Testing Accessors:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Full Name: {$student->full_name}\n";
        echo "Age: {$student->age}\n";
        echo "Gender Display: {$student->gender_display}\n";
        echo "Email: {$student->email}\n";
        echo "Username: {$student->username}\n";
        echo "Photo URL: {$student->photo_url}\n";
        echo "Role: {$student->role}\n";
        echo "Is Active: " . ($student->isActive() ? 'Yes' : 'No') . "\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Test scopes
        echo "Testing Scopes:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        $activeStudents = \App\Models\Student::active()->get();
        echo "Active students count: " . $activeStudents->count() . "\n";
        
        if ($student->kelas_id) {
            $classStudents = \App\Models\Student::byKelas($student->kelas_id)->get();
            echo "Students in class {$student->kelas_id}: " . $classStudents->count() . "\n";
        }
        
        if ($student->jurusan_id) {
            $majorStudents = \App\Models\Student::byMajor($student->jurusan_id)->get();
            echo "Students in major {$student->jurusan_id}: " . $majorStudents->count() . "\n";
        }
        
        $activeStatusStudents = \App\Models\Student::byStatus(true)->get();
        echo "Students with active status: " . $activeStatusStudents->count() . "\n";
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Test relationships
        echo "Testing Relationships:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        try {
            $kelas = $student->kelas;
            if ($kelas) {
                echo "✅ Kelas found: {$kelas->name}\n";
            } else {
                echo "⚠️  No kelas found for this student\n";
            }
        } catch (\Exception $e) {
            echo "❌ Error in kelas relationship: " . $e->getMessage() . "\n";
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Test the problematic query that was causing the error
        echo "Testing the problematic query (Student::where('id', 1)):\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        try {
            $result = \App\Models\Student::where('id', 1)->first();
            if ($result) {
                echo "✅ Query succeeded:\n";
                echo "Student ID: {$result->id}\n";
                echo "Student Name: {$result->name}\n";
            } else {
                echo "❌ No student found with id = 1\n";
            }
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
    } else {
        echo "❌ No students found in database\n";
    }
    
    // Test the original error scenario
    echo "🔍 Testing Original Error Scenario:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    try {
        // This was the problematic query causing the error
        $result = \App\Models\Student::where('user_id', 1)->first();
        echo "⚠️  Query Student::where('user_id', 1) succeeded (unexpected)\n";
    } catch (\Exception $e) {
        echo "❌ Expected error: " . $e->getMessage() . "\n";
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n✅ Debug selesai\n";
?>
