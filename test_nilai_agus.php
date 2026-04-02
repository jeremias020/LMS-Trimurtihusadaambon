<?php
echo "=== TEST QUERY NILAI PRAKTIK AGUS SETIAWAN ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $userId = 4; // ID Agus Setiawan
    
    echo "User ID: {$userId} (Agus Setiawan)\n\n";
    
    // 1. Test query yang digunakan di UserController
    echo "1. Test query seperti di UserController:\n";
    
    try {
        // Import model untuk testing
        $nilaiPraktikModel = new \App\Models\NilaiPraktik();
        
        // Test scopeFinal
        $queryFinal = $nilaiPraktikModel->newQuery()->where('siswa_id', $userId)->final();
        echo "   Query SQL: " . $queryFinal->toSql() . "\n";
        
        $hasilFinal = $queryFinal->get();
        echo "   Jumlah hasil (final): " . $hasilFinal->count() . "\n";
        
        if ($hasilFinal->count() > 0) {
            foreach ($hasilFinal as $item) {
                echo "   - ID: {$item->id}, Mata: {$item->mata_praktik}, Nilai: {$item->total_nilai}, Status: {$item->status}\n";
            }
        }
        
    } catch (Exception $e) {
        echo "   ❌ Error dengan model: " . $e->getMessage() . "\n";
    }
    
    // 2. Test query langsung ke database
    echo "\n2. Test query langsung ke database:\n";
    
    $directQuery = \Illuminate\Support\Facades\DB::table('nilai_praktik_new')
        ->where('siswa_id', $userId)
        ->where('status', 'final')
        ->get();
    
    echo "   Jumlah hasil (direct): " . $directQuery->count() . "\n";
    
    if ($directQuery->count() > 0) {
        foreach ($directQuery as $item) {
            echo "   - ID: {$item->id}, Mata: {$item->mata_praktik}, Nilai: {$item->total_nilai}, Status: {$item->status}\n";
        }
    }
    
    // 3. Test average calculation
    echo "\n3. Test perhitungan rata-rata:\n";
    
    $avgQuery = \Illuminate\Support\Facades\DB::table('nilai_praktik_new')
        ->where('siswa_id', $userId)
        ->where('status', 'final')
        ->avg('total_nilai');
    
    echo "   Rata-rata nilai: " . round($avgQuery ?? 0, 1) . "\n";
    
    // 4. Test count calculation
    echo "\n4. Test perhitungan jumlah:\n";
    
    $countQuery = \Illuminate\Support\Facades\DB::table('nilai_praktik_new')
        ->where('siswa_id', $userId)
        ->where('status', 'final')
        ->count();
    
    echo "   Jumlah nilai: " . $countQuery . "\n";
    
    // 5. Test latest grade
    echo "\n5. Test grade terakhir:\n";
    
    $latestQuery = \Illuminate\Support\Facades\DB::table('nilai_praktik_new')
        ->where('siswa_id', $userId)
        ->where('status', 'final')
        ->orderBy('created_at', 'desc')
        ->first();
    
    if ($latestQuery) {
        echo "   Grade terakhir: {$latestQuery->grade} (nilai: {$latestQuery->total_nilai})\n";
    } else {
        echo "   Tidak ada grade terakhir\n";
    }
    
    // 6. Cek apakah ada masalah dengan relasi
    echo "\n6. Cek relasi user-siswa:\n";
    
    $userCheck = \Illuminate\Support\Facades\DB::table('users')
        ->where('id', $userId)
        ->first();
    
    if ($userCheck) {
        echo "   ✅ User ditemukan: {$userCheck->name} (role: {$userCheck->role})\n";
    } else {
        echo "   ❌ User tidak ditemukan\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== SELESAI ===\n";
?>
