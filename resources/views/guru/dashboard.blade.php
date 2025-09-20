@extends('guru.layouts.guru-layout')

@section('title', 'Dashboard Guru')

@section('content')
<!-- Quick Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card hover-lift">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-book text-white"></i>
                    </div>
                    <div class="stats-info">
                        <h5>{{ $stats['total_materials'] ?? 0 }}</h5>
                        <p>Total Materi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card hover-lift">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="fas fa-tasks text-white"></i>
                    </div>
                    <div class="stats-info">
                        <h5>{{ $stats['total_assignments'] ?? 0 }}</h5>
                        <p>Total Tugas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card hover-lift">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-flask text-white"></i>
                    </div>
                    <div class="stats-info">
                        <h5>{{ $stats['total_practicals'] ?? 0 }}</h5>
                        <p>Total Praktikum</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card hover-lift">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div class="stats-info">
                        <h5>{{ $stats['total_students'] ?? 0 }}</h5>
                        <p>Total Siswa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card hover-lift">
            <div class="card-header bg-white border-0 pb-0">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-bolt text-primary me-2"></i>
                    Aksi Cepat
                </h5>
                <p class="text-muted small mb-0">Buat konten pembelajaran baru dengan cepat</p>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('guru.materials.create') }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="stats-icon bg-primary mb-3">
                                        <i class="fas fa-book text-white"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Buat Materi</h6>
                                    <small class="text-muted">Tambah materi pembelajaran baru</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('guru.assignments.create') }}" class="quick-action-card text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-tasks text-white fa-lg"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Buat Tugas</h6>
                                    <small class="text-muted">Buat tugas dan quiz baru</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('guru.praktikum.create') }}" class="quick-action-card text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="bg-warning bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-flask text-white fa-lg"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Buat Praktikum</h6>
                                    <small class="text-muted">Tambah sesi praktikum baru</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('guru.absensi.create') }}" class="quick-action-card text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="bg-danger bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-clipboard-check text-white fa-lg"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Input Absensi</h6>
                                    <small class="text-muted">Catat kehadiran siswa</small>
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
    
    <!-- Pending Grading & Upcoming Deadlines -->
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

<!-- Upcoming Deadlines -->
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
</div>

@endsection

@push('css')
<style>
.quick-action-card {
    transition: all 0.3s ease;
}

.quick-action-card:hover {
    transform: translateY(-4px);
    text-decoration: none;
}

.quick-action-card:hover .card {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.quick-action-card:hover .bg-gradient {
    transform: scale(1.1);
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.loading {
    animation: pulse 1.5s infinite;
}

.border-start {
    border-left-width: 4px !important;
}

.bg-gradient {
    background-image: linear-gradient(45deg, var(--bs-bg-opacity, 1), rgba(255,255,255,0.15)) !important;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
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
