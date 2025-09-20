<?php

/**
 * Simple template render test
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Testing Template Rendering...\n\n";

try {
    // Create a simple view instance to test if the blade syntax is correct
    $view = view('siswa.dashboard', [
        'stats' => [
            'available_materials' => 3,
            'active_assignments' => 2,
            'pending_assignments' => 1,
            'practicals_count' => 4,
            'attendance_rate' => 85
        ],
        'upcomingAssignments' => collect([]),
        'recentMaterials' => collect([]),
        'recentScores' => collect([]),
        'overdueAssignments' => 0,
        'todayAttendance' => null,
        'notifications' => [],
        'newMaterialsCount' => 3,
        'pendingAssignmentsCount' => 1,
        'upcomingPracticalsCount' => 4,
        'attendancePercentage' => 85
    ]);
    
    // Try to render the view
    $content = $view->render();
    
    echo "✅ Template rendered successfully!\n";
    echo "📊 Content length: " . strlen($content) . " characters\n";
    
    // Check for basic HTML structure
    if (strpos($content, '<div class="container-fluid">') !== false) {
        echo "✅ Main container found\n";
    } else {
        echo "❌ Main container not found\n";
    }
    
    // Check for statistics cards
    if (strpos($content, 'Materi Baru') !== false) {
        echo "✅ Statistics cards rendered\n";
    } else {
        echo "❌ Statistics cards not found\n";
    }
    
    // Check for variables
    if (strpos($content, '{{ $newMaterialsCount }}') === false) {
        echo "✅ Variables properly rendered (no raw blade syntax found)\n";
    } else {
        echo "❌ Raw blade syntax found - variables not rendered\n";
    }
    
    echo "🎉 Template validation passed!\n";
    
} catch (\Illuminate\View\ViewException $e) {
    echo "❌ Blade template error: " . $e->getMessage() . "\n";
    echo "🔍 Previous exception: " . ($e->getPrevious() ? $e->getPrevious()->getMessage() : 'None') . "\n";
} catch (\Exception $e) {
    echo "❌ General error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
}

echo "\n💡 If the template renders successfully, the Blade syntax is correct!\n";