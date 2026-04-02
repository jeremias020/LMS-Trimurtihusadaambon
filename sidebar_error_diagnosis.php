<?php
echo "=== SIDEBAR ERROR DIAGNOSIS ===\n";

echo "Analyzing sidebar HTML for potential errors...\n";

// 1. Check current sidebar file content
echo "\n=== 1. CURRENT SIDEBAR FILE ANALYSIS ===\n";

$sidebarFile = 'resources/views/partials/sidebar-admin.blade.php';
if (file_exists($sidebarFile)) {
    echo "✅ Sidebar file exists\n";
    
    $content = file_get_contents($sidebarFile);
    
    // Check for the problematic HTML you mentioned
    echo "🔍 CHECKING FOR SPECIFIC ISSUES:\n";
    
    // Check for fa-arrow-right (invalid class)
    if (strpos($content, 'fa-arrow-right') !== false) {
        echo "❌ ISSUE: fa-arrow-right class found (invalid)\n";
        echo "📍 Location: " . (strpos($content, 'fa-arrow-right') + 1) . "\n";
    } else {
        echo "✅ No fa-arrow-right class found\n";
    }
    
    // Check for data-bs-original-title (should be title)
    if (strpos($content, 'data-bs-original-title') !== false) {
        echo "❌ ISSUE: data-bs-original-title found (should be title)\n";
        echo "📍 Location: " . (strpos($content, 'data-bs-original-title') + 1) . "\n";
    } else {
        echo "✅ No data-bs-original-title found\n";
    }
    
    // Check for aria-label (might be causing issues)
    if (strpos($content, 'aria-label="Toggle Sidebar"') !== false) {
        echo "❌ ISSUE: aria-label found (might conflict)\n";
        echo "📍 Location: " . (strpos($content, 'aria-label="Toggle Sidebar"') + 1) . "\n";
    } else {
        echo "✅ No problematic aria-label found\n";
    }
    
    // Check for fa-angle-right in normal state (should be fa-angle-left)
    $faAngleRightCount = substr_count($content, 'fa-angle-right');
    if ($faAngleRightCount > 1) { // More than just the JavaScript update
        echo "❌ ISSUE: Multiple fa-angle-right found\n";
        echo "📊 Count: $faAngleRightCount\n";
    } else {
        echo "✅ Correct fa-angle-right usage\n";
    }
    
    // Check for correct toggle button structure
    if (strpos($content, '<button class="btn btn-link text-light p-0 sidebar-toggle" title="Toggle Sidebar">') !== false) {
        echo "✅ Toggle button structure correct\n";
    } else {
        echo "❌ Toggle button structure incorrect\n";
    }
    
    // Check for correct icon
    if (strpos($content, '<i class="fas fa-angle-left"></i>') !== false) {
        echo "✅ Default icon correct (fa-angle-left)\n";
    } else {
        echo "❌ Default icon incorrect\n";
    }
    
} else {
    echo "❌ Sidebar file not found\n";
}

// 2. Check for potential rendering issues
echo "\n=== 2. POTENTIAL RENDERING ISSUES ===\n";

if (file_exists($sidebarFile)) {
    $content = file_get_contents($sidebarFile);
    
    echo "🔍 CHECKING FOR RENDERING PROBLEMS:\n";
    
    // Check for unclosed tags
    $openNav = substr_count($content, '<nav');
    $closeNav = substr_count($content, '</nav>');
    if ($openNav !== $closeNav) {
        echo "❌ ISSUE: Unclosed nav tags (open: $openNav, close: $closeNav)\n";
    } else {
        echo "✅ Nav tags balanced\n";
    }
    
    // Check for unclosed divs
    $openDiv = substr_count($content, '<div');
    $closeDiv = substr_count($content, '</div>');
    if ($openDiv !== $closeDiv) {
        echo "❌ ISSUE: Unclosed div tags (open: $openDiv, close: $closeDiv)\n";
    } else {
        echo "✅ Div tags balanced\n";
    }
    
    // Check for unclosed buttons
    $openButton = substr_count($content, '<button');
    $closeButton = substr_count($content, '</button>');
    if ($openButton !== $closeButton) {
        echo "❌ ISSUE: Unclosed button tags (open: $openButton, close: $closeButton)\n";
    } else {
        echo "✅ Button tags balanced\n";
    }
    
    // Check for unclosed anchors
    $openA = substr_count($content, '<a');
    $closeA = substr_count($content, '</a>');
    if ($openA !== $closeA) {
        echo "❌ ISSUE: Unclosed anchor tags (open: $openA, close: $closeA)\n";
    } else {
        echo "✅ Anchor tags balanced\n";
    }
    
    // Check for malformed attributes
    if (strpos($content, '  ') !== false) {
        echo "⚠️ WARNING: Double spaces in attributes\n";
    }
    
    // Check for missing quotes
    if (preg_match('/\w+=\w+/', $content)) {
        echo "❌ ISSUE: Unquoted attribute values\n";
    } else {
        echo "✅ All attributes properly quoted\n";
    }
    
} else {
    echo "❌ Sidebar file not found\n";
}

// 3. Check JavaScript for icon update logic
echo "\n=== 3. JAVASCRIPT ICON UPDATE ANALYSIS ===\n";

if (file_exists($sidebarFile)) {
    $content = file_get_contents($sidebarFile);
    
    echo "🔍 CHECKING ICON UPDATE LOGIC:\n";
    
    // Check for icon update logic
    if (strpos($content, 'toggleIcon.className = collapsed ? \'fas fa-angle-right\' : \'fas fa-angle-left\'') !== false) {
        echo "✅ Icon update logic correct\n";
    } else {
        echo "❌ Icon update logic incorrect\n";
    }
    
    // Check for icon selector
    if (strpos($content, 'document.querySelector(\'.sidebar-toggle i\')') !== false) {
        echo "✅ Icon selector correct\n";
    } else {
        echo "❌ Icon selector incorrect\n";
    }
    
    // Check for null check
    if (strpos($content, 'if (toggleIcon)') !== false) {
        echo "✅ Icon null check present\n";
    } else {
        echo "❌ Icon null check missing\n";
    }
    
} else {
    echo "❌ Sidebar file not found\n";
}

// 4. Recommendations
echo "\n=== 4. RECOMMENDATIONS ===\n";

echo "🔧 IF ERRORS PERSIST:\n";
echo "1. Clear browser cache (Ctrl+F5)\n";
echo "2. Check browser console for JavaScript errors\n";
echo "3. Verify FontAwesome is loaded correctly\n";
echo "4. Check for CSS conflicts in browser inspector\n";
echo "5. Test toggle functionality in incognito mode\n";

echo "\n🎯 EXPECTED BEHAVIOR:\n";
echo "• Normal state: fa-angle-left icon\n";
echo "• Collapsed state: fa-angle-right icon\n";
echo "• Toggle button: title='Toggle Sidebar'\n";
echo "• No aria-label or data-bs-original-title\n";

echo "\n🎉 ERROR DIAGNOSIS COMPLETE!\n";

echo "\n=== COMPLETE ===\n";
?>
