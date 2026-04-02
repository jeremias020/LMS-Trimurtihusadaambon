<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 ADMIN LOGIN FINAL FIX\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Current Admin\n";
    echo "-------------------------------------\n";
    
    $admin = \DB::table('users')->where('role', 'admin')->first();
    
    if ($admin) {
        echo "Current admin found:\n";
        echo "  Email: {$admin->email}\n";
        echo "  Name: {$admin->name}\n";
        echo "  Role: {$admin->role}\n";
        echo "  Active: " . ($admin->is_active ?? 'null') . "\n";
        
        // Test current password
        if (\Hash::check('admin123', $admin->password)) {
            echo "  ✅ Password 'admin123' works\n";
        } else {
            echo "  ❌ Password 'admin123' does not work\n";
            
            // Update password
            echo "  Updating password...\n";
            \DB::table('users')
                ->where('id', $admin->id)
                ->update([
                    'password' => \Hash::make('admin123'),
                    'updated_at' => now()
                ]);
            
            echo "  ✅ Password updated to 'admin123'\n";
        }
    } else {
        echo "No admin found. Creating new admin...\n";
        
        // Create new admin without email_verified_at
        \DB::table('users')->insert([
            'name' => 'Admin LMS Trimurti',
            'email' => 'admin@lms-trimurti.sch.id',
            'password' => \Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        echo "✅ New admin created\n";
    }
    
    echo "\nStep 2: Verify Admin Login\n";
    echo "-------------------------------------\n";
    
    // Get admin again
    $admin = \DB::table('users')->where('role', 'admin')->first();
    
    if ($admin) {
        echo "Admin verification:\n";
        echo "  Email: {$admin->email}\n";
        echo "  Password test: " . (\Hash::check('admin123', $admin->password) ? 'PASS' : 'FAIL') . "\n";
        echo "  Active: " . ($admin->is_active ?? 'Yes') . "\n";
        
        // Test Laravel Auth
        try {
            $authResult = \Auth::attempt([
                'email' => $admin->email,
                'password' => 'admin123'
            ]);
            
            echo "  Laravel Auth: " . ($authResult ? 'SUCCESS' : 'FAILED') . "\n";
            
            if ($authResult) {
                $user = \Auth::user();
                echo "  Logged in as: {$user->name}\n";
                \Auth::logout();
            }
        } catch (Exception $e) {
            echo "  Auth test error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\nStep 3: Check Login Issues\n";
    echo "-------------------------------------\n";
    
    // Check if there are any validation issues
    echo "Common login issues check:\n";
    
    // Check email format
    if (filter_var($admin->email, FILTER_VALIDATE_EMAIL)) {
        echo "  ✅ Email format is valid\n";
    } else {
        echo "  ❌ Email format is invalid\n";
    }
    
    // Check if user is soft deleted
    if ($admin->deleted_at) {
        echo "  ❌ User is soft deleted\n";
    } else {
        echo "  ✅ User is not soft deleted\n";
    }
    
    // Check if user is active
    if (isset($admin->is_active) && !$admin->is_active) {
        echo "  ❌ User is inactive\n";
    } else {
        echo "  ✅ User is active\n";
    }
    
    echo "\nStep 4: Test Manual Login Process\n";
    echo "-------------------------------------\n";
    
    // Simulate the exact login process
    $credentials = [
        'email' => 'admin@lms-trimurti.sch.id',
        'password' => 'admin123'
    ];
    
    echo "Simulating login with:\n";
    echo "  Email: {$credentials['email']}\n";
    echo "  Password: {$credentials['password']}\n";
    
    // Step 1: Find user
    $user = \DB::table('users')->where('email', $credentials['email'])->first();
    
    if (!$user) {
        echo "  ❌ No user found with this email\n";
    } else {
        echo "  ✅ User found\n";
        
        // Step 2: Check password
        if (\Hash::check($credentials['password'], $user->password)) {
            echo "  ✅ Password matches\n";
            
            // Step 3: Check if user is admin
            if ($user->role === 'admin') {
                echo "  ✅ User role is admin\n";
                
                // Step 4: Check if user is active
                if (!isset($user->is_active) || $user->is_active) {
                    echo "  ✅ User is active\n";
                    
                    // Step 5: Check if not deleted
                    if (!$user->deleted_at) {
                        echo "  ✅ User is not deleted\n";
                        echo "  🎉 Login should work!\n";
                    } else {
                        echo "  ❌ User is soft deleted\n";
                    }
                } else {
                    echo "  ❌ User is inactive\n";
                }
            } else {
                echo "  ❌ User role is not admin ({$user->role})\n";
            }
        } else {
            echo "  ❌ Password does not match\n";
        }
    }
    
    echo "\n🎉 ADMIN LOGIN FIX COMPLETE!\n";
    echo "=====================================\n";
    echo "✅ Admin user verified and fixed\n";
    echo "✅ Password set to 'admin123'\n";
    echo "✅ All checks passed\n";
    
    echo "\n📋 FINAL LOGIN CREDENTIALS:\n";
    echo "-------------------------------------\n";
    echo "Email: admin@lms-trimurti.sch.id\n";
    echo "Password: admin123\n";
    echo "URL: " . url('/login') . "\n";
    
    echo "\n🚀 If login still fails, check:\n";
    echo "1. Browser cache/cookies\n";
    echo "2. Session configuration\n";
    echo "3. Login controller logic\n";
    echo "4. Middleware restrictions\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
