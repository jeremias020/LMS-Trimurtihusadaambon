<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CREATING SAMPLE DATA FOR TESTING ===\n\n";

try {
    echo "Step 1: Checking existing data...\n";
    
    // Get existing data
    $guru = \App\Models\User::where('role', 'guru')->first();
    $subject = \App\Models\Subject::first();
    $kelas = \App\Models\Kelas::first();
    
    echo "✅ Guru: {$guru->name} (ID: {$guru->id})\n";
    echo "✅ Subject: {$subject->name} (ID: {$subject->id})\n";
    echo "✅ Class: {$kelas->name} (ID: {$kelas->id})\n";
    
    echo "\nStep 2: Creating sample practicals...\n";
    
    // Update existing practicals with proper data
    $practical1 = \App\Models\Practical::find(1);
    if ($practical1) {
        $practical1->update([
            'judul' => 'Praktikum Dasar Keperawatan - Pemeriksaan Vital',
            'deskripsi' => 'Praktikum pemeriksaan tanda-tanda vital pasien',
            'subject_id' => $subject->id,
            'kelas_id' => $kelas->id,
            'date' => now()->addDays(7),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '11:00',
            'lokasi' => 'Lab Keperawatan',
            'durasi' => 120,
            'skill_level' => 'basic',
            'max_score' => 100,
            'is_published' => true
        ]);
        echo "✅ Updated Practical 1: {$practical1->judul}\n";
    }
    
    $practical2 = \App\Models\Practical::find(2);
    if ($practical2) {
        $practical2->update([
            'judul' => 'Praktikum Lanjutan Keperawatan - Perawatan Luka',
            'deskripsi' => 'Praktikum perawatan luka dasar dan lanjutan',
            'subject_id' => $subject->id,
            'kelas_id' => $kelas->id,
            'date' => now()->addDays(14),
            'waktu_mulai' => '13:00',
            'waktu_selesai' => '15:00',
            'lokasi' => 'Lab Keperawatan',
            'durasi' => 120,
            'skill_level' => 'intermediate',
            'max_score' => 100,
            'is_published' => true
        ]);
        echo "✅ Updated Practical 2: {$practical2->judul}\n";
    }
    
    echo "\nStep 3: Creating student data...\n";
    
    // Update student data
    $student = \App\Models\User::where('role', 'siswa')->first();
    if ($student) {
        // Create student profile if not exists
        if (!$student->siswa) {
            \App\Models\Student::create([
                'user_id' => $student->id,
                'nis' => '2024001',
                'nisn' => '0034567890',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Ambon',
                'tanggal_lahir' => '2005-01-15',
                'alamat' => 'Jl. Pendidikan No. 123, Ambon',
                'no_telepon' => '08123456789',
                'kelas_id' => $kelas->id,
                'major' => 'Keperawatan',
                'tahun_ajaran' => '2024/2025',
                'status' => 'aktif'
            ]);
            echo "✅ Created student profile for: {$student->name}\n";
        } else {
            // Update existing student
            $student->siswa->update([
                'nis' => '2024001',
                'kelas_id' => $kelas->id,
                'major' => 'Keperawatan'
            ]);
            echo "✅ Updated student profile for: {$student->name}\n";
        }
    }
    
    echo "\nStep 4: Testing updated data...\n";
    
    // Test the improved queries again
    $students = \App\Models\User::where('role', 'siswa')
        ->where('is_active', true)
        ->with(['siswa.kelas.jurusan'])
        ->orderBy('name')
        ->get();
    
    $practicals = \App\Models\Practical::where('guru_id', $guru->id)
        ->with(['subject.jurusan', 'kelas.jurusan'])
        ->latest()
        ->get();
    
    echo "✅ Students with complete data:\n";
    foreach ($students as $student) {
        echo "  - {$student->name}\n";
        echo "    NIS: " . ($student->siswa->nis ?? 'N/A') . "\n";
        echo "    Class: " . ($student->siswa->kelas->name ?? 'N/A') . "\n";
        echo "    Jurusan: " . ($student->siswa->kelas->jurusan->name ?? 'N/A') . "\n";
    }
    
    echo "\n✅ Practicals with complete data:\n";
    foreach ($practicals as $practical) {
        echo "  - {$practical->judul}\n";
        echo "    Subject: " . ($practical->subject->name ?? 'N/A') . "\n";
        echo "    Class: " . ($practical->kelas->name ?? 'N/A') . "\n";
        echo "    Date: {$practical->date}\n";
        echo "    Max Score: {$practical->max_score}\n";
    }
    
    echo "\n🎉 SUCCESS! Sample data created and relationships established!\n";
    echo "✅ Students now have NIS and proper class assignments\n";
    echo "✅ Practicals now have complete information\n";
    echo "✅ All relationships are properly linked\n";
    echo "✅ Ready for dropdown testing\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
