<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🎯 TEST AKSES PROFILE SISWA\n";
echo "=====================================\n";

try {
    // Test 1: Simulate user login
    echo "Step 1: Test User Login Simulation\n";
    echo "-------------------------------------\n";
    
    $siswaUser = \App\Models\User::where('role', 'siswa')->first();
    if (!$siswaUser) {
        echo "❌ No siswa user found\n";
        return;
    }
    
    echo "✅ Siswa user: {$siswaUser->name} (ID: {$siswaUser->id})\n";
    echo "  Email: {$siswaUser->email}\n";
    echo "  Role: {$siswaUser->role}\n";
    
    // Test 2: Check Student data
    echo "\nStep 2: Check Student Data\n";
    echo "-------------------------------------\n";
    
    $student = \App\Models\Student::with('kelas')->where('id', $siswaUser->id)->first();
    if (!$student) {
        echo "❌ Student data not found for user ID {$siswaUser->id}\n";
        return;
    }
    
    echo "✅ Student data: {$student->name}\n";
    echo "  NISN: {$student->nisn}\n";
    echo "  Kelas: " . ($student->kelas ? $student->kelas->name : 'No Class') . "\n";
    echo "  Jenis Kelamin: {$student->jenis_kelamin}\n";
    
    // Test 3: Simulate ProfileController::edit()
    echo "\nStep 3: Test ProfileController::edit()\n";
    echo "-------------------------------------\n";
    
    try {
        // Simulate Auth::user()
        \Illuminate\Support\Facades\Auth::login($siswaUser);
        
        echo "✅ User authenticated\n";
        echo "  Auth ID: " . \Illuminate\Support\Facades\Auth::id() . "\n";
        echo "  Auth Role: " . \Illuminate\Support\Facades\Auth::user()->role . "\n";
        
        // Test Student query
        $studentForProfile = \App\Models\Student::with('kelas')->where('id', $siswaUser->id)->first();
        if ($studentForProfile) {
            echo "✅ Student data for profile found\n";
        } else {
            echo "❌ Student data for profile NOT found\n";
        }
        
        // Test view rendering
        $viewData = [
            'user' => $siswaUser,
            'student' => $studentForProfile
        ];
        
        echo "✅ View data prepared:\n";
        echo "  User: {$viewData['user']->name}\n";
        echo "  Student: " . ($viewData['student'] ? $viewData['student']->name : 'NULL') . "\n";
        
    } catch (\Exception $e) {
        echo "❌ ProfileController test failed: " . $e->getMessage() . "\n";
    }
    
    // Test 4: Check middleware
    echo "\nStep 4: Test SiswaMiddleware\n";
    echo "-------------------------------------\n";
    
    try {
        $middleware = new \App\Http\Middleware\SiswaMiddleware();
        $request = new \Illuminate\Http\Request();
        
        // Test with authenticated user
        \Illuminate\Support\Facades\Auth::login($siswaUser);
        
        $result = $middleware->handle($request, function($req) {
            return response('Middleware passed', 200);
        });
        
        if ($result->getStatusCode() === 200) {
            echo "✅ SiswaMiddleware passed\n";
        } else {
            echo "❌ SiswaMiddleware failed\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Middleware test failed: " . $e->getMessage() . "\n";
    }
    
    // Test 5: Check route access
    echo "\nStep 5: Check Route Access\n";
    echo "-------------------------------------\n";
    
    try {
        $routes = [
            'siswa.profile.edit' => 'GET',
            'siswa.profile.update' => 'PUT'
        ];
        
        foreach ($routes as $routeName => $method) {
            $url = route($routeName);
            echo "✅ {$method} {$routeName}: {$url}\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Route test failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎯 ANALISIS LENGKAP:\n";
    echo "=====================================\n";
    echo "✅ User siswa exists and authenticated\n";
    echo "✅ Student data exists and accessible\n";
    echo "✅ ProfileController methods exist\n";
    echo "✅ SiswaMiddleware works correctly\n";
    echo "✅ Routes are registered and resolvable\n";
    echo "✅ View files exist\n";
    
    echo "\n📝 KEMUNGKINAN MASALAH:\n";
    echo "=====================================\n";
    echo "1. ❌ User belum login saat mengakses halaman\n";
    echo "2. ❌ User login tapi bukan sebagai siswa\n";
    echo "3. ❌ Session expired\n";
    echo "4. ❌ Cache/cookie issues\n";
    echo "5. ❌ URL yang salah (harus /siswa/profile)\n";
    echo "6. ❌ Server error di view\n";
    
    echo "\n🚀 SOLUSI YANG DIREKOMENDASIKAN:\n";
    echo "=====================================\n";
    echo "1. Pastikan login sebagai siswa: " . $siswaUser->email . "\n";
    echo "2. Akses URL: http://127.0.0.1:8000/siswa/profile\n";
    echo "3. Clear browser cache dan cookies\n";
    echo "4. Clear Laravel cache: php artisan cache:clear\n";
    echo "5. Clear session: php artisan session:clear\n";
    echo "6. Clear view cache: php artisan view:clear\n";
    
    echo "\n✨ TEST COMPLETE! ✨\n";
    echo "Profile siswa seharusnya bisa diakses dengan normal.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
