<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG PROFILE REDIRECT ISSUE\n";
echo "=====================================\n";

try {
    // Test 1: Check Routes
    echo "Step 1: Check Profile Routes\n";
    echo "-------------------------------------\n";
    
    $routeCollection = \Illuminate\Support\Facades\Route::getRoutes();
    $profileRoutes = [];
    
    foreach ($routeCollection as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'siswa/profile') !== false) {
            $profileRoutes[] = [
                'method' => implode('|', $route->methods()),
                'uri' => $uri,
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => $route->middleware()
            ];
        }
    }
    
    echo "Profile routes found:\n";
    foreach ($profileRoutes as $route) {
        echo "  - {$route['method']} {$route['uri']}\n";
        echo "    Name: {$route['name']}\n";
        echo "    Action: {$route['action']}\n";
        echo "    Middleware: " . implode(', ', $route['middleware']) . "\n\n";
    }
    
    // Test 2: Check User Authentication
    echo "Step 2: Test User Authentication\n";
    echo "-------------------------------------\n";
    
    $siswaUser = \App\Models\User::where('role', 'siswa')->first();
    if (!$siswaUser) {
        echo "❌ No siswa user found\n";
        return;
    }
    
    echo "✅ Siswa user: {$siswaUser->name} (ID: {$siswaUser->id})\n";
    echo "  Email: {$siswaUser->email}\n";
    echo "  Role: {$siswaUser->role}\n";
    
    // Test 3: Check Student Data
    echo "\nStep 3: Check Student Data\n";
    echo "-------------------------------------\n";
    
    $student = \App\Models\Student::where('id', $siswaUser->id)->first();
    if (!$student) {
        echo "❌ Student data NOT found for user ID {$siswaUser->id}\n";
        echo "This is likely the cause of redirect!\n";
    } else {
        echo "✅ Student data found: {$student->name}\n";
        echo "  NISN: " . ($student->nisn ?: 'NULL') . "\n";
        echo "  Kelas ID: " . ($student->kelas_id ?: 'NULL') . "\n";
        echo "  Kelas: " . ($student->kelas ? $student->kelas->name : 'No Class') . "\n";
    }
    
    // Test 4: Simulate ProfileController::edit()
    echo "\nStep 4: Simulate ProfileController::edit()\n";
    echo "-------------------------------------\n";
    
    try {
        // Simulate login
        \Illuminate\Support\Facades\Auth::login($siswaUser);
        echo "✅ User authenticated\n";
        
        // Check role validation
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'siswa') {
            echo "❌ Role validation failed - not siswa\n";
        } else {
            echo "✅ Role validation passed - siswa\n";
        }
        
        // Check student data validation
        $studentForProfile = \App\Models\Student::with('kelas')->where('id', $siswaUser->id)->first();
        if (!$studentForProfile) {
            echo "❌ Student validation failed - no student data\n";
            echo "This will cause redirect to dashboard with error\n";
        } else {
            echo "✅ Student validation passed\n";
            echo "  Student: {$studentForProfile->name}\n";
            echo "  Kelas: " . ($studentForProfile->kelas ? $studentForProfile->kelas->name : 'No Class') . "\n";
        }
        
        // Test view data preparation
        if ($studentForProfile) {
            $viewData = [
                'user' => $siswaUser,
                'student' => $studentForProfile
            ];
            echo "✅ View data prepared successfully\n";
            echo "  User: {$viewData['user']->name}\n";
            echo "  Student: " . ($viewData['student'] ? $viewData['student']->name : 'NULL') . "\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Controller simulation failed: " . $e->getMessage() . "\n";
    }
    
    // Test 5: Check Middleware
    echo "\nStep 5: Test Middleware Chain\n";
    echo "-------------------------------------\n";
    
    try {
        $middleware = new \App\Http\Middleware\SiswaMiddleware();
        $request = new \Illuminate\Http\Request();
        
        // Test with authenticated user
        \Illuminate\Support\Facades\Auth::login($siswaUser);
        
        $result = $middleware->handle($request, function($req) {
            return response('Middleware passed', 200);
        });
        
        if ($result->getStatusCode() === 200) {
            echo "✅ SiswaMiddleware passed\n";
        } else {
            echo "❌ SiswaMiddleware failed\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Middleware test failed: " . $e->getMessage() . "\n";
    }
    
    // Test 6: Check Database Connection
    echo "\nStep 6: Check Database Connection\n";
    echo "-------------------------------------\n";
    
    try {
        $users = \App\Models\User::count();
        $students = \App\Models\Student::count();
        
        echo "✅ Database connection OK\n";
        echo "  Users: {$users}\n";
        echo "  Students: {$students}\n";
        
        // Check if student ID matches user ID
        $studentWithUserId = \App\Models\Student::where('id', $siswaUser->id)->first();
        if ($studentWithUserId) {
            echo "✅ Student ID {$siswaUser->id} exists in students table\n";
        } else {
            echo "❌ Student ID {$siswaUser->id} NOT found in students table\n";
            echo "This is the ROOT CAUSE of the redirect!\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎯 ROOT CAUSE ANALYSIS:\n";
    echo "=====================================\n";
    
    if (!$student) {
        echo "🔴 PRIMARY ISSUE: No student data found for user ID {$siswaUser->id}\n";
        echo "   ProfileController redirects to dashboard when student data is missing\n";
        echo "   This is a safety measure to prevent errors\n";
    } else {
        echo "✅ Student data exists - checking other issues...\n";
    }
    
    echo "\n📝 POSSIBLE SOLUTIONS:\n";
    echo "=====================================\n";
    echo "1. Create missing student data:\n";
    echo "   php artisan tinker\n";
    echo "   \$user = User::find(1);\n";
    echo "   \$student = new Student();\n";
    echo "   \$student->id = \$user->id;\n";
    echo "   \$student->name = \$user->name;\n";
    echo "   \$student->email = \$user->email;\n";
    echo "   \$student->kelas_id = 1;\n";
    echo "   \$student->save();\n";
    echo "\n";
    echo "2. Run student seeder:\n";
    echo "   php artisan db:seed --class=StudentSeeder\n";
    echo "\n";
    echo "3. Check if user_id matches student_id in database\n";
    echo "   The issue might be ID mismatch\n";
    
    echo "\n🚀 IMMEDIATE FIX:\n";
    echo "=====================================\n";
    echo "Create missing student data for the siswa user:\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
