@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('content')
<!-- Hero Section -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg hero-card bg-gradient-primary">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between py-4 px-4">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <div class="hero-avatar bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-4" style="width:64px;height:64px;">
                        <i class="fas fa-chalkboard-teacher text-white fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="mb-2 text-white fw-bold">Selamat datang, {{ auth()->user()->name ?? 'Guru' }}! 👋</h2>
                        <p class="text-white-50 mb-0">{{ now()->translatedFormat('l, d F Y') }} • Siap untuk menginspirasi hari ini</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @if (Route::has('guru.praktikum.create'))
                        <a href="{{ route('guru.praktikum.create') }}" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-flask me-2"></i>Praktikum Baru
                        </a>
                    @endif
                    <div class="text-white">
                        <small class="d-block text-white-50">Status</small>
                        <span class="badge bg-success bg-opacity-25 text-white px-3 py-2">
                            <i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i>
                            Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stats-icon bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-book text-primary fa-2x"></i>
                        </div>
                        <div class="stats-trend">
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-arrow-up me-1"></i>12%
                            </span>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-2">{{ $stats['total_materials'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Total Materi</p>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stats-icon bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-tasks text-success fa-2x"></i>
                        </div>
                        <div class="stats-trend">
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-arrow-up me-1"></i>8%
                            </span>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-2">{{ $stats['total_assignments'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Total Tugas</p>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stats-icon bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-users text-info fa-2x"></i>
                        </div>
                        <div class="stats-trend">
                            <span class="badge bg-info bg-opacity-10 text-info">
                                <i class="fas fa-minus me-1"></i>0%
                            </span>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-2">{{ $stats['total_students'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Total Siswa</p>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: 90%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stats-icon bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-chart-line text-warning fa-2x"></i>
                        </div>
                        <div class="stats-trend">
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-arrow-up me-1"></i>15%
                            </span>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-2">95%</h3>
                    <p class="text-muted mb-0">Kehadiran</p>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: 95%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient-primary text-white border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title fw-bold mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Aksi Cepat
                        </h5>
                        <p class="text-white-50 small mb-0">Buat konten pembelajaran baru dengan cepat</p>
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
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('guru.absensi.create') }}" class="quick-action-card text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm action-card-hover">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon bg-danger bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                        <i class="fas fa-clipboard-check text-white fa-xl"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Input Absensi</h6>
                                    <p class="text-muted small mb-0">Catat kehadiran siswa dengan cepat</p>
                                    <div class="mt-3">
                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                            <i class="fas fa-clock me-1"></i>Daily
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @if (Route::has('guru.praktikum.create'))
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('guru.praktikum.create') }}" class="quick-action-card text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm action-card-hover">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon bg-warning bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                        <i class="fas fa-flask text-dark fa-xl"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Praktikum Baru</h6>
                                    <p class="text-muted small mb-0">Buat sesi praktikum interaktif</p>
                                    <div class="mt-3">
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            <i class="fas fa-star me-1"></i>Interactive
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('guru.materials.create') }}" class="quick-action-card text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm action-card-hover">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                        <i class="fas fa-book-open text-white fa-xl"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Upload Materi</h6>
                                    <p class="text-muted small mb-0">Bagikan materi pembelajaran</p>
                                    <div class="mt-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="fas fa-file me-1"></i>Document
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('guru.assignments.create') }}" class="quick-action-card text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm action-card-hover">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                        <i class="fas fa-tasks text-white fa-xl"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Buat Tugas</h6>
                                    <p class="text-muted small mb-0">Buat tugas untuk siswa</p>
                                    <div class="mt-3">
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-pencil me-1"></i>Assignment
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

