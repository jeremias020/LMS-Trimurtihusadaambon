<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 LOGIN CONTROLLER DEBUG\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Test Exact Login Controller Logic\n";
    echo "-------------------------------------\n";
    
    // Simulate exact login controller process
    $credentials = [
        'email' => 'admin@lms-trimurti.sch.id',
        'password' => 'admin123'
    ];
    
    echo "Testing with credentials:\n";
    echo "  Email: {$credentials['email']}\n";
    echo "  Password: {$credentials['password']}\n\n";
    
    // Test validation (should pass)
    $request = new \Illuminate\Http\Request();
    $request->merge($credentials);
    
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    
    echo "✅ Validation passed\n";
    
    // Test Auth::guard('web')->attempt()
    echo "Testing Auth::guard('web')->attempt()...\n";
    
    $webAuthResult = \Auth::guard('web')->attempt($credentials);
    echo "Web guard result: " . ($webAuthResult ? 'SUCCESS' : 'FAILED') . "\n";
    
    if ($webAuthResult) {
        $user = \Auth::guard('web')->user();
        echo "Logged in user: {$user->name} ({$user->role})\n";
        \Auth::guard('web')->logout();
    }
    
    // Test regular Auth::attempt()
    echo "\nTesting regular Auth::attempt()...\n";
    $authResult = \Auth::attempt($credentials);
    echo "Regular Auth result: " . ($authResult ? 'SUCCESS' : 'FAILED') . "\n";
    
    if ($authResult) {
        $user = \Auth::user();
        echo "Logged in user: {$user->name} ({$user->role})\n";
        \Auth::logout();
    }
    
    echo "\nStep 2: Check User Model\n";
    echo "-------------------------------------\n";
    
    $userModel = new \App\Models\User();
    echo "User model class: " . get_class($userModel) . "\n";
    
    // Test User model authentication
    echo "Testing User model authentication...\n";
    
    $user = \App\Models\User::where('email', $credentials['email'])->first();
    if ($user) {
        echo "✅ User found via model\n";
        echo "  ID: {$user->id}\n";
        echo "  Email: {$user->email}\n";
        echo "  Role: {$user->role}\n";
        echo "  Password hash: " . substr($user->password, 0, 20) . "...\n";
        
        // Test password verification
        if (\Hash::check($credentials['password'], $user->password)) {
            echo "✅ Password verification via model: PASS\n";
        } else {
            echo "❌ Password verification via model: FAIL\n";
        }
    } else {
        echo "❌ User not found via model\n";
    }
    
    echo "\nStep 3: Check Auth Configuration\n";
    echo "-------------------------------------\n";
    
    $guards = config('auth.guards');
    $providers = config('auth.providers');
    
    echo "Auth guards:\n";
    foreach ($guards as $name => $guard) {
        echo "  - {$name}: {$guard['driver']} (provider: {$guard['provider']})\n";
    }
    
    echo "\nAuth providers:\n";
    foreach ($providers as $name => $provider) {
        echo "  - {$name}: {$provider['driver']} (model: {$provider['model']})\n";
    }
    
    echo "\nStep 4: Test Direct Model Login\n";
    echo "-------------------------------------\n";
    
    // Test manual login using User model
    $user = \App\Models\User::where('email', $credentials['email'])->first();
    
    if ($user && \Hash::check($credentials['password'], $user->password)) {
        echo "✅ Manual verification successful\n";
        
        // Try to login manually
        try {
            \Auth::login($user);
            echo "✅ Manual Auth::login() successful\n";
            
            $loggedInUser = \Auth::user();
            echo "Logged in as: {$loggedInUser->name}\n";
            
            \Auth::logout();
            echo "✅ Logout successful\n";
        } catch (Exception $e) {
            echo "❌ Manual login failed: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\nStep 5: Fix Login Issue\n";
    echo "-------------------------------------\n";
    
    // The issue might be with the auth guard or model
    // Let's try to fix it by testing different approaches
    
    echo "Testing different login approaches...\n";
    
    // Approach 1: Using User model directly
    $user = \App\Models\User::where('email', $credentials['email'])->first();
    if ($user && \Hash::check($credentials['password'], $user->password)) {
        echo "✅ Approach 1 (User model): User exists and password matches\n";
    }
    
    // Approach 2: Check if there are session issues
    echo "Session ID: " . session()->getId() . "\n";
    echo "Session is started: " . (session()->isStarted() ? 'Yes' : 'No') . "\n";
    
    // Approach 3: Check if there are middleware issues
    echo "Checking middleware...\n";
    
    echo "\nStep 6: Create Simple Login Test\n";
    echo "-------------------------------------\n";
    
    // Create a simple test that mimics the login process exactly
    echo "Creating simple login test...\n";
    
    $testRequest = new class {
        public $email = 'admin@lms-trimurti.sch.id';
        public $password = 'admin123';
    };
    
    // Validate
    $validated = [
        'email' => $testRequest->email,
        'password' => $testRequest->password
    ];
    
    echo "Validated credentials:\n";
    echo "  Email: {$validated['email']}\n";
    echo "  Password: {$validated['password']}\n";
    
    // Attempt login
    if (\Auth::attempt($validated)) {
        echo "✅ Login successful!\n";
        $user = \Auth::user();
        echo "User: {$user->name} ({$user->role})\n";
        
        // Test redirect
        $redirect = match($user->role) {
            'admin' => 'admin.dashboard',
            'guru' => 'guru.dashboard',
            'siswa' => 'siswa.dashboard',
            default => 'home'
        };
        echo "Would redirect to: {$redirect}\n";
        
        \Auth::logout();
    } else {
        echo "❌ Login failed\n";
        
        // Debug why it failed
        $user = \App\Models\User::where('email', $validated['email'])->first();
        if (!$user) {
            echo "  Reason: User not found\n";
        } elseif (!\Hash::check($validated['password'], $user->password)) {
            echo "  Reason: Password mismatch\n";
        } else {
            echo "  Reason: Unknown (Auth system issue)\n";
        }
    }
    
    echo "\n🎉 LOGIN DEBUG COMPLETE!\n";
    echo "=====================================\n";
    echo "If the simple test above shows SUCCESS but the actual login fails,\n";
    echo "the issue might be:\n";
    echo "1. Session configuration\n";
    echo "2. CSRF token issues\n";
    echo "3. Browser cache/cookies\n";
    echo "4. Middleware interference\n";
    echo "5. Route configuration\n";
    
    echo "\n📋 Try these steps:\n";
    echo "1. Clear browser cache and cookies\n";
    echo "2. Try in incognito/private mode\n";
    echo "3. Check if session driver is working\n";
    echo "4. Verify CSRF token is present in form\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
