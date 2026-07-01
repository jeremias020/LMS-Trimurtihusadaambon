<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG NILAI PRAKTIKUM SISWA\n";
echo "=====================================\n";

try {
    echo "Step 1: Cek PracticalScore Data\n";
    echo "-------------------------------------\n";
    
    // Test query yang digunakan oleh controller
    $siswaId = 1; // Siti Nurhaliza
    $kelasId = 1; // Kelas X Keperawatan
    
    echo "Testing dengan siswa_id: {$siswaId}, kelas_id: {$kelasId}\n\n";
    
    $practicalScores = \App\Models\PracticalScore::with(['practical', 'criteria'])
        ->where('siswa_id', $siswaId)
        ->when($kelasId, function($q) use ($kelasId) {
            $q->whereHas('practical', function($sub) use ($kelasId) {
                $sub->where('kelas_id', $kelasId);
            });
        })
        ->latest()
        ->limit(5)
        ->get();
    
    echo "PracticalScore records found: {$practicalScores->count()}\n";
    
    foreach ($practicalScores as $score) {
        echo "\nRecord ID: {$score->id}\n";
        echo "  Practical ID: {$score->practical_id}\n";
        echo "  Criteria ID: {$score->criteria_id}\n";
        echo "  Score: {$score->score}\n";
        
        // Cek relasi
        echo "  Practical Title: " . ($score->practical ? $score->practical->judul : 'NULL') . "\n";
        echo "  Practical Kelas: " . ($score->practical ? $score->practical->kelas_id : 'NULL') . "\n";
        echo "  Criteria Name: " . ($score->criteria ? $score->criteria->name : 'NULL') . "\n";
        echo "  Created At: {$score->created_at}\n";
    }
    
    echo "\nStep 2: Cek Criteria Table\n";
    echo "-------------------------------------\n";
    
    // Cek apakah tabel criteria ada dan isinya
    try {
        $criteriaCount = \App\Models\Criteria::count();
        echo "Total criteria records: {$criteriaCount}\n";
        
        if ($criteriaCount > 0) {
            $criteriaSample = \App\Models\Criteria::limit(3)->get();
            foreach ($criteriaSample as $c) {
                echo "  - {$c->name} (ID: {$c->id})\n";
            }
        }
    } catch (\Exception $e) {
        echo "❌ Error accessing criteria: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 3: Cek Practical Table\n";
    echo "-------------------------------------\n";
    
    // Cek praktikum yang ada
    $practicals = \App\Models\Practical::where('kelas_id', $kelasId)
        ->limit(3)
        ->get();
    
    echo "Practicals in kelas {$kelasId}: {$practicals->count()}\n";
    foreach ($practicals as $p) {
        echo "  - {$p->judul} (ID: {$p->id})\n";
        echo "    Due Date: " . ($p->due_date ? $p->due_date : 'No due date') . "\n";
        echo "    Published: " . ($p->is_published ? 'Yes' : 'No') . "\n";
    }
    
    echo "\nStep 4: Test Controller Query\n";
    echo "-------------------------------------\n";
    
    // Simulate controller query
    try {
        $controllerScores = \App\Models\PracticalScore::with(['practical', 'criteria'])
            ->where('siswa_id', $siswaId)
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('practical', function($sub) use ($kelasId) {
                    $sub->where('kelas_id', $kelasId);
                });
            })
            ->latest()
            ->paginate(10);
        
        echo "Controller query successful: {$controllerScores->total()} records\n";
        
        // Test first record
        $firstScore = $controllerScores->first();
        if ($firstScore) {
            echo "First record:\n";
            echo "  Practical: " . ($firstScore->practical ? $firstScore->practical->judul : 'NULL') . "\n";
            echo "  Criteria: " . ($firstScore->criteria ? $firstScore->criteria->name : 'NULL') . "\n";
            echo "  Score: {$firstScore->score}\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Controller query failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎯 ANALISIS MASALAH:\n";
    echo "=====================================\n";
    
    if ($practicalScores->count() > 0) {
        echo "✅ Data PracticalScore ada\n";
        echo "✅ Relasi practical berfungsi\n";
        echo "✅ Relasi criteria: " . ($practicalScores->first()->criteria ? 'BERFUNGSI' : 'TIDAK ADA') . "\n";
        echo "✅ View seharusnya menampilkan data dengan benar\n";
    } else {
        echo "❌ Tidak ada data PracticalScore\n";
        echo "❌ Mungkin siswa belum memiliki nilai praktikum\n";
    }
    
    echo "\n📝 SOLUSI:\n";
    echo "=====================================\n";
    echo "1. Pastikan siswa memiliki nilai praktikum\n";
    echo "2. Pastikan relasi criteria terhubung dengan benar\n";
    echo "3. Pastikan praktikum sudah dinilai oleh guru\n";
    echo "4. View sudah diperbaiki: 'Jenis Praktikum' dan 'Kriteria'\n";
    
    echo "\n✨ DEBUG COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
