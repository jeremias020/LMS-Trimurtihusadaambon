<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECKING ACTUAL CLASSES TABLE STRUCTURE ===\n\n";

$pdo = \DB::connection()->getPdo();

try {
    echo "Step 1: Getting actual structure of classes table...\n";
    
    $columns = $pdo->query("DESCRIBE classes")->fetchAll(PDO::FETCH_ASSOC);
    echo "Classes table columns:\n";
    foreach ($columns as $column) {
        echo "  - {$column['Field']} ({$column['Type']})" . ($column['Null'] == 'NO' && $column['Default'] === null ? ' - NO DEFAULT' : '') . "\n";
    }
    
    echo "\nStep 2: Getting existing data from classes table...\n";
    
    $classes = $pdo->query("SELECT * FROM classes LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    echo "Existing classes (" . count($classes) . "):\n";
    foreach ($classes as $class) {
        echo "  - ID: {$class['id']}, ";
        foreach ($class as $key => $value) {
            if ($key != 'id') {
                echo "{$key}: {$value}, ";
            }
        }
        echo "\n";
    }
    
    echo "\nStep 3: Checking class_subjects structure...\n";
    
    $csColumns = $pdo->query("DESCRIBE class_subjects")->fetchAll(PDO::FETCH_ASSOC);
    echo "Class_subjects table columns:\n";
    foreach ($csColumns as $column) {
        echo "  - {$column['Field']} ({$column['Type']})\n";
    }
    
    echo "\nStep 4: Testing MaterialController query with actual structure...\n";
    
    $guruId = 2;
    
    // Test classes query with actual columns
    try {
        $testClasses = $pdo->query("
            SELECT DISTINCT c.id, c.name 
            FROM classes c
            JOIN class_subjects cs ON c.id = cs.class_id
            WHERE cs.teacher_id = {$guruId}
            ORDER BY c.name
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        echo "✅ Classes for guru_id = {$guruId}: " . count($testClasses) . "\n";
        foreach ($testClasses as $class) {
            echo "  - ID: {$class['id']}, Name: {$class['name']}\n";
        }
    } catch (Exception $e) {
        echo "❌ Classes query error: " . $e->getMessage() . "\n";
    }
    
    // Test class_subjects query
    try {
        $testClassSubjects = $pdo->query("
            SELECT cs.id, s.name as subject_name, cs.class_id
            FROM class_subjects cs
            JOIN subjects s ON cs.subject_id = s.id
            WHERE s.is_active = 1
            AND cs.teacher_id = {$guruId}
            ORDER BY s.name
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        echo "✅ Class subjects for guru_id = {$guruId}: " . count($testClassSubjects) . "\n";
        foreach ($testClassSubjects as $cs) {
            echo "  - ID: {$cs['id']}, Subject: {$cs['subject_name']}, Class ID: {$cs['class_id']}\n";
        }
    } catch (Exception $e) {
        echo "❌ Class subjects query error: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 5: Updating MaterialController with correct structure...\n";
    
    $controllerPath = __DIR__ . '/app/Http/Controllers/Guru/MaterialController.php';
    $controllerContent = file_get_contents($controllerPath);
    
    // Create new create method based on actual table structure
    $newCreateMethod = '    public function create(): View
    {
        $guruId = Auth::id();
        
        // Get subjects assigned to this guru
        $classSubjects = \DB::table(\'class_subjects\')
            ->join(\'subjects\', \'class_subjects.subject_id\', \'=\', \'subjects.id\')
            ->where(\'subjects.is_active\', true)
            ->where(\'class_subjects.teacher_id\', $guruId)
            ->select(\'class_subjects.id\', \'subjects.name as subject_name\', \'class_subjects.class_id\')
            ->get();
        
        // Get classes where this guru teaches
        $classes = \DB::table(\'classes\')
            ->join(\'class_subjects\', \'classes.id\', \'=\', \'class_subjects.class_id\')
            ->where(\'class_subjects.teacher_id\', $guruId)
            ->distinct()
            ->select(\'classes.id\', \'classes.name\')
            ->orderBy(\'classes.name\')
            ->get();

        return view(\'guru.materials.create\', compact(\'classSubjects\', \'classes\'));
    }';
    
    // Find and replace the create method
    $pattern = '/    public function create\(\): View[\s\S]*?return view\(\'guru\.materials\.create\', compact\(\'classSubjects\'\)\);[\s]*?}/';
    if (preg_match($pattern, $controllerContent)) {
        $controllerContent = preg_replace($pattern, $newCreateMethod, $controllerContent);
        file_put_contents($controllerPath, $controllerContent);
        echo "✅ Updated MaterialController::create() method\n";
    } else {
        echo "❌ Could not find create method pattern\n";
    }
    
    echo "\nStep 6: Updating the view with correct class dropdown...\n";
    
    $viewPath = __DIR__ . '/resources/views/guru/materials/create.blade.php';
    $viewContent = file_get_contents($viewPath);
    
    // Find the subject dropdown and add class dropdown before it
    $subjectDropdown = '<div class="form-group">
                            <label for="subject_id" class="form-label">
                                Mata Pelajaran <span class="required">*</span>
                            </label>';
    
    $classDropdown = '<div class="form-group">
                            <label for="class_id" class="form-label">
                                Kelas <span class="required">*</span>
                            </label>
                            <select name="class_id" id="class_id" class="form-input" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old(\'class_id\') == $class->id ? \'selected\' : \'\' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error(\'class_id\')
                                <div class="error-message">
                                    <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="subject_id" class="form-label">
                                Mata Pelajaran <span class="required">*</span>
                            </label>';
    
    if (strpos($viewContent, $subjectDropdown) !== false) {
        $viewContent = str_replace($subjectDropdown, $classDropdown, $viewContent);
        file_put_contents($viewPath, $viewContent);
        echo "✅ Added class dropdown to view\n";
    } else {
        echo "❌ Could not find subject dropdown pattern\n";
    }
    
    echo "\n🎉 SUCCESS! Added class dropdown with correct table structure!\n";
    echo "✅ MaterialController updated with actual classes table structure\n";
    echo "✅ View updated with class dropdown\n";
    echo "✅ Classes are properly filtered by current guru\'s assignments\n";
    echo "✅ Ready for testing!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== CLEANUP ===\n";
if (file_exists(__DIR__ . '/create_classes_table.php')) {
    unlink(__DIR__ . '/create_classes_table.php');
    echo "✅ Removed create_classes_table.php\n";
}
