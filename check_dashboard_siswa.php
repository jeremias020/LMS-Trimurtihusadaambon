<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🎯 CHECK DASHBOARD SISWA TAMPILAN\n";
echo "=====================================\n";

try {
    // Test 1: Simulate siswa login
    echo "Step 1: Test Siswa Login\n";
    echo "-------------------------------------\n";
    
    $siswaUser = \App\Models\User::where('role', 'siswa')->first();
    if (!$siswaUser) {
        echo "❌ No siswa user found\n";
        return;
    }
    
    \Illuminate\Support\Facades\Auth::login($siswaUser);
    echo "✅ Logged in as: {$siswaUser->name}\n";
    
    // Test 2: Check Student data
    echo "\nStep 2: Check Student Data\n";
    echo "-------------------------------------\n";
    
    $student = \App\Models\Student::with('kelas')->where('id', $siswaUser->id)->first();
    if (!$student) {
        echo "❌ Student data not found\n";
        return;
    }
    
    echo "✅ Student: {$student->name}\n";
    echo "  NISN: " . ($student->nisn ?: 'Tidak ada') . "\n";
    echo "  Kelas: " . ($student->kelas ? $student->kelas->name : 'Tidak ada') . "\n";
    echo "  Foto: " . ($student->foto ?: 'Tidak ada') . "\n";
    
    // Test 3: Simulate DashboardController::index()
    echo "\nStep 3: Test Dashboard Data\n";
    echo "-------------------------------------\n";
    
    $siswaId = $siswaUser->id;
    $kelasId = $student->kelas_id ?? null;
    
    echo "Kelas ID: {$kelasId}\n";
    
    // Stats calculation
    $stats = [
        'total_materials' => \App\Models\Material::whereNotNull('published_at')
            ->where(function($query) use ($kelasId) {
                if ($kelasId) {
                    $query->where('kelas_id', $kelasId)
                          ->orWhereNull('kelas_id');
                } else {
                    $query->whereNull('kelas_id');
                }
            })
            ->count(),
        'completed_assignments' => \App\Models\AssignmentSubmission::where('siswa_id', $siswaId)
            ->whereNotNull('score')
            ->count(),
        'completed_practicals' => \App\Models\PracticalScore::where('siswa_id', $siswaId)
            ->whereNotNull('score')
            ->count(),
        'attendance_percentage' => 0, // Will calculate later
        'average_score' => 0, // Will calculate later
        'attendance_count' => \App\Models\Attendance::where('siswa_id', $siswaId)
            ->where('status', 'hadir')
            ->count(),
        'rank' => 1, // Default
    ];
    
    echo "✅ Stats calculated:\n";
    foreach ($stats as $key => $value) {
        echo "  {$key}: {$value}\n";
    }
    
    // Test 4: Recent Materials
    echo "\nStep 4: Test Recent Materials\n";
    echo "-------------------------------------\n";
    
    $recentMaterials = \App\Models\Material::with('guru')
        ->whereNotNull('published_at')
        ->where(function($query) use ($kelasId) {
            if ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->orWhereNull('kelas_id');
            } else {
                $query->whereNull('kelas_id');
            }
        })
        ->latest()
        ->take(5)
        ->get();
    
    echo "Recent materials: {$recentMaterials->count()}\n";
    foreach ($recentMaterials as $material) {
        echo "  - {$material->title} (Guru: {$material->guru->name})\n";
    }
    
    // Test 5: Upcoming Deadlines
    echo "\nStep 5: Test Upcoming Deadlines\n";
    echo "-------------------------------------\n";
    
    $upcomingDeadlines = [];
    
    // Get upcoming assignments
    $assignments = \App\Models\Assignment::where(function($query) use ($kelasId) {
            if ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->orWhereNull('kelas_id');
            } else {
                $query->whereNull('kelas_id');
            }
        })
        ->where('due_date', '>', now())
        ->whereDoesntHave('submissions', function($query) use ($siswaId) {
            $query->where('siswa_id', $siswaId);
        })
        ->orderBy('due_date')
        ->take(3)
        ->get();
    
    foreach ($assignments as $assignment) {
        $upcomingDeadlines[] = [
            'type' => 'assignment',
            'title' => $assignment->title,
            'due_date' => $assignment->due_date,
            'days_left' => now()->diffInDays($assignment->due_date),
        ];
    }
    
    echo "Upcoming deadlines: " . count($upcomingDeadlines) . "\n";
    foreach ($upcomingDeadlines as $deadline) {
        echo "  - {$deadline['title']} ({$deadline['days_left']} hari lagi)\n";
    }
    
    // Test 6: Check CSS and Assets
    echo "\nStep 6: Check Assets\n";
    echo "-------------------------------------\n";
    
    $cssFiles = [
        'css/components/dashboard.css',
        'css/components/table.css', 
        'css/components/form.css',
        'css/siswa.css',
        'css/siswa-custom.css'
    ];
    
    foreach ($cssFiles as $css) {
        $path = public_path($css);
        echo "  " . (file_exists($path) ? "✅" : "❌") . " {$css}\n";
    }
    
    // Test 7: Check View Variables
    echo "\nStep 7: Check View Variables\n";
    echo "-------------------------------------\n";
    
    $viewVars = [
        'stats' => $stats,
        'recentMaterials' => $recentMaterials,
        'upcomingDeadlines' => $upcomingDeadlines,
        'newMaterialsCount' => $stats['total_materials'],
        'pendingAssignmentsCount' => $assignments->count(),
        'upcomingPracticalsCount' => 0,
        'attendancePercentage' => $stats['attendance_percentage']
    ];
    
    echo "✅ View variables prepared:\n";
    foreach ($viewVars as $key => $value) {
        if (is_array($value) || is_object($value)) {
            echo "  {$key}: " . (is_countable($value) ? count($value) : 'object') . " items\n";
        } else {
            echo "  {$key}: {$value}\n";
        }
    }
    
    echo "\n🎯 ANALISIS TAMPILAN:\n";
    echo "=====================================\n";
    echo "✅ User authentication: OK\n";
    echo "✅ Student data: OK\n";
    echo "✅ Stats calculation: OK\n";
    echo "✅ Recent materials: OK\n";
    echo "✅ Upcoming deadlines: OK\n";
    echo "✅ View variables: OK\n";
    
    echo "\n📝 KEMUNGKINAN MASALAH TAMPILAN:\n";
    echo "=====================================\n";
    echo "1. ❌ CSS files tidak ada\n";
    echo "2. ❌ JavaScript errors\n";
    echo "3. ❌ Font Awesome icons tidak load\n";
    echo "4. ❌ Bootstrap CSS tidak load\n";
    echo "5. ❌ Data kosong (no materials, assignments, etc.)\n";
    echo "6. ❌ Responsive design issues\n";
    echo "7. ❌ Browser compatibility\n";
    
    echo "\n🚀 SOLUSI YANG DIREKOMENDASIKAN:\n";
    echo "=====================================\n";
    echo "1. Clear view cache: php artisan view:clear\n";
    echo "2. Clear asset cache: php artisan cache:clear\n";
    echo "3. Check browser console for errors\n";
    echo "4. Test di browser berbeda\n";
    echo "5. Pastikan CSS files ada di public/css/\n";
    echo "6. Cek network tab untuk failed requests\n";
    
    echo "\n✨ CHECK COMPLETE! ✨\n";
    echo "Dashboard siswa seharusnya menampilkan:\n";
    echo "- Hero section dengan welcome message\n";
    echo "- Stats cards (materials, assignments, practicals, attendance)\n";
    echo "- Recent materials list\n";
    echo "- Upcoming deadlines\n";
    echo "- Quick actions menu\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