<!-- Main Content Row -->
<div class="row g-4 mb-4">
    <!-- Recent Activities -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title fw-bold mb-0">
                            <i class="fas fa-clock text-primary me-2"></i>
                            Aktivitas Terbaru
                        </h5>
                        <p class="text-muted small mb-0">Update terbaru dari kegiatan pembelajaran</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Lihat Semua</a></li>
                            <li><a class="dropdown-item" href="#">Refresh</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="activities-container">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom border-light">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-bolt text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-medium text-dark mb-1">{{ $activity->description ?? 'Aktivitas tidak tersedia' }}</div>
                            <small class="text-muted">{{ $activity->created_at ? $activity->created_at->diffForHumans() : 'Waktu tidak tersedia' }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Tidak ada aktivitas terbaru</p>
                        <small class="text-muted">Aktivitas akan muncul saat Anda mulai menggunakan sistem</small>
                    </div>
                    @endforelse
                </div>
                @if(isset($recentActivities) && count($recentActivities) > 0)
                <div class="text-center mt-3 pt-3 border-top border-light">
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i>
                        Lihat Semua Aktivitas
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Pending Grading -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title fw-bold mb-0">
                            <i class="fas fa-star text-warning me-2"></i>
                            Perlu Dinilai
                        </h5>
                        <p class="text-muted small mb-0">Tugas dan praktikum yang menunggu penilaian</p>
                    </div>
                    <a href="{{ route('guru.penilaian.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i>
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body">
                @forelse($recentSubmissions ?? [] as $submission)
                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom border-light">
                    <div class="d-flex align-items-center">
                        <img src="{{ $submission->student->avatar_url ?? asset('images/default-avatar.png') }}" 
                             alt="Student" 
                             class="rounded-circle me-3" 
                             style="width: 40px; height: 40px; object-fit: cover;">
                        <div>
                            <div class="fw-medium text-dark mb-1">{{ $submission->student->name ?? 'Nama Siswa' }}</div>
                            <div class="small text-muted">
                                {{ $submission->assignment->title ?? ($submission->practical->title ?? 'Judul tidak tersedia') }}
                            </div>
                            <small class="text-muted">
                                {{ $submission->submitted_at ? $submission->submitted_at->diffForHumans() : 'Waktu tidak tersedia' }}
                            </small>
                        </div>
                    </div>
                    <div class="text-end">
                        @php
                            $status = $submission->status ?? 'submitted';
                            $badgeClass = match($status) {
                                'graded' => 'bg-success',
                                'late' => 'bg-warning',
                                'missing' => 'bg-danger',
                                default => 'bg-primary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} mb-2">
                            {{ $status === 'graded' ? 'Sudah Dinilai' : 'Perlu Dinilai' }}
                        </span>
                        <div>
                            <a href="{{ route('guru.penilaian.edit', $submission->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-star me-1"></i>
                                {{ $status === 'graded' ? 'Edit Nilai' : 'Beri Nilai' }}
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <p class="text-muted mb-0">Semua tugas sudah dinilai!</p>
                    <small class="text-muted">Tugas baru akan muncul di sini</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Deadlines & Upcoming Exams -->
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title fw-bold mb-0">
                            <i class="fas fa-calendar-alt text-danger me-2"></i>
                            Deadline Mendekati
                        </h5>
                        <p class="text-muted small mb-0">Tugas dan praktikum yang akan berakhir</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-1"></i>
                            Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Semua</a></li>
                            <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                            <li><a class="dropdown-item" href="#">Minggu Ini</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @forelse($upcomingDeadlines ?? [] as $deadline)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-start border-danger border-4 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start justify-content-between mb-2">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1">{{ $deadline->title ?? 'Judul tidak tersedia' }}</h6>
                                        <small class="text-muted">
                                            {{ $deadline->subject->name ?? ($deadline->subject_name ?? 'Mata Pelajaran') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-danger">
                                        {{ $deadline->due_date ? $deadline->due_date->diffForHumans() : 'Segera' }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $deadline->due_date ? $deadline->due_date->format('d/m/Y H:i') : '' }}
                                    </small>
                                    <a href="#" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-eye me-1"></i>
                                        Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-check fa-3x text-success mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada deadline mendekati</p>
                            <small class="text-muted">Semua tugas masih dalam jadwal yang aman</small>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Exams -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title fw-bold mb-0">
                            <i class="fas fa-bell text-primary me-2"></i>
                            Ujian Mendatang
                        </h5>
                        <p class="text-muted small mb-0">Jadwal ujian yang akan berlangsung</p>
                    </div>
                    <a href="{{ route('admin.exam-schedules.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i>
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @php($exams = $upcomingExams ?? [])
                    @forelse($exams as $exam)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-start border-primary border-4 shadow-sm h-100">
                            <div class="card-body p-3 d-flex flex-column">
                                <div class="d-flex align-items-start justify-content-between mb-2">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1">{{ $exam->title ?? ('Jadwal ' . ($exam->subject->nama ?? '-')) }}</h6>
                                        <small class="text-muted">{{ $exam->subject->nama ?? '-' }} • {{ $exam->kelas->nama ?? 'Semua Kelas' }}</small>
                                    </div>
                                    <span class="badge bg-{{ $exam->status_color ?? 'primary' }}">{{ $exam->status ?? 'Scheduled' }}</span>
                                </div>
                                <div class="mt-auto d-flex align-items-center justify-content-between">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $exam->start_time->format('d/m/Y H:i') }}
                                    </small>
                                    <a href="{{ route('admin.exam-schedules.show', $exam->id ?? 0) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada ujian mendatang</p>
                            <small class="text-muted">Jadwal ujian akan tampil di sini</small>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
/* Hero Section Styles */
.hero-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    overflow: hidden;
    position: relative;
}

