@extends('layouts.admin')

@section('title', 'Laporan Absensi')
@section('page-title', 'Laporan Absensi')
@section('page-subtitle', 'Kehadiran siswa dan guru — SMK Kesehatan Trimurti Husada.')

@section('page-actions')
    <div class="dropdown">
        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-download fa-sm me-1"></i> Ekspor
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="#" onclick="exportReport('pdf')">PDF</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportReport('excel')">Excel</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportReport('csv')">CSV</a></li>
        </ul>
    </div>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Filter Actions:</div>
                    <a class="dropdown-item" href="#" onclick="resetFilters()">Reset Filter</a>
                    <a class="dropdown-item" href="#" onclick="saveFilter()">Simpan Filter</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" onclick="loadSavedFilter()">Muat Filter Tersimpan</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route('admin.reports.attendance') }}" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <label for="user_type" class="form-label">Jenis Pengguna</label>
                    <select name="user_type" id="user_type" class="form-select">
                        <option value="all" {{ request('user_type') == 'all' ? 'selected' : '' }}>Semua Pengguna</option>
                        <option value="students" {{ request('user_type') == 'students' ? 'selected' : '' }}>Siswa</option>
                        <option value="teachers" {{ request('user_type') == 'teachers' ? 'selected' : '' }}>Guru</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="class" class="form-label">Kelas</label>
                    <select name="class" id="class" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                        <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>{{ $class }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="subject" class="form-label">Mata Pelajaran</label>
                    <select name="subject" id="subject" class="form-select">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_range" class="form-label">Periode</label>
                    <select name="date_range" id="date_range" class="form-select">
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ request('date_range') == 'month' || !request('date_range') ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="semester" {{ request('date_range') == 'semester' ? 'selected' : '' }}>Semester Ini</option>
                        <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Kustom</option>
                    </select>
                </div>

                <!-- Custom Date Range -->
                <div class="col-12" id="customDateRange" style="display: {{ request('date_range') == 'custom' ? 'block' : 'none' }};">
                    <label class="form-label">Tanggal Mulai - Selesai</label>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end mt-3">
                    <button type="button" onclick="resetFilters()" class="btn btn-secondary me-2">
                        <i class="fas fa-redo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Generate Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards - Tetap sama seperti sebelumnya -->

    <!-- Attendance Report Table - Tetap sama seperti sebelumnya -->

    @if($attendances->count() > 0)
    <!-- Charts Section - Tetap sama seperti sebelumnya -->

    <!-- Summary by Class - Tetap sama seperti sebelumnya -->
    @endif
</div>
@endsection

@push('styles')
<style>
.chart-container {
    position: relative;
    height: 300px;
}

/* Pastikan konsistensi dengan Bootstrap */
.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}

/* Custom date range styling */
#customDateRange {
    transition: all 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }

    .card-header .dropdown {
        align-self: flex-end;
        margin-top: 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Function untuk menyimpan filter
function saveFilter() {
    const form = document.getElementById('filterForm');
    if (!form) return;

    const formData = new FormData(form);
    const filterData = {};

    for (const [key, value] of formData.entries()) {
        filterData[key] = value;
    }

    // Simpan ke localStorage
    localStorage.setItem('savedAttendanceFilter', JSON.stringify(filterData));

    // Tampilkan notifikasi
    showNotification('Filter berhasil disimpan', 'success');
}

// Function untuk memuat filter yang disimpan
function loadSavedFilter() {
    const savedFilter = localStorage.getItem('savedAttendanceFilter');
    if (!savedFilter) {
        showNotification('Tidak ada filter tersimpan', 'warning');
        return;
    }

    try {
        const filterData = JSON.parse(savedFilter);
        const form = document.getElementById('filterForm');

        if (form) {
            // Set nilai form
            Object.keys(filterData).forEach(key => {
                const element = form.elements[key];
                if (element) {
                    element.value = filterData[key];

                    // Trigger change event untuk select elements
                    if (element.tagName === 'SELECT') {
                        const event = new Event('change');
                        element.dispatchEvent(event);
                    }
                }
            });

            showNotification('Filter berhasil dimuat', 'success');
        }
    } catch (error) {
        console.error('Error loading saved filter:', error);
        showNotification('Gagal memuat filter', 'error');
    }
}

// Function untuk menampilkan notifikasi
function showNotification(message, type = 'info') {
    // Gunakan notifikasi sistem yang sudah ada atau buat sederhana
    const alertClass = type === 'success' ? 'alert-success' :
                      type === 'warning' ? 'alert-warning' :
                      type === 'error' ? 'alert-danger' : 'alert-info';

    // Buat elemen notifikasi
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    document.body.appendChild(notification);

    // Hapus otomatis setelah 3 detik
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

// Function untuk export report
function exportReport(format) {
    const form = document.getElementById('filterForm');
    if (!form) return;

    const formData = new FormData(form);
    const params = new URLSearchParams();

    for (const [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }

    const exportUrl = "{{ route('admin.reports.attendance.export') }}?format=" + format + "&" + params.toString();
    window.location.href = exportUrl;
}

// Function untuk reset filters
function resetFilters() {
    const form = document.getElementById('filterForm');
    if (form) {
        form.reset();
        const customDateRange = document.getElementById('customDateRange');
        if (customDateRange) {
            customDateRange.style.display = 'none';
        }
        // Submit form setelah reset
        form.submit();
    }
}

// Inisialisasi saat DOM loaded
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi date range picker
    const dateRangeSelect = document.getElementById('date_range');
    const customDateRange = document.getElementById('customDateRange');

    if (dateRangeSelect && customDateRange) {
        dateRangeSelect.addEventListener('change', function() {
            customDateRange.style.display = this.value === 'custom' ? 'block' : 'none';
        });
    }

    @if($attendances->count() > 0)
    // Inisialisasi charts
    const attendanceData = {
        present: {{ $stats['present_count'] ?? 0 }},
        absent: {{ $stats['absent_count'] ?? 0 }},
        late: {{ $stats['late_count'] ?? 0 }},
        excused: {{ $stats['excused_count'] ?? 0 }}
    };

    const trendLabels = @json($dailyTrend['labels'] ?? []);
    const trendData = @json($dailyTrend['data'] ?? []);

    // Buat charts
    createAttendanceDistributionChart('attendanceDistributionChart', attendanceData);
    createDailyTrendChart('dailyTrendChart', trendLabels, trendData);
    @endif
});

// Function untuk membuat chart distribusi kehadiran
function createAttendanceDistributionChart(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    new Chart(ctx, {
        type: 'doughnut',
         {
            labels: ['Hadir', 'Tidak Hadir', 'Terlambat', 'Izin'],
            datasets: [{
                 [
                    data.present || 0,
                    data.absent || 0,
                    data.late || 0,
                    data.excused || 0
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(220, 53, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(23, 162, 184, 0.8)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(23, 162, 184, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Function untuk membuat chart tren harian
function createDailyTrendChart(canvasId, labels, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
         {
            labels: labels,
            datasets: [{
                label: 'Tingkat Kehadiran (%)',
                 data,
                borderColor: 'rgba(44, 90, 160, 1)',
                backgroundColor: 'rgba(44, 90, 160, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false,
                    min: 0,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
}
</script>
@endpush