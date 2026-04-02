<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING LOGIN WITH SIMULATED REQUEST ===\n\n";

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

try {
    echo "1. CREATING SIMULATED LOGIN REQUEST...\n";
    
    // Simulate login request
    $request = new Request();
    $request->merge([
        'email' => 'admin@lms-trimurti.sch.id',
        'password' => 'admin123'
    ]);
    
    echo "✅ Request created with:\n";
    echo "  - Email: {$request->email}\n";
    echo "  - Password: " . str_repeat('*', strlen($request->password)) . "\n";
    
    echo "\n2. TESTING LOGIN CONTROLLER LOGIC...\n";
    
    // Validate credentials
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    
    echo "✅ Credentials validated\n";
    
    // Attempt authentication
    if (Auth::guard('web')->attempt($credentials)) {
        echo "✅ Authentication successful!\n";
        
        $user = Auth::user();
        echo "  - Logged in user: {$user->name}\n";
        echo "  - Role: {$user->role}\n";
        echo "  - ID: {$user->id}\n";
        
        // Test redirect logic
        $redirect = match($user->role) {
            'admin' => 'admin.dashboard',
            'guru' => 'guru.dashboard', 
            'siswa' => 'siswa.dashboard',
            default => 'home'
        };
        
        echo "  - Would redirect to: {$redirect}\n";
        
        // Logout
        Auth::logout();
        echo "✅ Logged out (cleanup)\n";
        
    } else {
        echo "❌ Authentication failed\n";
        
        // Debug why
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user) {
            echo "  - User exists: Yes\n";
            echo "  - Is active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
            echo "  - Email verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "\n";
            
            $passwordOk = \Illuminate\Support\Facades\Hash::check($request->password, $user->password);
            echo "  - Password correct: " . ($passwordOk ? 'Yes' : 'No') . "\n";
        } else {
            echo "  - User exists: No\n";
        }
    }
    
    echo "\n3. TESTING ALL USER CREDENTIALS...\n";
    
    $testCredentials = [
        ['email' => 'admin@lms-trimurti.sch.id', 'password' => 'admin123', 'role' => 'admin'],
        ['email' => 'guru@lms-trimurti.sch.id', 'password' => 'guru123', 'role' => 'guru'],
        ['email' => 'siti@lms-trimurti.sch.id', 'password' => 'siswa123', 'role' => 'siswa'],
    ];
    
    foreach ($testCredentials as $creds) {
        $request = new Request();
        $request->merge($creds);
        
        if (Auth::attempt($creds)) {
            echo "✅ {$creds['email']} - Login successful\n";
            Auth::logout();
        } else {
            echo "❌ {$creds['email']} - Login failed\n";
        }
    }
    
    echo "\n=== CONCLUSION ===\n";
    echo "If authentication works in this test but fails in browser:\n";
    echo "1. Check browser cookies and JavaScript\n";
    echo "2. Check CSRF token in login form\n";
    echo "3. Check session storage (file vs database)\n";
    echo "4. Check if routes are properly defined\n";
    echo "5. Try clearing browser cache and cookies\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== CLEANUP ===\n";
if (file_exists(__DIR__ . '/test_login_fixed.php')) {
    unlink(__DIR__ . '/test_login_fixed.php');
    echo "✅ Removed test_login_fixed.php\n";
}
if (file_exists(__DIR__ . '/debug_login.php')) {
    unlink(__DIR__ . '/debug_login.php');
    echo "✅ Removed debug_login.php\n";
}
