<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== INVESTIGATING SUBJECTS DROPDOWN ISSUE ===\n\n";

$pdo = \DB::connection()->getPdo();

try {
    echo "Step 1: Checking all subjects in database...\n";
    
    $allSubjects = $pdo->query("SELECT id, name, code, is_active, deleted_at FROM subjects ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    echo "Total subjects in database: " . count($allSubjects) . "\n";
    
    foreach ($allSubjects as $subject) {
        $status = ($subject['is_active'] ? 'Active' : 'Inactive') . ($subject['deleted_at'] ? ' (Deleted)' : '');
        echo "  - ID: {$subject['id']}, Name: {$subject['name']}, Code: {$subject['code']}, Status: {$status}\n";
    }
    
    echo "\nStep 2: Checking active subjects (is_active = 1 AND deleted_at IS NULL)...\n";
    
    $activeSubjects = $pdo->query("SELECT id, name, code FROM subjects WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    echo "Active subjects: " . count($activeSubjects) . "\n";
    
    foreach ($activeSubjects as $subject) {
        echo "  - ID: {$subject['id']}, Name: {$subject['name']}, Code: {$subject['code']}\n";
    }
    
    echo "\nStep 3: Checking class_subjects table...\n";
    
    $allClassSubjects = $pdo->query("SELECT id, class_id, subject_id, teacher_id FROM class_subjects ORDER BY subject_id")->fetchAll(PDO::FETCH_ASSOC);
    echo "Total class_subjects: " . count($allClassSubjects) . "\n";
    
    foreach ($allClassSubjects as $classSubject) {
        echo "  - ID: {$classSubject['id']}, Class ID: {$classSubject['class_id']}, Subject ID: {$classSubject['subject_id']}, Teacher ID: {$classSubject['teacher_id']}\n";
    }
    
    echo "\nStep 4: Testing MaterialController query (JOIN)...\n";
    
    $classSubjects = $pdo->query("
        SELECT cs.id, s.name as subject_name 
        FROM class_subjects cs 
        JOIN subjects s ON cs.subject_id = s.id 
        WHERE s.is_active = 1 
        AND s.deleted_at IS NULL 
        ORDER BY s.name
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Class subjects from MaterialController query: " . count($classSubjects) . "\n";
    
    foreach ($classSubjects as $classSubject) {
        echo "  - ID: {$classSubject['id']}, Subject: {$classSubject['subject_name']}\n";
    }
    
    echo "\nStep 5: Checking if we need more subjects or class_subjects...\n";
    
    if (count($activeSubjects) > count($classSubjects)) {
        echo "❌ Found " . (count($activeSubjects) - count($classSubjects)) . " active subjects without class_subjects entries!\n";
        
        // Find subjects without class_subjects
        $subjectsWithoutClass = [];
        foreach ($activeSubjects as $subject) {
            $hasClassSubject = false;
            foreach ($allClassSubjects as $classSubject) {
                if ($classSubject['subject_id'] == $subject['id']) {
                    $hasClassSubject = true;
                    break;
                }
            }
            if (!$hasClassSubject) {
                $subjectsWithoutClass[] = $subject;
            }
        }
        
        echo "Subjects without class_subjects:\n";
        foreach ($subjectsWithoutClass as $subject) {
            echo "  - {$subject['name']} (ID: {$subject['id']})\n";
        }
        
        echo "\nStep 6: Creating missing class_subjects entries...\n";
        
        $guru = $pdo->query("SELECT id FROM users_central WHERE role = 'guru' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $kelas = $pdo->query("SELECT id FROM kelas LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        
        if ($guru && $kelas) {
            foreach ($subjectsWithoutClass as $subject) {
                // Check if class_subject already exists for this subject
                $exists = $pdo->query("SELECT COUNT(*) FROM class_subjects WHERE subject_id = {$subject['id']}")->fetchColumn();
                
                if ($exists == 0) {
                    $pdo->exec("
                        INSERT INTO class_subjects (class_id, subject_id, teacher_id, day, start_time, end_time, room, created_at, updated_at)
                        VALUES ({$kelas['id']}, {$subject['id']}, {$guru['id']}, 'Senin', '08:00:00', '09:30:00', 'Ruang 101', NOW(), NOW())
                    ");
                    
                    echo "✅ Created class_subject for {$subject['name']}\n";
                }
            }
        }
        
        // Test query again
        $classSubjectsAfter = $pdo->query("
            SELECT cs.id, s.name as subject_name 
            FROM class_subjects cs 
            JOIN subjects s ON cs.subject_id = s.id 
            WHERE s.is_active = 1 
            AND s.deleted_at IS NULL 
            ORDER BY s.name
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        echo "\n✅ After creating missing entries: " . count($classSubjectsAfter) . " class subjects\n";
        foreach ($classSubjectsAfter as $classSubject) {
            echo "  - ID: {$classSubject['id']}, Subject: {$classSubject['subject_name']}\n";
        }
        
    } else {
        echo "✅ All active subjects have class_subjects entries\n";
    }
    
    echo "\nStep 7: Alternative solution - modify MaterialController to show all subjects...\n";
    
    // Alternative query that shows all active subjects
    $allActiveSubjects = $pdo->query("
        SELECT id, name as subject_name 
        FROM subjects 
        WHERE is_active = 1 
        AND deleted_at IS NULL 
        ORDER BY name
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Alternative query (all active subjects): " . count($allActiveSubjects) . "\n";
    foreach ($allActiveSubjects as $subject) {
        echo "  - ID: {$subject['id']}, Subject: {$subject['subject_name']}\n";
    }
    
    echo "\n🎯 RECOMMENDATIONS:\n";
    echo "1. Create class_subjects entries for all active subjects (✅ Done above)\n";
    echo "2. OR modify MaterialController to use subjects directly instead of class_subjects\n";
    echo "3. OR create more subjects if needed\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== CLEANUP ===\n";
if (file_exists(__DIR__ . '/test_material_controller.php')) {
    unlink(__DIR__ . '/test_material_controller.php');
    echo "✅ Removed test_material_controller.php\n";
}
