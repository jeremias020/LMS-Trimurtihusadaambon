@extends('layouts.guru')

@section('title', 'Manajemen Absensi - LMS Trimurti Husada')

@section('page-title', 'Manajemen Absensi')
@section('page-subtitle', 'Kelola kehadiran siswa')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Absensi</li>
@endsection


@push('css')
<style>
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Footer improvements */
        .card-footer {
            border-top: 1px solid #e9ecef !important;
        }

        .pagination .page-link {
            border-radius: 0.375rem !important;
            margin: 0 2px !important;
            border: 1px solid #dee2e6 !important;
            transition: all 0.2s ease !important;
        }

        .pagination .page-link:hover {
            background-color: #f8f9fa !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .pagination .page-item.active .page-link {
            background-color: #4e73df !important;
            border-color: #4e73df !important;
            box-shadow: 0 2px 4px rgba(78, 115, 223, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Info text improvements */
        .text-muted small {
            font-size: 0.875rem;
            line-height: 1.4;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-footer .row {
                text-align: center !important;
            }
            
            .card-footer .col-md-6 {
                margin-bottom: 1rem;
            }
        }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Debug Info -->
        <div class="alert alert-info">
            <h6>Debug Information:</h6>
            <ul>
                <li>Attendances Count: {{ $attendances->count() }}</li>
                <li>Attendances Total: {{ $attendances->total() }}</li>
                <li>Classes Count: {{ $classes->count() }}</li>
                <li>Subjects Count: {{ $subjects->count() }}</li>
                <li>Date: {{ $date }}</li>
                <li>Class: {{ $class }}</li>
                <li>Type: {{ $type }}</li>
                <li>Auth User: {{ Auth::user()->name }}</li>
                @if($attendances->count() > 0)
                    <li>First Attendance ID: {{ $attendances->first()->id }}</li>
                    <li>First Attendance Siswa ID: {{ $attendances->first()->siswa_id }}</li>
                    <li>First Attendance Siswa: {{ $attendances->first()->siswa?->name ?? 'NULL' }}</li>
                    <li>First Attendance Subject: {{ $attendances->first()->subject?->name ?? 'NULL' }}</li>
                    <li>First Attendance Date: {{ $attendances->first()->tanggal }}</li>
                @endif
            </ul>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Daftar Absensi
                    </h5>
                    <a href="{{ route('guru.absensi.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Buat Absensi
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Filter and Search -->
                <div class="mb-4 row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" placeholder="Cari siswa..." class="form-control" id="searchInput" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="subjectFilter" autocomplete="off">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="classFilter" autocomplete="off">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $classId => $className)
                            <option value="{{ $classId }}">{{ $className }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" id="dateFilter" placeholder="Pilih tanggal" autocomplete="off">
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-success bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-user-check text-success fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="text-muted mb-1">Hadir</h6>
                                        <h3 class="mb-0 text-success">{{ $stats['hadir'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-info bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-file-alt text-info fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="text-muted mb-1">Izin</h6>
                                        <h3 class="mb-0 text-info">{{ $stats['izin'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-thermometer-half text-warning fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="text-muted mb-1">Sakit</h6>
                                        <h3 class="mb-0 text-warning">{{ $stats['sakit'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-user-times text-danger fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="text-muted mb-1">Alpha</h6>
                                        <h3 class="mb-0 text-danger">{{ $stats['alpha'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Siswa</th>
                                <th scope="col">Kelas</th>
                                <th scope="col">Mata Pelajaran</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Waktu</th>
                                <th scope="col">Status</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                            <tr class="attendance-row" data-subject="{{ $attendance->subject_id ?? '' }}" data-class="{{ $attendance->siswa?->kelas_id ?? '' }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <img class="avatar rounded-circle"
                                                 src="{{ $attendance->siswa?->photoUrl ?? asset('images/default-avatar.png') }}"
                                                 alt="{{ e($attendance->siswa?->name ?? 'Siswa') }}"
                                                 onerror="this.src='/images/default-avatar.png'">
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ e($attendance->siswa?->name ?? 'N/A') }}</div>
                                            <div class="small text-muted">NIS: {{ e($attendance->siswa?->siswa?->nis ?? $attendance->siswa?->nis ?? '-') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-dark">{{ e($attendance->siswa?->kelas?->name ?? 'Tidak ada kelas') }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ e($attendance->subject?->name ?? 'Tidak ada mata pelajaran') }}</span>
                                </td>
                                <td>
                                    <span class="small text-muted">{{ $attendance->tanggal?->format('d/m/Y') ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="small">
                                    @if($attendance->waktu_masuk && $attendance->waktu_keluar)
                                        {{ $attendance->waktu_masuk?->format('H:i') ?? '-' }} - {{ $attendance->waktu_keluar?->format('H:i') ?? '-' }}
                                    @elseif($attendance->waktu_masuk)
                                        {{ $attendance->waktu_masuk?->format('H:i') ?? '-' }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'hadir' => 'bg-success',
                                            'izin' => 'bg-info',
                                            'sakit' => 'bg-warning',
                                            'alpha' => 'bg-danger'
                                        ];
                                        $statusText = [
                                            'hadir' => 'Hadir',
                                            'izin' => 'Izin',
                                            'sakit' => 'Sakit',
                                            'alpha' => 'Alpha'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusClasses[$attendance->status] ?? 'bg-secondary' }}">
                                        {{ $statusText[$attendance->status] ?? $attendance->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="small text-muted">{{ e($attendance->keterangan ?? '-') }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('guru.absensi.edit', $attendance->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $attendance->id }}">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mb-3">
                                            <i class="fas fa-clipboard-list text-muted" style="font-size: 4rem;"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">Belum ada data absensi</h5>
                                        <p class="text-muted mb-4">Mulai dengan membuat absensi untuk hari ini.</p>
                                        <a href="{{ route('guru.absensi.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Buat Absensi
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($attendances->hasPages())
            <div class="card-footer bg-light border-top py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="text-muted small me-3">
                                <i class="fas fa-info-circle me-1"></i>
                                Menampilkan <span class="fw-semibold">{{ $attendances->firstItem() }}</span> - 
                                <span class="fw-semibold">{{ $attendances->lastItem() }}</span> dari 
                                <span class="fw-semibold">{{ $attendances->total() }}</span> data
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end justify-content-center">
                            {{ $attendances->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

<!-- Delete Confirmation Modals -->
@foreach($attendances as $attendance)
<div class="modal fade" id="deleteModal{{ $attendance->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $attendance->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $attendance->id }}">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        Apakah Anda yakin ingin menghapus data absensi ini?
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Data yang akan dihapus:</strong>
                    <ul class="mb-0 mt-2">
                        <li><strong>Siswa:</strong> {{ $attendance->siswa?->name ?? 'N/A' }}</li>
                        <li><strong>Tanggal:</strong> {{ $attendance->tanggal?->format('d/m/Y') ?? 'N/A' }}</li>
                        <li><strong>Status:</strong> {{ $attendance->status ?? 'N/A' }}</li>
                        <li><strong>Keterangan:</strong> {{ $attendance->keterangan ?? 'Tidak ada' }}</li>
                    </ul>
                </div>
                
                <div class="alert alert-danger">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <form action="{{ route('guru.absensi.destroy', $attendance->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const subjectFilter = document.getElementById('subjectFilter');
        const classFilter = document.getElementById('classFilter');
        const dateFilter = document.getElementById('dateFilter');
        const attendanceRows = document.querySelectorAll('.attendance-row');

        function filterAttendances() {
            const searchTerm = searchInput.value.toLowerCase();
            const subjectValue = subjectFilter.value;
            const classValue = classFilter.value;
            const dateValue = dateFilter.value;

            attendanceRows.forEach(row => {
                const studentName = row.querySelector('td:first-child')?.textContent.toLowerCase() || '';
                const subject = row.dataset.subject || '';
                const classId = row.dataset.class || '';
                const classText = row.querySelector('td:nth-child(2)')?.textContent || '';
                const date = row.querySelector('td:nth-child(4)')?.textContent || '';

                // Convert date from dd/mm/yyyy to yyyy-mm-dd for comparison
                let rowDate = '';
                if (date && date !== '-') {
                    const dateParts = date.split('/');
                    if (dateParts.length === 3) {
                        rowDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                    }
                }

                const matchesSearch = studentName.includes(searchTerm);
                const matchesSubject = !subjectValue || subject === subjectValue;
                const matchesClass = !classValue || classId === classValue || classText.includes(classValue);
                const matchesDate = !dateValue || rowDate === dateValue;

                row.style.display = (matchesSearch && matchesSubject && matchesClass && matchesDate) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterAttendances);
        subjectFilter.addEventListener('change', filterAttendances);
        classFilter.addEventListener('change', filterAttendances);
        dateFilter.addEventListener('change', filterAttendances);

        // Initialize filters from URL parameters if present
        const urlParams = new URLSearchParams(window.location.search);
        let hasUrlFilters = false;
        
        if (urlParams.has('subject')) {
            subjectFilter.value = urlParams.get('subject');
            hasUrlFilters = true;
        }
        if (urlParams.has('class')) {
            classFilter.value = urlParams.get('class');
            hasUrlFilters = true;
        }
        if (urlParams.has('date')) {
            dateFilter.value = urlParams.get('date');
            hasUrlFilters = true;
        }
        if (urlParams.has('search')) {
            searchInput.value = urlParams.get('search');
            hasUrlFilters = true;
        }

        // Apply initial filters only if there are URL parameters
        if (hasUrlFilters) {
            filterAttendances();
        }

        // Handle delete form submission
        document.querySelectorAll('form[action*="destroy"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menghapus...';
                
                // Form will submit normally
            });
        });

        // Close modal after successful deletion
        window.addEventListener('popstate', function() {
            // Close any open modals
            document.querySelectorAll('.modal.show').forEach(modal => {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
        });
    });
</script>
@endpush
