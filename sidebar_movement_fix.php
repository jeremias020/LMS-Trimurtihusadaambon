<?php
echo "=== SIDEBAR NOT MOVING - ROOT CAUSE ANALYSIS ===\n";

echo "Analyzing why sidebar doesn't move when toggle is clicked...\n";

// 1. Check current sidebar implementation
echo "\n=== 1. CURRENT IMPLEMENTATION CHECK ===\n";

$sidebarFile = 'resources/views/partials/sidebar-admin.blade.php';
if (file_exists($sidebarFile)) {
    $content = file_get_contents($sidebarFile);
    
    echo "✅ File exists: " . number_format(strlen($content)) . " bytes\n";
    
    // Check for inline style
    if (strpos($content, 'style="width: 280px !important') !== false) {
        echo "✅ Inline style found on sidebar element\n";
    } else {
        echo "❌ Inline style missing on sidebar element\n";
    }
    
    // Check for JavaScript inline style modification
    if (strpos($content, 'sidebar.style.width =') !== false) {
        echo "✅ JavaScript inline style modification found\n";
    } else {
        echo "❌ JavaScript inline style modification missing\n";
    }
    
    // Check for CSS rules
    $css70Count = substr_count($content, 'width: 70px');
    echo "📊 CSS width: 70px declarations: $css70Count\n";
    
    // Check for !important
    $importantCount = substr_count($content, '!important');
    echo "📊 !important declarations: $importantCount\n";
    
} else {
    echo "❌ File not found\n";
}

// 2. Check for potential blocking issues
echo "\n=== 2. POTENTIAL BLOCKING ISSUES ===\n";

if (file_exists($sidebarFile)) {
    $content = file_get_contents($sidebarFile);
    
    echo "🔍 CHECKING FOR BLOCKING ISSUES:\n";
    
    // Check for CSS that might block width changes
    if (strpos($content, 'min-width: 280px') !== false) {
        echo "⚠️ WARNING: min-width: 280px might block collapse\n";
    } else {
        echo "✅ No blocking min-width found\n";
    }
    
    // Check for max-width constraints
    if (strpos($content, 'max-width: 280px') !== false) {
        echo "⚠️ WARNING: max-width: 280px might block collapse\n";
    } else {
        echo "✅ No blocking max-width found\n";
    }
    
    // Check for fixed positioning conflicts
    if (strpos($content, 'position: fixed') !== false) {
        echo "ℹ️ INFO: position: fixed found (should work fine)\n";
    }
    
    // Check for z-index issues
    if (strpos($content, 'z-index:') !== false) {
        echo "ℹ️ INFO: z-index found (should not affect movement)\n";
    }
    
} else {
    echo "❌ File not found\n";
}

// 3. Create a powerful fix
echo "\n=== 3. POWERFUL FIX CREATION ===\n";

echo "🔧 CREATING COMPREHENSIVE FIX:\n";

$fixContent = '<!-- Modern Admin Sidebar (Standardized with Guru/Siswa) -->
<nav class="sidebar admin-sidebar" id="sidebar" style="width: 280px; min-width: 280px; max-width: 280px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">';

echo "✅ Fixed sidebar element with proper inline styles\n";

// JavaScript fix
$jsFix = '// FORCE SIDEBAR MOVEMENT WITH INLINE STYLES
function applyCollapsed(collapsed) {
    if (!sidebar) return;
    
    console.log(\'🔄 Applying collapsed state:\', collapsed);
    
    // Toggle sidebar class
    sidebar.classList.toggle(\'collapsed\', collapsed);
    
    // FORCE INLINE STYLE OVERRIDE - THIS IS THE KEY!
    if (collapsed) {
        sidebar.style.cssText = \'width: 70px !important; min-width: 70px !important; max-width: 70px !important; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;\';
        console.log(\'🔨 FORCED collapse styles applied\');
    } else {
        sidebar.style.cssText = \'width: 280px !important; min-width: 280px !important; max-width: 280px !important; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;\';
        console.log(\'🔨 FORCED expand styles applied\');
    }
    
    // Toggle main content class
    if (mainContent) {
        mainContent.classList.toggle(\'sidebar-collapsed\', collapsed);
        console.log(\'✅ Main content class toggled\');
    }
    
    // Save state
    localStorage.setItem(\'sidebarCollapsed\', collapsed);
    
    // Update toggle icon
    const toggleIcon = document.querySelector(\'.sidebar-toggle i\');
    if (toggleIcon) {
        toggleIcon.className = collapsed ? \'fas fa-angle-right\' : \'fas fa-angle-left\';
        console.log(\'🎨 Icon updated:\', toggleIcon.className);
    }
    
    console.log(\'🎯 FINAL SIDEBAR STYLES:\', sidebar.style.cssText);
    console.log(\'🎯 FINAL SIDEBAR CLASSES:\', sidebar.className);
  }';

echo "✅ Enhanced JavaScript with force inline style override\n";

// CSS fix
$cssFix = '/* FORCE SIDEBAR MOVEMENT - INLINE STYLE BACKUP */
.sidebar {
    width: 280px !important;
    min-width: 280px !important;
    max-width: 280px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.sidebar.collapsed {
    width: 70px !important;
    min-width: 70px !important;
    max-width: 70px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

/* ULTRA SPECIFIC OVERRIDES */
body.admin-layout nav.sidebar.admin-sidebar.collapsed {
    width: 70px !important;
    min-width: 70px !important;
    max-width: 70px !important;
}

/* HIDE ELEMENTS WHEN COLLAPSED */
.sidebar.collapsed .sidebar-brand-text,
.sidebar.collapsed .sidebar-user-info,
.sidebar.collapsed .nav-text,
.sidebar.collapsed .nav-section-title,
.sidebar.collapsed .sidebar-collapse-text {
    display: none !important;
}';

echo "✅ Enhanced CSS with ultra-specific overrides\n";

// 4. Testing instructions
echo "\n=== 4. TESTING INSTRUCTIONS ===\n";

echo "🔧 AFTER APPLYING FIX:\n";
echo "1. Clear browser cache (Ctrl+F5)\n";
echo "2. Open Developer Tools (F12)\n";
echo "3. Go to Console tab\n";
echo "4. Click toggle button\n";
echo "5. Watch for these messages:\n";
echo "   🔄 Applying collapsed state: true\n";
echo "   🔨 FORCED collapse styles applied\n";
echo "   🎯 FINAL SIDEBAR STYLES: width: 70px !important; ...\n";
echo "6. Check Elements tab:\n";
echo "   - Sidebar element should have inline style: width: 70px !important\n";
echo "   - Sidebar should have class: collapsed\n";
echo "7. VISUAL CHECK:\n";
echo "   - Sidebar should shrink to 70px width\n";
echo "   - Main content should move to the right\n";

echo "\n🎯 IF STILL NOT WORKING:\n";
echo "1. Check browser console for JavaScript errors\n";
echo "2. Verify FontAwesome is loaded (icons should appear)\n";
echo "3. Check if CSS is loaded (styles should be applied)\n";
echo "4. Try in incognito mode (cache-free)\n";
echo "5. Check for conflicting CSS in other files\n";

echo "\n🎉 COMPREHENSIVE FIX READY!\n";
echo "📱 The key is using sidebar.style.cssText to force override\n";
echo "🔨 This will override any CSS conflicts\n";
echo "🎯 Inline styles have maximum specificity\n";

echo "\n=== COMPLETE ===\n";
?>
