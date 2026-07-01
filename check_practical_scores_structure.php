<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 CHECK PRACTICAL_SCORES TABLE STRUCTURE\n";
echo "=====================================\n";

try {
    // Cek kolom di tabel practical_scores
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('practical_scores');
    
    echo "Columns in practical_scores table:\n";
    foreach ($columns as $column) {
        echo "  - {$column}\n";
    }
    
    echo "\n🎯 ANALISIS:\n";
    echo "=====================================\n";
    
    if (in_array('criteria_id', $columns)) {
        echo "✅ criteria_id EXISTS\n";
    } else {
        echo "❌ criteria_id NOT FOUND\n";
        echo "💡 Need to add criteria_id column to practical_scores table\n";
    }
    
    // Cek sample data yang ada
    $existingScores = \App\Models\PracticalScore::limit(3)->get();
    echo "\nExisting practical_scores: {$existingScores->count()}\n";
    
    foreach ($existingScores as $score) {
        echo "  ID: {$score->id}, Practical: {$score->practical_id}, Siswa: {$score->siswa_id}, Score: {$score->score}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
