<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DATA PENGGUNA LMS TRIMURTI ===\n\n";

// Check users table
$users = DB::table('users')->get();
echo "USERS TABLE:\n";
foreach ($users as $user) {
    echo "- ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}, Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
}

echo "\nUSERS_CENTRAL TABLE:\n";
$usersCentral = DB::table('users_central')->get();
foreach ($usersCentral as $user) {
    echo "- ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}, Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
}

echo "\n=== LOGIN CREDENTIALS ===\n";
echo "Based on the data, here are the likely login credentials:\n\n";

// Check default credentials
echo "ADMIN:\n";
$admin = DB::table('users')->where('role', 'admin')->first();
if ($admin) {
    echo "- Email: {$admin->email}\n";
    echo "- Password: (Default通常是 admin123 或 password)\n";
    echo "- URL: /login\n\n";
} else {
    echo "- Email: admin@lms-trimurti.sch.id\n";
    echo "- Password: admin123\n";
    echo "- URL: /login\n\n";
}

echo "GURU:\n";
$guru = DB::table('users')->where('role', 'guru')->first();
if ($guru) {
    echo "- Email: {$guru->email}\n";
    echo "- Password: (Default通常是 guru123 或 password)\n";
    echo "- URL: /login\n\n";
} else {
    echo "- Email: guru@lms-trimurti.sch.id\n";
    echo "- Password: guru123\n";
    echo "- URL: /login\n\n";
}

echo "SISWA:\n";
$siswa = DB::table('users')->where('role', 'siswa')->first();
if ($siswa) {
    echo "- Email: {$siswa->email}\n";
    echo "- Password: (Default通常是 siswa123 或 password)\n";
    echo "- URL: /login\n\n";
} else {
    echo "- Email: siswa@lms-trimurti.sch.id\n";
    echo "- Password: siswa123\n";
    echo "- URL: /login\n\n";
}

echo "=== LOGIN LANGKAH-LANGKAH ===\n";
echo "1. Buka browser\n";
echo "2. Akses URL: http://localhost:8000/login\n";
echo "3. Masukkan email dan password sesuai role\n";
echo "4. Klik tombol Login\n";
echo "5. Sistem akan redirect ke dashboard sesuai role\n\n";

echo "=== DASHBOARD URL ===\n";
echo "Admin: http://localhost:8000/admin/dashboard\n";
echo "Guru: http://localhost:8000/guru/dashboard\n";
echo "Siswa: http://localhost:8000/siswa/dashboard\n\n";
?>
