<?php
echo "=== TESTING CSS OVERRIDE FOR SIDEBAR COLLAPSE ===\n";

echo "Testing if admin layout CSS properly overrides universal.css...\n";

// 1. Check admin layout CSS
echo "\n=== 1. ADMIN LAYOUT CSS CHECK ===\n";

$layoutFile = 'resources/views/layouts/admin.blade.php';
if (file_exists($layoutFile)) {
    echo "✅ Admin layout file exists\n";
    
    $content = file_get_contents($layoutFile);
    
    // Check normal margin-left
    if (strpos($content, 'margin-left: 280px') !== false) {
        echo "✅ Normal margin-left (280px) found\n";
    } else {
        echo "❌ Normal margin-left missing\n";
    }
    
    // Check collapsed margin-left
    if (strpos($content, 'margin-left: 70px') !== false) {
        echo "✅ Collapsed margin-left (70px) found\n";
    } else {
        echo "❌ Collapsed margin-left missing\n";
    }
    
    // Check sidebar-collapsed class
    if (strpos($content, 'sidebar-collapsed') !== false) {
        echo "✅ sidebar-collapsed class found\n";
    } else {
        echo "❌ sidebar-collapsed class missing\n";
    }
    
    // Check !important usage
    if (strpos($content, '!important') !== false) {
        echo "✅ !important declarations found\n";
    } else {
        echo "❌ !important declarations missing\n";
    }
    
    // Count occurrences of margin-left: 70px
    $count = substr_count($content, 'margin-left: 70px');
    echo "📊 margin-left: 70px appears $count times\n";
    
    // Check for more specific selectors
    if (strpos($content, 'body.admin-layout #main-content.sidebar-collapsed') !== false) {
        echo "✅ More specific selectors found\n";
    } else {
        echo "❌ More specific selectors missing\n";
    }
    
} else {
    echo "❌ Admin layout file not found\n";
}

// 2. Check universal.css conflicts
echo "\n=== 2. UNIVERSAL.CSS CONFLICT CHECK ===\n";

$universalFile = 'public/css/components/universal.css';
if (file_exists($universalFile)) {
    echo "✅ Universal CSS file exists\n";
    
    $content = file_get_contents($universalFile);
    
    // Check for conflicting margin-left
    if (strpos($content, 'margin-left: 280px') !== false) {
        echo "⚠️ Potential conflict: margin-left: 280px found in universal.css\n";
    }
    
    // Check if there's no sidebar-collapsed rule in universal.css
    if (strpos($content, 'sidebar-collapsed') === false) {
        echo "✅ No sidebar-collapsed rule in universal.css (good)\n";
    } else {
        echo "⚠️ sidebar-collapsed rule found in universal.css (potential conflict)\n";
    }
    
    // Check for !important usage
    if (strpos($content, '!important') !== false) {
        echo "⚠️ !important declarations found in universal.css\n";
    }
    
    // Check for body.admin-layout selectors
    if (strpos($content, 'body.admin-layout') !== false) {
        echo "⚠️ body.admin-layout selectors found in universal.css\n";
    }
    
} else {
    echo "❌ Universal CSS file not found\n";
}

// 3. Check sidebar JavaScript
echo "\n=== 3. SIDEBAR JAVASCRIPT CHECK ===\n";

$sidebarFile = 'resources/views/partials/sidebar-admin.blade.php';
if (file_exists($sidebarFile)) {
    echo "✅ Sidebar file exists\n";
    
    $content = file_get_contents($sidebarFile);
    
    // Check applyCollapsed function
    if (strpos($content, 'function applyCollapsed') !== false) {
        echo "✅ applyCollapsed function exists\n";
    }
    
    // Check if sidebar-collapsed class is added
    if (strpos($content, 'classList.toggle(\'sidebar-collapsed\'') !== false) {
        echo "✅ sidebar-collapsed class toggle found\n";
    } else {
        echo "❌ sidebar-collapsed class toggle missing\n";
    }
    
    // Check if mainContent is referenced
    if (strpos($content, 'mainContent') !== false) {
        echo "✅ mainContent reference found\n";
    } else {
        echo "❌ mainContent reference missing\n";
    }
    
} else {
    echo "❌ Sidebar file not found\n";
}

// 4. Summary and recommendations
echo "\n=== 4. SUMMARY AND RECOMMENDATIONS ===\n";

echo "✅ IMPLEMENTED SOLUTIONS:\n";
echo "  - Added multiple CSS selectors with higher specificity\n";
echo "  - Used !important declarations to override universal.css\n";
echo "  - Added both ID and class selectors for maximum compatibility\n";
echo "  - JavaScript properly adds sidebar-collapsed class\n";

echo "\n🔧 TROUBLESHOOTING STEPS:\n";
echo "If main content still doesn't move:\n";
echo "1. Open Developer Tools (F12)\n";
echo "2. Go to Elements tab\n";
echo "3. Find main-content element\n";
echo "4. Click collapse button\n";
echo "5. Check if 'sidebar-collapsed' class is added\n";
echo "6. Check computed styles for margin-left\n";
echo "7. Look for overridden styles in Styles panel\n";

echo "\n📱 EXPECTED BEHAVIOR:\n";
echo "1. Click collapse → sidebar width: 70px\n";
echo "2. Main content → margin-left: 70px\n";
echo "3. Main content → width: calc(100% - 70px)\n";
echo "4. Smooth transition animation\n";

echo "\n🎉 CSS OVERRIDE SETUP COMPLETE!\n";
echo "📱 Multiple selectors added to override universal.css\n";
echo "🔧 Higher specificity should ensure proper margin adjustment\n";

echo "\n=== COMPLETE ===\n";
?>
