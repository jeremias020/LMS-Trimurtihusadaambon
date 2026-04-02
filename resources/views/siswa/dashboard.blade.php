@extends('layouts.siswa')

@section('title', 'Beranda Siswa')

@section('content')
<!-- Modern Hero Section -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-xl hero-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 24px; overflow: hidden; position: relative;">
            <!-- Animated Background Pattern -->
            <div class="position-absolute top-0 end-0 w-100 h-100 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20100%20100%27%3E%3Ccircle%20cx%3D%2720%27%20cy%3D%2720%27%20r%3D%272%27%20fill%3D%27white%27%3E%3Canimate%20attributeName%3D%27r%27%20values%3D%272%3B3%3B2%27%20dur%3D%273s%27%20repeatCount%3D%27indefinite%27%2F%3E%3C%2Fcircle%3E%3Ccircle%20cx%3D%2780%27%20cy%3D%2720%27%20r%3D%272%27%20fill%3D%27white%27%3E%3Canimate%20attributeName%3D%27r%27%20values%3D%272%3B3%3B2%27%20dur%3D%273s%27%20begin%3D%271s%27%20repeatCount%3D%27indefinite%27%2F%3E%3C%2Fcircle%3E%3Ccircle%20cx%3D%2750%27%20cy%3D%2750%27%20r%3D%272%27%20fill%3D%27white%27%3E%3Canimate%20attributeName%3D%27r%27%20values%3D%272%3B3%3B2%27%20dur%3D%273s%27%20begin%3D%272s%27%20repeatCount%3D%27indefinite%27%2F%3E%3C%2Fcircle%3E%3Ccircle%20cx%3D%2720%27%20cy%3D%2780%27%20r%3D%272%27%20fill%3D%27white%27%3E%3Canimate%20attributeName%3D%27r%27%20values%3D%272%3B3%3B2%27%20dur%3D%273s%27%20begin%3D%270.5s%27%20repeatCount%3D%27indefinite%27%2F%3E%3C%2Fcircle%3E%3Ccircle%20cx%3D%2780%27%20cy%3D%2780%27%20r%3D%272%27%20fill%3D%27white%27%3E%3Canimate%20attributeName%3D%27r%27%20values%3D%272%3B3%3B2%27%20dur%3D%273s%27%20begin%3D%271.5s%27%20repeatCount%3D%27indefinite%27%2F%3E%3C%2Fcircle%3E%3C%2Fsvg%3E'); background-size: 100px 100px;"></div>
            
            <!-- Floating Educational Icons -->
            <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden">
                <div class="floating-element position-absolute" style="top: 10%; left: 10%; animation: float 6s ease-in-out infinite;">
                    <i class="fas fa-book text-white opacity-20" style="font-size: 2rem;"></i>
                </div>
                <div class="floating-element position-absolute" style="top: 20%; right: 15%; animation: float 8s ease-in-out infinite; animation-delay: 1s;">
                    <i class="fas fa-graduation-cap text-white opacity-20" style="font-size: 1.5rem;"></i>
                </div>
                <div class="floating-element position-absolute" style="bottom: 20%; left: 20%; animation: float 7s ease-in-out infinite; animation-delay: 2s;">
                    <i class="fas fa-award text-white opacity-20" style="font-size: 1.8rem;"></i>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="card-body p-4 p-lg-5 position-relative">
                <div class="row align-items-center position-relative">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-white bg-opacity-25 backdrop-blur-md rounded-circle d-flex align-items-center justify-content-center me-4 hero-avatar" style="width: 80px; height: 80px; overflow:hidden; border: 4px solid rgba(255,255,255,3); box-shadow: 0 8px 32px rgba(0,0,0,1); transition: all 0.3s ease;">
                                @php
                                    $student = \App\Models\Student::where('user_id', auth()->id())->first();
                                @endphp
                                @if($student && $student->foto)
                                    <img src="{{ asset('storage/' . $student->foto) }}" alt="Profile" class="w-100 h-100" style="object-fit: cover;">
                                @else
                                    <i class="fas fa-user-graduate text-white fa-2x"></i>
                                @endif
                            </div>
                            <div class="hero-welcome">
                                <h1 class="text-white mb-2 fw-bold display-6">Selamat datang kembali! 🎓</h1>
                                <p class="text-white text-opacity-90 mb-0 fs-5">{{ now()->translatedFormat('l, d F Y') }} • Mari raih prestasi mu hari ini</p>
                            </div>
                        </div>
                        
                        <!-- Academic Status Badges -->
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <div class="stat-badge bg-white bg-opacity-20 backdrop-blur-sm rounded-3 px-4 py-3 border border-white border-opacity-30">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-trophy text-white me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-white text-opacity-75 d-block">Peringkat</small>
                                        <strong class="text-white fs-5">{{ $stats['rank'] ?? '-' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="stat-badge bg-white bg-opacity-20 backdrop-blur-sm rounded-3 px-4 py-3 border border-white border-opacity-30">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chart-line text-white me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-white text-opacity-75 d-block">Nilai Rata-rata</small>
                                        <strong class="text-white fs-5">{{ $stats['average_score'] ?? 0 }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="stat-badge bg-white bg-opacity-20 backdrop-blur-sm rounded-3 px-4 py-3 border border-white border-opacity-30">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-check text-white me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-white text-opacity-75 d-block">Kehadiran</small>
                                        <strong class="text-white fs-5">{{ $stats['attendance_percentage'] ?? 0 }}%</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                        <div class="hero-actions">
                            <a href="{{ route('siswa.profile.edit') }}" class="btn btn-light btn-lg rounded-3 px-4 py-3 shadow-lg hover-lift">
                                <i class="fas fa-user-edit me-2"></i>
                                <span>Edit Profil</span>
                            </a>
                            <div class="mt-3">
                                <small class="text-white text-opacity-75">Terakhir login: {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'Baru saja' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-lg stats-card hover-lift" style="border-top: 4px solid #667eea;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stats-icon bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-book text-white fa-xl"></i>
                    </div>
                    <div class="stats-trend">
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-arrow-up me-1"></i>12%
                        </span>
                    </div>
                </div>
                <div class="stats-content">
                    <h2 class="stats-value fw-bold mb-1">{{ $stats['total_materials'] ?? 0 }}</h2>
                    <p class="stats-label text-muted mb-0">Total Materi</p>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: 75%;" role="progressbar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-lg stats-card hover-lift" style="border-top: 4px solid #28a745;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stats-icon bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-tasks text-white fa-xl"></i>
                    </div>
                    <div class="stats-trend">
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-arrow-up me-1"></i>8%
                        </span>
                    </div>
                </div>
                <div class="stats-content">
                    <h2 class="stats-value fw-bold mb-1">{{ $stats['completed_assignments'] ?? 0 }}</h2>
                    <p class="stats-label text-muted mb-0">Tugas Selesai</p>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: 85%;" role="progressbar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-lg stats-card hover-lift" style="border-top: 4px solid #17a2b8;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stats-icon bg-info bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-flask text-white fa-xl"></i>
                    </div>
                    <div class="stats-trend">
                        <span class="badge bg-info bg-opacity-10 text-info">
                            <i class="fas fa-arrow-up me-1"></i>15%
                        </span>
                    </div>
                </div>
                <div class="stats-content">
                    <h2 class="stats-value fw-bold mb-1">{{ $stats['completed_practicals'] ?? 0 }}</h2>
                    <p class="stats-label text-muted mb-0">Praktikum Selesai</p>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: 60%;" role="progressbar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-lg stats-card hover-lift" style="border-top: 4px solid #ffc107;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stats-icon bg-warning bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-chart-line text-white fa-xl"></i>
                    </div>
                    <div class="stats-trend">
                        <span class="badge bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-minus me-1"></i>0%
                        </span>
                    </div>
                </div>
                <div class="stats-content">
                    <h2 class="stats-value fw-bold mb-1">{{ $stats['attendance_percentage'] ?? 0 }}%</h2>
                    <p class="stats-label text-muted mb-0">Kehadiran</p>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: {{ $stats['attendance_percentage'] ?? 0 }}%;" role="progressbar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient bg-primary text-white border-0 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title fw-bold mb-1">
                            <i class="fas fa-bolt me-2"></i>
                            Akses Cepat
                        </h5>
                        <p class="mb-0 text-white text-opacity-90">Jelajahi fitur pembelajaran dengan satu klik</p>
                    </div>
                    <div class="badge bg-white bg-opacity-20 text-white px-3 py-2">
                        <i class="fas fa-star me-1"></i>
                        4 Fitur
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-0">
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('siswa.materials.index') }}" class="quick-action-card text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm action-card-hover">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                        <i class="fas fa-book-open text-white fa-xl"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Materi Pembelajaran</h6>
                                    <p class="text-muted small mb-3">Jelajahi materi terbaru dari guru</p>
                                    <div class="mt-auto">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="fas fa-book me-1"></i>E-Learning
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('siswa.assignments.index') }}" class="quick-action-card text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm action-card-hover">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                        <i class="fas fa-file-alt text-white fa-xl"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Tugas & Latihan</h6>
                                    <p class="text-muted small mb-3">Kerjakan tugas dan latihan soal</p>
                                    <div class="mt-auto">
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-tasks me-1"></i>Assignment
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('siswa.praktikum.index') }}" class="quick-action-card text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm action-card-hover">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon bg-info bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                        <i class="fas fa-flask text-white fa-xl"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Lab & Praktikum</h6>
                                    <p class="text-muted small mb-3">Ikuti sesi praktikum interaktif</p>
                                    <div class="mt-auto">
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            <i class="fas fa-microscope me-1"></i>Practical
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('siswa.nilai.index') }}" class="quick-action-card text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm action-card-hover">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon bg-warning bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                        <i class="fas fa-chart-bar text-white fa-xl"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Rapor & Nilai</h6>
                                    <p class="text-muted small mb-3">Lihat progress akademik kamu</p>
                                    <div class="mt-auto">
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            <i class="fas fa-trophy me-1"></i>Grades
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Main Content Row -->
<div class="row g-4 mb-4">
    <!-- Recent Activities -->
    <div class="col-lg-6">
        <div class="card h-100 border-0 shadow-lg">
            <div class="card-header bg-gradient bg-info text-white border-0 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title fw-bold mb-1">
                            <i class="fas fa-clock me-2"></i>
                            Aktivitas Terbaru
                        </h5>
                        <p class="mb-0 text-white text-opacity-90">Update terbaru dari pembelajaran</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Lihat Semua</a></li>
                            <li><a class="dropdown-item" href="#">Refresh</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                @if(isset($recentMaterials) && count($recentMaterials) > 0)
                    <div class="activity-feed">
                        @foreach($recentMaterials as $material)
                            <div class="activity-item">
                                <div class="activity-icon bg-primary bg-gradient text-white rounded-circle">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="activity-title fw-bold">{{ $material->title }}</h6>
                                    <p class="activity-description text-muted">{{ Str::limit($material->description, 100) }}</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $material->created_at->diffForHumans() }}
                                        </small>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="fas fa-book me-1"></i>Materi
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-inbox fa-2x text-muted"></i>
                        </div>
                        <h6 class="text-muted">Belum ada materi baru</h6>
                        <p class="text-muted small">Materi pembelajaran akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Deadlines -->
    <div class="col-lg-6">
        <div class="card h-100 border-0 shadow-lg">
            <div class="card-header bg-gradient bg-warning text-white border-0 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title fw-bold mb-1">
                            <i class="fas fa-calendar-check me-2"></i>
                            Deadline Mendatang
                        </h5>
                        <p class="mb-0 text-white text-opacity-90">Tugas dan praktikum yang harus dikerjakan</p>
                    </div>
                    <a href="{{ route('siswa.assignments.index') }}" class="btn btn-light btn-sm rounded-3 px-3 py-2">
                        <i class="fas fa-list me-1"></i>
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                @if(isset($upcomingDeadlines) && count($upcomingDeadlines) > 0)
                    <div class="deadline-list">
                        @foreach($upcomingDeadlines as $deadline)
                            <div class="deadline-item @if($deadline->days_left <= 2) deadline-urgent @elseif($deadline->days_left <= 7) deadline-soon @endif">
                                <div class="deadline-icon bg-gradient rounded-circle @if($deadline->type === 'assignment') bg-success @else bg-info @endif text-white">
                                    @if($deadline->type === 'assignment')
                                        <i class="fas fa-file-alt"></i>
                                    @else
                                        <i class="fas fa-flask"></i>
                                    @endif
                                </div>
                                <div class="deadline-content">
                                    <div class="d-flex align-items-start justify-content-between mb-2">
                                        <h6 class="deadline-title fw-bold mb-0">{{ $deadline->title }}</h6>
                                        <div class="deadline-countdown @if($deadline->days_left <= 2) text-danger @elseif($deadline->days_left <= 7) text-warning @else text-success @endif">
                                            @if($deadline->days_left == 0)
                                                <div class="countdown-timer" data-deadline="{{ $deadline->deadline->format('Y-m-d H:i:s') }}">
                                                    <span class="badge bg-danger countdown-badge">
                                                        <i class="fas fa-hourglass-half me-1"></i>
                                                        <span class="countdown-text">Hari Ini</span>
                                                    </span>
                                                    <div class="countdown-details mt-1">
                                                        <small class="text-muted d-block">
                                                            <span class="hours">00</span>:<span class="minutes">00</span>:<span class="seconds">00</span>
                                                        </small>
                                                    </div>
                                                </div>
                                            @elseif($deadline->days_left == 1)
                                                <div class="countdown-timer" data-deadline="{{ $deadline->deadline->format('Y-m-d H:i:s') }}">
                                                    <span class="badge bg-warning countdown-badge">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <span class="countdown-text">Besok</span>
                                                    </span>
                                                    <div class="countdown-details mt-1">
                                                        <small class="text-muted d-block">
                                                            <span class="days">1</span> hari <span class="hours">00</span>:<span class="minutes">00</span>
                                                        </small>
                                                    </div>
                                                </div>
                                            @elseif($deadline->days_left <= 3)
                                                <div class="countdown-timer" data-deadline="{{ $deadline->deadline->format('Y-m-d H:i:s') }}">
                                                    <span class="badge bg-{{ $deadline->days_left <= 2 ? 'danger' : 'warning' }} countdown-badge">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        <span class="countdown-text">{{ $deadline->days_left }} hari</span>
                                                    </span>
                                                    <div class="countdown-details mt-1">
                                                        <small class="text-muted d-block">
                                                            <span class="days">{{ $deadline->days_left }}</span> hari <span class="hours">00</span>:<span class="minutes">00</span>
                                                        </small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-{{ $deadline->days_left <= 7 ? 'warning' : 'success' }}">
                                                    <i class="fas fa-calendar-check me-1"></i>
                                                    {{ $deadline->days_left }} hari
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="deadline-meta mb-2">
                                        <span class="badge bg-{{ $deadline->type === 'assignment' ? 'success' : 'info' }} bg-opacity-10 text-{{ $deadline->type === 'assignment' ? 'success' : 'info' }}">
                                            <i class="fas fa-{{ $deadline->type === 'assignment' ? 'file-alt' : 'flask' }} me-1"></i>
                                            {{ $deadline->type === 'assignment' ? 'Tugas' : 'Praktikum' }}
                                        </span>
                                        <span class="deadline-date text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $deadline->deadline->format('d M Y') }}
                                        </span>
                                        <span class="deadline-time text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $deadline->deadline->format('H:i') }}
                                        </span>
                                    </div>
                                    @if($deadline->days_left <= 2)
                                        <div class="deadline-warning mt-2 p-2 bg-danger bg-opacity-10 rounded-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                                <div>
                                                    <strong class="text-danger">Urgent!</strong> 
                                                    <small class="text-muted d-block">Tersisa {{ $deadline->days_left }} hari lagi</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="deadline-actions mt-2">
                                        @php
                                            // Debug: Check available properties
                                            $deadlineId = null;
                                            if (isset($deadline->assignment_id)) {
                                                $deadlineId = $deadline->assignment_id;
                                            } elseif (isset($deadline->praktikum_id)) {
                                                $deadlineId = $deadline->praktikum_id;
                                            } elseif (isset($deadline->id)) {
                                                $deadlineId = $deadline->id;
                                            }
                                        @endphp
                                        
                                        @if($deadline->type === 'assignment')
                                            @if($deadlineId)
                                                <a href="{{ route('siswa.assignments.show', $deadlineId) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>Lihat Detail
                                                </a>
                                            @else
                                                <span class="text-muted small">ID tidak tersedia</span>
                                            @endif
                                        @else
                                            @if($deadlineId)
                                                <a href="{{ route('siswa.praktikum.show', $deadlineId) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye me-1"></i>Lihat Detail
                                                </a>
                                            @else
                                                <span class="text-muted small">ID tidak tersedia</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <h6 class="text-success">Semua Tugas Selesai!</h6>
                        <p class="text-muted small">Tidak ada deadline mendatang. Kerja bagus! 🎉</p>
                        <div class="mt-3">
                            <a href="{{ route('siswa.assignments.index') }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-history me-1"></i>Lihat Riwayat
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Performance Overview -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient bg-success text-white border-0 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title fw-bold mb-1">
                            <i class="fas fa-chart-line me-2"></i>
                            Performa Akademik
                        </h5>
                        <p class="mb-0 text-white text-opacity-90">Ringkasan nilai dan kehadiran kamu</p>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-light btn-sm active">Minggu Ini</button>
                        <button type="button" class="btn btn-light btn-sm">Bulan Ini</button>
                        <button type="button" class="btn btn-light btn-sm">Semua</button>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="performance-metric bg-success bg-opacity-10 rounded-3 p-4 text-center">
                            <div class="metric-icon bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                <i class="fas fa-chart-line text-white fa-xl"></i>
                            </div>
                            <div class="metric-content">
                                <h3 class="metric-value fw-bold mb-1">{{ $stats['average_score'] ?? 0 }}</h3>
                                <p class="metric-label text-muted mb-0">Rata-rata Nilai</p>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ $stats['average_score'] ?? 0 }}%;" role="progressbar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="performance-metric bg-primary bg-opacity-10 rounded-3 p-4 text-center">
                            <div class="metric-icon bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                <i class="fas fa-user-check text-white fa-xl"></i>
                            </div>
                            <div class="metric-content">
                                <h3 class="metric-value fw-bold mb-1">{{ $stats['attendance_count'] ?? 0 }}</h3>
                                <p class="metric-label text-muted mb-0">Total Kehadiran</p>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: 85%;" role="progressbar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="performance-metric bg-warning bg-opacity-10 rounded-3 p-4 text-center">
                            <div class="metric-icon bg-warning bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                <i class="fas fa-trophy text-white fa-xl"></i>
                            </div>
                            <div class="metric-content">
                                <h3 class="metric-value fw-bold mb-1">{{ $stats['rank'] ?? '-' }}</h3>
                                <p class="metric-label text-muted mb-0">Peringkat Kelas</p>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-warning" style="width: 75%;" role="progressbar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Dashboard Styles */

