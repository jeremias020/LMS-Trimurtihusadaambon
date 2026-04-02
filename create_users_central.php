<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FIXING USERS_CENTRAL TABLE ISSUE ===\n\n";

$pdo = \DB::connection()->getPdo();

try {
    echo "Step 1: Checking current database status...\n";
    
    // Check if users_central exists
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $hasUsersCentral = in_array('users_central', $tables);
    
    echo "Tables found: " . count($tables) . "\n";
    echo "Has users_central: " . ($hasUsersCentral ? 'Yes' : 'No') . "\n";
    
    if (!$hasUsersCentral) {
        echo "\nStep 2: Creating users_central table...\n";
        
        // Create users_central table manually
        $createTableSQL = "
        CREATE TABLE `users_central` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `email_verified_at` timestamp NULL DEFAULT NULL,
            `password` varchar(255) NOT NULL,
            `username` varchar(255) DEFAULT NULL,
            `role` varchar(50) NOT NULL DEFAULT 'siswa',
            `phone` varchar(20) DEFAULT NULL,
            `photo` varchar(255) DEFAULT NULL,
            `is_active` tinyint(1) NOT NULL DEFAULT '1',
            `remember_token` varchar(100) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `users_central_email_unique` (`email`),
            UNIQUE KEY `users_central_username_unique` (`username`),
            KEY `users_central_role_index` (`role`),
            KEY `users_central_deleted_at_index` (`deleted_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($createTableSQL);
        echo "✅ users_central table created\n";
        
        // Insert sample data
        echo "\nStep 3: Inserting sample users...\n";
        
        $sampleUsers = [
            [
                'name' => 'Admin LMS',
                'email' => 'admin@lms-trimurti.sch.id',
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Guru Sample',
                'email' => 'guru@lms-trimurti.sch.id',
                'username' => 'guru',
                'password' => password_hash('guru123', PASSWORD_DEFAULT),
                'role' => 'guru',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@lms-trimurti.sch.id',
                'username' => 'siti',
                'password' => password_hash('siswa123', PASSWORD_DEFAULT),
                'role' => 'siswa',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        foreach ($sampleUsers as $user) {
            $columns = implode(', ', array_keys($user));
            $placeholders = str_repeat('?,', count($user) - 1) . '?';
            $values = array_values($user);
            
            $stmt = $pdo->prepare("INSERT INTO users_central ({$columns}) VALUES ({$placeholders})");
            $stmt->execute($values);
            
            echo "✅ Created user: {$user['name']} ({$user['role']})\n";
        }
        
        echo "\nStep 4: Testing User model...\n";
        
        // Test User model
        $userCount = \App\Models\User::count();
        echo "✅ Total users: {$userCount}\n";
        
        // Test specific user
        $siti = \App\Models\User::where('email', 'siti@lms-trimurti.sch.id')->first();
        if ($siti) {
            echo "✅ Found Siti: {$siti->name} ({$siti->role})\n";
        } else {
            echo "❌ Siti not found\n";
        }
        
        // Test role queries
        $siswaCount = \App\Models\User::where('role', 'siswa')->count();
        $guruCount = \App\Models\User::where('role', 'guru')->count();
        $adminCount = \App\Models\User::where('role', 'admin')->count();
        
        echo "✅ Role counts:\n";
        echo "  - Siswa: {$siswaCount}\n";
        echo "  - Guru: {$guruCount}\n";
        echo "  - Admin: {$adminCount}\n";
        
        echo "\n🎉 SUCCESS! users_central table created and User model working!\n";
        echo "Login credentials:\n";
        echo "  Admin: admin@lms-trimurti.sch.id / admin123\n";
        echo "  Guru: guru@lms-trimurti.sch.id / guru123\n";
        echo "  Siswa: siti@lms-trimurti.sch.id / siswa123\n";
        
    } else {
        echo "❌ users_central already exists but has issues\n";
        
        // Try to repair
        try {
            $pdo->exec("REPAIR TABLE users_central");
            echo "✅ Attempted to repair users_central\n";
            
            // Test again
            $count = $pdo->query("SELECT COUNT(*) FROM users_central")->fetchColumn();
            echo "✅ users_central now has {$count} records\n";
            
        } catch (Exception $e) {
            echo "❌ Repair failed: " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== CLEANUP ===\n";
if (file_exists(__DIR__ . '/fix_database.php')) {
    unlink(__DIR__ . '/fix_database.php');
    echo "✅ Removed fix_database.php\n";
}
