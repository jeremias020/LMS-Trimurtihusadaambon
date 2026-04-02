@extends('layouts.guru')

@section('title', 'Materi Pembelajaran')
@section('page-title', 'Materi Pembelajaran')
@section('page-subtitle', 'Kelola dan upload materi pembelajaran untuk siswa')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Materi Pembelajaran</li>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        <button id="bulkActionButton" class="btn btn-outline-secondary" onclick="showBulkActionModal()" style="display: none;">
            <i class="fas fa-layer-group me-2"></i>
            Aksi Massal
        </button>
        <a href="{{ route('guru.materials.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>
            Tambah Materi Baru
        </a>
    </div>
@endsection

@section('content')
<!-- Modern Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card border-0 shadow-sm modern-stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="modern-icon bg-primary bg-gradient-primary">
                            <i class="fas fa-book-open"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Materi</h6>
                        <h3 class="mb-0 fw-bold text-primary">{{ $materials->total() }}</h3>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-primary" style="width: 75%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card border-0 shadow-sm modern-stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="modern-icon bg-success bg-gradient-success">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Diterbitkan</h6>
                        <h3 class="mb-0 fw-bold text-success">{{ $materials->where('published_at', '!=', null)->count() }}</h3>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-success" style="width: 60%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card border-0 shadow-sm modern-stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="modern-icon bg-info bg-gradient-info">
                            <i class="fas fa-download"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Download</h6>
                        <h3 class="mb-0 fw-bold text-info">{{ $materials->sum('downloads_count') ?? 0 }}</h3>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-info" style="width: 45%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card border-0 shadow-sm modern-stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="modern-icon bg-warning bg-gradient-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Draft</h6>
                        <h3 class="mb-0 fw-bold text-warning">{{ $materials->where('published_at', null)->count() }}</h3>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-warning" style="width: 25%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Filter Section -->
@if($materials->count() > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-filter me-2 text-primary"></i>
                Filter & Pencarian
            </h5>
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleAdvancedFilters()">
                <i class="fas fa-chevron-down me-1"></i>
                Lanjutan
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-lg-4">
                <label for="searchInput" class="form-label fw-semibold">
                    <i class="fas fa-search me-1 text-muted"></i>
                    Cari Materi
                </label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control border-start-0" 
                           placeholder="Cari judul atau deskripsi materi...">
                </div>
            </div>
            
            <div class="col-lg-3">
                <label for="subjectFilter" class="form-label fw-semibold">
                    <i class="fas fa-book me-1 text-muted"></i>
                    Mata Pelajaran
                </label>
                <select id="subjectFilter" class="form-select form-select-lg">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->nama ?? $subject->name ?? 'Subject' }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-lg-2">
                <label for="statusFilter" class="form-label fw-semibold">
                    <i class="fas fa-toggle-on me-1 text-muted"></i>
                    Status
                </label>
                <select id="statusFilter" class="form-select form-select-lg">
                    <option value="">Semua Status</option>
                    <option value="published">Diterbitkan</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
            
            <div class="col-lg-3">
                <label class="form-label fw-semibold">
                    <i class="fas fa-tasks me-1 text-muted"></i>
                    Aksi Cepat
                </label>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary flex-fill" onclick="clearFilters()">
                        <i class="fas fa-times me-1"></i>
                        Reset
                    </button>
                    <div class="form-check form-switch d-flex align-items-center">
                        <input type="checkbox" id="selectAllCheckbox" class="form-check-input" onchange="selectAllMaterials()">
                        <label class="form-check-label ms-2" for="selectAllCheckbox" title="Pilih/Batal pilih semua">
                            Pilih Semua
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Advanced Filters (Hidden by default) -->
        <div id="advancedFilters" class="row g-3 mt-3" style="display: none;">
            <div class="col-lg-3">
                <label for="dateFromFilter" class="form-label fw-semibold">
                    <i class="fas fa-calendar-alt me-1 text-muted"></i>
                    Dari Tanggal
                </label>
                <input type="date" id="dateFromFilter" class="form-control">
            </div>
            <div class="col-lg-3">
                <label for="dateToFilter" class="form-label fw-semibold">
                    <i class="fas fa-calendar-alt me-1 text-muted"></i>
                    Sampai Tanggal
                </label>
                <input type="date" id="dateToFilter" class="form-control">
            </div>
            <div class="col-lg-3">
                <label for="fileTypeFilter" class="form-label fw-semibold">
                    <i class="fas fa-file me-1 text-muted"></i>
                    Tipe File
                </label>
                <select id="fileTypeFilter" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="pdf">PDF</option>
                    <option value="doc">Document</option>
                    <option value="ppt">Presentation</option>
                    <option value="video">Video</option>
                    <option value="audio">Audio</option>
                </select>
            </div>
            <div class="col-lg-3">
                <label for="sortBy" class="form-label fw-semibold">
                    <i class="fas fa-sort me-1 text-muted"></i>
                    Urutkan
                </label>
                <select id="sortBy" class="form-select">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="name">Nama A-Z</option>
                    <option value="downloads">Terbanyak Diunduh</option>
                </select>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modern Materials Grid -->
