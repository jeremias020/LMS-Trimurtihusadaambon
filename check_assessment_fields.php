<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECK ASSESSMENT FIELDS ===" . PHP_EOL;

try {
    $guruId = 118;
    
    // Get assignment submissions
    echo PHP_EOL . "=== ASSIGNMENT SUBMISSIONS ===" . PHP_EOL;
    $assignmentSubmissions = \App\Models\AssignmentSubmission::whereHas('assignment', function($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })
        ->with(['assignment.subject', 'siswa.kelas'])
        ->latest('graded_at')
        ->get();
    
    echo "Found " . $assignmentSubmissions->count() . " assignment submissions" . PHP_EOL;
    
    if ($assignmentSubmissions->count() > 0) {
        $first = $assignmentSubmissions->first();
        echo "First assignment submission fields:" . PHP_EOL;
        echo "- ID: " . $first->id . PHP_EOL;
        echo "- Score: " . ($first->score ?? 'NULL') . PHP_EOL;
        echo "- Graded At: " . ($first->graded_at ?? 'NULL') . PHP_EOL;
        echo "- Student: " . ($first->siswa->name ?? 'N/A') . PHP_EOL;
    }
    
    // Get nilai praktik
    echo PHP_EOL . "=== NILAI PRAKTIK ===" . PHP_EOL;
    $nilaiPraktiks = \App\Models\NilaiPraktik::where('guru_id', $guruId)
        ->with(['practical.subject', 'siswa.kelas'])
        ->latest('tanggal_praktik')
        ->get();
    
    echo "Found " . $nilaiPraktiks->count() . " nilai praktik records" . PHP_EOL;
    
    if ($nilaiPraktiks->count() > 0) {
        $first = $nilaiPraktiks->first();
        echo "First nilai praktik fields:" . PHP_EOL;
        echo "- ID: " . $first->id . PHP_EOL;
        echo "- Total Nilai: " . ($first->total_nilai ?? 'NULL') . PHP_EOL;
        echo "- Grade: " . ($first->grade ?? 'NULL') . PHP_EOL;
        echo "- Tanggal Praktik: " . ($first->tanggal_praktik ?? 'NULL') . PHP_EOL;
        echo "- Student: " . ($first->siswa->name ?? 'N/A') . PHP_EOL;
    }
    
    // Combine and test
    echo PHP_EOL . "=== COMBINED ASSESSMENTS ===" . PHP_EOL;
    $allAssessments = collect()
        ->merge($assignmentSubmissions)
        ->merge($nilaiPraktiks);
    
    echo "Total combined assessments: " . $allAssessments->count() . PHP_EOL;
    
    if ($allAssessments->count() > 0) {
        echo PHP_EOL . "Testing field access on combined assessments:" . PHP_EOL;
        foreach ($allAssessments->take(3) as $index => $assessment) {
            echo PHP_EOL . "Assessment " . ($index + 1) . ":" . PHP_EOL;
            echo "- Type: " . get_class($assessment) . PHP_EOL;
            
            // Test different field names
            $score = null;
            if (isset($assessment->score)) {
                $score = $assessment->score;
                echo "- Score field: " . $score . PHP_EOL;
            } elseif (isset($assessment->total_nilai)) {
                $score = $assessment->total_nilai;
                echo "- Total Nilai field: " . $score . PHP_EOL;
            } else {
                echo "- No score field found!" . PHP_EOL;
            }
            
            // Test student access
            $studentName = 'N/A';
            if (isset($assessment->siswa)) {
                $studentName = $assessment->siswa->name ?? 'N/A';
            } elseif (isset($assessment->student)) {
                $studentName = $assessment->student->name ?? 'N/A';
            }
            echo "- Student: " . $studentName . PHP_EOL;
            
            // Test date access
            $date = 'N/A';
            if (isset($assessment->graded_at)) {
                $date = $assessment->graded_at;
            } elseif (isset($assessment->tanggal_praktik)) {
                $date = $assessment->tanggal_praktik;
            }
            echo "- Date: " . $date . PHP_EOL;
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
