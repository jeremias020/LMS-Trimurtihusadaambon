<?php
echo "=== MENCARI DATA AGUS SETIAWAN ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // 1. Cari user dengan nama Agus Setiawan
    echo "1. Mencari data user Agus Setiawan...\n";
    $users = \Illuminate\Support\Facades\DB::table('users')
        ->where('name', 'LIKE', '%Agus%')
        ->orWhere('name', 'LIKE', '%Setiawan%')
        ->orWhere('name', 'LIKE', '%agus%')
        ->orWhere('name', 'LIKE', '%setiawan%')
        ->get();
    
    if ($users->count() > 0) {
        echo "✅ Ditemukan {$users->count()} user:\n";
        foreach ($users as $user) {
            echo "   - ID: {$user->id}, Nama: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
        }
    } else {
        echo "❌ Tidak ditemukan user dengan nama Agus Setiawan\n";
        
        // Cari semua user siswa
        echo "\n   Daftar semua user siswa:\n";
        $allSiswa = \Illuminate\Support\Facades\DB::table('users')
            ->where('role', 'siswa')
            ->limit(10)
            ->get();
        
        foreach ($allSiswa as $siswa) {
            echo "   - ID: {$siswa->id}, Nama: {$siswa->name}\n";
        }
    }
    
    // 2. Jika ditemukan, periksa nilai praktiknya
    if ($users->count() > 0) {
        $agusUser = $users->first();
        $agusId = $agusUser->id;
        
        echo "\n2. Memeriksa nilai praktik untuk user ID: {$agusId}...\n";
        
        // Periksa tabel nilai_praktik_new
        $nilaiPraktik = \Illuminate\Support\Facades\DB::table('nilai_praktik_new')
            ->where('siswa_id', $agusId)
            ->get();
        
        echo "📊 Jumlah nilai praktik: {$nilaiPraktik->count()}\n";
        
        if ($nilaiPraktik->count() > 0) {
            foreach ($nilaiPraktik as $nilai) {
                echo "   - ID: {$nilai->id}, Mata Praktik: {$nilai->mata_praktik}, Nilai: {$nilai->total_nilai}, Grade: {$nilai->grade}, Status: {$nilai->status}\n";
            }
        } else {
            echo "❌ Tidak ada nilai praktik untuk user ini\n";
        }
        
        // 3. Periksa juga tabel nilai_praktik (backup)
        echo "\n3. Memeriksa tabel nilai_praktik (backup)...\n";
        $nilaiPraktikBackup = \Illuminate\Support\Facades\DB::table('nilai_praktik')
            ->where('siswa_id', $agusId)
            ->get();
        
        echo "📊 Jumlah nilai praktik (backup): {$nilaiPraktikBackup->count()}\n";
        
        if ($nilaiPraktikBackup->count() > 0) {
            foreach ($nilaiPraktikBackup as $nilai) {
                echo "   - ID: {$nilai->id}, Mata Praktik: {$nilai->mata_praktik}, Nilai: {$nilai->total_nilai}, Grade: {$nilai->grade}, Status: {$nilai->status}\n";
            }
        }
        
        // 4. Periksa data siswa terkait
        echo "\n4. Memeriksa data siswa terkait...\n";
        $dataSiswa = \Illuminate\Support\Facades\DB::table('siswa')
            ->where('user_id', $agusId)
            ->first();
        
        if ($dataSiswa) {
            echo "✅ Data siswa ditemukan:\n";
            echo "   - NIS: {$dataSiswa->nis}\n";
            echo "   - Kelas ID: {$dataSiswa->kelas_id}\n";
            echo "   - Jurusan ID: {$dataSiswa->jurusan_id}\n";
        } else {
            echo "❌ Data siswa tidak ditemukan di tabel siswa\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== SELESAI ===\n";
?>
