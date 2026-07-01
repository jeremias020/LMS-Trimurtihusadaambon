<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 COMPREHENSIVE VIEW TEMPLATE CHECK\n";
echo "=====================================\n";

$viewsByRole = [
    'Admin' => 'resources/views/admin',
    'Guru' => 'resources/views/guru',
    'Siswa' => 'resources/views/siswa'
];

$totalViews = 0;
$missingViews = [];

foreach ($viewsByRole as $role => $path) {
    echo "\n📂 {$role} Views:\n";
    echo "-------------------------------------\n";
    
    $fullPath = __DIR__ . '/' . $path;
    if (!is_dir($fullPath)) {
        echo "  ❌ Directory not found: {$path}\n";
        continue;
    }
    
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fullPath));
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $relativePath = str_replace($fullPath . '/', '', $file->getPathname());
            $files[] = $relativePath;
            echo "  ✅ {$relativePath}\n";
            $totalViews++;
        }
    }
    
    if (empty($files)) {
        echo "  ⚠️  No view files found\n";
    }
}

echo "\n\n🔍 CHECKING VIEW EXTENDS AND INCLUDES\n";
echo "=====================================\n";

// Check for common issues in views
$commonIssues = [
    'Missing extends' => [],
    'Missing layouts' => [],
    'Undefined variables' => []
];

foreach ($viewsByRole as $role => $path) {
    $fullPath = __DIR__ . '/' . $path;
    if (!is_dir($fullPath)) continue;
    
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fullPath));
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            $relativePath = str_replace($fullPath . '/', '', $file->getPathname());
            
            // Check for extends
            if (!preg_match('/@extends\(/', $content) && !preg_match('/@extends\s+/', $content)) {
                $commonIssues['Missing extends'][] = "{$role}/{$relativePath}";
            }
            
            // Check for common undefined variables
            $undefinedVars = [];
            if (preg_match_all('/\{\{\s*\$(\w+)\s*\}\}/', $content, $matches)) {
                $vars = array_unique($matches[1]);
                foreach ($vars as $var) {
                    if (!preg_match('/\$' . $var . '\s*=/', $content)) {
                        $undefinedVars[] = $var;
                    }
                }
                if (!empty($undefinedVars)) {
                    $commonIssues['Undefined variables'][] = "{$role}/{$relativePath}: " . implode(', ', $undefinedVars);
                }
            }
        }
    }
}

echo "\n\n⚠️  POTENTIAL ISSUES FOUND:\n";
echo "=====================================\n";

if (empty($commonIssues['Missing extends'])) {
    echo "✅ All views have proper extends\n";
} else {
    echo "❌ Views missing extends:\n";
    foreach ($commonIssues['Missing extends'] as $view) {
        echo "  - {$view}\n";
    }
}

if (empty($commonIssues['Undefined variables'])) {
    echo "✅ No obvious undefined variables\n";
} else {
    echo "❌ Potential undefined variables:\n";
    foreach (array_slice($commonIssues['Undefined variables'], 0, 10) as $view) {
        echo "  - {$view}\n";
    }
    if (count($commonIssues['Undefined variables']) > 10) {
        echo "  ... and " . (count($commonIssues['Undefined variables']) - 10) . " more\n";
    }
}

echo "\n\n📊 SUMMARY:\n";
echo "=====================================\n";
echo "Total Views Found: {$totalViews}\n";
echo "Roles Checked: " . count($viewsByRole) . "\n";
echo "Issues Found: " . (count($commonIssues['Missing extends']) + count($commonIssues['Undefined variables'])) . "\n";

echo "\n✅ View template check complete\n";
?>
