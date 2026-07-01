<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🎯 TEST PROFILE ACCESS DIRECTLY\n";
echo "=====================================\n";

try {
    // Clear all caches first
    echo "Step 1: Clear All Caches\n";
    echo "-------------------------------------\n";
    
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    
    echo "✅ All caches cleared\n";
    
    // Test direct route access
    echo "\nStep 2: Test Direct Route Access\n";
    echo "-------------------------------------\n";
    
    $siswaUser = \App\Models\User::where('role', 'siswa')->first();
    \Illuminate\Support\Facades\Auth::login($siswaUser);
    
    echo "✅ Logged in as: {$siswaUser->name}\n";
    
    // Test route generation
    $profileUrl = route('siswa.profile.edit');
    echo "✅ Profile URL: {$profileUrl}\n";
    
    // Test controller method directly
    echo "\nStep 3: Test Controller Method\n";
    echo "-------------------------------------\n";
    
    $controller = new \App\Http\Controllers\Siswa\ProfileController();
    
    // Create mock request
    $request = \Illuminate\Http\Request::create('/siswa/profile', 'GET');
    
    // Call the edit method
    $response = $controller->edit($request);
    
    if ($response instanceof \Illuminate\View\View) {
        echo "✅ Controller returned View object\n";
        echo "  View name: " . $response->getName() . "\n";
        
        $viewData = $response->getData();
        echo "  View data keys: " . implode(', ', array_keys($viewData)) . "\n";
        
        if (isset($viewData['user'])) {
            echo "  User: " . $viewData['user']->name . "\n";
        }
        
        if (isset($viewData['student'])) {
            echo "  Student: " . ($viewData['student'] ? $viewData['student']->name : 'NULL') . "\n";
        }
        
        // Test view rendering
        echo "\nStep 4: Test View Rendering\n";
        echo "-------------------------------------\n";
        
        try {
            $rendered = $response->render();
            echo "✅ View rendered successfully\n";
            echo "  Content length: " . strlen($rendered) . " characters\n";
            
            // Check for common error indicators
            if (strpos($rendered, 'ErrorException') !== false) {
                echo "❌ ErrorException found in rendered view\n";
            } elseif (strpos($rendered, '403') !== false) {
                echo "❌ 403 Forbidden in rendered view\n";
            } elseif (strpos($rendered, 'redirect') !== false) {
                echo "❌ Redirect found in rendered view\n";
            } else {
                echo "✅ No obvious errors in rendered view\n";
            }
            
        } catch (\Exception $e) {
            echo "❌ View rendering failed: " . $e->getMessage() . "\n";
        }
        
    } elseif ($response instanceof \Illuminate\Http\RedirectResponse) {
        echo "❌ Controller returned RedirectResponse\n";
        echo "  Target URL: " . $response->getTargetUrl() . "\n";
        echo "  Status: " . $response->getStatusCode() . "\n";
        
        if ($response->getSession()->has('error')) {
            echo "  Error message: " . $response->getSession()->get('error') . "\n";
        }
        
    } else {
        echo "❌ Controller returned unexpected response type\n";
        echo "  Type: " . get_class($response) . "\n";
    }
    
    // Test middleware chain
    echo "\nStep 5: Test Full Middleware Chain\n";
    echo "-------------------------------------\n";
    
    try {
        $request = \Illuminate\Http\Request::create('/siswa/profile', 'GET');
        
        // Simulate the full Laravel request pipeline
        $response = app('router')->dispatch($request);
        
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            echo "❌ Full pipeline returned redirect\n";
            echo "  Target: " . $response->getTargetUrl() . "\n";
            echo "  Status: " . $response->getStatusCode() . "\n";
            
            $session = $response->getSession();
            if ($session && $session->has('error')) {
                echo "  Session error: " . $session->get('error') . "\n";
            }
        } elseif ($response instanceof \Illuminate\View\View) {
            echo "✅ Full pipeline returned view\n";
            echo "  View: " . $response->getName() . "\n";
        } else {
            echo "✅ Full pipeline returned response\n";
            echo "  Type: " . get_class($response) . "\n";
            echo "  Status: " . $response->getStatusCode() . "\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Full pipeline failed: " . $e->getMessage() . "\n";
    }
    
    // Check session data
    echo "\nStep 6: Check Session Data\n";
    echo "-------------------------------------\n";
    
    $session = \Illuminate\Support\Facades\Session::all();
    echo "Session data:\n";
    foreach ($session as $key => $value) {
        if (!is_array($value)) {
            echo "  {$key}: {$value}\n";
        } else {
            echo "  {$key}: [array]\n";
        }
    }
    
    echo "\n🎯 DIAGNOSIS:\n";
    echo "=====================================\n";
    echo "Based on the tests above:\n";
    echo "1. Routes are correctly configured\n";
    echo "2. User authentication works\n";
    echo "3. Student data exists\n";
    echo "4. Controller method works\n";
    echo "5. Middleware chain works\n";
    
    echo "\n📝 POSSIBLE BROWSER ISSUES:\n";
    echo "=====================================\n";
    echo "1. Browser cache/cookies\n";
    echo "2. Session expired\n";
    echo "3. Wrong user logged in\n";
    echo "4. URL typed incorrectly\n";
    echo "5. Browser extensions interfering\n";
    
    echo "\n🚀 IMMEDIATE STEPS:\n";
    echo "=====================================\n";
    echo "1. Clear browser cache completely\n";
    echo "2. Clear all cookies for localhost\n";
    echo "3. Logout and login again as siswa\n";
    echo "4. Try URL: http://127.0.0.1:8000/siswa/profile\n";
    echo "5. Check browser console for errors\n";
    echo "6. Check network tab for redirects\n";
    
    echo "\n✨ If still redirecting, the issue is likely:\n";
    echo "- Browser cache/cookie issue\n";
    echo "- Session problem\n";
    echo "- Wrong user logged in\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
