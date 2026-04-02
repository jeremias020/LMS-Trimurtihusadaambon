<?php
echo "=== TESTING SIDEBAR COLLAPSE FUNCTION ===\n";

echo "Testing sidebar collapse functionality...\n";

// 1. Check if sidebar file exists and has correct JavaScript
echo "\n=== 1. JAVASCRIPT FUNCTION CHECK ===\n";

$sidebarFile = 'resources/views/partials/sidebar-admin.blade.php';
if (file_exists($sidebarFile)) {
    echo "✅ Sidebar file exists\n";
    
    $content = file_get_contents($sidebarFile);
    
    // Check if applyCollapsed function exists
    if (strpos($content, 'function applyCollapsed') !== false) {
        echo "✅ applyCollapsed function exists\n";
    } else {
        echo "❌ applyCollapsed function missing\n";
    }
    
    // Check if main-content class is being toggled
    if (strpos($content, 'sidebar-collapsed') !== false) {
        echo "✅ main-content sidebar-collapsed class toggle found\n";
    } else {
        echo "❌ main-content sidebar-collapsed class toggle missing\n";
    }
    
    // Check if sidebar class is being toggled
    if (strpos($content, 'sidebar.classList.toggle(\'collapsed\'') !== false) {
        echo "✅ sidebar collapsed class toggle found\n";
    } else {
        echo "❌ sidebar collapsed class toggle missing\n";
    }
    
    // Check if localStorage is used
    if (strpos($content, 'localStorage.setItem(\'sidebarCollapsed\'') !== false) {
        echo "✅ localStorage state saving found\n";
    } else {
        echo "❌ localStorage state saving missing\n";
    }
    
    // Check if toggle icon changes
    if (strpos($content, 'fa-angle-right') !== false && strpos($content, 'fa-angle-left') !== false) {
        echo "✅ Toggle icon change logic found\n";
    } else {
        echo "❌ Toggle icon change logic missing\n";
    }
    
} else {
    echo "❌ Sidebar file not found\n";
}

// 2. Check if CSS has correct styles
echo "\n=== 2. CSS STYLES CHECK ===\n";

if (file_exists($sidebarFile)) {
    $content = file_get_contents($sidebarFile);
    
    // Check sidebar collapsed styles
    if (strpos($content, '.sidebar.collapsed {') !== false) {
        echo "✅ Sidebar collapsed styles found\n";
        
        // Check if width is set correctly
        if (strpos($content, 'width: 70px') !== false) {
            echo "✅ Sidebar collapsed width set to 70px\n";
        } else {
            echo "❌ Sidebar collapsed width not set correctly\n";
        }
    } else {
        echo "❌ Sidebar collapsed styles missing\n";
    }
    
    // Check if nav elements are hidden when collapsed
    if (strpos($content, '.sidebar.collapsed .nav-text') !== false) {
        echo "✅ Nav text hide styles found\n";
    } else {
        echo "❌ Nav text hide styles missing\n";
    }
}

// 3. Check if admin layout has correct main content styles
echo "\n=== 3. ADMIN LAYOUT STYLES CHECK ===\n";

$layoutFile = 'resources/views/layouts/admin.blade.php';
if (file_exists($layoutFile)) {
    echo "✅ Admin layout file exists\n";
    
    $content = file_get_contents($layoutFile);
    
    // Check if main-content has margin-left for normal sidebar
    if (strpos($content, 'margin-left: 280px') !== false) {
        echo "✅ Main content normal margin-left (280px) found\n";
    } else {
        echo "❌ Main content normal margin-left missing\n";
    }
    
    // Check if main-content has margin-left for collapsed sidebar
    if (strpos($content, 'margin-left: 70px') !== false) {
        echo "✅ Main content collapsed margin-left (70px) found\n";
    } else {
        echo "❌ Main content collapsed margin-left missing\n";
    }
    
    // Check if sidebar-collapsed class exists in CSS
    if (strpos($content, '.sidebar-collapsed') !== false) {
        echo "✅ sidebar-collapsed class styles found\n";
    } else {
        echo "❌ sidebar-collapsed class styles missing\n";
    }
    
} else {
    echo "❌ Admin layout file not found\n";
}

// 4. Check if toggle buttons exist
echo "\n=== 4. TOGGLE BUTTONS CHECK ===\n";

if (file_exists($sidebarFile)) {
    $content = file_get_contents($sidebarFile);
    
    // Check if sidebar-toggle class exists
    if (strpos($content, 'sidebar-toggle') !== false) {
        echo "✅ Sidebar toggle button found\n";
    } else {
        echo "❌ Sidebar toggle button missing\n";
    }
    
    // Check if headerToggle is referenced
    if (strpos($content, 'headerToggle') !== false) {
        echo "❌ headerToggle reference found (should be removed)\n";
    } else {
        echo "✅ No headerToggle reference (good)\n";
    }
    
    // Check if footer toggles are referenced
    if (strpos($content, 'footerToggles') !== false) {
        echo "✅ Footer toggles reference found\n";
    } else {
        echo "❌ Footer toggles reference missing\n";
    }
}

// 5. Summary
echo "\n=== 5. TROUBLESHOOTING SUMMARY ===\n";

echo "Potential issues to check:\n";
echo "1. JavaScript console errors - check browser console\n";
echo "2. CSS conflicts - check if other CSS overrides styles\n";
echo "3. Element IDs - ensure main-content ID exists\n";
echo "4. Event listeners - ensure click events are bound\n";
echo "5. Browser cache - try hard refresh (Ctrl+F5)\n";

echo "\n=== SOLUTIONS TO TRY ===\n";

echo "If collapse still doesn't work:\n";
echo "1. Open browser developer tools (F12)\n";
echo "2. Check Console tab for JavaScript errors\n";
echo "3. Check Elements tab to see if classes are applied\n";
echo "4. Try clicking the toggle button and watch for class changes\n";
echo "5. Clear browser cache and cookies\n";

echo "\n🎉 SIDEBAR COLLAPSE TROUBLESHOOTING COMPLETE!\n";
echo "📱 All necessary code should now be in place\n";
echo "🔧 If still not working, check browser console for errors\n";

echo "\n=== COMPLETE ===\n";
?>
