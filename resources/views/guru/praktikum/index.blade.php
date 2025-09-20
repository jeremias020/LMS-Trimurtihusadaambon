@extends('layouts.guru')

@section('title', 'Manajemen Praktikum - LMS Trimurti Husada')
@section('page-title', 'Manajemen Praktikum')
@section('page-subtitle', 'Kelola kegiatan praktikum untuk siswa')

@section('page-actions')
<a href="{{ route('guru.praktikum.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>Buat Praktikum
</a>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active">Manajemen Praktikum</li>
@endsection

@section('content')

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-flask fa-2x opacity-75"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="small opacity-75 text-uppercase fw-medium">Total Praktikum</div>
                        <div class="h3 mb-0 fw-bold">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="small opacity-75 text-uppercase fw-medium">Dipublikasikan</div>
                        <div class="h3 mb-0 fw-bold">{{ $stats['published'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="small opacity-75 text-uppercase fw-medium">Mendatang</div>
                        <div class="h3 mb-0 fw-bold">{{ $stats['upcoming'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="small opacity-75 text-uppercase fw-medium">Total Peserta</div>
                        <div class="h3 mb-0 fw-bold">{{ $practicals->sum('participant_count') ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="card border-0 shadow-lg">
    <div class="card-header bg-gradient-primary text-white border-0">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-20 rounded-3 p-2 me-3">
                    <i class="fas fa-flask fa-lg"></i>
                </div>
                <div>
                    <h5 class="mb-1 fw-bold">Daftar Praktikum</h5>
                    <small class="opacity-90">{{ $practicals->total() }} praktikum ditemukan</small>
                </div>
            </div>
            <div class="text-end">
                <button class="btn btn-light btn-sm" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Collapsible Filter -->
        <div class="collapse" id="filterCollapse">
            <div class="border rounded-3 p-3 mb-4 bg-light">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Cari Praktikum</label>
                        <input type="text" placeholder="Cari judul praktikum..." class="form-control" id="searchInput">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Mata Pelajaran</label>
                        <select class="form-select" id="subjectFilter">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Kelas</label>
                        <select class="form-select" id="classFilter">
                            <option value="">Semua Kelas</option>
                            <option value="X">Kelas X</option>
                            <option value="XI">Kelas XI</option>
                            <option value="XII">Kelas XII</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="upcoming">Mendatang</option>
                            <option value="completed">Selesai</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clearFilters">
                        <i class="fas fa-times me-1"></i>Bersihkan Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="text-center py-5 d-none">
            <div class="mb-3">
                <i class="fas fa-search fa-3x text-muted opacity-50"></i>
            </div>
            <h5 class="text-muted">Tidak ada praktikum yang cocok</h5>
            <p class="text-muted small">Coba ubah filter pencarian Anda.</p>
        </div>

        <!-- Praktikum Grid -->
        <div class="row g-4" id="praktikumGrid">
            @forelse($practicals as $practical)
            <div class="col-lg-4 col-md-6">
                <div class="card practical-card h-100 border-0 shadow-sm" 
                 data-subject-id="{{ $practical->subject_id }}" data-class="{{ $practical->kelas_id }}" data-status="{{ $practical->status }}">
                    <div class="card-header border-0 bg-transparent pb-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="badge 
                                @if($practical->status === 'completed') bg-success
                                @elseif($practical->status === 'upcoming') bg-warning  
                                @else bg-secondary
                                @endif">
                                @if($practical->status === 'completed') 
                                    <i class="fas fa-check me-1"></i>Selesai
                                @elseif($practical->status === 'upcoming') 
                                    <i class="fas fa-clock me-1"></i>Mendatang
                                @else 
                                    <i class="fas fa-draft2digital me-1"></i>Draft
                                @endif
                            </span>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $practical->tanggal ? \Carbon\Carbon::parse($practical->tanggal)->translatedFormat('d M Y') : '-' }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-2">{{ $practical->judul }}</h5>
                        <p class="card-text text-muted mb-3">{{ Str::limit($practical->deskripsi, 100) }}</p>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center text-primary">
                                    <i class="fas fa-book-open fa-sm me-2"></i>
                                    <small class="fw-medium">{{ optional($practical->subject)->name ?? 'Tanpa Mata Pelajaran' }}</small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center text-info">
                                    <i class="fas fa-users fa-sm me-2"></i>
                                    <small class="fw-medium">{{ optional($practical->kelas)->name ?? 'Tanpa Kelas' }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center text-success">
                                    <i class="fas fa-user-check fa-sm me-2"></i>
                                    <small>{{ $practical->participant_count ?? 0 }} peserta</small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center text-warning">
                                    <i class="fas fa-clock fa-sm me-2"></i>
                                    <small>{{ $practical->durasi ?? 0 }} menit</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer border-0 bg-transparent">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('guru.praktikum.show', $practical->id) }}"
                               class="btn btn-outline-primary btn-sm" title="Lihat detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('guru.praktikum.edit', $practical->id) }}"
                               class="btn btn-outline-secondary btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('guru.praktikum.destroy', $practical->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus praktikum ini?')" 
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-flask fa-4x text-muted opacity-50"></i>
                    </div>
                    <h4 class="text-muted">Belum ada praktikum</h4>
                    <p class="text-muted mb-4">Mulai dengan membuat praktikum pertama Anda.</p>
                    <a href="{{ route('guru.praktikum.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Buat Praktikum Pertama
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        @if($practicals->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $practicals->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

@push('css')
<style>
/* Enhanced hover effects for cards */
.practical-card {
    transition: all 0.3s ease;
    border-radius: 12px !important;
}

.practical-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Gradient background for header */
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%) !important;
}

/* Animation for statistics cards */
.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}

/* Filter collapse animation */
.collapse {
    transition: all 0.3s ease;
}

/* Button hover effects */
.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    const searchInput = $('#searchInput');
    const subjectFilter = $('#subjectFilter');
    const classFilter = $('#classFilter');
    const statusFilter = $('#statusFilter');
    const clearFiltersBtn = $('#clearFilters');
    const practicalCards = $('.practical-card');
    const noResults = $('#noResults');
    const praktikumGrid = $('#praktikumGrid');

    function filterPracticals() {
        const searchTerm = searchInput.val().toLowerCase();
        const subjectValue = subjectFilter.val();
        const classValue = classFilter.val();
        const statusValue = statusFilter.val();

        let visibleCount = 0;

        practicalCards.each(function() {
            const $card = $(this);
            const $cardParent = $card.parent();
            const title = $card.find('.card-title').text().toLowerCase();
            const subjectId = $card.data('subject-id');
            const classVal = $card.data('class');
            const status = $card.data('status');

            const matchesSearch = title.includes(searchTerm);
            const matchesSubject = !subjectValue || subjectId == subjectValue;
            const matchesClass = !classValue || classVal == classValue;
            const matchesStatus = !statusValue || status == statusValue;

            const shouldShow = matchesSearch && matchesSubject && matchesClass && matchesStatus;
            
            if (shouldShow) {
                $cardParent.show();
                visibleCount++;
            } else {
                $cardParent.hide();
            }
        });

        // Show/hide no results message
        if (visibleCount === 0 && practicalCards.length > 0) {
            noResults.removeClass('d-none');
            praktikumGrid.addClass('d-none');
        } else {
            noResults.addClass('d-none');
            praktikumGrid.removeClass('d-none');
        }
    }

    // Event listeners
    searchInput.on('input', filterPracticals);
    subjectFilter.on('change', filterPracticals);
    classFilter.on('change', filterPracticals);
    statusFilter.on('change', filterPracticals);

    clearFiltersBtn.on('click', function() {
        searchInput.val('');
        subjectFilter.val('');
        classFilter.val('');
        statusFilter.val('');
        filterPracticals();
        
        // Close filter collapse
        $('#filterCollapse').collapse('hide');
    });
    
    // Enhanced delete confirmation
    $('.btn-outline-danger').on('click', function(e) {
        e.preventDefault();
        const $form = $(this).closest('form');
        const praktikumTitle = $(this).closest('.card').find('.card-title').text();
        
        if (confirm(`Apakah Anda yakin ingin menghapus praktikum "${praktikumTitle}"?\n\nTindakan ini tidak dapat dibatalkan!`)) {
            $(this).html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
            $form.submit();
        }
    });
    
    // Filter toggle animation
    $('#filterCollapse').on('show.bs.collapse', function() {
        $('[data-bs-target="#filterCollapse"] i').removeClass('fa-filter').addClass('fa-times');
    }).on('hide.bs.collapse', function() {
        $('[data-bs-target="#filterCollapse"] i').removeClass('fa-times').addClass('fa-filter');
    });
    
    // Add loading states for navigation
    $('.practical-card .btn').on('click', function() {
        if (!$(this).hasClass('btn-outline-danger')) {
            const originalText = $(this).html();
            $(this).html('<i class="fas fa-spinner fa-spin"></i>');
        }
    });
    
    // Initialize tooltips
    $('[title]').tooltip();
    
    console.log('✨ Praktikum page loaded with enhanced interactions!');
});
</script>
@endpush
@endsection
