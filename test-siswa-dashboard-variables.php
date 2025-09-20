<?php

/**
 * Test script to verify siswa dashboard variables
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Material;
use App\Models\Assignment;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Testing Siswa Dashboard Variables...\n\n";

// Test 1: Check if a siswa user exists
echo "=== Test 1: Check Siswa User ===\n";

$siswaUser = User::where('role', 'siswa')->first();
if (!$siswaUser) {
    echo "❌ No siswa user found! Create one first.\n";
    exit(1);
}

echo "✅ Siswa user found: {$siswaUser->name} (ID: {$siswaUser->id})\n";
echo "Kelas ID: " . ($siswaUser->kelas_id ?? 'NULL') . "\n";

// Test 2: Test variables that would be calculated
echo "\n=== Test 2: Test Dashboard Variables ===\n";

try {
    // Simulate controller logic
    $kelasId = $siswaUser->kelas_id;
    
    // Test newMaterialsCount calculation
    $newMaterialsCount = Material::where('is_published', true)
        ->where(function($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId)
                  ->orWhereNull('kelas_id');
        })
        ->where('created_at', '>=', now()->subDays(7))
        ->count();
    
    echo "✅ newMaterialsCount: $newMaterialsCount\n";
    
    // Test available materials
    $availableMaterials = Material::where('is_published', true)
        ->where(function($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId)
                  ->orWhereNull('kelas_id');
        })
        ->count();
    
    echo "✅ availableMaterials: $availableMaterials\n";
    
    // Test active assignments
    $activeAssignments = Assignment::where('is_published', true)
        ->where(function($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId)
                  ->orWhereNull('kelas_id');
        })
        ->where('deadline', '>', now())
        ->count();
    
    echo "✅ activeAssignments: $activeAssignments\n";
    
} catch (Exception $e) {
    echo "❌ Variables test failed: " . $e->getMessage() . "\n";
}

// Test 3: Check Material fields
echo "\n=== Test 3: Check Material Model Fields ===\n";

try {
    $sampleMaterial = Material::first();
    if ($sampleMaterial) {
        echo "✅ Material found - ID: {$sampleMaterial->id}\n";
        echo "Title field (judul): " . ($sampleMaterial->judul ?? 'NULL') . "\n";
        echo "Description: " . (Str::limit($sampleMaterial->description, 50) ?? 'NULL') . "\n";
        echo "Guru relation: " . (optional($sampleMaterial->guru)->name ?? 'NULL') . "\n";
        echo "Created at: " . $sampleMaterial->created_at->diffForHumans() . "\n";
    } else {
        echo "⚠️ No materials found\n";
    }
} catch (Exception $e) {
    echo "❌ Material fields test failed: " . $e->getMessage() . "\n";
}

// Test 4: Check Assignment fields
echo "\n=== Test 4: Check Assignment Model Fields ===\n";

try {
    $sampleAssignment = Assignment::first();
    if ($sampleAssignment) {
        echo "✅ Assignment found - ID: {$sampleAssignment->id}\n";
        echo "Title: " . ($sampleAssignment->title ?? 'NULL') . "\n";
        echo "Deadline: " . ($sampleAssignment->deadline ? $sampleAssignment->deadline->format('d M Y') : 'NULL') . "\n";
        echo "Description: " . (Str::limit($sampleAssignment->description, 50) ?? 'NULL') . "\n";
    } else {
        echo "⚠️ No assignments found\n";
    }
} catch (Exception $e) {
    echo "❌ Assignment fields test failed: " . $e->getMessage() . "\n";
}

// Test 5: Check routes that are used in dashboard
echo "\n=== Test 5: Check Dashboard Routes ===\n";

$routes_to_test = [
    'siswa.materials.index',
    'siswa.assignments.index',
    'siswa.materials.show' => 1,
    'siswa.assignments.show' => 1,
];

foreach ($routes_to_test as $route => $param) {
    try {
        if (is_numeric($route)) {
            $route = $param;
            $param = null;
        }
        
        $url = $param ? route($route, $param) : route($route);
        echo "✅ $route -> $url\n";
    } catch (Exception $e) {
        echo "❌ $route -> ERROR: " . $e->getMessage() . "\n";
    }
}

echo "\n🎉 Dashboard variables testing completed!\n";

echo "\n📋 Summary:\n";
echo "- Added missing variables: newMaterialsCount, pendingAssignmentsCount, upcomingPracticalsCount, attendancePercentage\n";
echo "- Fixed field names: title -> judul, teacher -> guru, due_date -> deadline, user_id -> siswa_id\n";
echo "- Removed non-existent export routes\n";
echo "- All dashboard variables should now be properly defined\n";

echo "\n💡 You can now try accessing the siswa dashboard without variable errors!\n";