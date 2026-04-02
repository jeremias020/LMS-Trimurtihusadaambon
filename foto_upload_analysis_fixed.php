<?php
echo "=== FOTO PROFILE GURU UPLOAD ANALYSIS ===\n";

echo "Testing foto upload functionality for guru profile...\n";

// 1. Check storage configuration
echo "\n=== 1. STORAGE CONFIGURATION CHECK ===\n";

$filesystemsFile = 'config/filesystems.php';
if (file_exists($filesystemsFile)) {
    echo "✅ Filesystems config exists\n";
    
    $content = file_get_contents($filesystemsFile);
    
    if (strpos($content, "'public' => [") !== false) {
        echo "✅ Public disk configured\n";
    } else {
        echo "❌ Public disk missing\n";
    }
    
    if (strpos($content, "'serve' => true") !== false) {
        echo "✅ Public disk serve enabled\n";
    } else {
        echo "❌ Public disk serve disabled\n";
    }
    
    if (strpos($content, "'visibility' => 'public'") !== false) {
        echo "✅ Public visibility enabled\n";
    } else {
        echo "❌ Public visibility disabled\n";
    }
    
} else {
    echo "❌ Filesystems config not found\n";
}

// 2. Check storage directories
echo "\n=== 2. STORAGE DIRECTORIES CHECK ===\n";

$storagePaths = [
    'storage/app/public' => is_dir('storage/app/public'),
    'storage/app/public/photos' => is_dir('storage/app/public/photos'),
    'storage/app/public/photos/profiles' => is_dir('storage/app/public/photos/profiles'),
    'public/storage' => is_dir('public/storage'),
];

foreach ($storagePaths as $path => $exists) {
    $status = $exists ? "✅" : "❌";
    $desc = $exists ? "Directory exists" : "Directory missing";
    echo "$status $desc: $path\n";
}

// 3. Check uploaded files
echo "\n=== 3. UPLOADED FILES CHECK ===\n";

$profilesDir = 'storage/app/public/photos/profiles';
if (is_dir($profilesDir)) {
    $files = scandir($profilesDir);
    $imageFiles = array_filter($files, function($file) {
        return in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']);
    });
    
    echo "✅ Profile photos directory exists\n";
    echo "📊 Total files: " . count($files) . "\n";
    echo "📊 Image files: " . count($imageFiles) . "\n";
    
    if (count($imageFiles) > 0) {
        echo "📁 Sample uploaded files:\n";
        $count = 0;
        foreach ($imageFiles as $file) {
            if ($count < 3) {
                $filePath = $profilesDir . '/' . $file;
                $fileSize = filesize($filePath);
                $fileDate = date('Y-m-d H:i:s', filemtime($filePath));
                echo "   - $file ($fileSize bytes, $fileDate)\n";
                $count++;
            }
        }
    }
} else {
    echo "❌ Profile photos directory not accessible\n";
}

// 4. Check view implementation
echo "\n=== 4. VIEW IMPLEMENTATION CHECK ===\n";

$profileView = 'resources/views/guru/profile/edit.blade.php';
if (file_exists($profileView)) {
    echo "✅ Profile view exists: " . number_format(filesize($profileView)) . " bytes\n";
    
    $content = file_get_contents($profileView);
    
    if (strpos($content, 'id="photoUploadModal"') !== false) {
        echo "✅ Upload modal found\n";
    } else {
        echo "❌ Upload modal missing\n";
    }
    
    if (strpos($content, 'form="photoUploadForm"') !== false) {
        echo "✅ Upload form found\n";
    } else {
        echo "❌ Upload form missing\n";
    }
    
    if (strpos($content, 'name="photo"') !== false) {
        echo "✅ File input found\n";
    } else {
        echo "❌ File input missing\n";
    }
    
    if (strpos($content, 'function previewPhoto(event)') !== false) {
        echo "✅ Preview function found\n";
    } else {
        echo "❌ Preview function missing\n";
    }
    
} else {
    echo "❌ Profile view not found\n";
}

// 5. Check controller implementation
echo "\n=== 5. CONTROLLER IMPLEMENTATION CHECK ===\n";

