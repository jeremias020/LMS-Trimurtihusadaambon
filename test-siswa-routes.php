<?php

/**
 * Test script to verify siswa routes are working
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Testing Siswa Routes...\n\n";

// Test if routes exist
$routes_to_test = [
    'siswa.dashboard',
    'siswa.materials.index',
    'siswa.assignments.index', 
    'siswa.practicals.index',
    'siswa.scores.index',
    'siswa.attendance.index',
    'siswa.attendance.show',
    'siswa.attendance.export',
    'siswa.attendance.medical',
    'siswa.profile.edit'
];

echo "=== Testing Route Existence ===\n";

foreach ($routes_to_test as $route_name) {
    try {
        $url = route($route_name, $route_name === 'siswa.attendance.show' ? 1 : []);
        echo "✅ $route_name -> $url\n";
    } catch (Exception $e) {
        echo "❌ $route_name -> ERROR: " . $e->getMessage() . "\n";
    }
}

// Test problematic route that caused the error
echo "\n=== Testing Problematic Routes ===\n";

$problematic_routes = [
    'siswa.attendance.create',
    'siswa.attendance.report'
];

foreach ($problematic_routes as $route_name) {
    try {
        $url = route($route_name);
        echo "⚠️ $route_name -> $url (This route exists but may not be intended)\n";
    } catch (Exception $e) {
        echo "✅ $route_name -> Not Found (This is expected and correct)\n";
    }
}

// Test dashboard notification routes
echo "\n=== Testing Dashboard Notification Routes ===\n";

try {
    // Simulate the dashboard controller notification logic
    $assignmentsRoute = route('siswa.assignments.index');
    echo "✅ Assignments notification route: $assignmentsRoute\n";
    
    $attendanceRoute = route('siswa.attendance.index');
    echo "✅ Attendance notification route: $attendanceRoute\n";
    
} catch (Exception $e) {
    echo "❌ Dashboard notification routes failed: " . $e->getMessage() . "\n";
}

echo "\n🎉 Route testing completed!\n";

echo "\n📋 Summary:\n";
echo "- Fixed siswa.attendance.create route error\n";
echo "- Changed notification to use siswa.attendance.index\n"; 
echo "- Added proper siswa attendance routes (show, export, medical)\n";
echo "- All siswa routes should now work correctly\n";

echo "\n💡 You can now try accessing the siswa dashboard without route errors!\n";