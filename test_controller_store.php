<?php
echo "=== TESTING USER STORE CONTROLLER ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Create mock request
    $mockRequest = new \Illuminate\Http\Request();
    $mockRequest->merge([
        'name' => 'Test Siswa Controller ' . time(),
        'email' => 'testcontroller' . time() . '@example.com',
        'role' => 'siswa',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'nis' => '12345',
        'kelas_id' => '1',
        'jurusan_id' => '1',
        'birth_date' => '2000-01-01',
        'address' => 'Test Address 123'
    ]);
    
    // Mock IP
    $mockRequest->server->set('REMOTE_ADDR', '127.0.0.1');
    
    echo "Testing UserController::store method...\n";
    echo "Request data:\n";
    foreach ($mockRequest->all() as $key => $value) {
        echo "  - $key: $value\n";
    }
    
    // Create controller instance
    $controller = new \App\Http\Controllers\Admin\UserController();
    
    echo "\nCalling store method...\n";
    
    try {
        $response = $controller->store($mockRequest);
        
        echo "✅ Store method executed successfully\n";
        echo "Response type: " . get_class($response) . "\n";
        
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            echo "Redirect URL: " . $response->getTargetUrl() . "\n";
            echo "Session data:\n";
            if (session('success')) {
                echo "  - Success: " . session('success') . "\n";
            }
            if (session('error')) {
                echo "  - Error: " . session('error') . "\n";
            }
        }
        
        // Check if user was actually created
        $email = $mockRequest->email;
        $user = \App\Models\User::where('email', $email)->first();
        
        if ($user) {
            echo "✅ User created with ID: {$user->id}\n";
            // Clean up
            $user->delete();
            echo "✅ Test user cleaned up\n";
        } else {
            echo "❌ User was not created\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Controller store method failed: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