<div class="row g-4" id="materialsContainer">
    @forelse($materials as $material)
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm modern-material-card" data-subject-id="{{ $material->subject_id }}">
            <!-- Card Header with Status Badge -->
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="file-icon me-2">
                            @switch($material->file_type ?? 'pdf')
                                @case('pdf')
                                    <i class="fas fa-file-pdf text-danger"></i>
                                @break
                                @case('doc')
                                    <i class="fas fa-file-word text-primary"></i>
                                @break
                                @case('ppt')
                                    <i class="fas fa-file-powerpoint text-warning"></i>
                                @break
                                @case('video')
                                    <i class="fas fa-file-video text-info"></i>
                                @break
                                @case('audio')
                                    <i class="fas fa-file-audio text-success"></i>
                                @break
                                @default
                                    <i class="fas fa-file text-secondary"></i>
                            @endswitch
                        </div>
                        <div>
                            <h6 class="mb-1 fw-semibold text-truncate" title="{{ $material->judul }}">
                                {{ $material->judul }}
                            </h6>
                            <small class="text-muted">
                                <i class="fas fa-book me-1"></i>
                                {{ $material->subject->name ?? 'Unknown Subject' }}
                            </small>
                        </div>
                    </div>
                    <div>
                        @if($material->published_at)
                            <span class="badge bg-success bg-gradient-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Diterbitkan
                            </span>
                        @else
                            <span class="badge bg-warning bg-gradient-warning">
                                <i class="fas fa-clock me-1"></i>
                                Draft
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Card Body with Preview -->
            <div class="card-body">
                <p class="card-text text-muted small mb-3" style="height: 60px; overflow: hidden;">
                    {{ Str::limit(strip_tags($material->content ?? ''), 100) }}
                </p>
                
                <!-- File Info -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex gap-3">
                        <span class="text-muted small">
                            <i class="fas fa-download me-1 text-primary"></i>
                            {{ $material->downloads_count ?? 0 }}
                        </span>
                        <span class="text-muted small">
                            <i class="fas fa-hdd me-1 text-info"></i>
                            {{ $material->file_size_formatted ?? 'Unknown' }}
                        </span>
                    </div>
                    <div class="text-muted small">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $material->created_at->format('d M Y') }}
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm flex-fill" onclick="viewMaterial({{ $material->id }})">
                        <i class="fas fa-eye me-1"></i>
                        Lihat
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="editMaterial({{ $material->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    @if($material->file)
                    <button class="btn btn-success btn-sm" onclick="downloadMaterial({{ $material->id }})">
                        <i class="fas fa-download"></i>
                    </button>
                    @endif
                </div>
            </div>
            
            <!-- Card Footer with Actions -->
            <div class="card-footer bg-light border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input material-checkbox" 
                               value="{{ $material->id }}" onchange="updateBulkAction()">
                        <label class="form-check-label small" for="material{{ $material->id }}">
                            Pilih
                        </label>
                    </div>
                    
                    <div class="btn-group btn-group-sm">
                        <form method="POST" action="{{ route('guru.materials.toggle-publish', $material->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn {{ $material->published_at ? 'btn-warning' : 'btn-success' }}" 
                                    title="{{ $material->published_at ? 'Sembunyikan materi dari siswa' : 'Terbitkan materi untuk siswa' }}">
                                <i class="fas {{ $material->published_at ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('guru.materials.destroy', $material->id) }}" class="d-inline" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi \"{{ addslashes($material->judul) }}\"?\n\nTindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" title="Hapus materi ini">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-folder-open fa-4x text-muted"></i>
                </div>
                <h4 class="text-muted mb-3">Belum Ada Materi</h4>
                <p class="text-muted mb-4">Mulai dengan menambahkan materi pembelajaran pertama Anda.</p>
                <a href="{{ route('guru.materials.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>
                    Tambah Materi Baru
                </a>
            </div>
        </div>
    @endforelse
