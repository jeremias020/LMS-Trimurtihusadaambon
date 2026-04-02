<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CREATE PRACTICAL DATA ===" . PHP_EOL;

try {
    $guruId = 118;
    
    // Get guru
    $guru = \App\Models\User::find($guruId);
    if (!$guru) {
        echo "❌ Guru with ID $guruId not found!" . PHP_EOL;
        exit;
    }
    
    echo "✅ Guru found: " . $guru->name . PHP_EOL;
    
    // Get a subject
    $subject = \App\Models\Subject::first();
    if (!$subject) {
        echo "❌ No subject found!" . PHP_EOL;
        exit;
    }
    
    echo "✅ Subject found: " . $subject->name . PHP_EOL;
    
    // Create a practical for this guru
    $practical = \App\Models\Practical::updateOrCreate(
        [
            'guru_id' => $guruId,
            'judul' => 'Praktikum Keperawatan Dasar',
        ],
        [
            'subject_id' => $subject->id,
            'deskripsi' => 'Praktikum dasar untuk mahasiswa keperawatan',
            'instruksi' => 'Ikuti semua prosedur keselamatan dan kebersihan',
            'tingkat_kelas' => 'X',
            'skill_level' => 'Menengah',
            'is_published' => true,
            'tanggal' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );
    
    echo "✅ Practical created/updated: ID " . $practical->id . PHP_EOL;
    echo "   - Judul: " . $practical->judul . PHP_EOL;
    echo "   - Subject: " . $practical->subject->name . PHP_EOL;
    echo "   - Published: " . ($practical->is_published ? 'Yes' : 'No') . PHP_EOL;
    
    // Now create test data again
    echo PHP_EOL . "=== CREATE TEST DATA ===" . PHP_EOL;
    
    // Get a student
    $student = \App\Models\User::where('role', 'siswa')->first();
    if (!$student) {
        echo "❌ No student found!" . PHP_EOL;
        exit;
    }
    
    echo "✅ Student found: " . $student->name . PHP_EOL;
    
    // Create test assignment submission
    $assignment = \App\Models\Assignment::where('guru_id', $guruId)->first();
    if ($assignment) {
        $assignmentSubmission = \App\Models\AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'siswa_id' => $student->id,
            ],
            [
                'score' => 85,
                'feedback' => 'Good work!',
                'graded_at' => now(),
                'graded_by' => $guruId,
                'submitted_at' => now(),
            ]
        );
        echo "✅ Assignment submission created: ID " . $assignmentSubmission->id . PHP_EOL;
    }
    
    // Create test nilai praktik
    $nilaiPraktik = \App\Models\NilaiPraktik::updateOrCreate(
        [
            'siswa_id' => $student->id,
            'mata_praktik' => $practical->judul,
            'tanggal_praktik' => now()->toDateString(),
        ],
        [
            'guru_id' => $guruId,
            'total_nilai' => 90,
            'grade' => 'A',
            'feedback_otomatis' => 'Excellent practical work!',
            'catatan_guru' => 'Keep up the good work',
            'status' => 'final',
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );
    echo "✅ Nilai praktik created: ID " . $nilaiPraktik->id . PHP_EOL;
    
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
            foreach ($allAssessments->take(3) as $index => $assessment) {
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