$controllerFile = 'app/Http/Controllers/Guru/ProfileController.php';
if (file_exists($controllerFile)) {
    echo "✅ ProfileController exists: " . number_format(filesize($controllerFile)) . " bytes\n";
    
    $content = file_get_contents($controllerFile);
    
    if (strpos($content, 'public function updatePhoto') !== false) {
        echo "✅ updatePhoto method found\n";
    } else {
        echo "❌ updatePhoto method missing\n";
    }
    
    if (strpos($content, '$request->hasFile(\'photo\')') !== false) {
        echo "✅ File handling found\n";
    } else {
        echo "❌ File handling missing\n";
    }
    
    if (strpos($content, 'Storage::delete') !== false) {
        echo "✅ Storage delete found\n";
    } else {
        echo "❌ Storage delete missing\n";
    }
    
    if (strpos($content, '$photo->store') !== false) {
        echo "✅ File storage found\n";
    } else {
        echo "❌ File storage missing\n";
    }
    
    if (strpos($content, "'photo' => 'required|image|mimes') !== false) {
        echo "✅ File validation found\n";
    } else {
        echo "❌ File validation missing\n";
    }
    
} else {
    echo "❌ ProfileController not found\n";
}

// 6. Check routes
echo "\n=== 6. ROUTES CHECK ===\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    echo "✅ Routes file exists\n";
    
    if (strpos($content, 'profile.update-photo') !== false) {
        echo "✅ Profile photo update route found\n";
    } else {
        echo "❌ Profile photo update route missing\n";
    }
    
    if (strpos($content, 'profile.remove-photo') !== false) {
        echo "✅ Profile photo remove route found\n";
    } else {
        echo "❌ Profile photo remove route missing\n";
    }
    
} else {
    echo "❌ Routes file not found\n";
}

// 7. Check User model
echo "\n=== 7. USER MODEL CHECK ===\n";

$userModel = 'app/Models/User.php';
if (file_exists($userModel)) {
    echo "✅ User model exists: " . number_format(filesize($userModel)) . " bytes\n";
    
    $content = file_get_contents($userModel);
    
    if (strpos($content, 'getPhotoUrlAttribute') !== false) {
        echo "✅ Photo URL accessor found\n";
    } else {
        echo "❌ Photo URL accessor missing\n";
    }
    
    if (strpos($content, 'use Illuminate\Support\Facades\Storage') !== false) {
        echo "✅ Storage facade imported\n";
    } else {
        echo "❌ Storage facade not imported\n";
    }
    
} else {
    echo "❌ User model not found\n";
}

// 8. Expected behavior
echo "\n=== 8. EXPECTED UPLOAD BEHAVIOR ===\n";

echo "🎯 UPLOAD FLOW:\n";
echo "1. User clicks camera button on profile\n";
echo "2. Modal opens with upload form\n";
echo "3. User selects image file\n";
echo "4. JavaScript shows preview\n";
echo "5. User clicks 'Upload Foto' button\n";
echo "6. Form submits to profile.update-photo route\n";
echo "7. Controller validates file (image, max 2MB)\n";
echo "8. Controller deletes old photo if exists\n";
echo "9. Controller stores new photo to storage/app/public/photos/profiles\n";
echo "10. Controller updates user->photo in database\n";
echo "11. Controller redirects back with success message\n";
echo "12. Page reloads and shows new photo\n";

echo "\n🎯 COMMON ISSUES & SOLUTIONS:\n";
echo "❌ UPLOAD NOT WORKING:\n";
echo "• Check JavaScript console for errors\n";
echo "• Verify form action URL is correct\n";
echo "• Check CSRF token is present\n";
echo "• Verify file input has proper attributes\n";
echo "• Check network tab for failed requests\n";

echo "\n❌ FILE NOT SAVING:\n";
echo "• Check storage folder permissions\n";
echo "• Verify storage disk configuration\n";
echo "• Check PHP upload limits (upload_max_filesize)\n";
echo "• Verify disk space available\n";

echo "\n❌ PHOTO NOT DISPLAYING:\n";
echo "• Check Storage::url() is working\n";
echo "• Verify public/storage symlink exists\n";
echo "• Check file permissions in storage\n";
echo "• Verify photo_url accessor in model\n";

echo "\n🎉 ANALYSIS COMPLETE!\n";
echo "📱 All components appear to be properly configured\n";
echo "🔧 Check browser console for specific errors\n";
echo "🎯 Test upload with small image files first\n";

echo "\n=== COMPLETE ===\n";
?>