</div>

<!-- Modern Pagination -->
@if($materials->hasPages())
<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted">
        Menampilkan {{ $materials->firstItem() }} - {{ $materials->lastItem() }} dari {{ $materials->total() }} materi
    </div>
    {{ $materials->links() }}
</div>
@endif

@endsection

@push('styles')
<style>
.modern-stats-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.modern-stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.modern-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.bg-gradient-success { background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%); }
.bg-gradient-info { background: linear-gradient(135deg, #0093E9 0%, #80D0C7 100%); }
.bg-gradient-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }

.modern-material-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.modern-material-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}

.file-icon {
    font-size: 1.5rem;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(0,0,0,0.05);
}

.progress {
    border-radius: 10px;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.badge {
    font-size: 0.7rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
}
</style>
@endpush

@push('scripts')
<script>
function toggleAdvancedFilters() {
    const filters = document.getElementById('advancedFilters');
    const button = event.target.closest('button');
    
    if (filters.style.display === 'none') {
        filters.style.display = 'flex';
        button.innerHTML = '<i class="fas fa-chevron-up me-1"></i>Sederhana';
    } else {
        filters.style.display = 'none';
        button.innerHTML = '<i class="fas fa-chevron-down me-1"></i>Lanjutan';
    }
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('subjectFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFromFilter').value = '';
    document.getElementById('dateToFilter').value = '';
    document.getElementById('fileTypeFilter').value = '';
    document.getElementById('sortBy').value = 'newest';
    
    // Trigger search to reset results
    filterMaterials();
}

function viewMaterial(id) {
    window.location.href = `/guru/materials/${id}`;
}

function editMaterial(id) {
    window.location.href = `/guru/materials/${id}/edit`;
}

function downloadMaterial(id) {
    window.location.href = `/guru/materials/${id}/download`;
}

// Enhanced search and filter functionality
function filterMaterials() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const subjectFilter = document.getElementById('subjectFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    const cards = document.querySelectorAll('.modern-material-card');
    
    cards.forEach(card => {
        const title = card.querySelector('h6').textContent.toLowerCase();
        const subjectId = card.dataset.subjectId;
        const statusBadge = card.querySelector('.badge');
        const isPublished = statusBadge.textContent.includes('Diterbitkan');
        
        let show = true;
        
        // Search filter
        if (searchTerm && !title.includes(searchTerm)) {
            show = false;
        }
        
        // Subject filter
        if (subjectFilter && subjectId !== subjectFilter) {
            show = false;
        }
        
        // Status filter
        if (statusFilter === 'published' && !isPublished) {
            show = false;
        } else if (statusFilter === 'draft' && isPublished) {
            show = false;
        }
        
        card.parentElement.style.display = show ? 'block' : 'none';
    });
}

// Add event listeners
document.getElementById('searchInput')?.addEventListener('input', filterMaterials);
document.getElementById('subjectFilter')?.addEventListener('change', filterMaterials);
document.getElementById('statusFilter')?.addEventListener('change', filterMaterials);

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
