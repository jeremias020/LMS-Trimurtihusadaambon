@extends('layouts.guru')

@section('title', 'Laporan Absensi')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 mb-1 text-dark fw-bold">
            <i class="fas fa-user-check text-primary me-2"></i>
            Laporan Absensi
        </h1>
        <p class="text-muted mb-0">Laporan kehadiran siswa per mata pelajaran dan periode</p>
        <nav aria-label="breadcrumb" class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('guru.reports.index') }}" class="text-decoration-none">Dashboard Laporan</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Absensi</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('guru.reports.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <button class="btn btn-primary btn-sm" onclick="showExportModal()">
            <i class="fas fa-download me-1"></i> Ekspor
        </button>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-gradient bg-primary text-white border-0">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>
            Filter Laporan
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('guru.reports.attendance') }}" class="row g-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label fw-medium">
                    <i class="fas fa-calendar-alt text-primary me-1"></i>
                    Tanggal Mulai
                </label>
                <input type="date" id="start_date" name="start_date" 
                       value="{{ $filters['start_date'] }}" 
                       class="form-control border-2" required>
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label fw-medium">
                    <i class="fas fa-calendar-alt text-primary me-1"></i>
                    Tanggal Akhir
                </label>
                <input type="date" id="end_date" name="end_date" 
                       value="{{ $filters['end_date'] }}" 
                       class="form-control border-2" required>
            </div>
            <div class="col-md-3">
                <label for="kelas" class="form-label fw-medium">
                    <i class="fas fa-users text-primary me-1"></i>
                    Kelas
                </label>
                <select name="kelas" id="kelas" class="form-select border-2">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $id => $name)
                        <option value="{{ $id }}" {{ $filters['kelas'] == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label fw-medium">
                    <i class="fas fa-info-circle text-primary me-1"></i>
                    Status
                </label>
                <select name="status" id="status" class="form-select border-2">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ $filters['status'] == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="alpha" {{ $filters['status'] == 'alpha' ? 'selected' : '' }}>Alpha</option>
                    <option value="izin" {{ $filters['status'] == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ $filters['status'] == 'sakit' ? 'selected' : '' }}>Sakit</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i> Tampilkan Laporan
                </button>
                <button type="button" onclick="resetFilters()" class="btn btn-outline-secondary">
                    <i class="fas fa-undo me-1"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stats-card hover-lift border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-lg bg-success bg-gradient rounded-circle me-3">
                        <i class="fas fa-check text-white fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted text-uppercase fs-7 fw-medium mb-1">Hadir</div>
                        <div class="h3 mb-0 text-success fw-bold">{{ number_format($stats['present_count'] ?? 0) }}</div>
                        <small class="text-success">
                            <i class="fas fa-user-check me-1"></i>
                            Kehadiran
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stats-card hover-lift border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-lg bg-warning bg-gradient rounded-circle me-3">
                        <i class="fas fa-clock text-white fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted text-uppercase fs-7 fw-medium mb-1">Terlambat</div>
                        <div class="h3 mb-0 text-warning fw-bold">{{ number_format($stats['late_count'] ?? 0) }}</div>
                        <small class="text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Keterlambatan
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stats-card hover-lift border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-lg bg-danger bg-gradient rounded-circle me-3">
                        <i class="fas fa-times text-white fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted text-uppercase fs-7 fw-medium mb-1">Tidak Hadir</div>
                        <div class="h3 mb-0 text-danger fw-bold">{{ number_format($stats['absent_count'] ?? 0) }}</div>
                        <small class="text-danger">
                            <i class="fas fa-user-times me-1"></i>
                            Absen
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stats-card hover-lift border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-lg bg-info bg-gradient rounded-circle me-3">
                        <i class="fas fa-percentage text-white fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted text-uppercase fs-7 fw-medium mb-1">Tingkat Kehadiran</div>
                        <div class="h3 mb-0 text-info fw-bold">{{ number_format($stats['attendance_rate'] ?? 0, 1) }}%</div>
                        <small class="text-info">
                            <i class="fas fa-chart-line me-1"></i>
                            Persentase
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Summary by Subject -->
@if($attendanceSummary && $attendanceSummary->count() > 0)
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-light border-0">
        <h5 class="card-title mb-0 fw-bold">
            <i class="fas fa-chart-bar text-primary me-2"></i>
            Ringkasan Kehadiran per Mata Pelajaran
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-medium">Mata Pelajaran</th>
                        <th class="fw-medium">Kelas</th>
                        <th class="fw-medium text-center">Total Sesi</th>
                        <th class="fw-medium text-center">Hadir</th>
                        <th class="fw-medium text-center">Terlambat</th>
                        <th class="fw-medium text-center">Tidak Hadir</th>
                        <th class="fw-medium text-center">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceSummary as $summary)
                    <tr>
                        <td class="fw-medium">{{ $summary->subject_name ?? 'Tidak ada data' }}</td>
                        <td>{{ $summary->class ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $summary->total_sessions ?? 0 }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success bg-opacity-10 text-success">
                                {{ $summary->present_count ?? 0 }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                {{ $summary->late_count ?? 0 }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                {{ $summary->absent_count ?? 0 }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $attendanceRate = $summary->attendance_rate ?? 0;
                                $badgeClass = $attendanceRate >= 90 ? 'success' : ($attendanceRate >= 75 ? 'warning' : 'danger');
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}">
                                {{ number_format($attendanceRate, 1) }}%
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            Tidak ada data ringkasan kehadiran
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Detailed Student Attendance -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold">
            <i class="fas fa-users text-primary me-2"></i>
            Detail Kehadiran Siswa
        </h5>
        <span class="badge bg-primary bg-opacity-10 text-primary">
            {{ $students->total() ?? 0 }} siswa ditemukan
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-medium">Siswa</th>
                        <th class="fw-medium">Kelas</th>
                        <th class="fw-medium text-center">Hadir</th>
                        <th class="fw-medium text-center">Terlambat</th>
                        <th class="fw-medium text-center">Tidak Hadir</th>
                        <th class="fw-medium text-center">Izin</th>
                        <th class="fw-medium text-center">Persentase</th>
                        <th class="fw-medium text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-primary bg-gradient rounded-circle me-2">
                                    <span class="text-white fs-6 fw-bold">
                                        {{ substr($student->name ?? 'U', 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $student->name ?? 'Nama tidak tersedia' }}</div>
                                    <small class="text-muted">{{ $student->nis ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $student->class ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-success bg-opacity-10 text-success">
                                {{ $student->present_count ?? 0 }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                {{ $student->late_count ?? 0 }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                {{ $student->absent_count ?? 0 }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info bg-opacity-10 text-info">
                                {{ $student->excused_count ?? 0 }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $attendanceRate = $student->attendance_rate ?? 0;
                                $badgeClass = $attendanceRate >= 90 ? 'success' : ($attendanceRate >= 75 ? 'warning' : 'danger');
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}">
                                {{ number_format($attendanceRate, 1) }}%
                            </span>
                        </td>
                        <td class="text-center">
                            @if($attendanceRate >= 90)
                                <i class="fas fa-check-circle text-success" title="Kehadiran Baik"></i>
                            @elseif($attendanceRate >= 75)
                                <i class="fas fa-exclamation-circle text-warning" title="Kehadiran Cukup"></i>
                            @else
                                <i class="fas fa-times-circle text-danger" title="Kehadiran Kurang"></i>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            Tidak ada data siswa untuk periode yang dipilih
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($students->hasPages())
        <div class="card-footer bg-light border-0">
            <div class="d-flex justify-content-center">
                {{ $students->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="exportModalLabel">
                    <i class="fas fa-file-export me-2"></i>
                    Ekspor Laporan Absensi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="{{ route('guru.reports.generate') }}" method="POST" id="exportForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">
                                <i class="fas fa-calendar-alt text-primary me-1"></i>
                                Tanggal Mulai
                            </label>
                            <input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">
                                <i class="fas fa-calendar-alt text-primary me-1"></i>
                                Tanggal Akhir
                            </label>
                            <input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-users text-primary me-1"></i>
                            Kelas (Opsional)
                        </label>
                        <select name="kelas" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $id => $name)
                                <option value="{{ $id }}" {{ $filters['kelas'] == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="type" value="absensi">
                    <input type="hidden" name="format" value="pdf">
                    <div class="alert alert-info d-flex align-items-center mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            <strong>Catatan:</strong> Laporan akan dibuat dalam format PDF dan otomatis diunduh.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="exportBtn">
                        <i class="fas fa-download me-1"></i> 
                        <span class="btn-text">Ekspor PDF</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showExportModal() {
    const modal = new bootstrap.Modal(document.getElementById('exportModal'));
    modal.show();
}

function resetFilters() {
    window.location.href = "{{ route('guru.reports.attendance') }}";
}

// Handle export form submission
document.getElementById('exportForm').addEventListener('submit', function() {
    const btn = document.getElementById('exportBtn');
    const btnText = btn.querySelector('.btn-text');
    const spinner = btn.querySelector('.spinner-border');
    
    // Show loading state
    btnText.textContent = 'Membuat...';
    spinner.classList.remove('d-none');
    btn.disabled = true;
    
    // Reset after 3 seconds (assuming download starts)
    setTimeout(() => {
        btnText.textContent = 'Ekspor PDF';
        spinner.classList.add('d-none');
        btn.disabled = false;
        bootstrap.Modal.getInstance(document.getElementById('exportModal')).hide();
    }, 3000);
});
</script>

<style>
/* Custom CSS for enhanced styling */
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.avatar-sm {
    width: 2rem;
    height: 2rem;
    font-size: 0.875rem;
}

.avatar-lg {
    width: 3rem;
    height: 3rem;
    font-size: 1.25rem;
}

.fs-7 {
    font-size: 0.875rem;
}

.stats-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05) !important;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--bs-primary), var(--bs-info));
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
}

@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .avatar-lg {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1rem;
    }
}
</style>
@endsection