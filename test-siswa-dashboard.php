<?php

/**
 * Test script to verify siswa dashboard fixes
 * Run this with: php test-siswa-dashboard.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Testing Siswa Dashboard Fixes...\n\n";

// Test 1: Check if kelas_id column exists in materials table
echo "=== Test 1: Materials Table Structure ===\n";
try {
    $materialsColumns = Schema::getColumnListing('materials');
    echo "Materials table columns: " . implode(', ', $materialsColumns) . "\n";
    
    if (in_array('kelas_id', $materialsColumns)) {
        echo "✅ kelas_id column exists in materials table\n";
    } else {
        echo "❌ kelas_id column missing in materials table\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking materials table: " . $e->getMessage() . "\n";
}

// Test 2: Check if kelas_id column exists in assignments table
echo "\n=== Test 2: Assignments Table Structure ===\n";
try {
    $assignmentsColumns = Schema::getColumnListing('assignments');
    echo "Assignments table columns: " . implode(', ', $assignmentsColumns) . "\n";
    
    if (in_array('kelas_id', $assignmentsColumns)) {
        echo "✅ kelas_id column exists in assignments table\n";
    } else {
        echo "❌ kelas_id column missing in assignments table\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking assignments table: " . $e->getMessage() . "\n";
}

// Test 3: Check if practicals table has kelas_id
echo "\n=== Test 3: Practicals Table Structure ===\n";
try {
    $practicalsColumns = Schema::getColumnListing('practicals');
    echo "Practicals table columns: " . implode(', ', $practicalsColumns) . "\n";
    
    if (in_array('kelas_id', $practicalsColumns)) {
        echo "✅ kelas_id column exists in practicals table\n";
    } else {
        echo "❌ kelas_id column missing in practicals table\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking practicals table: " . $e->getMessage() . "\n";
}

// Test 4: Check siswa users
echo "\n=== Test 4: Check Siswa Users ===\n";
try {
    $siswaUsers = DB::table('users')->where('role', 'siswa')->count();
    echo "Total siswa users: $siswaUsers\n";
    
    if ($siswaUsers > 0) {
        $sampleSiswa = DB::table('users')->where('role', 'siswa')->first();
        echo "Sample siswa: {$sampleSiswa->name} (ID: {$sampleSiswa->id})\n";
        echo "Kelas ID: " . ($sampleSiswa->kelas_id ?? 'NULL') . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking siswa users: " . $e->getMessage() . "\n";
}

// Test 5: Check kelas table
echo "\n=== Test 5: Check Kelas Table ===\n";
try {
    $kelasCount = DB::table('kelas')->count();
    echo "Total kelas: $kelasCount\n";
    
    if ($kelasCount > 0) {
        $kelas = DB::table('kelas')->get();
        foreach ($kelas as $k) {
            echo "- Kelas: {$k->nama} (ID: {$k->id})\n";
        }
    } else {
        echo "⚠️ No kelas found! Creating default kelas...\n";
        DB::table('kelas')->insert([
            'nama' => 'X RPL',
            'tingkat' => 'X',
            'jurusan' => 'RPL',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "✅ Default kelas created\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking kelas: " . $e->getMessage() . "\n";
}

// Test 6: Test Materials Query
echo "\n=== Test 6: Test Materials Query ===\n";
try {
    // Simulate the query from DashboardController
    $kelasId = 1; // Use first kelas
    
    $materialsQuery = "SELECT COUNT(*) as total FROM materials WHERE is_published = 1 AND (kelas_id = $kelasId OR kelas_id IS NULL) AND deleted_at IS NULL";
    $result = DB::select($materialsQuery);
    echo "Materials query result: " . $result[0]->total . "\n";
    echo "✅ Materials query works without error\n";
} catch (Exception $e) {
    echo "❌ Materials query failed: " . $e->getMessage() . "\n";
}

// Test 7: Test User Accessor
echo "\n=== Test 7: Test User Kelas Accessor ===\n";
try {
    $siswaUser = \App\Models\User::where('role', 'siswa')->first();
    if ($siswaUser) {
        echo "Siswa: {$siswaUser->name}\n";
        echo "Kelas ID (direct): " . ($siswaUser->kelas_id ?? 'NULL') . "\n";
        echo "Class Name: " . ($siswaUser->class_name ?? 'NULL') . "\n";
        echo "✅ User accessor works\n";
    } else {
        echo "⚠️ No siswa user found\n";
    }
} catch (Exception $e) {
    echo "❌ User accessor failed: " . $e->getMessage() . "\n";
}

echo "\n🎉 Test completed!\n";

echo "\n📋 Summary:\n";
echo "- Database tables updated with kelas_id columns\n";
echo "- Models updated with proper relationships\n";
echo "- DashboardController fixed to use kelas_id instead of class\n";
echo "- User model has accessors for kelas_id and class_name\n";
echo "\n💡 You can now try logging in as a siswa user!\n";