.hero-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: -50px;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.hero-avatar {
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.2);
}

/* Stats Cards */
.stats-card {
    border-radius: 12px;
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
    height: 4px;
    background: linear-gradient(90deg, var(--bs-primary), var(--bs-success));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.stats-card:hover::before {
    transform: scaleX(1);
}

.stats-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.stats-icon {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.stats-card:hover .stats-icon {
    transform: scale(1.1);
}

.stats-trend .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
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
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
}

.action-icon {
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.action-card-hover:hover .action-icon {
    transform: scale(1.15) rotate(5deg);
}

/* Quick Actions Card Styling */
.quick-action-card {
    transition: all 0.3s ease;
}

.quick-action-card:hover {
    text-decoration: none;
    transform: translateY(-4px);
}

.quick-action-card .card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.quick-action-card:hover .card {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

/* Activity Cards */
.activity-item {
    transition: all 0.3s ease;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 0.75rem;
}

.activity-item:hover {
    background: rgba(102, 126, 234, 0.05);
    transform: translateX(4px);
}

/* Progress Bars */
.progress {
    background-color: rgba(0, 0, 0, 0.05);
    border-radius: 2px;
}

.progress-bar {
    border-radius: 2px;
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

.stats-card, .action-card-hover {
    animation: fadeInUp 0.6s ease-out;
}

.stats-card:nth-child(2) { animation-delay: 0.1s; }
.stats-card:nth-child(3) { animation-delay: 0.2s; }
.stats-card:nth-child(4) { animation-delay: 0.3s; }

/* Responsive */
@media (max-width: 768px) {
    .hero-card .card-body {
        padding: 1.5rem;
    }
    
    .hero-avatar {
        width: 48px;
        height: 48px;
    }
    
    .stats-card .card-body {
        padding: 1.5rem;
    }
}

/* Gradient Backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark, #0056b3) 100%);
}

/* Card Headers */
.card-header.bg-gradient-primary {
    border-radius: 12px 12px 0 0;
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    // Open header notification dropdown from dashboard bell button
    const openBtn = document.getElementById('openNotificationsBtn');
    const headerBtn = document.getElementById('notificationDropdown');
    if (openBtn && headerBtn) {
        openBtn.addEventListener('click', function() {
            // Toggle the header dropdown
            headerBtn.click();
        });
    }

    // Auto refresh activities every 5 minutes
    setInterval(function() {
        refreshActivities();
    }, 300000);

    function refreshActivities() {
        const container = $('#activities-container');
        if (container.length) {
            container.addClass('loading');
            
            // Simulate API call - replace with actual endpoint
            setTimeout(function() {
                container.removeClass('loading');
                console.log('Activities refreshed');
            }, 1000);
        }
    }

    // Add smooth hover animations
    $('.stats-card, .quick-action-card').hover(
        function() {
            $(this).addClass('shadow-lg');
        },
        function() {
            $(this).removeClass('shadow-lg');
        }
    );

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endpush
