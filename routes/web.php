<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\FileController;

// ✅ USE STATEMENTS UNTUK CONTROLLERS
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\AssignmentController as AdminAssignmentController;
use App\Http\Controllers\Admin\PracticalController as AdminPracticalController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\PracticeScheduleController as AdminPracticeScheduleController;
use App\Http\Controllers\Admin\BackupController as AdminBackupController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\KriteriaPenilaianController;

use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\MaterialController as GuruMaterialController;
use App\Http\Controllers\Guru\AssignmentController as GuruAssignmentController;
use App\Http\Controllers\Guru\PracticalController as GuruPracticalController;
use App\Http\Controllers\Guru\AttendanceController as GuruAttendanceController;
use App\Http\Controllers\Guru\ScoringController as GuruScoringController;
use App\Http\Controllers\Guru\ReportController as GuruReportController;
use App\Http\Controllers\Guru\ProfileController as GuruProfileController;
use App\Http\Controllers\Guru\SubmissionController as GuruSubmissionController;
use App\Http\Controllers\Guru\PenilaianController as GuruPenilaianController;

use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\MaterialController as SiswaMaterialController;
use App\Http\Controllers\Siswa\AssignmentController as SiswaAssignmentController;
use App\Http\Controllers\Siswa\PracticalController as SiswaPracticalController;
use App\Http\Controllers\Siswa\ScoreController as SiswaScoreController;
use App\Http\Controllers\Siswa\AttendanceController as SiswaAttendanceController;
use App\Http\Controllers\Siswa\ProfileController as SiswaProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact.send');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard')->middleware('auth');

// Route untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Logout harus tersedia untuk semua yang terautentikasi
Route::post('logout', [LoginController::class, 'logout'])
    ->middleware('web')
    ->name('logout');

// ✅ ADMIN ROUTES - middleware digabung dalam array
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminUserController::class, 'index'])->name('dashboard');
    
    // Kelola Data User
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.status');
    Route::post('users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('users.bulk-action');
    
    // Kelola Kelas
    Route::resource('kelas', KelasController::class);
    
    // Kelola Jurusan
    Route::resource('jurusan', SubjectController::class);
    
    // Kelola Kriteria Penilaian Praktik
    Route::resource('kriteria-penilaian', KriteriaPenilaianController::class);
    
    // Kelola Jadwal Ujian dengan Notifikasi
    Route::resource('jadwal-ujian', AdminPracticeScheduleController::class);
    Route::post('jadwal-ujian/bulk-action', [AdminPracticeScheduleController::class, 'bulkAction'])->name('jadwal-ujian.bulk-action');
    Route::get('jadwal-ujian/upcoming/api', [AdminPracticeScheduleController::class, 'upcoming'])->name('jadwal-ujian.upcoming');
    Route::post('jadwal-ujian/{jadwal}/send-notification', [AdminPracticeScheduleController::class, 'sendNotification'])->name('jadwal-ujian.send-notification');
    
    // Profile
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
});