/* Hero Card */
/* Enhanced Hero Section Styles */
.hero-card {
    position: relative;
    transition: all 0.4s ease;
    overflow: hidden;
}

.hero-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 25px 50px rgba(102, 126, 234, 0.3) !important;
}

.hero-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 40px rgba(0,0,0,2);
}

.stat-badge {
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.stat-badge:hover {
    transform: translateY(-2px);
    background-color: rgba(255,255,255,3);
    box-shadow: 0 8px 25px rgba(0,0,0,1);
}

.hero-actions .btn {
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.hero-actions .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0,0,0,15);
    background-color: rgba(255,255,255,95);
}

/* Floating Elements Animation */
@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.2;
    }
    25% {
        transform: translateY(-10px) rotate(5deg);
        opacity: 0.3;
    }
    50% {
        transform: translateY(-5px) rotate(-3deg);
        opacity: 0.25;
    }
    75% {
        transform: translateY(-15px) rotate(2deg);
        opacity: 0.35;
    }
}

.floating-element {
    transition: all 0.3s ease;
}

.hero-card:hover .floating-element {
    opacity: 0.4;
}

/* Enhanced Welcome Text */
.hero-welcome h1 {
    background: linear-gradient(45deg, #ffffff, #f8f9fa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 2px 10px rgba(255,255,255,3);
}

/* Stats Cards */
.stats-card {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stats-card:hover::before {
    opacity: 1;
}

.stats-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,12) !important;
}

