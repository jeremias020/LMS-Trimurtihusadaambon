<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 TESTING MATERIAL OBSERVER FIX\n";
echo "=====================================\n\n";

try {
    // Test Material Observer functionality
    echo "📊 Testing Material Observer:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Get a sample material
    $material = \App\Models\Material::first();
    
    if ($material) {
        echo "✅ Material found:\n";
        echo "Material ID: {$material->id}\n";
        echo "Material Title: {$material->title}\n";
        echo "Kelas ID: {$material->kelas_id}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Test the query that was in MaterialObserver
        echo "Testing Student Query from MaterialObserver:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        try {
            if ($material->kelas_id) {
                $students = \App\Models\Student::where('kelas_id', $material->kelas_id)
                    ->where('is_active', true)
                    ->get();
                
                echo "✅ Query succeeded:\n";
                echo "Students found: " . $students->count() . "\n";
                
                foreach ($students as $student) {
                    echo "- Student ID: {$student->id}, Name: {$student->name}, Email: {$student->email}\n";
                }
            } else {
                echo "⚠️  Material has no kelas_id\n";
            }
            
        } catch (\Exception $e) {
            echo "❌ Error in student query: " . $e->getMessage() . "\n";
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
    } else {
        echo "❌ No materials found in database\n";
    }
    
    // Test if there are any other places using the problematic pattern
    echo "🔍 Checking for other potential issues:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Test Student::where('id', Auth::id()) pattern
    try {
        $testStudent = \App\Models\Student::where('id', 1)->first();
        if ($testStudent) {
            echo "✅ Student::where('id', 1) works correctly\n";
        } else {
            echo "⚠️  No student found with id = 1\n";
        }
    } catch (\Exception $e) {
        echo "❌ Error in Student::where('id', 1): " . $e->getMessage() . "\n";
    }
    
    // Test the original error query
    try {
        $errorQuery = \App\Models\Student::where('user_id', 1)->first();
        echo "⚠️  Student::where('user_id', 1) succeeded (unexpected)\n";
    } catch (\Exception $e) {
        echo "✅ Student::where('user_id', 1) correctly fails: " . $e->getMessage() . "\n";
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n✅ Test selesai\n";
?>
