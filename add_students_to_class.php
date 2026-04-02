<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ADDING STUDENTS TO CLASS FOR TESTING ===\n\n";

try {
    echo "Step 1: Getting available students and classes...\n";
    
    // Get students
    $students = \DB::table('users_central')
        ->where('role', 'siswa')
        ->get();
    
    echo "Found {$students->count()} students:\n";
    foreach ($students as $student) {
        echo "  - {$student->name} (ID: {$student->id})\n";
    }
    
    // Get classes
    $classes = \DB::table('classes')->get();
    
    echo "\nFound {$classes->count()} classes:\n";
    foreach ($classes as $class) {
        echo "  - {$class->name} (ID: {$class->id})\n";
    }
    
    echo "\nStep 2: Adding students to class_students table...\n";
    
    if ($students->count() > 0 && $classes->count() > 0) {
        // Clear existing data
        \DB::table('class_students')->delete();
        
        // Add all students to the first class
        $firstClass = $classes->first();
        
        foreach ($students as $student) {
            \DB::table('class_students')->insert([
                'class_id' => $firstClass->id,
                'student_id' => $student->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            echo "✅ Added {$student->name} to {$firstClass->name}\n";
        }
        
        echo "\nStep 3: Verifying the data...\n";
        
        $classStudentsCount = \DB::table('class_students')->count();
        echo "Total class_students records: {$classStudentsCount}\n";
        
        // Verify students in class
        $studentsInClass = \DB::table('users_central')
            ->join('class_students', 'users_central.id', '=', 'class_students.student_id')
            ->where('users_central.role', 'siswa')
            ->where('class_students.class_id', $firstClass->id)
            ->orderBy('users_central.name')
            ->get(['users_central.*', 'class_students.class_id']);
        
        echo "Students in {$firstClass->name}:\n";
        foreach ($studentsInClass as $student) {
            echo "  - {$student->name} (Class ID: {$student->class_id})\n";
        }
        
        echo "\n🎉 SUCCESS! Students added to class successfully!\n";
        
    } else {
        echo "❌ No students or classes found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== CLEANUP ===\n";
if (file_exists(__DIR__ . '/check_student_tables.php')) {
    unlink(__DIR__ . '/check_student_tables.php');
    echo "✅ Removed check_student_tables.php\n";
}
if (file_exists(__DIR__ . '/check_class_students.php')) {
    unlink(__DIR__ . '/check_class_students.php');
    echo "✅ Removed check_class_students.php\n";
}