// ✅ GURU ROUTES - middleware digabung dalam array
Route::prefix('guru')->name('guru.')->middleware(['auth', 'guru'])->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
    
    // Materials Management
    Route::resource('materials', GuruMaterialController::class);
    Route::get('materials/{material}/download', [GuruMaterialController::class, 'download'])->name('materials.download');
    Route::post('materials/{material}/publish', [GuruMaterialController::class, 'publish'])->name('materials.publish');
    Route::post('materials/{material}/unpublish', [GuruMaterialController::class, 'unpublish'])->name('materials.unpublish');
    Route::post('materials/{material}/toggle-publish', [GuruMaterialController::class, 'togglePublish'])->name('materials.toggle-publish');
    Route::post('materials/bulk-delete', [GuruMaterialController::class, 'bulkDelete'])->name('materials.bulk-delete');
    Route::post('materials/bulk-publish', [GuruMaterialController::class, 'bulkPublish'])->name('materials.bulk-publish');
    Route::post('materials/bulk-unpublish', [GuruMaterialController::class, 'bulkUnpublish'])->name('materials.bulk-unpublish');
    
    // Assignments Management
    Route::resource('assignments', GuruAssignmentController::class);
    Route::get('assignments/{assignment}/submissions', [GuruAssignmentController::class, 'submissions'])->name('assignments.submissions');
    Route::post('assignments/{assignment}/submissions/{submission}/grade', [GuruAssignmentController::class, 'grade'])->name('assignments.grade');
    
    // Practicals Management
    Route::resource('practicals', GuruPracticalController::class);
    Route::get('practicals/{praktikum}/score', [GuruPracticalController::class, 'showScoringForm'])->name('practicals.score');
    Route::get('practicals/{practical}/scores', [GuruPracticalController::class, 'scores'])->name('practicals.scores');
    Route::post('practicals/{practical}/scores', [GuruPracticalController::class, 'storeScores'])->name('practicals.store-scores');
    
    // Praktikum Management (Alias routes for backward compatibility)
    Route::resource('praktikum', GuruPracticalController::class)->names([
        'index' => 'praktikum.index',
        'create' => 'praktikum.create', 
        'store' => 'praktikum.store',
        'show' => 'praktikum.show',
        'edit' => 'praktikum.edit',
        'update' => 'praktikum.update',
        'destroy' => 'praktikum.destroy'
    ]);
    Route::post('praktikum/{practical}/complete', [GuruPracticalController::class, 'complete'])->name('praktikum.complete');
    
    // Attendance Management
    Route::resource('attendance', GuruAttendanceController::class);
    Route::post('attendance/bulk', [GuruAttendanceController::class, 'bulkStore'])->name('attendance.bulk');
    Route::get('attendance/report', [GuruAttendanceController::class, 'report'])->name('attendance.report');
    
    // Absensi Management (Alias routes for backward compatibility)
    Route::resource('absensi', GuruAttendanceController::class)->names([
        'index' => 'absensi.index',
        'create' => 'absensi.create', 
        'store' => 'absensi.store',
        'show' => 'absensi.show',
        'edit' => 'absensi.edit',
        'update' => 'absensi.update',
        'destroy' => 'absensi.destroy'
    ]);
    Route::post('absensi/bulk', [GuruAttendanceController::class, 'bulkStore'])->name('absensi.bulk');
    Route::get('absensi/report', [GuruAttendanceController::class, 'report'])->name('absensi.report');
    
    // Praktik attendance routes
    Route::get('absensi/praktik', [GuruAttendanceController::class, 'praktikAttendance'])->name('absensi.praktik');
    Route::post('absensi/praktik/batch', [GuruAttendanceController::class, 'storePraktikBatch'])->name('absensi.praktik.batch');
    
    // Submissions Management
    Route::get('submissions', [GuruSubmissionController::class, 'index'])->name('submissions');
    Route::get('submissions/{submission}', [GuruSubmissionController::class, 'show'])->name('submissions.show');
    
    // Penilaian (Grading) Management
    Route::get('penilaian', [GuruPenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('penilaian/create', [GuruPenilaianController::class, 'create'])->name('penilaian.create');
    Route::post('penilaian', [GuruPenilaianController::class, 'store'])->name('penilaian.store');
    Route::get('penilaian/{submission}/edit', [GuruPenilaianController::class, 'edit'])->name('penilaian.edit');
    Route::put('penilaian/{submission}', [GuruPenilaianController::class, 'update'])->name('penilaian.update');
    Route::delete('penilaian/{submission}', [GuruPenilaianController::class, 'destroy'])->name('penilaian.destroy');
    
    // Scoring Management
    Route::resource('scoring', GuruScoringController::class);
    Route::post('scoring/{practical}/{siswa}/auto', [GuruScoringController::class, 'generateAutoScore'])->name('scoring.auto');
    Route::get('scoring/students/{student}', [GuruScoringController::class, 'studentScores'])->name('scoring.student');
    
    // Reports
    Route::get('reports', [GuruReportController::class, 'index'])->name('reports.index');
    Route::get('reports/attendance', [GuruReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('reports/practical', [GuruReportController::class, 'practical'])->name('reports.practical');
    Route::post('reports/generate', [GuruReportController::class, 'generate'])->name('reports.generate');
    
    // Laporan (Report) routes with Indonesian naming
    Route::get('laporan', [GuruReportController::class, 'index'])->name('laporan.index');
    
    // Absensi (Attendance) Reports
    Route::get('laporan/absensi', [GuruReportController::class, 'absensi'])->name('laporan.absensi');
    Route::get('laporan/absensi/bulanan', [GuruReportController::class, 'absensi'])->name('laporan.absensi.bulanan');
    Route::get('laporan/absensi/semester', [GuruReportController::class, 'absensi'])->name('laporan.absensi.semester');
    
    // Praktik (Practical) Reports
    Route::get('laporan/praktik', [GuruReportController::class, 'praktik'])->name('laporan.praktik');
    Route::get('laporan/praktik/nilai', [GuruReportController::class, 'praktik'])->name('laporan.praktik.nilai');
    Route::get('laporan/praktik/peserta', [GuruReportController::class, 'praktik'])->name('laporan.praktik.peserta');
    
    // Tugas (Assignment) Reports
    Route::get('laporan/tugas', [GuruReportController::class, 'tugas'])->name('laporan.tugas');
    Route::get('laporan/tugas/nilai', [GuruReportController::class, 'tugas'])->name('laporan.tugas.nilai');
    Route::get('laporan/tugas/terlambat', [GuruReportController::class, 'tugas'])->name('laporan.tugas.terlambat');
    
    // Nilai (Grade) Reports
    Route::get('laporan/nilai', [GuruReportController::class, 'absensi'])->name('laporan.nilai');
    Route::get('laporan/nilai/mid', [GuruReportController::class, 'absensi'])->name('laporan.nilai.mid');
    Route::get('laporan/nilai/semester', [GuruReportController::class, 'absensi'])->name('laporan.nilai.semester');
    
    // Siswa (Student) Reports
    Route::get('laporan/siswa', [GuruReportController::class, 'absensi'])->name('laporan.siswa');
    Route::get('laporan/siswa/detail', [GuruReportController::class, 'absensi'])->name('laporan.siswa.detail');
    Route::get('laporan/siswa/prestasi', [GuruReportController::class, 'absensi'])->name('laporan.siswa.prestasi');
    
    // Material Reports
    Route::get('laporan/materi', [GuruReportController::class, 'materi'])->name('laporan.materi');
    
    // Export and Generate
    Route::post('laporan/generate', [GuruReportController::class, 'generate'])->name('laporan.generate');
    
    // Profile
    Route::get('profile', [GuruProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [GuruProfileController::class, 'update'])->name('profile.update');
});

// ✅ SISWA ROUTES - middleware digabung dalam array
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'siswa'])->group(function () {
    // Dashboard Siswa
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    
    // Materi - Siswa dapat melihat dan mengunduh materi
    Route::get('materials', [SiswaMaterialController::class, 'index'])->name('materials.index');
    Route::get('materials/{material}', [SiswaMaterialController::class, 'show'])->name('materials.show');
    Route::get('materials/{material}/download', [SiswaMaterialController::class, 'download'])->name('materials.download');
    Route::post('materials/{material}/track-download', [SiswaMaterialController::class, 'trackDownload'])->name('materials.track-download');
    Route::get('materials/search', [SiswaMaterialController::class, 'search'])->name('materials.search');
    
    // Soal/Quiz - Siswa dapat mengakses, mengunduh, dan mengirimkan tugas
    Route::get('assignments', [SiswaAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('assignments/{assignment}', [SiswaAssignmentController::class, 'show'])->name('assignments.show');
    Route::post('assignments/{assignment}/submit', [SiswaAssignmentController::class, 'submit'])->name('assignments.submit');
    Route::get('assignments/{assignment}/submission', [SiswaAssignmentController::class, 'submission'])->name('assignments.submission');
    
    // Laporan Nilai - Siswa dapat melihat nilai praktikum dan absen
    Route::get('reports', [SiswaScoreController::class, 'index'])->name('reports.index');
    Route::get('reports/praktikum', [SiswaScoreController::class, 'practical'])->name('reports.practical');
    Route::get('reports/attendance', [SiswaAttendanceController::class, 'index'])->name('reports.attendance');
    
    // Kelola Profil Siswa
    Route::get('profile', [SiswaProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [SiswaProfileController::class, 'update'])->name('profile.update');
});

// File Download Routes
Route::get('storage/{folder}/{filename}', [FileController::class, 'downloadFile'])
    ->where('filename', '.*')
    ->name('storage.file');

// Test routes untuk debugging - hanya di development
if (config('app.debug')) {
    require __DIR__ . '/test.php';
}

// Fallback Route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
