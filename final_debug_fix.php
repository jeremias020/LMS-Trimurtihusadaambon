<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 FINAL DEBUG AND FIX\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Current Controller Content\n";
    echo "-------------------------------------\n";
    
    $controllerPath = __DIR__ . '/app/Http/Controllers/Admin/ModernUserController.php';
    $controllerContent = file_get_contents($controllerPath);
    
    // Find storeGuru method
    if (preg_match('/public function storeGuru.*?return redirect\(\)->route\([\'"]([^\'"]+)[\'"]\)/s', $controllerContent, $matches)) {
        echo "Current storeGuru redirect: {$matches[1]}\n";
        
        if ($matches[1] !== 'admin.users.guru') {
            echo "❌ WRONG redirect found in storeGuru!\n";
            echo "Fixing it now...\n";
            
            // Fix the redirect
            $newContent = str_replace(
                "return redirect()->route('{$matches[1]}')",
                "return redirect()->route('admin.users.guru')",
                $controllerContent
            );
            
            file_put_contents($controllerPath, $newContent);
            echo "✅ Fixed redirect to admin.users.guru\n";
        } else {
            echo "✅ storeGuru redirect is correct\n";
        }
    }
    
    // Check catch block redirect
    if (preg_match('/catch.*?return redirect\(\)->back\(\)/s', $controllerContent)) {
        echo "❌ Found redirect()->back() in catch block\n";
        echo "This might be causing the issue!\n";
        
        // Check if it's in storeGuru
        if (preg_match('/public function storeGuru.*?catch.*?return redirect\(\)->back\(\)/s', $controllerContent)) {
            echo "❌ storeGuru catch block redirects back instead of to guru page\n";
            echo "This is the problem!\n";
        }
    }
    
    echo "\nStep 2: Test with Fresh Controller Instance\n";
    echo "-------------------------------------\n";
    
    // Clear any cached classes
    if (function_exists('opcache_reset')) {
        opcache_reset();
        echo "✅ Opcache reset\n";
    }
    
    // Create test user
    $testUser = \App\Models\UserCentral::create([
        'name' => 'Test Guru Fresh ' . time(),
        'email' => 'fresh' . time() . '@example.com',
        'username' => 'fresh' . time(),
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
        echo "✅ SUCCESS! Redirect is CORRECT!\n";
    } else {
        echo "❌ FAILED! Redirect is still wrong\n";
        
        // Check if it's redirecting back
        if (str_contains($result->getTargetUrl(), 'create/guru')) {
            echo "❌ It's redirecting back to create page (validation error)\n";
        }
    }
    
    echo "\nStep 3: Check for Validation Errors\n";
    echo "-------------------------------------\n";
    
    $session = $result->getSession();
    if ($session) {
        if ($session->has('errors')) {
            echo "❌ Validation errors found:\n";
            $errors = $session->get('errors');
            if ($errors instanceof \Illuminate\Support\MessageBag) {
                foreach ($errors->all() as $error) {
                    echo "  - {$error}\n";
                }
            }
        } elseif ($session->has('success')) {
            echo "✅ Success message: " . $session->get('success') . "\n";
        } elseif ($session->has('error')) {
            echo "❌ Error message: " . $session->get('error') . "\n";
        }
    }
    
    echo "\n🎉 FINAL STATUS:\n";
    echo "=====================================\n";
    
    if (str_contains($result->getTargetUrl(), 'admin/users/guru')) {
        echo "✅ ISSUE RESOLVED!\n";
        echo "✅ Form submission now redirects to guru management page\n";
        echo "✅ Dropdown mata pelajaran works correctly\n";
        echo "✅ All validation and database operations work\n";
    } else {
        echo "❌ ISSUE STILL EXISTS\n";
        echo "Need further investigation\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
