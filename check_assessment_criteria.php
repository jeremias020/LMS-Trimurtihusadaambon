<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECK ASSESSMENT CRITERIA ===" . PHP_EOL;

try {
    $guruId = 118;
    
    echo PHP_EOL . "=== GURU INFO ===" . PHP_EOL;
    $guru = \App\Models\User::find($guruId);
    echo "Guru: " . $guru->name . " (ID: " . $guru->id . ")" . PHP_EOL;
    
    echo PHP_EOL . "=== ASSIGNMENTS ===" . PHP_EOL;
    $assignments = \App\Models\Assignment::where('guru_id', $guruId)->get();
    echo "Total assignments: " . $assignments->count() . PHP_EOL;
    
    foreach ($assignments->take(3) as $assignment) {
        echo "- " . $assignment->judul . " (Published: " . ($assignment->is_published ? 'Yes' : 'No') . ")" . PHP_EOL;
    }
    
    echo PHP_EOL . "=== PRACTICALS ===" . PHP_EOL;
    $practicals = \App\Models\Practical::where('guru_id', $guruId)->get();
    echo "Total practicals: " . $practicals->count() . PHP_EOL;
    
    foreach ($practicals->take(3) as $practical) {
        echo "- " . $practical->judul . " (Published: " . ($practical->is_published ? 'Yes' : 'No') . ")" . PHP_EOL;
    }
    
    echo PHP_EOL . "=== KRITERIA PENILAIAN ===" . PHP_EOL;
    
    // Check different possible table names
    $tables = ['kriteria_penilaian', 'kriteria_penilaian_new', 'criteria'];
    foreach ($tables as $table) {
        try {
            $count = DB::table($table)->count();
            echo "Table '$table': $count records" . PHP_EOL;
            
            if ($count > 0) {
                $sample = DB::table($table)->limit(3)->get();
                foreach ($sample as $criteria) {
                    echo "- " . ($criteria->nama_kriteria ?? $criteria->name ?? 'N/A') . PHP_EOL;
                }
            }
        } catch (Exception $e) {
            echo "Table '$table': Not found or error" . PHP_EOL;
        }
    }
    
    echo PHP_EOL . "=== CREATE TEST PRACTICAL ===" . PHP_EOL;
    
    // Create a test practical if none exists
    if ($practicals->count() === 0) {
        $subject = \App\Models\Subject::first();
        if ($subject) {
            $practical = \App\Models\Practical::create([
                'guru_id' => $guruId,
                'subject_id' => $subject->id,
                'judul' => 'Test Praktikum untuk Auto Assessment',
                'deskripsi' => 'Test praktikum dengan kriteria penilaian',
                'instruksi' => 'Ikuti semua prosedur dengan benar',
                'skill_level' => 'Menengah',
                'is_published' => true,
                'tanggal' => now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            echo "✅ Test practical created: " . $practical->judul . PHP_EOL;
            echo "   - ID: " . $practical->id . PHP_EOL;
            echo "   - Subject: " . $practical->subject->name . PHP_EOL;
            
            // Create some criteria for this practical
            $criteriaData = [
                ['nama_kriteria' => 'Persiapan Alat', 'bobot' => 20, 'deskripsi' => 'Menyiapkan alat dengan benar'],
                ['nama_kriteria' => 'Prosedur Kerja', 'bobot' => 40, 'deskripsi' => 'Mengikuti prosedur kerja'],
                ['nama_kriteria' => 'Hasil Akhir', 'bobot' => 30, 'deskripsi' => 'Hasil akhir yang baik'],
                ['nama_kriteria' => 'Kebersihan', 'bobot' => 10, 'deskripsi' => 'Menjaga kebersihan area kerja'],
            ];
            
            foreach ($criteriaData as $criteria) {
                try {
                    DB::table('kriteria_penilaian_new')->insert([
                        'practical_id' => $practical->id,
                        'nama_kriteria' => $criteria['nama_kriteria'],
                        'bobot' => $criteria['bobot'],
                        'deskripsi' => $criteria['deskripsi'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    echo "✅ Criteria created: " . $criteria['nama_kriteria'] . PHP_EOL;
                } catch (Exception $e) {
                    echo "❌ Failed to create criteria: " . $e->getMessage() . PHP_EOL;
                }
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
