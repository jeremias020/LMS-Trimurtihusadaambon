<?php
echo "=== TEST GRADE BADGE COLOR ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $userId = 4; // ID Agus Setiawan
    
    echo "Testing grade_badge_color method untuk Agus Setiawan...\n\n";
    
    // Ambil nilai praktik terakhir
    $latestGrade = \Illuminate\Support\Facades\DB::table('nilai_praktik_new')
        ->where('siswa_id', $userId)
        ->where('status', 'final')
        ->orderBy('created_at', 'desc')
        ->first();
    
    if ($latestGrade) {
        echo "Data grade terakhir:\n";
        echo "   - ID: {$latestGrade->id}\n";
        echo "   - Grade: {$latestGrade->grade}\n";
        echo "   - Nilai: {$latestGrade->total_nilai}\n";
        echo "   - Status: {$latestGrade->status}\n\n";
        
        // Test dengan model
        try {
            // Buat instance model manual
            $nilaiPraktik = new \App\Models\NilaiPraktik();
            $nilaiPraktik->id = $latestGrade->id;
            $nilaiPraktik->grade = $latestGrade->grade;
            $nilaiPraktik->total_nilai = $latestGrade->total_nilai;
            $nilaiPraktik->status = $latestGrade->status;
            
            echo "Testing grade_badge_color method:\n";
            $badgeColor = $nilaiPraktik->getGradeBadgeAttribute();
            echo "   ✅ Grade badge color: {$badgeColor}\n";
            
        } catch (Exception $e) {
            echo "   ❌ Error dengan method grade_badge_color: " . $e->getMessage() . "\n";
        }
        
        // Test manual color mapping (seperti di view)
        echo "\nTest manual color mapping:\n";
        $grade = $latestGrade->grade;
        $color = 'secondary';
        
        switch ($grade) {
            case 'A': $color = 'success'; break;
            case 'B': $color = 'primary'; break;
            case 'C': $color = 'warning'; break;
            case 'D': $color = 'danger'; break;
            case 'E': $color = 'dark'; break;
        }
        
        echo "   Grade: {$grade} -> Color: {$color}\n";
        
    } else {
        echo "❌ Tidak ada nilai praktik untuk diuji\n";
    }
    
    // Test full stats seperti di UserController
    echo "\n=== TEST FULL STATS ===\n";
    
    $stats = [
        'practical_grades_count' => \App\Models\NilaiPraktik::where('siswa_id', $userId)->final()->count(),
        'practical_average' => round((float) (\App\Models\NilaiPraktik::where('siswa_id', $userId)->final()->avg('total_nilai') ?? 0), 1),
        'practical_latest_grade' => \App\Models\NilaiPraktik::where('siswa_id', $userId)->final()->latest()->first(),
    ];
    
    echo "Practical grades count: " . $stats['practical_grades_count'] . "\n";
    echo "Practical average: " . $stats['practical_average'] . "\n";
    echo "Practical latest grade: " . ($stats['practical_latest_grade'] ? 'Found' : 'Not found') . "\n";
    
    if ($stats['practical_latest_grade']) {
        echo "   - Grade: " . $stats['practical_latest_grade']->grade . "\n";
        echo "   - Nilai: " . $stats['practical_latest_grade']->total_nilai . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== SELESAI ===\n";
?>
