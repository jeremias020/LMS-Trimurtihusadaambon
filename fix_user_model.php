<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 FIX USER MODEL TABLE ISSUE\n";
echo "=====================================\n";

try {
    // Check User model table
    $user = new \App\Models\User();
    echo "User model table: " . $user->getTable() . "\n";
    
    // Check where admin is
    $adminInUsers = \DB::table('users')->where('role', 'admin')->first();
    $adminInUsersCentral = \DB::table('users_central')->where('role', 'admin')->first();
    
    echo "Admin in users table: " . ($adminInUsers ? 'YES' : 'NO') . "\n";
    echo "Admin in users_central: " . ($adminInUsersCentral ? 'YES' : 'NO') . "\n";
    
    // Move admin to users_central if needed
    if ($adminInUsers && !$adminInUsersCentral) {
        echo "Moving admin to users_central...\n";
        
        \DB::table('users_central')->insert([
            'id' => $adminInUsers->id,
            'name' => $adminInUsers->name,
            'email' => $adminInUsers->email,
            'password' => $adminInUsers->password,
            'role' => $adminInUsers->role,
            'is_active' => $adminInUsers->is_active ?? 1,
            'created_at' => $adminInUsers->created_at,
            'updated_at' => now()
        ]);
        
        echo "✅ Admin moved to users_central\n";
    }
    
    // Test login
    $credentials = [
        'email' => 'admin@lms-trimurti.sch.id',
        'password' => 'admin123'
    ];
    
    $user = \App\Models\User::where('email', $credentials['email'])->first();
    if ($user && \Hash::check($credentials['password'], $user->password)) {
        echo "✅ Login test: SUCCESS\n";
        echo "User: {$user->name} ({$user->role})\n";
    } else {
        echo "❌ Login test: FAILED\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
