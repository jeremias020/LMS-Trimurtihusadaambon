@extends('layouts.guru')

@section('title', 'Dashboard Laporan')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 mb-1 text-dark fw-bold">
            <i class="fas fa-chart-line text-primary me-2"></i>
            Dashboard Laporan
        </h1>
        <p class="text-muted mb-0">Analitik dan laporan mengajar yang komprehensif</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary btn-sm" onclick="refreshData()">
            <i class="fas fa-sync-alt me-1"></i> Perbarui
        </button>
        <button class="btn btn-primary btn-sm" onclick="showExportModal()">
            <i class="fas fa-download me-1"></i> Ekspor
        </button>
    </div>
</div>

<!-- Filter Rentang Tanggal -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-gradient bg-primary text-white border-0">
        <h5 class="card-title mb-0">
            <i class="fas fa-calendar-range me-2"></i>
            Periode Laporan
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('guru.reports.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label fw-medium">
                    <i class="fas fa-calendar-alt text-primary me-1"></i>
                    Tanggal Mulai
                </label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" 
                       class="form-control form-control-lg border-2" required>
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label fw-medium">
                    <i class="fas fa-calendar-alt text-primary me-1"></i>
                    Tanggal Akhir
                </label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" 
                       class="form-control form-control-lg border-2" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-search me-2"></i>
                    Perbarui Periode
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan Statistik -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="stats-card hover-lift">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar avatar-lg bg-primary bg-gradient rounded-circle">
                        <i class="fas fa-book-open text-white fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="text-muted text-uppercase fs-7 fw-medium mb-1">Materi</div>
                    <div class="h3 mb-0 text-primary fw-bold">{{ number_format($stats['total_materials'] ?? 0) }}</div>
                    <small class="text-success">
                        <i class="fas fa-arrow-up me-1"></i>
                        Sumber aktif
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="stats-card hover-lift">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar avatar-lg bg-success bg-gradient rounded-circle">
                        <i class="fas fa-tasks text-white fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="text-muted text-uppercase fs-7 fw-medium mb-1">Tugas</div>
                    <div class="h3 mb-0 text-success fw-bold">{{ number_format($stats['total_assignments'] ?? 0) }}</div>
                    <small class="text-info">
                        <i class="fas fa-clock me-1"></i>
                        Total dibuat
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="stats-card hover-lift">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar avatar-lg bg-warning bg-gradient rounded-circle">
                        <i class="fas fa-flask text-white fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="text-muted text-uppercase fs-7 fw-medium mb-1">Praktikum</div>
                    <div class="h3 mb-0 text-warning fw-bold">{{ number_format($stats['total_practicals'] ?? 0) }}</div>
                    <small class="text-warning">
                        <i class="fas fa-beaker me-1"></i>
                        Kegiatan lab
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="stats-card hover-lift">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar avatar-lg bg-info bg-gradient rounded-circle">
                        <i class="fas fa-user-check text-white fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="text-muted text-uppercase fs-7 fw-medium mb-1">Absensi</div>
                    <div class="h3 mb-0 text-info fw-bold">{{ number_format($stats['total_attendance'] ?? 0) }}</div>
                    <small class="text-primary">
                        <i class="fas fa-calendar me-1"></i>
                        Rekaman terlacak
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kategori Laporan -->
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm hover-lift report-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-lg bg-primary bg-gradient rounded-3 me-3">
                        <i class="fas fa-user-check text-white fs-4"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1 fw-bold">Laporan Absensi</h5>
                        <small class="text-muted">Pelacakan kehadiran siswa</small>
                    </div>
                </div>
                <p class="text-muted mb-3">Laporan kehadiran siswa yang komprehensif berdasarkan mata pelajaran, kelas, dan periode waktu. Lacak pola kehadiran dan hasilkan wawasan.</p>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('guru.reports.attendance') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-chart-bar me-1"></i> Lihat Laporan
                    </a>
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ number_format($stats['total_attendance'] ?? 0) }} rekam</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm hover-lift report-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-lg bg-success bg-gradient rounded-3 me-3">
                        <i class="fas fa-flask text-white fs-4"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1 fw-bold">Laporan Praktikum</h5>
                        <small class="text-muted">Kegiatan lab & penilaian</small>
                    </div>
                </div>
                <p class="text-muted mb-3">Laporan sesi praktikum terperinci termasuk kinerja siswa, penggunaan peralatan lab, dan skor penilaian.</p>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('guru.reports.practical') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-microscope me-1"></i> Lihat Laporan
                    </a>
                    <span class="badge bg-success bg-opacity-10 text-success">{{ number_format($stats['total_practicals'] ?? 0) }} sesi</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm hover-lift report-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-lg bg-info bg-gradient rounded-3 me-3">
                        <i class="fas fa-download text-white fs-4"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1 fw-bold">Ekspor & Alat</h5>
                        <small class="text-muted">Opsi ekspor data</small>
                    </div>
                </div>
                <p class="text-muted mb-3">Ekspor laporan dalam berbagai format (PDF, Excel, CSV). Buat laporan khusus dengan opsi filter lanjutan.</p>
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-info btn-sm" onclick="showExportModal()">
                        <i class="fas fa-file-export me-1"></i> Ekspor Data
                    </button>
                    <span class="badge bg-info bg-opacity-10 text-info">Multi-format</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan Aktivitas -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light border-0">
        <h5 class="card-title mb-0 fw-bold">
            <i class="fas fa-chart-pie text-primary me-2"></i>
            Ringkasan Aktivitas
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded-3">
                    <div class="avatar avatar-md bg-success bg-gradient rounded-circle me-3">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <div>
                        <div class="h4 mb-0 text-success fw-bold">{{ number_format($stats['graded_assignments'] ?? 0) }}</div>
                        <small class="text-muted fw-medium">Tugas Dinilai</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded-3">
                    <div class="avatar avatar-md bg-warning bg-gradient rounded-circle me-3">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                        <div class="h4 mb-0 text-warning fw-bold">{{ number_format($stats['pending_assignments'] ?? 0) }}</div>
                        <small class="text-muted fw-medium">Tugas Tertunda</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="d-flex align-items-center p-3 bg-info bg-opacity-10 rounded-3">
                    <div class="avatar avatar-md bg-info bg-gradient rounded-circle me-3">
                        <i class="fas fa-download text-white"></i>
                    </div>
                    <div>
                        <div class="h4 mb-0 text-info fw-bold">{{ number_format($stats['materials_downloads'] ?? 0) }}</div>
                        <small class="text-muted fw-medium">Unduhan Materi</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="d-flex align-items-center p-3 bg-primary bg-opacity-10 rounded-3">
                    <div class="avatar avatar-md bg-primary bg-gradient rounded-circle me-3">
                        <i class="fas fa-star text-white"></i>
                    </div>
                    <div>
                        <div class="h4 mb-0 text-primary fw-bold">{{ number_format($stats['average_practical_score'] ?? 0, 1) }}</div>
                        <small class="text-muted fw-medium">Rata-rata Nilai Praktikum</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ekspor -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="exportModalLabel">
                    <i class="fas fa-file-export me-2"></i>
                    Ekspor Laporan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="{{ route('guru.reports.generate') }}" method="POST" id="exportForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-chart-bar text-primary me-1"></i>
                            Jenis Laporan
                        </label>
                        <select name="type" class="form-select form-select-lg" required>
                            <option value="">Pilih jenis laporan...</option>
                            <option value="absensi">
                                <i class="fas fa-user-check"></i> Laporan Absensi
                            </option>
                            <option value="praktik">
                                <i class="fas fa-flask"></i> Laporan Praktikum
                            </option>
                            <option value="tugas">
                                <i class="fas fa-tasks"></i> Laporan Tugas
                            </option>
                            <option value="materi">
                                <i class="fas fa-book"></i> Laporan Materi
                            </option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">
                                <i class="fas fa-calendar-alt text-primary me-1"></i>
                                Tanggal Mulai
                            </label>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="form-control form-control-lg" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">
                                <i class="fas fa-calendar-alt text-primary me-1"></i>
                                Tanggal Akhir
                            </label>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="form-control form-control-lg" required>
                        </div>
                    </div>
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
// Initialize Bootstrap components
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Add loading animation to stats cards
    setTimeout(() => {
        document.querySelectorAll('.stats-card').forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            }, index * 100);
        });
    }, 100);
});

function showExportModal() {
    const modal = new bootstrap.Modal(document.getElementById('exportModal'));
    modal.show();
}

function refreshData() {
    // Show loading state
    const refreshBtn = event.target;
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memuat...';
    refreshBtn.disabled = true;
    
    // Reload the page after a short delay
    setTimeout(() => {
        window.location.reload();
    }, 1000);
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
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
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

.avatar-md {
    width: 2.5rem;
    height: 2.5rem;
    font-size: 1rem;
}

.avatar-lg {
    width: 3rem;
    height: 3rem;
    font-size: 1.25rem;
}

.fs-7 {
    font-size: 0.875rem;
}

.report-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05) !important;
}

.report-card:hover {
    border-color: var(--bs-primary) !important;
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

.card-header.bg-gradient {
    background: linear-gradient(135deg, var(--bs-primary), var(--bs-info)) !important;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Animation classes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeInUp 0.6s ease forwards;
}

/* Responsive adjustments */
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
