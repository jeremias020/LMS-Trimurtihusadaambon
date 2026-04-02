<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECKING ADMIN-CREATED DATA FOR AUTO ASSESSMENT ===\n\n";

try {
    echo "Step 1: Checking Students data...\n";
    
    // Check students with their classes
    $students = \App\Models\User::where('role', 'siswa')
        ->where('is_active', true)
        ->with('kelas')
        ->orderBy('name')
        ->get();
    
    echo "✅ Found {$students->count()} active students:\n";
    foreach ($students as $student) {
        echo "  - ID: {$student->id}, Name: {$student->name}, NIS: {$student->nis_nip}, Class: " . ($student->kelas->name ?? 'N/A') . "\n";
    }
    
    echo "\nStep 2: Checking Classes data...\n";
    
    $classes = \App\Models\Kelas::with('jurusan')->get();
    echo "✅ Found {$classes->count()} classes:\n";
    foreach ($classes as $class) {
        echo "  - ID: {$class->id}, Name: {$class->name}, Code: {$class->code}, Jurusan: " . ($class->jurusan->name ?? 'N/A') . "\n";
    }
    
    echo "\nStep 3: Checking Subjects data...\n";
    
    $subjects = \App\Models\Subject::where('is_active', true)->with('jurusan')->get();
    echo "✅ Found {$subjects->count()} active subjects:\n";
    foreach ($subjects as $subject) {
        echo "  - ID: {$subject->id}, Name: {$subject->name}, Jurusan: " . ($subject->jurusan->name ?? 'N/A') . "\n";
    }
    
    echo "\nStep 4: Checking Practicals data...\n";
    
    $guruId = 2; // Guru Sample ID
    $practicals = \App\Models\Practical::where('guru_id', $guruId)
        ->with(['subject', 'subject.jurusan'])
        ->latest()
        ->get();
    
    echo "✅ Found {$practicals->count()} practicals for guru ID {$guruId}:\n";
    foreach ($practicals as $practical) {
        echo "  - ID: {$practical->id}, Title: {$practical->judul}, Subject: " . ($practical->subject->name ?? 'N/A') . ", Jurusan: " . ($practical->subject->jurusan->name ?? 'N/A') . "\n";
    }
    
    echo "\nStep 5: Checking Class-Subject relationships...\n";
    
    $classSubjects = \App\Models\ClassSubject::with(['kelas', 'subject'])->get();
    echo "✅ Found {$classSubjects->count()} class-subject relationships:\n";
    foreach ($classSubjects as $cs) {
        echo "  - Class: {$cs->kelas->name}, Subject: {$cs->subject->name}, Guru: " . ($cs->subject->guru->name ?? 'N/A') . "\n";
    }
    
    echo "\nStep 6: Checking Student-Class assignments...\n";
    
    // Check if students are properly assigned to classes
    $studentsWithClasses = \App\Models\User::where('role', 'siswa')
        ->where('is_active', true)
        ->whereHas('kelas')
        ->with('kelas')
        ->get();
    
    echo "✅ Found {$studentsWithClasses->count()} students assigned to classes:\n";
    foreach ($studentsWithClasses as $student) {
        echo "  - {$student->name} -> {$student->kelas->name}\n";
    }
    
    echo "\n🎉 Data analysis complete!\n";
    echo "✅ Students: {$students->count()} active\n";
    echo "✅ Classes: {$classes->count()} available\n";
    echo "✅ Subjects: {$subjects->count()} active\n";
    echo "✅ Practicals: {$practicals->count()} for this guru\n";
    echo "✅ Class-Subject: {$classSubjects->count()} relationships\n";
    echo "✅ Students with Classes: {$studentsWithClasses->count()} assigned\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
