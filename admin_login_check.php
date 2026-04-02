<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 ADMIN LOGIN TROUBLESHOOTING\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Admin User Data\n";
    echo "-------------------------------------\n";
    
    // Check users table for admin
    $adminUsers = \DB::table('users')->where('role', 'admin')->get();
    echo "Admin users in users table: " . count($adminUsers) . "\n";
    
    foreach ($adminUsers as $admin) {
        echo "  - ID: {$admin->id}\n";
        echo "  - Name: {$admin->name}\n";
        echo "  - Email: {$admin->email}\n";
        echo "  - Role: {$admin->role}\n";
        echo "  - Is Active: " . ($admin->is_active ?? 'N/A') . "\n";
        echo "  - Created At: {$admin->created_at}\n";
        echo "  - Password Hash: " . substr($admin->password, 0, 20) . "...\n";
        echo "\n";
    }
    
    // Check users_central table for admin
    $adminCentral = \DB::table('users_central')->where('role', 'admin')->get();
    echo "Admin users in users_central table: " . count($adminCentral) . "\n";
    
    foreach ($adminCentral as $admin) {
        echo "  - ID: {$admin->id}\n";
        echo "  - Name: {$admin->name}\n";
        echo "  - Email: {$admin->email}\n";
        echo "  - Role: {$admin->role}\n";
        echo "  - Password Hash: " . substr($admin->password, 0, 20) . "...\n";
        echo "\n";
    }
    
    echo "Step 2: Test Admin Password Verification\n";
    echo "-------------------------------------\n";
    
    foreach ($adminUsers as $admin) {
        echo "Testing admin: {$admin->email}\n";
        
        // Test with common passwords
        $testPasswords = ['admin123', 'password', '123456', 'admin'];
        
        foreach ($testPasswords as $password) {
            if (\Hash::check($password, $admin->password)) {
                echo "  ✅ Password '{$password}' WORKS!\n";
                break;
            }
        }
        
        // Check if password is properly hashed
        if (strlen($admin->password) < 60) {
            echo "  ⚠️ Password appears to be plain text or short hash\n";
        } else {
            echo "  ✅ Password appears to be properly hashed\n";
        }
        
        echo "\n";
    }
    
    echo "Step 3: Check Authentication Configuration\n";
    echo "-------------------------------------\n";
    
    // Check auth configuration
    echo "Auth guards:\n";
    $guards = config('auth.guards');
    foreach ($guards as $guardName => $guard) {
        echo "  - {$guardName}: " . ($guard['driver'] ?? 'N/A') . "\n";
    }
    
    echo "\nAuth providers:\n";
    $providers = config('auth.providers');
    foreach ($providers as $providerName => $provider) {
        echo "  - {$providerName}: " . ($provider['driver'] ?? 'N/A') . "\n";
        echo "    Model: " . ($provider['model'] ?? 'N/A') . "\n";
    }
    
    echo "\nStep 4: Check Session Configuration\n";
    echo "-------------------------------------\n";
    
    echo "Session driver: " . config('session.driver') . "\n";
    echo "Session lifetime: " . config('session.lifetime') . " minutes\n";
    echo "Session path: " . config('session.path') . "\n";
    echo "Session domain: " . config('session.domain') . "\n";
    
    echo "\nStep 5: Test Manual Authentication\n";
    echo "-------------------------------------\n";
    
    // Try to find and test admin user
    $adminUser = \DB::table('users')->where('role', 'admin')->first();
    if ($adminUser) {
        echo "Found admin user: {$adminUser->email}\n";
        
        // Test password verification
        if (\Hash::check('admin123', $adminUser->password)) {
            echo "✅ Password 'admin123' is correct\n";
        } else {
            echo "❌ Password 'admin123' is incorrect\n";
            
            // Try to update password
            echo "Updating admin password to 'admin123'...\n";
            \DB::table('users')
                ->where('id', $adminUser->id)
                ->update([
                    'password' => \Hash::make('admin123'),
                    'updated_at' => now()
                ]);
            echo "✅ Password updated successfully\n";
        }
        
        // Check if user is active
        if (isset($adminUser->is_active) && !$adminUser->is_active) {
            echo "⚠️ Admin user is not active. Activating...\n";
            \DB::table('users')
                ->where('id', $adminUser->id)
                ->update(['is_active' => true]);
            echo "✅ Admin user activated\n";
        }
        
    } else {
        echo "❌ No admin user found in users table\n";
        
        // Create admin user
        echo "Creating admin user...\n";
        \DB::table('users')->insert([
            'name' => 'Admin LMS',
            'email' => 'admin@lms-trimurti.sch.id',
            'password' => \Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "✅ Admin user created successfully\n";
    }
    
    echo "\nStep 6: Check Routes and Middleware\n";
    echo "-------------------------------------\n";
    
    // Check if admin routes exist
    $routes = \Route::getRoutes();
    $adminRoutes = [];
    
    foreach ($routes as $route) {
        if (str_contains($route->uri(), 'admin')) {
            $adminRoutes[] = $route->uri();
        }
    }
    
    echo "Admin routes found: " . count($adminRoutes) . "\n";
    if (!empty($adminRoutes)) {
        foreach (array_slice($adminRoutes, 0, 5) as $route) {
            echo "  - {$route}\n";
        }
        if (count($adminRoutes) > 5) {
            echo "  - ... and " . (count($adminRoutes) - 5) . " more\n";
        }
    }
    
    echo "\nStep 7: Final Admin Verification\n";
    echo "-------------------------------------\n";
    
    // Final check
    $finalAdmin = \DB::table('users')->where('role', 'admin')->first();
    if ($finalAdmin) {
        echo "✅ Admin user exists: {$finalAdmin->email}\n";
        echo "✅ Admin user is active: " . ($finalAdmin->is_active ?? 'Yes') . "\n";
        echo "✅ Admin password test: " . (\Hash::check('admin123', $finalAdmin->password) ? 'PASS' : 'FAIL') . "\n";
        
        if (\Hash::check('admin123', $finalAdmin->password)) {
            echo "\n🎉 ADMIN LOGIN SHOULD WORK NOW!\n";
            echo "=====================================\n";
            echo "Email: admin@lms-trimurti.sch.id\n";
            echo "Password: admin123\n";
            echo "URL: " . url('/login') . "\n";
        } else {
            echo "\n❌ Admin password verification still failed\n";
        }
    } else {
        echo "❌ Admin user still not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
