<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 AKUN LOGIN UTAMA YANG TERSEDIA\n";
echo "=====================================\n\n";

try {
    $adminUsers = \App\Models\User::where('role', 'admin')->get();
    $guruUsers = \App\Models\User::where('role', 'guru')->limit(3)->get();
    $siswaUsers = \App\Models\User::where('role', 'siswa')->limit(3)->get();
    
    echo "👨‍💼 ADMIN (1 akun)\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    if ($adminUsers->count() > 0) {
        $admin = $adminUsers->first();
        echo "📧 Email: {$admin->email}\n";
        echo "👤 Nama: {$admin->name}\n";
        echo "🔑 Password: password\n";
    } else {
        echo "❌ Tidak ada akun admin\n";
    }
    echo "\n";
    
    echo "👨‍🏫 GURU (3 akun utama)\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    if ($guruUsers->count() > 0) {
        foreach ($guruUsers as $index => $guru) {
            echo ($index + 1) . ". 📧 Email: {$guru->email}\n";
            echo "   👤 Nama: {$guru->name}\n";
            echo "   🔑 Password: password\n\n";
        }
    } else {
        echo "❌ Tidak ada akun guru\n";
    }
    
    echo "👨‍🎓 SISWA (3 akun utama)\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    if ($siswaUsers->count() > 0) {
        foreach ($siswaUsers as $index => $siswa) {
            echo ($index + 1) . ". 📧 Email: {$siswa->email}\n";
            echo "   👤 Nama: {$siswa->name}\n";
            echo "   🔑 Password: password\n";
            
            $student = \App\Models\Student::where('id', $siswa->id)->first();
            if ($student) {
                echo "   📚 NIS: {$student->nis}\n";
            }
            echo "\n";
        }
    } else {
        echo "❌ Tidak ada akun siswa\n";
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📋 CARA LOGIN\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "1. Buka browser dan akses: http://localhost:8000/login\n";
    echo "2. Masukkan email sesuai role yang ingin digunakan\n";
    echo "3. Masukkan password: password\n";
    echo "4. Klik tombol Login\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🎯 REKOMENDASI AKUN UNTUK TESTING\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ Gunakan akun admin untuk testing fitur admin\n";
    echo "✅ Gunakan akun guru pertama untuk testing fitur guru\n";
    echo "✅ Gunakan akun siswa pertama untuk testing fitur siswa\n\n";
    
    echo "⚠️  CATATAN PENTING:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "- Password default untuk semua akun: password\n";
    echo "- Ganti password setelah login pertama untuk keamanan\n";
    echo "- Setiap role memiliki akses ke fitur yang berbeda\n";
    echo "- Admin memiliki akses penuh ke seluruh sistem\n";
    echo "- Guru dapat mengelola materi, tugas, dan penilaian\n";
    echo "- Siswa dapat mengakses materi, tugas, dan melihat nilai\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n✅ Selesai\n";
?>
