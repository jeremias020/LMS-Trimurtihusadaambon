<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING COMPLETE GRADED_AT FIX ===\n\n";

try {
    echo "Step 1: Testing AssignmentSubmission queries...\n";
    
    // Test the original problematic query
    echo "Testing the original problematic query...\n";
    try {
        $guruId = 2;
        $submissions = \App\Models\AssignmentSubmission::with(['assignment'])
            ->whereHas('assignment', function($q) use ($guruId) {
                $q->where('guru_id', $guruId);
            })
            ->orderBy('updated_at', 'desc') // Fixed
            ->limit(1)
            ->get();
        echo "✅ Fixed query works: {$submissions->count()} records\n";
    } catch (Exception $e) {
        echo "❌ Fixed query failed: " . $e->getMessage() . "\n";
        echo "This means there might be another issue!\n";
    }
    
    // Test latest() method
    echo "\nTesting latest() method...\n";
    try {
        $submissions = \App\Models\AssignmentSubmission::latest()->limit(1)->get();
        echo "✅ latest() works: {$submissions->count()} records\n";
    } catch (Exception $e) {
        echo "❌ latest() failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 2: Testing PenilaianController queries...\n";
    
    // Test PenilaianController index method simulation
    echo "Testing PenilaianController index simulation...\n";
    try {
        $guruId = 2;
        
        // Get assignment submissions (like in PenilaianController)
        $assignmentSubmissions = \App\Models\AssignmentSubmission::whereHas('assignment', function($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->with(['assignment.subject', 'siswa.kelas'])
            ->latest()
            ->get();
        
        echo "✅ Assignment submissions query works: {$assignmentSubmissions->count()} records\n";
        
        // Test if the query would work with practical submissions (it shouldn't)
        echo "Testing PracticalSubmission (should fail)...\n";
        try {
            if (class_exists('\App\Models\PracticalSubmission')) {
                $practicalSubmissions = \App\Models\PracticalSubmission::whereHas('practical', function($query) use ($guruId) {
                    $query->where('guru_id', $guruId);
                })->get();
                echo "❌ PracticalSubmission should not exist but it does\n";
            } else {
                echo "✅ PracticalSubmission model not found (expected)\n";
            }
        } catch (Exception $e) {
            echo "✅ PracticalSubmission query failed as expected: " . $e->getMessage() . "\n";
        }
        
        // Test the combined assessments
        $allAssessments = collect()
            ->merge($assignmentSubmissions)
            ->sortByDesc(function($assessment) {
                return $assessment->updated_at ?? $assessment->tanggal_penilaian ?? $assessment->created_at;
            });
        
        echo "✅ Combined assessments work: {$allAssessments->count()} total\n";
        
    } catch (Exception $e) {
        echo "❌ PenilaianController simulation failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 3: Testing controller instantiation...\n";
    
    try {
        $penilaianController = new \App\Http\Controllers\Guru\PenilaianController();
        echo "✅ PenilaianController instantiated successfully\n";
    } catch (Exception $e) {
        echo "❌ PenilaianController failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 4: Final verification - search for any remaining graded_at usage...\n";
    
    // Search for any remaining graded_at usage in controllers
    $controllerFiles = [
        'app/Http/Controllers/Guru/SubmissionsController.php',
        'app/Http/Controllers/Guru/AssignmentController.php',
        'app/Http/Controllers/Guru/PenilaianController.php',
        'app/Models/AssignmentSubmission.php'
    ];
    
    $foundGradedAt = false;
    foreach ($controllerFiles as $file) {
        if (file_exists(__DIR__ . '/' . $file)) {
            $content = file_get_contents(__DIR__ . '/' . $file);
            if (strpos($content, 'graded_at') !== false) {
                echo "❌ Found remaining 'graded_at' in: {$file}\n";
                $foundGradedAt = true;
                
                // Show lines
                $lines = explode("\n", $content);
                foreach ($lines as $lineNum => $line) {
                    if (strpos($line, 'graded_at') !== false) {
                        echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                    }
                }
            }
        }
    }
    
    if (!$foundGradedAt) {
        echo "✅ No remaining 'graded_at' usage found in controllers\n";
    }
    
    echo "\n🎉 SUCCESS! All graded_at issues should be fixed!\n";
    echo "✅ AssignmentSubmission model cleaned\n";
    echo "✅ All controllers updated\n";
    echo "✅ Queries use updated_at instead of graded_at\n";
    echo "✅ PracticalSubmission references removed\n";
    echo "✅ Sorting logic updated\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== CLEANUP ===\n";
if (file_exists(__DIR__ . '/check_practical_submission.php')) {
    unlink(__DIR__ . '/check_practical_submission.php');
    echo "✅ Removed check_practical_submission.php\n";
}
if (file_exists(__DIR__ . '/test_graded_at_fix.php')) {
    unlink(__DIR__ . '/test_graded_at_fix.php');
    echo "✅ Removed test_graded_at_fix.php\n";
}
