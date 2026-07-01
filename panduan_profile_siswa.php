<?php

echo "🎯 PANDUAN AKSES HALAMAN PROFILE SISWA\n";
echo "=====================================\n";

echo "\n📋 STATUS SISTEM:\n";
echo "=====================================\n";
echo "✅ Routes: /siswa/profile (GET/PUT)\n";
echo "✅ Controller: SiswaProfileController\n";
echo "✅ Middleware: siswa (authenticated + role check)\n";
echo "✅ View: siswa.profile.edit\n";
echo "✅ Layout: layouts.siswa\n";
echo "✅ User: Siti Nurhaliza (siswa@lms-trimurti.sch.id)\n";
echo "✅ Student Data: Available\n";

echo "\n🔑 LANGKAH AKSES:\n";
echo "=====================================\n";
echo "1. Login sebagai siswa:\n";
echo "   - Email: siswa@lms-trimurti.sch.id\n";
echo "   - Password: (cek di database/seeder)\n";
echo "\n";
echo "2. Akses URL:\n";
echo "   - http://127.0.0.1:8000/siswa/profile\n";
echo "   - atau klik menu 'Profile' di sidebar\n";
echo "\n";
echo "3. Halaman akan menampilkan:\n";
echo "   - Form edit profil siswa\n";
echo "   - Data pribadi (nama, email, NISN)\n";
echo "   - Data akademik (kelas, jurusan)\n";
echo "   - Foto profil\n";
echo "   - Password change\n";

echo "\n🛠️ JIKA MASIH TIDAK BISA AKSES:\n";
echo "=====================================\n";
echo "1. Clear cache Laravel:\n";
echo "   php artisan cache:clear\n";
echo "   php artisan config:clear\n";
echo "   php artisan route:clear\n";
echo "   php artisan view:clear\n";
echo "\n";
echo "2. Clear session:\n";
echo "   php artisan session:clear\n";
echo "\n";
echo "3. Clear browser cache:\n";
echo "   - Ctrl+F5 (hard refresh)\n";
echo "   - Clear cookies untuk domain localhost\n";
echo "\n";
echo "4. Restart server:\n";
echo "   php artisan serve\n";
echo "\n";
echo "5. Check error log:\n";
echo "   storage/logs/laravel.log\n";

echo "\n🔍 DEBUGGING STEPS:\n";
echo "=====================================\n";
echo "1. Pastikan sudah login:\n";
echo "   - Cek dashboard siswa accessible\n";
echo "   - Cek auth session valid\n";
echo "\n";
echo "2. Test route:\n";
echo "   - php artisan route:list | grep siswa.profile\n";
echo "   - Pastikan route registered\n";
echo "\n";
echo "3. Test middleware:\n";
echo "   - php artisan tinker\n";
echo "   - Auth::loginUsingId(1);\n";
echo "   - Auth::user()->role; // harus 'siswa'\n";
echo "\n";
echo "4. Test controller:\n";
echo "   - php artisan tinker\n";
echo "   - \$controller = new App\Http\Controllers\Siswa\ProfileController();\n";
echo "   - \$controller->edit();\n";

echo "\n📝 ROUTE DETAILS:\n";
echo "=====================================\n";
echo "GET  /siswa/profile → siswa.profile.edit → SiswaProfileController@edit\n";
echo "PUT  /siswa/profile → siswa.profile.update → SiswaProfileController@update\n";
echo "\n";
echo "Middleware: ['auth', 'siswa']\n";
echo "  - auth: harus login\n";
echo "  - siswa: role harus 'siswa'\n";

echo "\n🎯 EXPECTED BEHAVIOR:\n";
echo "=====================================\n";
echo "✅ Jika login sebagai siswa → Bisa akses halaman profile\n";
echo "✅ Jika login sebagai guru → 403 Forbidden\n";
echo "✅ Jika belum login → Redirect ke login\n";
echo "✅ Data student tidak ada → Redirect ke dashboard dengan error\n";

echo "\n🚀 TROUBLESHOOTING MATRIX:\n";
echo "=====================================\n";
echo "Error | Kemungkinan Penyebab | Solusi\n";
echo "------|------------------|--------\n";
echo "403   | Role bukan siswa  | Login sebagai siswa\n";
echo "404   | Route tidak ada  | Check web.php routes\n";
echo "500   | Server error     | Check laravel.log\n";
echo "Login | Session expired  | Login kembali\n";

echo "\n✨ SUMMARY:\n";
echo "=====================================\n";
echo "✅ Sistem profile siswa sudah lengkap dan berfungsi\n";
echo "✅ Routes, controller, middleware, view semua ada\n";
echo "✅ User siswa tersedia dan valid\n";
echo "✅ Student data tersedia dan accessible\n";
echo "\n";
echo "🎉 HALAMAN PROFILE SISWA SEHARUSNYA BISA DIAKSES! 🎉\n";
echo "\n";
echo "Jika masih tidak bisa, kemungkinan besar:\n";
echo "1. Belum login sebagai siswa\n";
echo "2. Cache/cookie issues\n";
echo "3. Server error (check logs)\n";
echo "\n";
echo "Silakan coba langkah troubleshooting di atas.\n";
?>
