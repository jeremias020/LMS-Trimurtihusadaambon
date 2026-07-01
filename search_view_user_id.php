<?php
require_once __DIR__ . '/vendor/autoload.php';

echo "🔍 SEARCHING ALL USER_ID REFERENCES IN VIEW FILES\n";
echo "=====================================\n";

$viewDir = __DIR__ . '/resources/views/siswa';
$foundIssues = [];

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $filename = str_replace(__DIR__, '', $file->getPathname());
        
        // Look for user_id references
        if (preg_match("/user_id/", $content)) {
            $lines = explode("\n", $content);
            foreach ($lines as $lineNum => $line) {
                if (preg_match("/user_id/", $line)) {
                    $foundIssues[] = [
                        'file' => $filename,
                        'line' => $lineNum + 1,
                        'content' => trim($line)
                    ];
                }
            }
        }
    }
}

if (empty($foundIssues)) {
    echo "✅ No more user_id references found in view files\n";
} else {
    echo "❌ Found user_id references in view files:\n";
    foreach ($foundIssues as $issue) {
        echo "  {$issue['file']}:{$issue['line']}\n";
        echo "    {$issue['content']}\n\n";
    }
}

echo "\n✨ SEARCH COMPLETE! ✨\n";
?>
