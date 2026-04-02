@extends('admin.layouts.admin-layout')

@section('title', 'Detail Pengguna - ' . $user->name)

@section('page-title')
    <i class="fas fa-user-circle me-2"></i>
    Detail Pengguna: {{ $user->name }}
@endsection

@section('page-actions')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Manajemen User</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>
    <div class="d-flex gap-2 mt-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-edit me-1"></i>
            Edit
        </a>
    </div>
@endsection

@push('css')
<style>
/* Custom styling for user detail page */
.user-avatar {
    width: 120px;
    height: 120px;
    border: 4px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.user-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}

.stat-card {
    background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
    border: 1px solid #e3e6f0;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.info-label {
    font-weight: 600;
    color: #5a5c69;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    color: #3a3b45;
    font-weight: 500;
    font-size: 0.95rem;
}

.role-badge {
    font-size: 0.8rem;
    padding: 6px 12px;
    font-weight: 600;
    border-radius: 20px;
}

.status-badge {
    font-size: 0.8rem;
    padding: 6px 12px;
    font-weight: 600;
    border-radius: 20px;
}

.activity-item {
    padding: 1rem;
    border-left: 3px solid #e3e6f0;
    background: #f8f9fc;
    border-radius: 0 8px 8px 0;
    transition: all 0.3s ease;
}

.activity-item:hover {
    border-left-color: #4e73df;
    background: #f0f4ff;
}

.action-buttons .btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .user-avatar {
        width: 100px;
        height: 100px;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .action-buttons .btn {
        width: 100%;
    }
}
</style>
@endpush

@section('content')

