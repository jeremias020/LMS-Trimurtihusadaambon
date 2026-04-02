<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PASSWORD HASH CHECK ===\n";
$users = DB::table('users_central')->get();
foreach ($users as $user) {
    echo "User: {$user->name} ({$user->email})\n";
    echo "Role: {$user->role}\n";
    echo "Password Hash: " . substr($user->password, 0, 20) . "...\n";
    echo "---\n";
}

echo "\n=== TESTING COMMON PASSWORDS ===\n";
$commonPasswords = ['admin123', 'password', '123456', 'guru123', 'siswa123'];

foreach ($users as $user) {
    echo "\nTesting passwords for {$user->name}:\n";
    foreach ($commonPasswords as $password) {
        if (password_verify($password, $user->password)) {
            echo "✓ PASSWORD FOUND: {$password}\n";
        }
    }
}
?>
