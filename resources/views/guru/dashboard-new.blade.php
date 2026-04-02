@extends('layouts.guru')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Guru')
@section('page-subtitle', 'Selamat datang di LMS Trimurti Husada')

@section('content')
<div class="container-fluid px-0">
    <!-- Greeting Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">
                    <i class="fas fa-chalkboard-teacher text-white"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="mb-0 fw-bold">Halo, {{ Auth::user()->name }}! 👋</h3>
                    <div class="text-muted small">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
                </div>
                
                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn btn-ghost position-relative" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                        <i class="fas fa-bell"></i>
                        <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                            0
                        </span>
                    </button>
                    <ul id="notification-dropdown" class="dropdown-menu dropdown-menu-end" style="min-width: 320px;">
                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                            <span>Notifikasi</span>
                            <button id="mark-all-read" class="btn btn-sm btn-outline-primary">Tandai semua dibaca</button>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <!-- Notifications will be loaded here -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="mini-stat-icon bg-primary-subtle text-primary me-3">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-0 fw-bold">{{ $stats['total_materials'] ?? 0 }}</h3>
                        <div class="text-muted small">Total Materi</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="mini-stat-icon bg-warning-subtle text-warning me-3">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-0 fw-bold">{{ $stats['total_assignments'] ?? 0 }}</h3>
                        <div class="text-muted small">Total Tugas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="mini-stat-icon bg-success-subtle text-success me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-0 fw-bold">{{ $stats['total_students'] ?? 0 }}</h3>
                        <div class="text-muted small">Total Siswa</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="mini-stat-icon bg-info-subtle text-info me-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-0 fw-bold">{{ $stats['attendance_rate'] ?? 0 }}%</h3>
                        <div class="text-muted small">Tingkat Kehadiran</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Exams -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Jadwal Ujian Mendatang</h6>
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            @php
                $guru = \App\Models\Guru::where('user_id', auth()->id())->first();
                $upcomingExams = \App\Models\ExamSchedule::with(['subject', 'kelas'])
                    ->published()
                    ->upcoming()
                    ->where(function($query) use ($guru) {
                        if ($guru && $guru->kelas_id) {
                            $query->where('kelas_id', $guru->kelas_id)
                                  ->orWhereNull('kelas_id');
                        } else {
                            $query->whereNull('kelas_id');
                        }
                    })
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
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingExams as $exam)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $exam->title }}</div>
                                    @if($exam->location)
                                        <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ $exam->location }}</small>
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
                                    <a href="{{ route('exam-schedules.show', $exam) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                    <div class="text-muted">Tidak ada jadwal ujian mendatang</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-6 col-md-3">
            <a href="{{ route('guru.materials.index') }}" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                <i class="fas fa-book fa-2x mb-2"></i>
                <span>Kelola Materi</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('guru.assignments.index') }}" class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                <i class="fas fa-tasks fa-2x mb-2"></i>
                <span>Kelola Tugas</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('guru.absensi.index') }}" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                <i class="fas fa-user-check fa-2x mb-2"></i>
                <span>Absensi</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('guru.practicals.index') }}" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                <i class="fas fa-flask fa-2x mb-2"></i>
                <span>Praktikum</span>
            </a>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-history me-2"></i>Aktivitas Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="timeline">
                @forelse($recentActivities ?? [] as $activity)
                <div class="timeline-item">
                    <div class="timeline-marker bg-primary"></div>
                    <div class="timeline-content">
                        <div class="fw-medium">{{ $activity->title }}</div>
                        <div class="text-muted small">{{ $activity->description }}</div>
                        <div class="text-muted smaller">{{ $activity->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-history fa-2x text-muted mb-2"></i>
                    <div class="text-muted">Belum ada aktivitas</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e2e8f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -12px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content {
    background: #f8fafc;
    padding: 12px;
    border-radius: 8px;
    border-left: 3px solid #3b82f6;
}

.smaller {
    font-size: 0.75rem;
}
</style>
@endpush
