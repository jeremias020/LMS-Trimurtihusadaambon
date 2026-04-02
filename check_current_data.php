<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECKING EXISTING DATA ===\n\n";

try {
    echo "Step 1: Users table data:\n";
    $users = DB::table('users')->get();
    foreach ($users as $user) {
        echo "  - ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
    }
    
    echo "\nStep 2: Users_central table data:\n";
    $usersCentral = DB::table('users_central')->get();
    foreach ($usersCentral as $user) {
        echo "  - ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
    }
    
    echo "\nStep 3: Testing current relationships...\n";
    
    // Test User -> Student relationship
    $user = \App\Models\User::find(3);
    if ($user) {
        echo "Found user (ID: 3): {$user->name}\n";
        
        try {
            $student = $user->siswa;
            if ($student) {
                echo "✅ Student relationship works!\n";
                echo "  Student name: {$student->name}\n";
                echo "  Student email: {$student->email}\n";
                echo "  Student role: {$student->role}\n";
                echo "  Student NIS: {$student->nis_nip}\n";
            } else {
                echo "❌ Student relationship returns null\n";
                
                // Check if there's a student record in users table with ID 3
                $studentInUsers = DB::table('users')->where('id', 3)->first();
                if ($studentInUsers) {
                    echo "✅ Student data exists in users table:\n";
                    echo "  Name: {$studentInUsers->name}\n";
                    echo "  Email: {$studentInUsers->email}\n";
                    echo "  Role: {$studentInUsers->role}\n";
                    echo "  NIS: {$studentInUsers->nis_nip}\n";
                } else {
                    echo "❌ No student data in users table with ID 3\n";
                }
            }
        } catch (Exception $e) {
            echo "❌ Student relationship error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ User with ID 3 not found\n";
    }
    
    echo "\nStep 4: Testing Student model directly...\n";
    
    try {
        $student = \App\Models\Student::find(3);
        if ($student) {
            echo "✅ Direct Student model works:\n";
            echo "  Name: {$student->name}\n";
            echo "  Email: {$student->email}\n";
            echo "  Role: {$student->role}\n";
            echo "  NIS: {$student->nis_nip}\n";
        } else {
            echo "❌ No student found with ID 3\n";
            
            // Try to find any student
            $anyStudent = \App\Models\Student::first();
            if ($anyStudent) {
                echo "✅ Found student with ID {$anyStudent->id}: {$anyStudent->name}\n";
            } else {
                echo "❌ No students found in Student model\n";
            }
        }
    } catch (Exception $e) {
        echo "❌ Direct Student model error: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 5: Testing controller methods...\n";
    
    $controller = new \App\Http\Controllers\Guru\PenilaianController();
    
    try {
        $view = $controller->autoAssessment();
        $data = $view->getData();
        echo "✅ Controller methods working:\n";
        echo "  - Students: " . count($data['students']) . "\n";
        echo "  - Practicals: " . count($data['practicals']) . "\n";
        
        // Test student relationships
        foreach ($data['students'] as $student) {
            echo "\nTesting student {$student->name} (ID: {$student->id}):\n";
            try {
                $siswaProfile = $student->siswa;
                if ($siswaProfile) {
                    echo "  ✅ Siswa profile accessible\n";
                    echo "    NIS: {$siswaProfile->nis_nip}\n";
                } else {
                    echo "  ❌ Siswa profile not accessible\n";
                }
            } catch (Exception $e) {
                echo "  ❌ Error accessing siswa profile: " . $e->getMessage() . "\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Controller error: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== ANALYSIS ===\n";
    echo "The 'user_id' column error is likely coming from:\n";
    echo "1. A hardcoded query somewhere in the code\n";
    echo "2. A model relationship that's incorrectly configured\n";
    echo "3. A migration or seeder that's using wrong column names\n";
    echo "\nThe current setup should work with the existing data.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