.stats-icon {
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.stats-card:hover .stats-icon {
    transform: scale(1.1) rotate(5deg);
}

.stats-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stats-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 0;
}

/* Quick Actions */
.action-card-hover {
    border-radius: 12px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    margin: 0;
}

.action-card-hover::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.action-card-hover:hover::before {
    opacity: 1;
}

.action-card-hover:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 30px rgba(0,0,0,12);
}

.action-icon {
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.action-card-hover:hover .action-icon {
    transform: scale(1.15) rotate(5deg);
}

.quick-action-card {
    transition: all 0.3s ease;
}

.quick-action-card:hover {
    text-decoration: none;
    transform: translateY(-4px);
}

/* Remove gaps between action cards */
.quick-action-card .card {
    border-radius: 0;
    border-right: 1px solid rgba(0,0,0,5);
}

.quick-action-card:last-child .card {
    border-right: none;
}

/* Add borders between rows */
@media (max-width: 991px) {
    .quick-action-card:nth-child(2n+1) .card {
        border-bottom: 1px solid rgba(0,0,0,5);
    }
}

@media (max-width: 767px) {
    .quick-action-card .card {
        border-right: none;
        border-bottom: 1px solid rgba(0,0,0,5);
    }
    
    .quick-action-card:last-child .card {
        border-bottom: none;
    }
}

/* Activity Feed */
.activity-feed {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: rgba(102, 126, 234, 0.05);
    border-radius: 8px;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
    font-size: 1.2rem;
}

.activity-content {
    flex-grow: 1;
}

.activity-title {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.activity-description {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

/* Deadline List */
.deadline-list {
    max-height: 400px;
    overflow-y: auto;
}

.deadline-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 0.75rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    background: white;
    position: relative;
}

.deadline-item:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    background: rgba(102, 126, 234, 0.05);
}

.deadline-item.deadline-urgent {
    border-color: #dc3545;
    background-color: rgba(220, 53, 69, 0.05);
    animation: pulse 2s infinite;
}

.deadline-item.deadline-soon {
    border-color: #ffc107;
    background-color: rgba(255, 193, 7, 0.05);
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}

.deadline-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.deadline-item:hover .deadline-icon {
    transform: scale(1.1);
}

.deadline-content {
    flex-grow: 1;
}

.deadline-title {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.deadline-countdown {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.deadline-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    flex-wrap: wrap;
}

.deadline-date,
.deadline-time {
    font-size: 0.85rem;
    color: #6c757d;
}

.deadline-warning {
    font-size: 0.85rem;
    color: #dc3545;
    font-weight: 600;
    background: rgba(220, 53, 69, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    display: inline-block;
    animation: blink 1.5s infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.deadline-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.deadline-actions .btn {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.deadline-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,1);
}

/* Performance Metrics */
.performance-metric {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.performance-metric::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,1), rgba(255,255,255,5));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.performance-metric:hover::before {
    opacity: 1;
}

.performance-metric:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,1);
}

