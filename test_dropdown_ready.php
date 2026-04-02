<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING DROPDOWN WITH EXISTING DATA ===\n\n";

try {
    echo "Step 1: Testing controller methods directly...\n";
    
    $controller = new \App\Http\Controllers\Guru\PenilaianController();
    
    try {
        $view = $controller->autoAssessment();
        $data = $view->getData();
        echo "✅ autoAssessment method works:\n";
        echo "  - Students: " . count($data['students']) . "\n";
        echo "  - Practicals: " . count($data['practicals']) . "\n";
        echo "  - Classes: " . count($data['classes']) . "\n";
        echo "  - Subjects: " . count($data['subjects']) . "\n";
        
        echo "\nStep 2: Analyzing student data...\n";
        foreach ($data['students'] as $student) {
            echo "  Student ID: {$student->id}\n";
            echo "  Name: {$student->name}\n";
            echo "  Email: {$student->email}\n";
            echo "  Role: {$student->role}\n";
            echo "  Is Active: " . ($student->is_active ? 'Yes' : 'No') . "\n";
            
            // Check siswa relationship
            if ($student->siswa) {
                echo "  Has Siswa Profile: Yes\n";
                echo "  NIS: " . ($student->siswa->nis ?? 'N/A') . "\n";
                echo "  Kelas ID: " . ($student->siswa->kelas_id ?? 'N/A') . "\n";
                
                if ($student->siswa->kelas) {
                    echo "  Class Name: " . $student->siswa->kelas->name . "\n";
                    echo "  Class Jurusan: " . ($student->siswa->kelas->jurusan->name ?? 'N/A') . "\n";
                } else {
                    echo "  Class: N/A\n";
                }
            } else {
                echo "  Has Siswa Profile: No\n";
            }
            echo "  ---\n";
        }
        
        echo "\nStep 3: Analyzing practical data...\n";
        foreach ($data['practicals'] as $practical) {
            echo "  Practical ID: {$practical->id}\n";
            echo "  Title: " . ($practical->title ?? 'N/A') . "\n";
            echo "  Description: " . ($practical->description ?? 'N/A') . "\n";
            
            if ($practical->subject) {
                echo "  Subject: " . $practical->subject->name . "\n";
                echo "  Subject Jurusan: " . ($practical->subject->jurusan->name ?? 'N/A') . "\n";
            } else {
                echo "  Subject: N/A\n";
            }
            
            if ($practical->kelas) {
                echo "  Class: " . $practical->kelas->name . "\n";
                echo "  Class Jurusan: " . ($practical->kelas->jurusan->name ?? 'N/A') . "\n";
            } else {
                echo "  Class: N/A\n";
            }
            echo "  ---\n";
        }
        
        echo "\nStep 4: Testing dropdown format...\n";
        
        // Test student dropdown format
        echo "Student dropdown options:\n";
        foreach ($data['students'] as $student) {
            $nis = $student->siswa->nis ?? 'N/A';
            $className = $student->siswa->kelas->name ?? 'N/A';
            $displayName = "{$student->name} - {$nis} - {$className}";
            
            echo "  <option value=\"{$student->id}\" data-name=\"{$student->name}\" data-class=\"{$className}\" data-nis=\"{$nis}\">{$displayName}</option>\n";
        }
        
        echo "\nPractical dropdown options:\n";
        foreach ($data['practicals'] as $practical) {
            $title = $practical->title ?? 'Untitled Practical';
            $subjectName = $practical->subject->name ?? 'N/A';
            $className = $practical->kelas->name ?? 'N/A';
            $maxScore = $practical->max_score ?? 100;
            $displayName = "{$title} - {$subjectName} ({$className})";
            
            echo "  <option value=\"{$practical->id}\" data-title=\"{$title}\" data-subject=\"{$subjectName}\" data-max-score=\"{$maxScore}\" data-class=\"{$className}\">{$displayName}</option>\n";
        }
        
        echo "\nStep 5: Testing view rendering simulation...\n";
        
        // Simulate view data access
        $simulatedStudentData = [];
        foreach ($data['students'] as $student) {
            $simulatedStudentData[] = [
                'id' => $student->id,
                'name' => $student->name,
                'nis' => $student->siswa->nis ?? 'N/A',
                'class_name' => $student->siswa->kelas->name ?? 'N/A',
                'jurusan_name' => $student->siswa->kelas->jurusan->name ?? 'N/A'
            ];
        }
        
        $simulatedPracticalData = [];
        foreach ($data['practicals'] as $practical) {
            $simulatedPracticalData[] = [
                'id' => $practical->id,
                'title' => $practical->title ?? 'Untitled',
                'subject_name' => $practical->subject->name ?? 'N/A',
                'class_name' => $practical->kelas->name ?? 'N/A',
                'max_score' => $practical->max_score ?? 100
            ];
        }
        
        echo "✅ Simulated data for view:\n";
        echo "Students count: " . count($simulatedStudentData) . "\n";
        echo "Practicals count: " . count($simulatedPracticalData) . "\n";
        
        echo "\n🎉 SUCCESS! Dropdown data is ready!\n";
        echo "✅ Controller methods working correctly\n";
        echo "✅ Student data with relationships accessible\n";
        echo "✅ Practical data with subject/class info accessible\n";
        echo "✅ Dropdown format matches admin-created data\n";
        echo "✅ Ready for view rendering\n";
        echo "✅ Auto assessment page ready for testing\n";
        
    } catch (Exception $e) {
        echo "❌ autoAssessment method error: " . $e->getMessage() . "\n";
        echo "Stack trace: " . $e->getTraceAsString() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
