<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CREATE SIMPLE TEST DATA ===" . PHP_EOL;

try {
    $guruId = 118;
    
    // Get guru
    $guru = \App\Models\User::find($guruId);
    if (!$guru) {
        echo "❌ Guru with ID $guruId not found!" . PHP_EOL;
        exit;
    }
    
    echo "✅ Guru found: " . $guru->name . PHP_EOL;
    
    // Get a student
    $student = \App\Models\User::where('role', 'siswa')->first();
    if (!$student) {
        echo "❌ No student found!" . PHP_EOL;
        exit;
    }
    
    echo "✅ Student found: " . $student->name . PHP_EOL;
    
    // Create test nilai praktik only (no practical needed for this test)
    $nilaiPraktik = \App\Models\NilaiPraktik::create([
        'siswa_id' => $student->id,
        'guru_id' => $guruId,
        'mata_praktik' => 'Test Praktikum',
        'tanggal_praktik' => now()->toDateString(),
        'total_nilai' => 85,
        'grade' => 'B',
        'feedback_otomatis' => 'Good practical work!',
        'catatan_guru' => 'Keep up the good work',
        'status' => 'final',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "✅ Nilai praktik created: ID " . $nilaiPraktik->id . PHP_EOL;
    echo "   - Student: " . $nilaiPraktik->siswa->name . PHP_EOL;
    echo "   - Total Nilai: " . $nilaiPraktik->total_nilai . PHP_EOL;
    echo "   - Grade: " . $nilaiPraktik->grade . PHP_EOL;
    
    echo PHP_EOL . "=== TESTING CONTROLLER INDEX METHOD ===" . PHP_EOL;
    
    // Test controller method
    $controller = new \App\Http\Controllers\Guru\PenilaianController();
    
    // Mock authentication
    \Illuminate\Support\Facades\Auth::shouldReceive('id')->andReturn($guruId);
    
    try {
        $response = $controller->index();
        echo "✅ Controller index method executed successfully!" . PHP_EOL;
        
        // Get the view data
        $viewData = $response->getData();
        $allAssessments = $viewData['allAssessments'] ?? collect();
        
        echo "✅ Total assessments: " . $allAssessments->count() . PHP_EOL;
        
        if ($allAssessments->count() > 0) {
            echo PHP_EOL . "Assessment details:" . PHP_EOL;
            foreach ($allAssessments->take(2) as $index => $assessment) {
                echo PHP_EOL . "Assessment " . ($index + 1) . ":" . PHP_EOL;
                echo "- Type: " . get_class($assessment) . PHP_EOL;
                
                // Test helper functions
                $score = null;
                if (isset($assessment->score)) {
                    $score = $assessment->score;
                } elseif (isset($assessment->total_nilai)) {
                    $score = $assessment->total_nilai;
                }
                echo "- Score: " . ($score ?? 'NULL') . PHP_EOL;
                
                $studentName = 'N/A';
                if (isset($assessment->siswa)) {
                    $studentName = $assessment->siswa->name ?? 'N/A';
                } elseif (isset($assessment->student)) {
                    $studentName = $assessment->student->name ?? 'N/A';
                }
                echo "- Student: " . $studentName . PHP_EOL;
                
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
        echo "❌ Controller error: " . $e->getMessage() . PHP_EOL;
        echo "Stack trace: " . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
