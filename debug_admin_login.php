<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG LOGIN ADMIN\n";
echo "=====================================\n\n";

try {
    // Cek admin account
    $adminEmail = 'admin@lms-trimurti.sch.id';
    $admin = \App\Models\User::where('email', $adminEmail)->first();
    
    if (!$admin) {
        echo "❌ Admin account tidak ditemukan!\n";
        echo "Email: {$adminEmail}\n\n";
        
        echo "Membuat admin account baru...\n";
        $admin = \App\Models\User::create([
            'name' => 'Admin LMS Trimurti',
            'email' => $adminEmail,
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
        echo "✅ Admin account berhasil dibuat!\n";
    } else {
        echo "✅ Admin account ditemukan:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "ID: {$admin->id}\n";
        echo "Email: {$admin->email}\n";
        echo "Nama: {$admin->name}\n";
        echo "Role: {$admin->role}\n";
        echo "Email Verified: " . ($admin->email_verified_at ? 'Yes' : 'No') . "\n";
        echo "Created: {$admin->created_at}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }
    
    // Test password verification
    echo "🔑 Testing Password Verification:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $testPassword = 'password';
    $isMatch = \Illuminate\Support\Facades\Hash::check($testPassword, $admin->password);
    
    echo "Testing password: '{$testPassword}'\n";
    echo "Hash match: " . ($isMatch ? '✅ YES' : '❌ NO') . "\n";
    echo "Stored hash: " . substr($admin->password, 0, 20) . "...\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    if (!$isMatch) {
        echo "❌ Password tidak cocok! Reset password...\n";
        $admin->password = bcrypt($testPassword);
        $admin->save();
        echo "✅ Password berhasil di-reset!\n\n";
        
        // Test lagi
        $isMatch = \Illuminate\Support\Facades\Hash::check($testPassword, $admin->password);
        echo "Testing password setelah reset: " . ($isMatch ? '✅ YES' : '❌ NO') . "\n";
    }
    
    // Cek semua admin accounts
    echo "\n📊 Semua Admin Accounts:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $allAdmins = \App\Models\User::where('role', 'admin')->get();
    echo "Total: " . $allAdmins->count() . "\n";
    
    foreach ($allAdmins as $a) {
        echo "- {$a->email} ({$a->name})\n";
    }
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "✅ ADMIN ACCOUNT SIAP UNTUK LOGIN\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Email: {$admin->email}\n";
    echo "Password: password\n";
    echo "Role: admin\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n✅ Debug selesai\n";
?>
