<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;

echo "=== USER LOGIN TEST ===\n\n";

try {
    // Test admin user
    $admin = User::where('email', 'admin@lms-trimurti.sch.id')->first();
    
    if ($admin) {
        echo "✅ Admin User Found:\n";
        echo "  Name: {$admin->name}\n";
        echo "  Email: {$admin->email}\n";
        echo "  Role: {$admin->role}\n";
        echo "  Active: " . ($admin->is_active ? 'Yes' : 'No') . "\n";
        echo "  Deleted At: " . ($admin->deleted_at ? $admin->deleted_at : 'NULL') . "\n";
    } else {
        echo "❌ Admin user not found!\n";
    }
    
    echo "\n" . str_repeat("-", 40) . "\n\n";
    
    // Test guru user
    $guru = User::where('email', 'siti@lms-trimurti.sch.id')->first();
    
    if ($guru) {
        echo "✅ Guru User Found:\n";
        echo "  Name: {$guru->name}\n";
        echo "  Email: {$guru->email}\n";
        echo "  Role: {$guru->role}\n";
        echo "  NIP: {$guru->nis_nip}\n";
    } else {
        echo "❌ Guru user not found!\n";
    }
    
    echo "\n" . str_repeat("-", 40) . "\n\n";
    
    // Test siswa user
    $siswa = User::where('email', 'agus.setiawan@lms-trimurti.sch.id')->first();
    
    if ($siswa) {
        echo "✅ Siswa User Found:\n";
        echo "  Name: {$siswa->name}\n";
        echo "  Email: {$siswa->email}\n";
        echo "  Role: {$siswa->role}\n";
        echo "  NIS: {$siswa->nis_nip}\n";
    } else {
        echo "❌ Siswa user not found!\n";
    }
    
    echo "\n=== TEST COMPLETED ===\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