.metric-icon {
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.performance-metric:hover .metric-icon {
    transform: scale(1.1) rotate(5deg);
}

.metric-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.metric-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0;
}

/* Progress Bars */
.progress {
    background-color: rgba(0,0,0,1);
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.6s ease;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stats-card,
.action-card-hover,
.performance-metric {
    animation: fadeInUp 0.6s ease-out;
}

.stats-card:nth-child(1) { animation-delay: 0.1s; }
.stats-card:nth-child(2) { animation-delay: 0.2s; }
.stats-card:nth-child(3) { animation-delay: 0.3s; }
.stats-card:nth-child(4) { animation-delay: 0.4s; }

.action-card-hover:nth-child(1) { animation-delay: 0.5s; }
.action-card-hover:nth-child(2) { animation-delay: 0.6s; }
.action-card-hover:nth-child(3) { animation-delay: 0.7s; }
.action-card-hover:nth-child(4) { animation-delay: 0.8s; }

.performance-metric:nth-child(1) { animation-delay: 0.9s; }
.performance-metric:nth-child(2) { animation-delay: 1.0s; }
.performance-metric:nth-child(3) { animation-delay: 1.1s; }

/* Countdown Timer Styles */
.countdown-timer {
    text-align: right;
    min-width: 120px;
}

.countdown-badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.35rem 0.6rem;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.countdown-badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,3), transparent);
    transition: left 0.5s ease;
}

