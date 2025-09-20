<?php

/**
 * Simple blade template validation script
 */

echo "🔍 Validating Blade Template Structure...\n\n";

// Read the dashboard file
$filePath = __DIR__ . '/resources/views/siswa/dashboard.blade.php';
if (!file_exists($filePath)) {
    echo "❌ File not found: $filePath\n";
    exit(1);
}

$content = file_get_contents($filePath);
echo "✅ Dashboard file read successfully\n";

// Check for blade directive balance
$sections = [];
$pushes = [];
$lines = explode("\n", $content);

echo "\n=== Analyzing Blade Directives ===\n";

foreach ($lines as $lineNum => $line) {
    $line = trim($line);
    
    // Check for @section
    if (preg_match('/@section\s*\(\s*[\'"]([^\'"]*)[\'"].*\)/', $line, $matches)) {
        $sections[] = ['type' => 'start', 'name' => $matches[1], 'line' => $lineNum + 1];
        echo "🔵 Line " . ($lineNum + 1) . ": @section('{$matches[1]}')\n";
    }
    
    // Check for @endsection
    if (preg_match('/@endsection/', $line)) {
        $sections[] = ['type' => 'end', 'line' => $lineNum + 1];
        echo "🔴 Line " . ($lineNum + 1) . ": @endsection\n";
    }
    
    // Check for @push
    if (preg_match('/@push\s*\(\s*[\'"]([^\'"]*)[\'"].*\)/', $line, $matches)) {
        $pushes[] = ['type' => 'start', 'name' => $matches[1], 'line' => $lineNum + 1];
        echo "🟢 Line " . ($lineNum + 1) . ": @push('{$matches[1]}')\n";
    }
    
    // Check for @endpush
    if (preg_match('/@endpush/', $line)) {
        $pushes[] = ['type' => 'end', 'line' => $lineNum + 1];
        echo "🟡 Line " . ($lineNum + 1) . ": @endpush\n";
    }
}

echo "\n=== Validation Results ===\n";

// Validate sections
$sectionStack = [];
$sectionErrors = [];

foreach ($sections as $section) {
    if ($section['type'] === 'start') {
        $sectionStack[] = $section;
    } else { // end
        if (empty($sectionStack)) {
            $sectionErrors[] = "Line {$section['line']}: @endsection without matching @section";
        } else {
            array_pop($sectionStack);
        }
    }
}

if (!empty($sectionStack)) {
    foreach ($sectionStack as $unclosed) {
        $sectionErrors[] = "Line {$unclosed['line']}: @section('{$unclosed['name']}') never closed";
    }
}

// Validate pushes
$pushStack = [];
$pushErrors = [];

foreach ($pushes as $push) {
    if ($push['type'] === 'start') {
        $pushStack[] = $push;
    } else { // end
        if (empty($pushStack)) {
            $pushErrors[] = "Line {$push['line']}: @endpush without matching @push";
        } else {
            array_pop($pushStack);
        }
    }
}

if (!empty($pushStack)) {
    foreach ($pushStack as $unclosed) {
        $pushErrors[] = "Line {$unclosed['line']}: @push('{$unclosed['name']}') never closed";
    }
}

// Report results
if (empty($sectionErrors) && empty($pushErrors)) {
    echo "✅ All blade directives are properly balanced!\n";
    echo "📊 Found " . count(array_filter($sections, fn($s) => $s['type'] === 'start')) . " @section(s)\n";
    echo "📊 Found " . count(array_filter($pushes, fn($p) => $p['type'] === 'start')) . " @push(es)\n";
} else {
    echo "❌ Blade directive errors found:\n";
    foreach (array_merge($sectionErrors, $pushErrors) as $error) {
        echo "  - $error\n";
    }
}

// Additional checks
echo "\n=== Additional Checks ===\n";

// Check for common issues
if (strpos($content, '@endsection@endsection') !== false) {
    echo "❌ Found duplicate @endsection\n";
} else {
    echo "✅ No duplicate @endsection found\n";
}

if (strpos($content, '@endpush@endpush') !== false) {
    echo "❌ Found duplicate @endpush\n";
} else {
    echo "✅ No duplicate @endpush found\n";
}

// Count total directives
$totalSections = substr_count($content, '@section');
$totalEndsections = substr_count($content, '@endsection');
$totalPushes = substr_count($content, '@push');
$totalEndpushes = substr_count($content, '@endpush');

echo "📈 Summary:\n";
echo "  - @section: $totalSections\n";
echo "  - @endsection: $totalEndsections\n";
echo "  - @push: $totalPushes\n";
echo "  - @endpush: $totalEndpushes\n";

if ($totalSections === $totalEndsections && $totalPushes === $totalEndpushes) {
    echo "✅ All directive counts match!\n";
} else {
    echo "❌ Directive count mismatch detected!\n";
}

echo "\n🎉 Blade template validation completed!\n";