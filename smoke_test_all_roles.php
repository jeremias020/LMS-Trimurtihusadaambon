<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 SMOKE TEST - ROLE SISWA\n";
echo "=====================================\n";

$siswaTests = [
    'Dashboard' => 'siswa.dashboard',
    'Pelajaran' => 'siswa.pelajaran.index',
    'Materi' => 'siswa.materials.index',
    'Tugas' => 'siswa.assignments.index',
    'Praktikum' => 'siswa.praktikum.index',
    'Absensi' => 'siswa.absensi.index',
    'Nilai' => 'siswa.nilai.index',
    'Profile Edit' => 'siswa.profile.edit',
    'Reports' => 'siswa.reports.index',
];

$totalTests = count($siswaTests);
$passedTests = 0;
$failedTests = 0;

foreach ($siswaTests as $name => $route) {
    echo "\nTesting: {$name} ({$route})\n";
    try {
        $url = route($route);
        echo "  ✅ Route exists: {$url}";
        $passedTests++;
    } catch (\Exception $e) {
        echo "  ❌ Route failed: " . $e->getMessage();
        $failedTests++;
    }
}

echo "\n\n📊 SISWA SMOKE TEST RESULTS:\n";
echo "=====================================\n";
echo "Total Tests: {$totalTests}\n";
echo "Passed: ✅ {$passedTests}\n";
echo "Failed: ❌ {$failedTests}\n";
echo "Success Rate: " . round(($passedTests / $totalTests) * 100, 2) . "%\n";

echo "\n🔍 SMOKE TEST - ROLE GURU\n";
echo "=====================================\n";

$guruTests = [
    'Dashboard' => 'guru.dashboard',
    'Materi' => 'guru.materials.index',
    'Tugas' => 'guru.assignments.index',
    'Praktikum' => 'guru.practicals.index',
    'Penilaian' => 'guru.penilaian.index',
    'Laporan' => 'guru.laporan.index',
    'Profile Edit' => 'guru.profile.edit',
    'Submissions' => 'guru.submissions.index',
];

$totalGuruTests = count($guruTests);
$passedGuruTests = 0;
$failedGuruTests = 0;

foreach ($guruTests as $name => $route) {
    echo "\nTesting: {$name} ({$route})\n";
    try {
        $url = route($route);
        echo "  ✅ Route exists: {$url}";
        $passedGuruTests++;
    } catch (\Exception $e) {
        echo "  ❌ Route failed: " . $e->getMessage();
        $failedGuruTests++;
    }
}

echo "\n\n📊 GURU SMOKE TEST RESULTS:\n";
echo "=====================================\n";
echo "Total Tests: {$totalGuruTests}\n";
echo "Passed: ✅ {$passedGuruTests}\n";
echo "Failed: ❌ {$failedGuruTests}\n";
echo "Success Rate: " . round(($passedGuruTests / $totalGuruTests) * 100, 2) . "%\n";

echo "\n🔍 SMOKE TEST - ROLE ADMIN\n";
echo "=====================================\n";

$adminTests = [
    'Dashboard' => 'admin.dashboard',
    'Users' => 'admin.users.index',
    'Materi' => 'admin.materials.index',
    'Tugas' => 'admin.assignments.index',
    'Praktikum' => 'admin.practicals.index',
    'Absensi' => 'admin.attendance.index',
    'Settings' => 'admin.settings.index',
    'Reports' => 'admin.reports.index',
    'Kelas' => 'admin.kelas.index',
    'Jurusan' => 'admin.jurusan.index',
    'Mata Pelajaran' => 'admin.mata-pelajaran.index',
    'Profile Edit' => 'admin.profile.edit',
];

$totalAdminTests = count($adminTests);
$passedAdminTests = 0;
$failedAdminTests = 0;

foreach ($adminTests as $name => $route) {
    echo "\nTesting: {$name} ({$route})\n";
    try {
        $url = route($route);
        echo "  ✅ Route exists: {$url}";
        $passedAdminTests++;
    } catch (\Exception $e) {
        echo "  ❌ Route failed: " . $e->getMessage();
        $failedAdminTests++;
    }
}

echo "\n\n📊 ADMIN SMOKE TEST RESULTS:\n";
echo "=====================================\n";
echo "Total Tests: {$totalAdminTests}\n";
echo "Passed: ✅ {$passedAdminTests}\n";
echo "Failed: ❌ {$failedAdminTests}\n";
echo "Success Rate: " . round(($passedAdminTests / $totalAdminTests) * 100, 2) . "%\n";

echo "\n\n🎯 OVERALL SMOKE TEST SUMMARY:\n";
echo "=====================================\n";
$totalOverallTests = $totalTests + $totalGuruTests + $totalAdminTests;
$totalOverallPassed = $passedTests + $passedGuruTests + $passedAdminTests;
$totalOverallFailed = $failedTests + $failedGuruTests + $failedAdminTests;

echo "Total Tests: {$totalOverallTests}\n";
echo "Total Passed: ✅ {$totalOverallPassed}\n";
echo "Total Failed: ❌ {$totalOverallFailed}\n";
echo "Overall Success Rate: " . round(($totalOverallPassed / $totalOverallTests) * 100, 2) . "%\n";

if ($totalOverallFailed > 0) {
    echo "\n⚠️  ISSUES FOUND - NEED ATTENTION\n";
    echo "=====================================\n";
    if ($failedTests > 0) echo "- Siswa: {$failedTests} failed routes\n";
    if ($failedGuruTests > 0) echo "- Guru: {$failedGuruTests} failed routes\n";
    if ($failedAdminTests > 0) echo "- Admin: {$failedAdminTests} failed routes\n";
} else {
    echo "\n✅ ALL SMOKE TESTS PASSED!\n";
    echo "=====================================\n";
    echo "All routes are working correctly.\n";
}
?>
