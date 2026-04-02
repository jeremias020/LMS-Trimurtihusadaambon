<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CREATING SAMPLE DATA WITH CORRECT FIELDS ===\n\n";

try {
    echo "Step 1: Getting existing data...\n";
    
    $guru = \App\Models\User::where('role', 'guru')->first();
    $subject = \App\Models\Subject::first();
    $kelas = \App\Models\Kelas::first();
    
    echo "✅ Guru: {$guru->name} (ID: {$guru->id})\n";
    echo "✅ Subject: {$subject->name} (ID: {$subject->id})\n";
    echo "✅ Class: {$kelas->name} (ID: {$kelas->id})\n";
    
    echo "\nStep 2: Creating class-subject relationship...\n";
    
    // Check if class_subject relationship exists
    $classSubject = \DB::table('class_subject')
        ->where('class_id', $kelas->id)
        ->where('subject_id', $subject->id)
        ->first();
    
    if (!$classSubject) {
        \DB::table('class_subject')->insert([
            'class_id' => $kelas->id,
            'subject_id' => $subject->id,
            'guru_id' => $guru->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "✅ Created class-subject relationship\n";
        $classSubjectId = \DB::getPdo()->lastInsertId();
    } else {
        echo "✅ Class-subject relationship already exists\n";
        $classSubjectId = $classSubject->id;
    }
    
    echo "\nStep 3: Updating practicals with correct fields...\n";
    
    // Update practicals using actual table fields
    $practical1 = \DB::table('practicals')->where('id', 1)->first();
    if ($practical1) {
        \DB::table('practicals')->where('id', 1)->update([
            'guru_id' => $guru->id,
            'class_subject_id' => $classSubjectId,
            'title' => 'Praktikum Dasar Keperawatan - Pemeriksaan Vital',
            'description' => 'Praktikum pemeriksaan tanda-tanda vital pasien',
            'instructions' => json_encode([
                '1. Persiapkan alat pemeriksaan vital',
                '2. Jelaskan prosedur pada pasien',
                '3. Lakukan pemeriksaan dengan benar',
                '4. Dokumentasikan hasil'
            ]),
            'due_date' => now()->addDays(7),
            'published_at' => now(),
            'updated_at' => now()
        ]);
        echo "✅ Updated Practical 1\n";
    }
    
    $practical2 = \DB::table('practicals')->where('id', 2)->first();
    if ($practical2) {
        \DB::table('practicals')->where('id', 2)->update([
            'guru_id' => $guru->id,
            'class_subject_id' => $classSubjectId,
            'title' => 'Praktikum Lanjutan Keperawatan - Perawatan Luka',
            'description' => 'Praktikum perawatan luka dasar dan lanjutan',
            'instructions' => json_encode([
                '1. Persiapkan alat perawatan luka',
                '2. Lakukan antisepsis dengan benar',
                '3. Tutup luka dengan teknik steril',
                '4. Berikan instruksi perawatan lanjutan'
            ]),
            'due_date' => now()->addDays(14),
            'published_at' => now(),
            'updated_at' => now()
        ]);
        echo "✅ Updated Practical 2\n";
    }
    
    echo "\nStep 4: Creating student data...\n";
    
    $student = \App\Models\User::where('role', 'siswa')->first();
    if ($student) {
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
            echo "✅ Created student profile\n";
        } else {
            $student->siswa->update([
                'nis' => '2024001',
                'kelas_id' => $kelas->id,
                'major' => 'Keperawatan'
            ]);
            echo "✅ Updated student profile\n";
        }
    }
    
    echo "\nStep 5: Testing updated data...\n";
    
    // Test practical query with actual table structure
    $practicals = \DB::table('practicals')
        ->join('class_subject', 'practicals.class_subject_id', '=', 'class_subject.id')
        ->join('subjects', 'class_subject.subject_id', '=', 'subjects.id')
        ->join('classes', 'class_subject.class_id', '=', 'classes.id')
        ->where('practicals.guru_id', $guru->id)
        ->select(
            'practicals.*',
            'subjects.name as subject_name',
            'classes.name as class_name'
        )
        ->get();
    
    echo "✅ Updated practicals:\n";
    foreach ($practicals as $practical) {
        echo "  - {$practical->title}\n";
        echo "    Subject: {$practical->subject_name}\n";
        echo "    Class: {$practical->class_name}\n";
        echo "    Due Date: {$practical->due_date}\n";
        echo "    ---\n";
    }
    
    echo "\n🎉 SUCCESS! Sample data created with correct structure!\n";
    echo "✅ Practical data updated with proper fields\n";
    echo "✅ Class-subject relationships established\n";
    echo "✅ Student data created with NIS and class\n";
    echo "✅ Ready for dropdown testing\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
