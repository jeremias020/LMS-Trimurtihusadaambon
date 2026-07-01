<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🎯 CREATE SAMPLE PRACTICAL SCORES\n";
echo "=====================================\n";

try {
    // Get data yang dibutuhkan
    $siswa = \App\Models\Student::first();
    $practical = \App\Models\Practical::first();
    $criteria = \App\Models\Criteria::first();
    
    if (!$siswa || !$practical || !$criteria) {
        echo "❌ Data tidak lengkap:\n";
        echo "  Siswa: " . ($siswa ? 'ADA' : 'TIDAK ADA') . "\n";
        echo "  Practical: " . ($practical ? 'ADA' : 'TIDAK ADA') . "\n";
        echo "  Criteria: " . ($criteria ? 'ADA' : 'TIDAK ADA') . "\n";
        return;
    }
    
    echo "Data yang akan digunakan:\n";
    echo "  Siswa: {$siswa->name} (ID: {$siswa->id})\n";
    echo "  Practical: " . ($practical->judul ?: 'Tanpa Judul') . " (ID: {$practical->id})\n";
    echo "  Criteria: {$criteria->name} (ID: {$criteria->id})\n\n";
    
    // Hapus data lama untuk siswa ini
    $deletedCount = \App\Models\PracticalScore::where('siswa_id', $siswa->id)->delete();
    echo "Deleted old scores: {$deletedCount}\n\n";
    
    // Buat sample scores untuk beberapa kriteria
    $allCriteria = \App\Models\Criteria::active()->get();
    $createdScores = 0;
    
    foreach ($allCriteria as $criterion) {
        $score = \App\Models\PracticalScore::create([
            'practical_id' => $practical->id,
            'siswa_id' => $siswa->id,
            'criteria_id' => $criterion->id,
            'score' => rand(75, 95), // Random score 75-95
            'feedback' => "Bagus! {$criterion->name} sudah dilakukan dengan baik.",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $createdScores++;
        echo "✅ Created score for: {$criterion->name} (Score: {$score->score})\n";
    }
    
    echo "\n🎉 Total scores created: {$createdScores}\n";
    
    // Test query controller
    echo "\n🔍 Testing Controller Query:\n";
    echo "-------------------------------------\n";
    
    $controllerScores = \App\Models\PracticalScore::with(['practical', 'criteria'])
        ->where('siswa_id', $siswa->id)
        ->when($siswa->kelas_id, function($q) use ($siswa) {
            $q->whereHas('practical', function($sub) use ($siswa) {
                $sub->where('kelas_id', $siswa->kelas_id);
            });
        })
        ->latest()
        ->get();
    
    echo "Controller query result: {$controllerScores->count()} records\n";
    
    foreach ($controllerScores as $score) {
        echo "  - {$score->criteria->name}: {$score->score}\n";
        echo "    Practical: " . ($score->practical->judul ?: 'Tanpa Judul') . "\n";
        echo "    Feedback: {$score->feedback}\n\n";
    }
    
    echo "✨ SAMPLE DATA CREATION COMPLETE! ✨\n";
    echo "Sekarang halaman nilai praktikum siswa seharusnya menampilkan:\n";
    echo "1. ✅ Jenis Praktikum (judul practical)\n";
    echo "2. ✅ Kriteria (nama criteria)\n";
    echo "3. ✅ Nilai (score)\n";
    echo "4. ✅ Tanggal (created_at)\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