<div class="row">
    <!-- User Profile Card -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-circle me-2"></i>
                    Profil Pengguna
                </h5>
            </div>

            <div class="card-body text-center">
                <div class="mb-4">
                    <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" 
                         alt="{{ $user->name }}" 
                         class="user-avatar rounded-circle mx-auto d-block">
                    <h4 class="mt-3 mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    <div class="mb-3">
                        <span class="role-badge me-2
                            @if($user->role === 'admin') bg-primary text-white
                            @elseif($user->role === 'guru') bg-success text-white
                            @else bg-info text-white
                            @endif">
                            <i class="fas fa-user-tag me-1"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                        <br class="d-md-none">
                        <span class="status-badge mt-2 mt-md-0
                            @if($user->isActive()) bg-success text-white
                            @else bg-danger text-white
                            @endif">
                            <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>
                            {{ $user->isActive() ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <div class="border-top pt-3">
                    <div class="row text-start">
                        @if($user->phone)
                        <div class="col-12 mb-2">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <small class="text-muted">{{ $user->phone }}</small>
                        </div>
                        @endif
                        <div class="col-12 mb-2">
                            <i class="fas fa-calendar-plus text-muted me-2"></i>
                            <small class="text-muted">Bergabung: {{ $user->created_at->format('d/m/Y') }}</small>
                        </div>
                        <div class="col-12 mb-2">
                            <i class="fas fa-clock text-muted me-2"></i>
                            <small class="text-muted">Update: {{ $user->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="action-buttons d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary flex-fill">
                        <i class="fas fa-edit me-1"></i>
                        Edit
                    </a>
                    <button type="button" class="btn btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-1"></i>
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Details -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Detail
                </h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user me-2"></i>
                            Informasi Dasar
                        </h6>
                        <div class="mb-3">
                            <label class="info-label">Nama Lengkap</label>
                            <div class="info-value">{{ $user->name }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="info-label">Email</label>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                        @if($user->phone)
                        <div class="mb-3">
                            <label class="info-label">Nomor Telepon</label>
                            <div class="info-value">{{ $user->phone }}</div>
                        </div>
                        @endif
                        <div class="mb-3">
                            <label class="info-label">Role</label>
                            <div class="info-value">
                                <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="info-label">Status</label>
                            <div class="info-value">
                                <span class="badge {{ $user->isActive() ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->isActive() ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="info-label">Bergabung Pada</label>
                            <div class="info-value">{{ $user->created_at->format('d F Y H:i') }}</div>
                        </div>
                    </div>

                    <!-- Role Specific Information -->
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Informasi Spesifik
                        </h6>
                        @if($user->role === 'guru')
                            <div class="mb-3">
                                <label class="info-label">NIP</label>
                                <div class="info-value">{{ $user->guru?->nip ?? '-' }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="info-label">Mata Pelajaran</label>
                                <div class="info-value">{{ $user->guru?->mata_pelajaran ?? '-' }}</div>
                            </div>
                        @elseif($user->role === 'siswa')
                            <div class="mb-3">
                                <label class="info-label">NIS</label>
                                <div class="info-value">{{ $user->siswa?->nis ?? '-' }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="info-label">Kelas</label>
                                <div class="info-value">{{ $user->class_name ?? ($user->siswa?->kelas?->name ?? '-') }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="info-label">Jurusan</label>
                                <div class="info-value">{{ $user->jurusan?->nama ?? ($user->kelas?->major ?? '-') }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="info-label">Tanggal Lahir</label>
                                <div class="info-value">{{ $user->siswa?->tanggal_lahir ? $user->siswa?->tanggal_lahir->format('d/m/Y') : '-' }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="info-label">Alamat</label>
                                <div class="info-value">{{ $user->siswa?->alamat ?? $user->address ?? '-' }}</div>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="info-label">Hak Akses</label>
                                <div class="info-value">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-crown me-1"></i>
                                        Akses Penuh Sistem
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        @if($user->role === 'guru' || $user->role === 'siswa')
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistik
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($user->role === 'guru')
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card p-3 text-center">
                            <div class="text-primary">
                                <i class="fas fa-book fa-2x mb-2"></i>
                            </div>
                            <div class="h4 mb-1 text-primary">{{ $stats['materials_count'] ?? 0 }}</div>
                            <div class="small text-muted">Materi</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card p-3 text-center">
                            <div class="text-success">
                                <i class="fas fa-tasks fa-2x mb-2"></i>
                            </div>
                            <div class="h4 mb-1 text-success">{{ $stats['assignments_count'] ?? 0 }}</div>
                            <div class="small text-muted">Tugas</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card p-3 text-center">
                            <div class="text-warning">
                                <i class="fas fa-flask fa-2x mb-2"></i>
                            </div>
                            <div class="h4 mb-1 text-warning">{{ $stats['practicals_count'] ?? 0 }}</div>
                            <div class="small text-muted">Praktikum</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card p-3 text-center">
                            <div class="text-info">
                                <i class="fas fa-user-graduate fa-2x mb-2"></i>
                            </div>
                            <div class="h4 mb-1 text-info">{{ $stats['students_count'] ?? 0 }}</div>
                            <div class="small text-muted">Siswa</div>
                        </div>
                    </div>
                    @elseif($user->role === 'siswa')
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card p-3 text-center">
                            <div class="text-primary">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                            </div>
                            <div class="h4 mb-1 text-primary">{{ $stats['completed_assignments'] ?? 0 }}</div>
                            <div class="small text-muted">Tugas Selesai</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card p-3 text-center">
                            <div class="text-success">
                                <i class="fas fa-star fa-2x mb-2"></i>
                            </div>
                            <div class="h4 mb-1 text-success">{{ $stats['average_score'] ?? 0 }}</div>
                            <div class="small text-muted">Nilai Rata-rata</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card p-3 text-center">
                            <div class="text-warning">
                                <i class="fas fa-calendar-check fa-2x mb-2"></i>
                            </div>
                            <div class="h4 mb-1 text-warning">{{ $stats['attendance_rate'] ?? 0 }}%</div>
                            <div class="small text-muted">Kehadiran</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card p-3 text-center">
                            <div class="text-danger">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                            </div>
                            <div class="h4 mb-1 text-danger">{{ $stats['pending_tasks'] ?? 0 }}</div>
                            <div class="small text-muted">Tugas Tertunda</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card p-3 text-center">
                            <div class="text-info">
                                <i class="fas fa-flask fa-2x mb-2"></i>
                            </div>
                            <div class="h4 mb-1 text-info">{{ $stats['practical_grades_count'] ?? 0 }}</div>
                            <div class="small text-muted">Nilai Praktik</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card p-3 text-center">
                            <div class="text-warning">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                            </div>
                            <div class="h4 mb-1 text-warning">{{ $stats['practical_average'] ?? 0 }}</div>
                            <div class="small text-muted">Rata-rata Praktik</div>
                        </div>
                    </div>
                    @if(isset($stats['practical_latest_grade']))
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card p-3 text-center">
                            <div class="{{ $stats['practical_latest_grade']->grade_badge_color == 'success' ? 'text-success' : ($stats['practical_latest_grade']->grade_badge_color == 'primary' ? 'text-primary' : ($stats['practical_latest_grade']->grade_badge_color == 'warning' ? 'text-warning' : ($stats['practical_latest_grade']->grade_badge_color == 'danger' ? 'text-danger' : 'text-secondary'))) }}">
                                <i class="fas fa-medal fa-2x mb-2"></i>
                            </div>
                            <div class="h4 mb-1 {{ $stats['practical_latest_grade']->grade_badge_color == 'success' ? 'text-success' : ($stats['practical_latest_grade']->grade_badge_color == 'primary' ? 'text-primary' : ($stats['practical_latest_grade']->grade_badge_color == 'warning' ? 'text-warning' : ($stats['practical_latest_grade']->grade_badge_color == 'danger' ? 'text-danger' : 'text-secondary')) }}">{{ $stats['practical_latest_grade']->grade ?? '-' }}</div>
                            <div class="small text-muted">Grade Terakhir</div>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Aktivitas Terbaru
                </h5>
            </div>

            <div class="card-body">
                @if(isset($activities) && $activities->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($activities as $activity)
                    <div class="activity-item d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fas fa-bolt" style="font-size: 14px;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 fw-medium text-dark">{{ $activity->description ?? 'Aktivitas' }}</p>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="flex-shrink-0">
                            <small class="text-muted">{{ $activity->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Tidak ada aktivitas terbaru</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-user-times fa-4x text-danger mb-3"></i>
                    <h5 class="mb-3">Hapus Pengguna: {{ $user->name }}?</h5>
                    <p class="text-muted mb-0">Tindakan ini tidak dapat dibatalkan. Semua data yang terkait dengan pengguna ini akan dihapus secara permanen.</p>
                </div>
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Peringatan:</strong> Data yang akan dihapus meliputi:
                    <ul class="mb-0 mt-2">
                        <li>Informasi profil pengguna</li>
                        <li>History aktivitas</li>
                        @if($user->role === 'guru')
                        <li>Materi dan tugas yang dibuat</li>
                        @elseif($user->role === 'siswa')
                        <li>Hasil ujian dan nilai</li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Batal
                </button>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Ya, Hapus Pengguna
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
