<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG REDIRECT ISSUE\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Test Route Generation\n";
    echo "-------------------------------------\n";
    
    $routes = ['admin.users.guru', 'users.guru', 'admin.users.index'];
    
    foreach ($routes as $routeName) {
        try {
            $url = route($routeName);
            echo "✅ route('{$routeName}') = {$url}\n";
        } catch (Exception $e) {
            echo "❌ route('{$routeName}') ERROR: {$e->getMessage()}\n";
        }
    }
    
    echo "\nStep 2: Check Controller Redirect Code\n";
    echo "-------------------------------------\n";
    
    $controllerPath = __DIR__ . '/app/Http/Controllers/Admin/ModernUserController.php';
    $controllerContent = file_get_contents($controllerPath);
    
    // Find the redirect line
    if (preg_match('/return redirect\(\)->route\([\'"]([^\'"]+)[\'"]\)/', $controllerContent, $matches)) {
        echo "Current redirect route: {$matches[1]}\n";
        
        if ($matches[1] === 'admin.users.guru') {
            echo "✅ Controller redirect is CORRECT\n";
        } else {
            echo "❌ Controller redirect is WRONG\n";
            echo "Should be: admin.users.guru\n";
            echo "Currently: {$matches[1]}\n";
        }
    } else {
        echo "❌ Could not find redirect line in controller\n";
    }
    
    echo "\nStep 3: Test Controller Redirect Directly\n";
    echo "-------------------------------------\n";
    
    // Create a simple redirect response
    $redirect = new \Illuminate\Http\RedirectResponse(route('admin.users.guru'));
    echo "Direct redirect URL: {$redirect->getTargetUrl()}\n";
    
    // Add session flash message
    $redirect->with('success', 'Guru berhasil ditambahkan');
    echo "✅ Redirect with success message created\n";
    
    echo "\nStep 4: Check if Route is Actually Working\n";
    echo "-------------------------------------\n";
    
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    
    $found = false;
    foreach ($routes as $route) {
        if ($route->getName() === 'admin.users.guru') {
            echo "✅ Route 'admin.users.guru' found\n";
            echo "  URI: {$route->uri()}\n";
            echo "  Methods: " . implode(', ', $route->methods()) . "\n";
            echo "  Action: {$route->getActionName()}\n";
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        echo "❌ Route 'admin.users.guru' NOT found!\n";
        
        // Check if we need to create it
        echo "\nChecking existing similar routes...\n";
        foreach ($routes as $route) {
            $name = $route->getName();
            if ($name && str_contains($name, 'guru')) {
                echo "  - {$name}: {$route->uri()}\n";
            }
        }
    }
    
    echo "\nStep 5: Test Full Flow with Mock Request\n";
    echo "-------------------------------------\n";
    
    // Create test user
    $testUser = \App\Models\UserCentral::create([
        'name' => 'Test Guru Final ' . time(),
        'email' => 'final' . time() . '@example.com',
        'username' => 'final' . time(),
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'role' => 'guru',
        'phone' => '08123456789',
        'is_active' => true,
    ]);
    
    echo "Created test user: {$testUser->name} (ID: {$testUser->id})\n";
    
    // Create mock request
    $request = new \Illuminate\Http\Request();
    $request->merge([
        'name' => $testUser->name,
        'email' => $testUser->email,
        'username' => $testUser->username,
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone' => '08123456789',
        'nip' => str_shuffle('1234567890123456'),
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '1990-01-01',
        'alamat' => 'Jakarta',
        'email_pribadi' => 'personal@example.com',
        'subject_id' => 1,
        'pendidikan_terakhir' => 'S1',
        'jurusan_pendidikan' => 'Teknik',
        'tahun_mulai_kerja' => 2020,
    ]);
    
    // Test controller
    $controller = new \App\Http\Controllers\Admin\ModernUserController();
    $result = $controller->storeGuru($request);
    
    echo "Controller result type: " . get_class($result) . "\n";
    echo "Redirect URL: {$result->getTargetUrl()}\n";
    
    if (str_contains($result->getTargetUrl(), 'admin/users/guru')) {
        echo "✅ Redirect is CORRECT!\n";
    } else {
        echo "❌ Redirect is WRONG!\n";
        echo "Expected: admin/users/guru\n";
        echo "Got: {$result->getTargetUrl()}\n";
    }
    
    // Check session
    $session = $result->getSession();
    if ($session && $session->has('success')) {
        echo "✅ Success message: " . $session->get('success') . "\n";
    } elseif ($session && $session->has('error')) {
        echo "❌ Error message: " . $session->get('error') . "\n";
    }
    
    echo "\n🔍 ANALYSIS:\n";
    echo "=====================================\n";
    
    if (str_contains($result->getTargetUrl(), 'admin/users/guru')) {
        echo "✅ Everything is working correctly!\n";
        echo "The redirect issue should be resolved.\n";
    } else {
        echo "❌ There's still an issue with the redirect.\n";
        echo "The controller is redirecting to the wrong URL.\n";
        
        // Suggest fix
        echo "\n🔧 SUGGESTED FIX:\n";
        echo "Update the redirect line in storeGuru method to:\n";
        echo "return redirect()->route('admin.users.guru')\n";
        echo "    ->with('success', 'Guru berhasil ditambahkan');\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
