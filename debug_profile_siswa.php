<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG PROFILE SISWA ACCESS\n";
echo "=====================================\n";

try {
    // Test 1: Cek routes yang tersedia
    echo "Step 1: Cek Siswa Routes\n";
    echo "-------------------------------------\n";
    
    $routeCollection = \Illuminate\Support\Facades\Route::getRoutes();
    $siswaRoutes = [];
    
    foreach ($routeCollection as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'siswa') === 0) {
            $siswaRoutes[] = [
                'method' => implode('|', $route->methods()),
                'uri' => $uri,
                'name' => $route->getName(),
                'action' => $route->getActionName()
            ];
        }
    }
    
    // Cari route profile
    $profileRoutes = array_filter($siswaRoutes, function($route) {
        return strpos($route['uri'], 'profile') !== false;
    });
    
    echo "Profile routes found:\n";
    foreach ($profileRoutes as $route) {
        echo "  - {$route['method']} {$route['uri']} -> {$route['name']}\n";
        echo "    Action: {$route['action']}\n";
    }
    
    echo "\nStep 2: Cek Controller yang tersedia\n";
    echo "-------------------------------------\n";
    
    $controllerPath = app_path('Http/Controllers/Siswa');
    if (is_dir($controllerPath)) {
        $files = glob($controllerPath . '/*.php');
        echo "Siswa controllers:\n";
        foreach ($files as $file) {
            $controller = basename($file, '.php');
            echo "  - {$controller}\n";
        }
    }
    
    echo "\nStep 3: Cek View yang tersedia\n";
    echo "-------------------------------------\n";
    
    $viewPath = resource_path('views/siswa/profile');
    if (is_dir($viewPath)) {
        $files = glob($viewPath . '/*.blade.php');
        echo "Profile views:\n";
        foreach ($files as $file) {
            $view = basename($file, '.blade.php');
            echo "  - {$view}\n";
        }
    }
    
    echo "\nStep 4: Test User Authentication\n";
    echo "-------------------------------------\n";
    
    // Test dengan user siswa
    $siswaUser = \App\Models\User::where('role', 'siswa')->first();
    if ($siswaUser) {
        echo "✅ Siswa user found: {$siswaUser->name} (ID: {$siswaUser->id})\n";
        
        // Test Student data
        $student = \App\Models\Student::where('id', $siswaUser->id)->first();
        if ($student) {
            echo "✅ Student data found: {$student->name}\n";
            echo "  Kelas: " . ($student->kelas ? $student->kelas->name : 'No Class') . "\n";
        } else {
            echo "❌ Student data NOT found for user ID {$siswaUser->id}\n";
        }
    } else {
        echo "❌ No siswa user found\n";
    }
    
    echo "\nStep 5: Test Route Resolution\n";
    echo "-------------------------------------\n";
    
    try {
        // Test route URL generation
        $profileEditUrl = route('siswa.profile.edit');
        echo "✅ Route 'siswa.profile.edit' resolved: {$profileEditUrl}\n";
        
        $profileUpdateUrl = route('siswa.profile.update');
        echo "✅ Route 'siswa.profile.update' resolved: {$profileUpdateUrl}\n";
        
    } catch (\Exception $e) {
        echo "❌ Route resolution failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎯 ANALISIS MASALAH:\n";
    echo "=====================================\n";
    
    if (count($profileRoutes) > 0) {
        echo "✅ Routes profile siswa ada\n";
    } else {
        echo "❌ Routes profile siswa TIDAK ADA\n";
    }
    
    if (file_exists(app_path('Http/Controllers/Siswa/ProfileController.php'))) {
        echo "✅ ProfileController ada\n";
    } else {
        echo "❌ ProfileController TIDAK ADA\n";
    }
    
    if (file_exists(resource_path('views/siswa/profile/edit.blade.php'))) {
        echo "✅ View edit.blade.php ada\n";
    } else {
        echo "❌ View edit.blade.php TIDAK ADA\n";
    }
    
    if (file_exists(resource_path('views/siswa/profile/index.blade.php'))) {
        echo "✅ View index.blade.php ada\n";
    } else {
        echo "❌ View index.blade.php TIDAK ADA (mungkin tidak diperlukan)\n";
    }
    
    echo "\n📝 SOLUSI:\n";
    echo "=====================================\n";
    echo "1. Pastikan user sudah login sebagai siswa\n";
    echo "2. Coba akses: /siswa/profile (edit)\n";
    echo "3. Coba akses: /siswa/profile (PUT untuk update)\n";
    echo "4. Periksa middleware 'siswa' di route group\n";
    echo "5. Periksa apakah user memiliki data Student yang valid\n";
    
    echo "\n✨ DEBUG COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
