@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Section with Welcome Card -->
    <div class="card border-0 shadow-lg mb-4 overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-shield text-white fa-2x"></i>
                        </div>
                        <div class="text-white">
                            <h1 class="mb-1 fw-bold">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                            <p class="mb-0 opacity-90">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>
                    <p class="text-white opacity-90 mb-4">Kelola sistem LMS Trimurti Husada dengan mudah dan efisien. Monitor aktivitas, kelola data, dan pantau perkembangan pembelajaran.</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.exam-schedules.create') }}" class="btn btn-light">
                            <i class="fas fa-calendar-plus me-2"></i>Buat Jadwal Ujian
                        </a>
                        <a href="{{ route('admin.exam-schedules.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-calendar-check me-2"></i>Kelola Jadwal
                        </a>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="bg-white bg-opacity-10 rounded-4 p-4">
                        <i class="fas fa-chart-line text-white fa-3x mb-3"></i>
                        <h4 class="text-white">Dashboard</h4>
                        <p class="text-white opacity-90 small mb-0">Sistem Manajemen Pembelajaran</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="text-muted small mb-1">Total Pengguna</div>
                            <div class="h3 mb-0 fw-bold">{{ $stats['total_users'] ?? 0 }}</div>
                            <div class="text-success small">
                                <i class="fas fa-arrow-up"></i>
                                {{ $stats['new_users_today'] ?? 0 }} hari ini
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success">
                            <i class="fas fa-user-graduate fa-lg"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="text-muted small mb-1">Total Siswa</div>
                            <div class="h3 mb-0 fw-bold">{{ $stats['total_siswa'] ?? 0 }}</div>
                            <div class="text-muted small">Aktif belajar</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-chalkboard-teacher fa-lg"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="text-muted small mb-1">Total Guru</div>
                            <div class="h3 mb-0 fw-bold">{{ $stats['total_guru'] ?? 0 }}</div>
                            <div class="text-muted small">Pengajar aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-info bg-opacity-10 text-info">
                            <i class="fas fa-book fa-lg"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="text-muted small mb-1">Mata Pelajaran</div>
                            <div class="h3 mb-0 fw-bold">{{ \App\Models\MataPelajaran::count() }}</div>
                            <div class="text-muted small">Tersedia</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Statistik Bulanan
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary active">6 Bulan</button>
                            <button class="btn btn-outline-primary">1 Tahun</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-pie text-success me-2"></i>
                        Distribusi Pengguna
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="userDistributionChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activities -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-rocket text-warning me-2"></i>
                            Aksi Cepat
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-4">
                            <a href="{{ route('admin.users.create') }}" class="text-decoration-none">
                                <div class="card border hover-lift-sm h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary mb-3">
                                            <i class="fas fa-user-plus fa-2x"></i>
                                        </div>
                                        <h6 class="mb-1">Tambah User</h6>
                                        <p class="text-muted small mb-0">Buat pengguna baru</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <a href="{{ route('admin.kelas.create') }}" class="text-decoration-none">
                                <div class="card border hover-lift-sm h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success mb-3">
                                            <i class="fas fa-school fa-2x"></i>
                                        </div>
                                        <h6 class="mb-1">Buat Kelas</h6>
                                        <p class="text-muted small mb-0">Tambah kelas baru</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <a href="{{ route('admin.mata-pelajaran.create') }}" class="text-decoration-none">
                                <div class="card border hover-lift-sm h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning mb-3">
                                            <i class="fas fa-book-open fa-2x"></i>
                                        </div>
                                        <h6 class="mb-1">Mata Pelajaran</h6>
                                        <p class="text-muted small mb-0">Kelola mata pelajaran</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <a href="{{ route('admin.exam-schedules.create') }}" class="text-decoration-none">
                                <div class="card border hover-lift-sm h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="rounded-3 p-3 bg-info bg-opacity-10 text-info mb-3">
                                            <i class="fas fa-calendar-alt fa-2x"></i>
                                        </div>
                                        <h6 class="mb-1">Jadwal Ujian</h6>
                                        <p class="text-muted small mb-0">Buat jadwal ujian</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <a href="{{ route('admin.jurusan.create') }}" class="text-decoration-none">
                                <div class="card border hover-lift-sm h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="rounded-3 p-3 bg-danger bg-opacity-10 text-danger mb-3">
                                            <i class="fas fa-graduation-cap fa-2x"></i>
                                        </div>
                                        <h6 class="mb-1">Jurusan</h6>
                                        <p class="text-muted small mb-0">Kelola jurusan</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <a href="{{ route('admin.kriteria-penilaian.index') }}" class="text-decoration-none">
                                <div class="card border hover-lift-sm h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="rounded-3 p-3 bg-secondary bg-opacity-10 text-secondary mb-3">
                                            <i class="fas fa-clipboard-list fa-2x"></i>
                                        </div>
                                        <h6 class="mb-1">Kriteria Penilaian</h6>
                                        <p class="text-muted small mb-0">Atur kriteria penilaian</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history text-info me-2"></i>
                            Aktivitas Terbaru
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Pengguna</th>
                                    <th>Aktivitas</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities ?? [] as $activity)
                                <tr>
                                    <td class="fw-medium">{{ $activity->user->name }}</td>
                                    <td>{{ $activity->description }}</td>
                                    <td class="text-muted small">{{ $activity->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <div>Belum ada aktivitas</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-calendar-check text-primary me-2"></i>
                            Jadwal Mendatang
                        </h5>
                        <a href="{{ route('admin.exam-schedules.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @php
                        $upcomingExams = \App\Models\ExamSchedule::with(['subject', 'kelas'])
                            ->published()
                            ->upcoming()
                            ->orderBy('start_time')
                            ->take(5)
                            ->get();
                    @endphp
                    @if($upcomingExams->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Tipe</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingExams as $exam)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $exam->title }}</div>
                                            @if($exam->kelas)
                                                <small class="text-muted">{{ $exam->kelas->nama }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $exam->exam_type == 'uts' ? 'info' : ($exam->exam_type == 'uas' ? 'danger' : ($exam->exam_type == 'quiz' ? 'warning' : ($exam->exam_type == 'praktikum' ? 'success' : 'secondary'))) }}">
                                                {{ strtoupper($exam->exam_type) }}
                                            </span>
                                        </td>
                                        <td>{{ $exam->subject->nama ?? '-' }}</td>
                                        <td>
                                            <div class="small">
                                                <div>{{ $exam->start_time->format('d M Y') }}</div>
                                                <div class="text-muted">{{ $exam->start_time->format('H:i') }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $exam->status_color }}">
                                                {{ $exam->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted mb-3">Tidak ada jadwal mendatang</h5>
                            <a href="{{ route('admin.exam-schedules.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Buat Jadwal Baru
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.hover-lift {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.hover-lift-sm {
    transition: all 0.3s ease;
}

.hover-lift-sm:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}

.card {
    transition: all 0.3s ease;
}

.text-decoration-none {
    text-decoration: none !important;
}

.text-decoration-none:hover {
    text-decoration: none !important;
}

.card-header h5 {
    color: #2c3e50;
}

.badge {
    font-size: 0.75rem;
}

.small {
    font-size: 0.875rem;
}

.fw-bold {
    font-weight: 600 !important;
}

.fw-medium {
    font-weight: 500 !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['months'] ?? []),
            datasets: @json($chartData['datasets'] ?? [])
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // User Distribution Chart
    const distributionCtx = document.getElementById('userDistributionChart').getContext('2d');
    const distributionChart = new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: @json($userDistribution['labels'] ?? []),
            datasets: [{
                data: @json($userDistribution['data'] ?? []),
                backgroundColor: @json($userDistribution['colors'] ?? []),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
