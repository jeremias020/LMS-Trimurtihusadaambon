<?php
echo "=== TESTING ADMIN MANAGEMENT FEATURES ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "1. Management Users:\n";
    $users = \App\Models\User::count();
    echo "   ✅ Total Users: $users\n";
    
    echo "\n2. Management Kelas:\n";
    $kelas = \App\Models\Kelas::count();
    echo "   ✅ Total Kelas: $kelas\n";
    
    echo "\n3. Management Jurusan:\n";
    try {
        $jurusan = \App\Models\Jurusan::count();
        echo "   ✅ Total Jurusan: $jurusan\n";
    } catch (Exception $e) {
        echo "   ⚠️  Jurusan table not available (migration disabled)\n";
    }
    
    echo "\n4. Management Mata Pelajaran:\n";
    $mataPelajaran = \App\Models\MataPelajaran::count();
    echo "   ✅ Total Mata Pelajaran: $mataPelajaran\n";
    
    echo "\n5. Management Kriteria Penilaian:\n";
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('kriteria_penilaian')) {
            $kriteria = \App\Models\KriteriaPenilaian::count();
            echo "   ✅ Total Kriteria Penilaian: $kriteria\n";
        } else {
            echo "   ⚠️  Kriteria Penilaian table not available (migration disabled)\n";
        }
    } catch (Exception $e) {
        echo "   ⚠️  Kriteria Penilaian model not available\n";
    }
    
    echo "\n6. Management Jadwal Ujian:\n";
    try {
        $examSchedules = \App\Models\ExamSchedule::count();
        echo "   ✅ Total Jadwal Ujian: $examSchedules\n";
    } catch (Exception $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== ROUTE CHECK ===\n";
    $routes = [
        'admin.users.index',
        'admin.kelas.index', 
        'admin.jurusan.index',
        'admin.mata-pelajaran.index',
        'admin.kriteria-penilaian.index',
        'admin.exam-schedules.index'
    ];
    
    foreach ($routes as $route) {
        try {
            $url = route($route);
            echo "✅ $route -> $url\n";
        } catch (Exception $e) {
            echo "❌ $route -> Route not found\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
