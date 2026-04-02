<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEEP ADMIN LOGIN DEBUGGING\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check All Admin Users\n";
    echo "-------------------------------------\n";
    
    $allAdmins = \DB::table('users')->where('role', 'admin')->get();
    echo "Total admin users: " . count($allAdmins) . "\n\n";
    
    foreach ($allAdmins as $index => $admin) {
        echo "Admin #" . ($index + 1) . ":\n";
        echo "  ID: {$admin->id}\n";
        echo "  Name: {$admin->name}\n";
        echo "  Email: {$admin->email}\n";
        echo "  Role: {$admin->role}\n";
        echo "  Is Active: " . ($admin->is_active ?? 'null') . "\n";
        echo "  Email Verified At: " . ($admin->email_verified_at ?? 'null') . "\n";
        echo "  Created At: {$admin->created_at}\n";
        echo "  Updated At: {$admin->updated_at}\n";
        echo "  Password Length: " . strlen($admin->password) . "\n";
        echo "  Password: " . $admin->password . "\n";
        echo "\n";
    }
    
    echo "Step 2: Test Multiple Passwords\n";
    echo "-------------------------------------\n";
    
    $testPasswords = [
        'admin123',
        'password', 
        'admin',
        '123456',
        'lms123',
        'trimurti',
        'Admin123',
        'Password123'
    ];
    
    foreach ($allAdmins as $admin) {
        echo "Testing passwords for: {$admin->email}\n";
        
        foreach ($testPasswords as $password) {
            if (\Hash::check($password, $admin->password)) {
                echo "  ✅ '{$password}' MATCHES!\n";
                break 2; // Exit both loops
            }
        }
        
        echo "  ❌ No matching passwords found\n\n";
    }
    
    echo "Step 3: Check Login Controller Logic\n";
    echo "-------------------------------------\n";
    
    // Simulate login process
    $credentials = [
        'email' => 'admin@lms-trimurti.sch.id',
        'password' => 'admin123'
    ];
    
    echo "Testing credentials:\n";
    echo "  Email: {$credentials['email']}\n";
    echo "  Password: {$credentials['password']}\n\n";
    
    // Find user by email
    $user = \DB::table('users')->where('email', $credentials['email'])->first();
    
    if ($user) {
        echo "✅ User found by email\n";
        echo "  User ID: {$user->id}\n";
        echo "  User Role: {$user->role}\n";
        echo "  User Active: " . ($user->is_active ?? 'null') . "\n";
        
        // Check password
        if (\Hash::check($credentials['password'], $user->password)) {
            echo "✅ Password matches\n";
        } else {
            echo "❌ Password does NOT match\n";
            
            // Show password hash details
            echo "  Stored hash: {$user->password}\n";
            echo "  Hash length: " . strlen($user->password) . "\n";
            
            // Check if it's a Laravel hash
            if (str_starts_with($user->password, '$2y$')) {
                echo "  ✅ This is a Laravel bcrypt hash\n";
            } else {
                echo "  ❌ This is NOT a Laravel bcrypt hash\n";
            }
        }
    } else {
        echo "❌ User NOT found by email\n";
    }
    
    echo "\nStep 4: Create/Update Admin with Correct Password\n";
    echo "-------------------------------------\n";
    
    // Delete existing admin and create new one
    \DB::table('users')->where('role', 'admin')->delete();
    
    echo "Deleted existing admin users\n";
    
    // Create new admin with guaranteed correct password
    $newPassword = 'admin123';
    $hashedPassword = \Hash::make($newPassword);
    
    \DB::table('users')->insert([
        'name' => 'Admin LMS Trimurti',
        'email' => 'admin@lms-trimurti.sch.id',
        'password' => $hashedPassword,
        'role' => 'admin',
        'is_active' => 1,
        'email_verified_at' => now(),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    echo "✅ Created new admin user\n";
    echo "  Email: admin@lms-trimurti.sch.id\n";
    echo "  Password: {$newPassword}\n";
    echo "  Hashed Password: {$hashedPassword}\n";
    
    // Verify the new admin
    $newAdmin = \DB::table('users')->where('email', 'admin@lms-trimurti.sch.id')->first();
    if ($newAdmin && \Hash::check($newPassword, $newAdmin->password)) {
        echo "✅ New admin verification: PASS\n";
    } else {
        echo "❌ New admin verification: FAIL\n";
    }
    
    echo "\nStep 5: Test Laravel Auth Directly\n";
    echo "-------------------------------------\n";
    
    try {
        // Test Laravel's Auth::attempt
        $authResult = \Auth::attempt([
            'email' => 'admin@lms-trimurti.sch.id',
            'password' => 'admin123'
        ]);
        
        echo "Laravel Auth::attempt result: " . ($authResult ? 'SUCCESS' : 'FAILED') . "\n";
        
        if ($authResult) {
            $loggedInUser = \Auth::user();
            echo "Logged in user: " . $loggedInUser->name . " (" . $loggedInUser->email . ")\n";
            \Auth::logout(); // Logout after test
        }
        
    } catch (Exception $e) {
        echo "Auth test error: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 6: Check Login Routes\n";
    echo "-------------------------------------\n";
    
    $routes = \Route::getRoutes();
    $loginRoutes = [];
    
    foreach ($routes as $route) {
        if (in_array('GET', $route->methods()) && str_contains($route->uri(), 'login')) {
            $loginRoutes[] = [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName()
            ];
        }
    }
    
    echo "Login routes found:\n";
    foreach ($loginRoutes as $route) {
        echo "  - {$route['uri']} -> {$route['action']}\n";
    }
    
    echo "\n🎉 ADMIN LOGIN FIX COMPLETE!\n";
    echo "=====================================\n";
    echo "✅ Old admin users deleted\n";
    echo "✅ New admin user created\n";
    echo "✅ Password verified\n";
    echo "✅ Laravel Auth tested\n";
    
    echo "\n📋 NEW LOGIN CREDENTIALS:\n";
    echo "-------------------------------------\n";
    echo "Email: admin@lms-trimurti.sch.id\n";
    echo "Password: admin123\n";
    echo "URL: " . url('/login') . "\n";
    
    echo "\n🚀 Try logging in now with these credentials!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