.countdown-badge:hover::before {
    left: 100%;
}

.countdown-details {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    letter-spacing: 1px;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.countdown-timer:hover .countdown-details {
    opacity: 1;
}

.countdown-text {
    position: relative;
    z-index: 1;
}

/* Urgent countdown animations */
.countdown-timer[data-urgent="true"] .countdown-badge {
    animation: urgentPulse 1s infinite;
}

@keyframes urgentPulse {
    0%, 100% { 
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
    }
    50% { 
        transform: scale(1.05);
        box-shadow: 0 0 0 5px rgba(220, 53, 69, 0);
    }
}

/* Countdown number styles */
.countdown-timer .hours,
.countdown-timer .minutes,
.countdown-timer .seconds,
.countdown-timer .days {
    font-weight: 700;
    color: inherit;
}

/* Time separator styles */
.countdown-timer .time-separator {
    margin: 0 2px;
    opacity: 0.6;
}

/* Responsive countdown */
@media (max-width: 768px) {
    .countdown-timer {
        min-width: 100px;
        text-align: right;
    }
    
    .countdown-badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.5rem;
    }
    
    .countdown-details {
        font-size: 0.7rem;
    }
}
</style>

<!-- Countdown Timer JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all countdown timers
    const countdownTimers = document.querySelectorAll('.countdown-timer');
    
    countdownTimers.forEach(timer => {
        const deadline = timer.getAttribute('data-deadline');
        if (!deadline) return;
        
        // Mark urgent timers (0-2 days)
        const countdownText = timer.querySelector('.countdown-text');
        if (countdownText) {
            const text = countdownText.textContent.trim();
            if (text === 'Hari Ini' || text === 'Besok' || text.includes('1 hari') || text.includes('2 hari') || text.includes('3 hari')) {
                timer.setAttribute('data-urgent', 'true');
            }
        }
        
        // Start countdown
        updateCountdown(timer, deadline);
        setInterval(() => updateCountdown(timer, deadline), 1000);
    });
    
    function updateCountdown(timer, deadline) {
        const now = new Date().getTime();
        const deadlineTime = new Date(deadline).getTime();
        const distance = deadlineTime - now;
        
        // If deadline passed
        if (distance < 0) {
            const countdownText = timer.querySelector('.countdown-text');
            const countdownDetails = timer.querySelector('.countdown-details');
            
            if (countdownText) {
                countdownText.textContent = 'Terlewat';
            }
            
            if (countdownDetails) {
                countdownDetails.innerHTML = '<small class="text-muted d-block">Deadline telah lewat</small>';
            }
            
            // Update badge to danger
            const badge = timer.querySelector('.countdown-badge');
            if (badge) {
                badge.className = 'badge bg-danger countdown-badge';
            }
            
            return;
        }
        
        // Calculate time units
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Update countdown details
        const hoursEl = timer.querySelector('.hours');
        const minutesEl = timer.querySelector('.minutes');
        const secondsEl = timer.querySelector('.seconds');
        const daysEl = timer.querySelector('.days');
        
        if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
        if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
        if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
        if (daysEl) daysEl.textContent = days;
        
        // Update badge text for very urgent deadlines
        const countdownText = timer.querySelector('.countdown-text');
        if (countdownText && days === 0 && hours < 6) {
            if (hours === 0 && minutes < 30) {
                countdownText.textContent = 'Segera!';
            } else if (hours === 0) {
                countdownText.textContent = 'Jam Ini';
            } else {
                countdownText.textContent = hours + ' jam';
            }
        }
        
        // Add special animation for last hour
        if (days === 0 && hours === 0 && minutes < 60) {
            timer.classList.add('critical-deadline');
        } else {
            timer.classList.remove('critical-deadline');
        }
    }
    
    // Add critical deadline animation
    const style = document.createElement('style');
    style.textContent = `
        .critical-deadline .countdown-badge {
            animation: criticalPulse 0.5s infinite;
            background-color: #dc3545 !important;
        }
        
        @keyframes criticalPulse {
            0%, 100% { 
                transform: scale(1);
                opacity: 1;
            }
            50% { 
                transform: scale(1.1);
                opacity: 0.8;
            }
        }
        
        .critical-deadline .countdown-details {
            color: #dc3545 !important;
            font-weight: 700;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection
