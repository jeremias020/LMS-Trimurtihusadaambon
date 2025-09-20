@extends('layouts.guru')

@section('title', 'Daftar Penilaian - SMK Kesehatan Trimurti Husada')

@section('page-title', 'Penilaian Siswa')
@section('page-subtitle', 'Kelola penilaian tugas dan praktikum siswa')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Penilaian</li>
@endsection

@section('page-actions')
<a href="{{ route('guru.penilaian.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>Buat Penilaian Manual
</a>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-clipboard-list text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Penilaian</h6>
                        <h3 class="mb-0">{{ $assessments->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-clock text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Belum Dinilai</h6>
                        <h3 class="mb-0">{{ $assessments->where('score', null)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Sudah Dinilai</h6>
                        <h3 class="mb-0">{{ $assessments->whereNotNull('score')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-star text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Rata-rata Nilai</h6>
                        <h3 class="mb-0">{{ number_format($assessments->whereNotNull('score')->avg('score'), 1) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-bold">Filter Penilaian</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="subject_filter" class="form-label">Mata Pelajaran</label>
                <select id="subject_filter" class="form-select">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label for="class_filter" class="form-label">Kelas</label>
                <select id="class_filter" class="form-select">
                    <option value="">Semua Kelas</option>
                    @php
                        $classes = $assessments->pluck('class')->unique()->sort()->values();
                    @endphp
                    @foreach($classes as $class)
                        @if($class)
                        <option value="{{ $class }}">{{ $class }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label for="status_filter" class="form-label">Status Penilaian</label>
                <select id="status_filter" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="graded">Sudah Dinilai</option>
                    <option value="ungraded">Belum Dinilai</option>
                </select>
            </div>

            <div class="col-md-3 mb-3 d-flex align-items-end">
                <button id="reset_filters" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-undo me-2"></i>Reset Filter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <ul class="nav nav-tabs card-header-tabs" id="penilaianTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" data-tab="all">
                    <i class="fas fa-list me-2"></i>Semua Penilaian
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="assignments-tab" data-bs-toggle="tab" data-bs-target="#assignments" type="button" role="tab" data-tab="assignments">
                    <i class="fas fa-tasks me-2"></i>Tugas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="practicals-tab" data-bs-toggle="tab" data-bs-target="#practicals" type="button" role="tab" data-tab="practicals">
                    <i class="fas fa-flask me-2"></i>Praktikum
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="assessmentTable">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px">#</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Jenis</th>
                        <th>Judul Aktivitas</th>
                        <th>Nilai</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th style="width: 120px">Aksi</th>
                    </tr>
                </thead>
                <tbody id="assessmentTableBody">
                    @forelse($assessments as $index => $assessment)
                    <tr class="assessment-row"
                        data-type="{{ $assessment->type }}"
                        data-subject="{{ $assessment->subject_id }}"
                        data-class="{{ $assessment->class }}"
                        data-status="{{ $assessment->score !== null ? 'graded' : 'ungraded' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                    <span class="text-primary fw-bold">{{ substr($assessment->student->name ?? 'N/A', 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $assessment->student->name ?? 'Tidak tersedia' }}</div>
                                    <small class="text-muted">NIS: {{ $assessment->student->nis ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $assessment->class }}</span>
                        </td>
                        <td>{{ $assessment->subject->name ?? 'Tidak tersedia' }}</td>
                        <td>
                            @if($assessment->type === 'assignment')
                                <span class="badge bg-info">Tugas</span>
                            @else
                                <span class="badge bg-success">Praktikum</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-medium">
                                @if($assessment->type === 'assignment')
                                    {{ $assessment->activity->title ?? 'Tanpa judul' }}
                                @else
                                    {{ $assessment->activity->judul ?? 'Tanpa judul' }}
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($assessment->score !== null)
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-primary me-1">{{ number_format($assessment->score, 1) }}</span>
                                    <small class="text-muted">/ {{ $assessment->max_score }}</small>
                                </div>
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar" style="width: {{ ($assessment->score / $assessment->max_score) * 100 }}%"></div>
                                </div>
                            @else
                                <span class="text-muted fst-italic">Belum dinilai</span>
                            @endif
                        </td>
                        <td>
                            @if($assessment->score !== null)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Dinilai
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Pending
                                </span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $assessment->assessment_date ? $assessment->assessment_date->format('d M Y') : '-' }}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('guru.penilaian.edit', $assessment->id) }}" 
                                   class="btn btn-outline-primary" 
                                   data-bs-toggle="tooltip" 
                                   title="Edit Penilaian">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('guru.penilaian.destroy', $assessment->id) }}" 
                                      method="POST" 
                                      class="d-inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus penilaian ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="tooltip" 
                                            title="Hapus Penilaian">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon mb-3">
                                    <i class="fas fa-clipboard-list text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted mb-3">Belum Ada Penilaian</h5>
                                <p class="text-muted mb-4">Belum ada data penilaian yang tersedia. Mulai buat penilaian untuk siswa Anda.</p>
                                <a href="{{ route('guru.penilaian.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Buat Penilaian Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('css')
<style>
.avatar-sm {
    width: 2rem;
    height: 2rem;
}

.stats-card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.progress {
    background-color: #e9ecef;
}

.progress-bar {
    background: linear-gradient(45deg, var(--bs-primary), #0d6efd);
}

.empty-state {
    padding: 3rem 2rem;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Get elements
    const tabButtons = document.querySelectorAll('[data-tab]');
    const rows = document.querySelectorAll('.assessment-row');
    const subjectFilter = document.getElementById('subject_filter');
    const classFilter = document.getElementById('class_filter');
    const statusFilter = document.getElementById('status_filter');
    const resetButton = document.getElementById('reset_filters');

    function filterTable() {
        const activeTab = document.querySelector('[data-tab].active')?.dataset.tab || 'all';
        const subjectValue = subjectFilter.value;
        const classValue = classFilter.value;
        const statusValue = statusFilter.value;
        
        let visibleCount = 0;

        rows.forEach(row => {
            const type = row.dataset.type;
            const subject = row.dataset.subject;
            const klass = row.dataset.class;
            const status = row.dataset.status;

            let show = true;

            // Filter by tab
            if (activeTab !== 'all') {
                if (activeTab === 'assignments' && type !== 'assignment') show = false;
                if (activeTab === 'practicals' && type !== 'practical') show = false;
            }

            // Filter by subject
            if (subjectValue && subjectValue !== subject) show = false;

            // Filter by class
            if (classValue && classValue !== klass) show = false;

            // Filter by status
            if (statusValue && statusValue !== status) show = false;

            if (show) {
                row.style.display = '';
                visibleCount++;
                // Update row number
                row.querySelector('td:first-child').textContent = visibleCount;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide empty state
        const emptyRow = document.querySelector('tbody tr td[colspan]')?.closest('tr');
        if (emptyRow) {
            emptyRow.style.display = visibleCount === 0 && rows.length === 1 ? '' : 'none';
        }
    }

    // Tab click event using Bootstrap tabs
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active from all tabs
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // Add active to clicked tab
            this.classList.add('active');
            // Filter table
            setTimeout(filterTable, 100);
        });
    });

    // Filter change events
    if (subjectFilter) subjectFilter.addEventListener('change', filterTable);
    if (classFilter) classFilter.addEventListener('change', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);

    // Reset filters
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (subjectFilter) subjectFilter.value = '';
            if (classFilter) classFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            
            // Reset active tab
            tabButtons.forEach(btn => btn.classList.remove('active'));
            const allTab = document.querySelector('[data-tab="all"]');
            if (allTab) allTab.classList.add('active');
            
            filterTable();
        });
    }

    // Initialize first tab as active
    const firstTab = document.querySelector('[data-tab="all"]');
    if (firstTab && !document.querySelector('[data-tab].active')) {
        firstTab.classList.add('active');
    }
    
    // Initial filter
    filterTable();
    
    // Auto-refresh every 5 minutes to check for new assessments
    setTimeout(function() {
        location.reload();
    }, 300000);
    
    // Highlight rows that need attention
    $('.assessment-row').each(function() {
        const status = $(this).data('status');
        if (status === 'ungraded') {
            $(this).addClass('table-warning bg-opacity-25');
        }
    });
});
</script>
@endpush
@endsection
