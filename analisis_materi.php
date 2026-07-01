<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 ANALISIS SISTEM MATERI GURU-SISWA\n";
echo "=====================================\n";

try {
    echo "Step 1: Struktur Tabel Materials\n";
    echo "-------------------------------------\n";
    
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('materials');
    echo "Materials table columns:\n";
    foreach ($columns as $column) {
        echo "  - {$column}\n";
    }
    
    echo "\nStep 2: Cek Relasi Saat Ini\n";
    echo "-------------------------------------\n";
    
    // Cek materi yang ada
    $materials = \App\Models\Material::with(['guru', 'subject', 'kelas'])->limit(3)->get();
    
    foreach ($materials as $material) {
        echo "\nMaterial: {$material->title}\n";
        echo "  Guru: " . ($material->guru ? $material->guru->name : 'No Guru') . "\n";
        echo "  Kelas: " . ($material->kelas ? $material->kelas->name : 'No Class') . "\n";
        echo "  Subject: " . ($material->subject ? $material->subject->name : 'No Subject') . "\n";
        echo "  Published: " . ($material->published_at ? $material->published_at : 'Not Published') . "\n";
    }
    
    echo "\nStep 3: Cek Cara Siswa Melihat Materi\n";
    echo "-------------------------------------\n";
    
    // Test query siswa
    $student = \App\Models\Student::where('id', 1)->first();
    if ($student) {
        echo "Student: {$student->name}\n";
        echo "Kelas ID: " . ($student->kelas_id ?? 'No Class') . "\n";
        
        // Test query materi untuk siswa ini
        $studentMaterials = \App\Models\Material::whereNotNull('published_at')
            ->where(function($query) use ($student) {
                if ($student->kelas_id) {
                    $query->where('kelas_id', $student->kelas_id)
                          ->orWhereNull('kelas_id');
                } else {
                    $query->whereNull('kelas_id');
                }
            })
            ->limit(3)
            ->get();
            
        echo "Materials accessible to this student: {$studentMaterials->count()}\n";
        foreach ($studentMaterials as $mat) {
            echo "  - {$mat->title}\n";
        }
    } else {
        echo "No student found for testing\n";
    }
    
    echo "\nStep 4: Identifikasi Masalah\n";
    echo "-------------------------------------\n";
    
    echo "Masalah yang teridentifikasi:\n";
    echo "1. Materials diupload oleh guru dengan guru_id\n";
    echo "2. Siswa melihat materi berdasarkan kelas_id\n";
    echo "3. Perlu relasi antara guru dan kelas\n";
    echo "4. Perlu filter berdasarkan kelas siswa\n\n";
    
    echo "🎯 REKOMENDASI PERBAIKAN:\n";
    echo "=====================================\n";
    echo "1. Pastikan guru menghubungkan materi ke kelas yang diajar\n";
    echo "2. Siswa hanya melihat materi untuk kelasnya\n";
    echo "3. Tambahkan relasi many-to-many antara materi dan kelas\n";
    echo "4. Filter materi berdasarkan kelas siswa yang aktif\n";
    
    echo "\n✨ ANALISIS SELESAI! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
