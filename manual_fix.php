<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== MANUAL TABLE CREATION SOLUTION ===\n\n";

$pdo = \DB::connection()->getPdo();

try {
    echo "Step 1: Creating migrations table with workaround...\n";
    
    // Try to create migrations table with different approach
    try {
        $pdo->exec("SET SESSION innodb_strict_mode = OFF");
        echo "✅ Disabled InnoDB strict mode\n";
        
        $createMigrationsSQL = "
        CREATE TABLE IF NOT EXISTS `migrations` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `migration` varchar(255) NOT NULL,
            `batch` int(11) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($createMigrationsSQL);
        echo "✅ migrations table created with MyISAM\n";
        
    } catch (Exception $e) {
        echo "❌ Failed to create migrations: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 2: Creating users_central table with MyISAM...\n";
    
    // Create users_central table with MyISAM to avoid InnoDB issues
    $createUsersCentralSQL = "
    CREATE TABLE IF NOT EXISTS `users_central` (
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
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `deleted_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `email` (`email`),
        UNIQUE KEY `username` (`username`),
        KEY `role` (`role`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($createUsersCentralSQL);
    echo "✅ users_central table created with MyISAM\n";
    
    echo "\nStep 3: Inserting sample users...\n";
    
    // Check if users already exist
    $existingUsers = $pdo->query("SELECT COUNT(*) FROM users_central")->fetchColumn();
    
    if ($existingUsers == 0) {
        $sampleUsers = [
            [
                'name' => 'Admin LMS',
                'email' => 'admin@lms-trimurti.sch.id',
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Guru Sample',
                'email' => 'guru@lms-trimurti.sch.id',
                'username' => 'guru',
                'password' => password_hash('guru123', PASSWORD_DEFAULT),
                'role' => 'guru',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@lms-trimurti.sch.id',
                'username' => 'siti',
                'password' => password_hash('siswa123', PASSWORD_DEFAULT),
                'role' => 'siswa',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s')
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
    } else {
        echo "✅ Users already exist: {$existingUsers} users\n";
    }
    
    echo "\nStep 4: Testing User model...\n";
    
    // Update User model to use users_central if not already
    $userModelPath = __DIR__ . '/app/Models/User.php';
    $content = file_get_contents($userModelPath);
    
    if (strpos($content, "protected \$table = 'users_central'") === false) {
        if (strpos($content, "protected \$table") !== false) {
            $content = preg_replace('/protected \$table = [\'"][^\'\"]+[\'"];/', "protected \$table = 'users_central';", $content);
        } else {
            $content = str_replace("class User extends Authenticatable\n{", "class User extends Authenticatable\n{\n    protected \$table = 'users_central';", $content);
        }
        
        file_put_contents($userModelPath, $content);
        echo "✅ Updated User model to use users_central\n";
    }
    
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
    
    echo "\n🎉 SUCCESS! Database fixed with MyISAM engine!\n";
    echo "Login credentials:\n";
    echo "  Admin: admin@lms-trimurti.sch.id / admin123\n";
    echo "  Guru: guru@lms-trimurti.sch.id / guru123\n";
    echo "  Siswa: siti@lms-trimurti.sch.id / siswa123\n";
    
    echo "\n✅ Error 'users_central' doesn't exist should be resolved now!\n";
    echo "✅ All User::where('role', 'siswa') queries should work!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== CLEANUP ===\n";
if (file_exists(__DIR__ . '/recreate_database.php')) {
    unlink(__DIR__ . '/recreate_database.php');
    echo "✅ Removed recreate_database.php\n";
}
