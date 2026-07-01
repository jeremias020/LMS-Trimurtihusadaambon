<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 AKUN LOGIN YANG TERSEDIA\n";
echo "=====================================\n\n";

try {
    $users = \App\Models\User::all();
    
    if ($users->count() === 0) {
        echo "❌ Tidak ada akun yang ditemukan di database.\n";
        echo "\nSilakan buat akun terlebih dahulu dengan perintah:\n";
        echo "php artisan tinker\n";
        echo ">>> \\App\\Models\\User::create(['name' => 'Admin', 'email' => 'admin@lms-trimurti.sch.id', 'password' => bcrypt('password'), 'role' => 'admin']);\n";
    } else {
        echo "📊 Total Akun: " . $users->count() . "\n\n";
        
        foreach ($users as $user) {
            $roleBadge = '';
            switch($user->role) {
                case 'admin':
                    $roleBadge = '👨‍💼 ADMIN';
                    break;
                case 'guru':
                    $roleBadge = '👨‍🏫 GURU';
                    break;
                case 'siswa':
                    $roleBadge = '👨‍🎓 SISWA';
                    break;
                default:
                    $roleBadge = '👤 ' . strtoupper($user->role);
            }
            
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo "{$roleBadge}\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo "📧 Email: {$user->email}\n";
            echo "👤 Nama: {$user->name}\n";
            echo "🔑 Password: password (default)\n";
            echo "📅 Dibuat: {$user->created_at->format('d/m/Y H:i')}\n";
            
            // Check if user has student data
            if ($user->role === 'siswa') {
                $student = \App\Models\Student::where('id', $user->id)->first();
                if ($student) {
                    echo "📚 NIS: {$student->nis}\n";
                    echo "🏫 Kelas: " . ($student->kelas_id ? "ID {$student->kelas_id}" : "Belum ada") . "\n";
                }
            }
            
            // Check if user has guru data
            if ($user->role === 'guru') {
                echo "📝 Status: Aktif sebagai Guru\n";
            }
            
            echo "\n";
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📋 INSTRUKSI LOGIN\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "1. Buka halaman login: http://localhost:8000/login\n";
        echo "2. Gunakan email dan password di atas\n";
        echo "3. Password default: password\n";
        echo "4. Klik tombol Login\n\n";
        
        echo "⚠️  CATATAN PENTING:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "- Semua akun menggunakan password default: password\n";
        echo "- Admin memiliki akses penuh ke semua fitur\n";
        echo "- Guru dapat mengelola materi, tugas, dan penilaian\n";
        echo "- Siswa dapat mengakses materi, tugas, dan melihat nilai\n";
        echo "- Ganti password setelah login pertama untuk keamanan\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n✅ Cek akun selesai\n";
?>
