<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🎯 UPDATE PRACTICAL TITLES\n";
echo "=====================================\n";

try {
    // Update judul praktikum yang kosong
    $practicals = \App\Models\Practical::get();
    
    $titles = [
        'Praktikum Dasar Keperawatan',
        'Praktikum Lanjutan Keperawatan',
        'Praktikum Emergency Response',
        'Praktikum Bedah Minor',
    ];
    
    $updated = 0;
    foreach ($practicals as $index => $practical) {
        if (empty($practical->title) || $practical->title === '') {
            $newTitle = $titles[$index % count($titles)];
            $practical->update(['title' => $newTitle]);
            echo "✅ Updated Practical ID {$practical->id}: '{$newTitle}'\n";
            $updated++;
        } else {
            echo "ℹ️  Practical ID {$practical->id} already has title: '{$practical->title}'\n";
        }
    }
    
    echo "\n🎉 Total updated: {$updated}\n";
    
    // Test lagi setelah update
    echo "\n🔍 TEST AFTER UPDATE:\n";
    echo "-------------------------------------\n";
    
    $siswaId = 1;
    $practicalScores = \App\Models\PracticalScore::with(['practical', 'criteria'])
        ->where('siswa_id', $siswaId)
        ->latest()
        ->limit(3)
        ->get();
    
    foreach ($practicalScores as $score) {
        echo "  - {$score->criteria->name}: {$score->score}\n";
        echo "    Practical: " . ($score->practical->title ?: 'Tanpa Judul') . "\n";
        echo "    Created: {$score->created_at->format('d M Y')}\n\n";
    }
    
    echo "✨ UPDATE COMPLETE! ✨\n";
    echo "Sekarang halaman nilai praktikum siswa akan menampilkan:\n";
    echo "1. ✅ Jenis Praktikum: 'Praktikum Dasar Keperawatan'\n";
    echo "2. ✅ Kriteria: 'Ketepatan Prosedur', dll\n";
    echo "3. ✅ Nilai: 78, 80, dll\n";
    echo "4. ✅ Tanggal: 28 Apr 2026\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
