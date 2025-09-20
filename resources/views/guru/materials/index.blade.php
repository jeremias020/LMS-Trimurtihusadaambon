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
            <i class="fas fa-check-square me-1"></i>
            Aksi Massal
        </button>
        <a href="{{ route('guru.materials.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Tambah Materi Baru
        </a>
    </div>
@endsection

@section('content')

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card bg-primary bg-opacity-10 border border-primary border-opacity-25">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-primary text-white rounded p-3 me-3">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">Total Materi</small>
                    <h3 class="mb-0 text-primary">{{ $materials->total() }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card bg-success bg-opacity-10 border border-success border-opacity-25">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-success text-white rounded p-3 me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">Diterbitkan</small>
                    <h3 class="mb-0 text-success">{{ $materials->where('is_published', true)->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card bg-warning bg-opacity-10 border border-warning border-opacity-25">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-warning text-white rounded p-3 me-3">
                        <i class="fas fa-download fa-2x"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">Total Download</small>
                    <h3 class="mb-0 text-warning">{{ $materials->sum('downloads_count') }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card bg-info bg-opacity-10 border border-info border-opacity-25">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-info text-white rounded p-3 me-3">
                        <i class="fas fa-database fa-2x"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">Total Size</small>
                    <h3 class="mb-0 text-info">{{ number_format(($totalSize ?? 0) / 1024 / 1024, 1) }} MB</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
@if($materials->count() > 0)
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i>
            Filter & Pencarian
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="searchInput" class="form-label">Cari Materi</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari judul materi...">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="subjectFilter" class="form-label">Mata Pelajaran</label>
                <select id="subjectFilter" class="form-select">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->nama ?? $subject->name ?? 'Subject' }}</option>
                    @endforeach
                </select>
            </div>
                <div class="col-md-3 mb-3">
                    <label for="statusFilter" class="form-label">Status</label>
                    <select id="statusFilter" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="published">Diterbitkan</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div class="col-md-1 mb-3 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" id="selectAllCheckbox" class="form-check-input" onchange="selectAllMaterials()">
                        <label class="form-check-label" for="selectAllCheckbox" title="Pilih/Batal pilih semua">
                            <small>Pilih Semua</small>
                        </label>
                    </div>
                </div>
        </div>
    </div>
</div>
@endif

<!-- Materials Grid -->
<div class="row g-4" id="materialsContainer">
    @forelse($materials as $material)
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card h-100 shadow-sm material-card" data-subject-id="{{ $material->subject_id }}">
            <!-- Card Header -->
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                <div class="d-flex align-items-center gap-2">
                    <input type="checkbox" class="form-check-input material-checkbox" 
                           value="{{ $material->id }}" 
                           onchange="toggleMaterialSelection({{ $material->id }})">
                    <span class="badge {{ $material->is_published ? 'bg-success' : 'bg-secondary' }} px-2 py-1">
                        {{ $material->is_published ? 'Diterbitkan' : 'Draft' }}
                    </span>
                </div>
                <small class="text-muted">{{ $material->created_at->diffForHumans() }}</small>
            </div>
            
            <!-- Card Body -->
            <div class="card-body d-flex flex-column">
                <!-- File Type Icon -->
                <div class="text-center mb-3">
                    @php
                        $fileType = strtolower($material->file_type ?? 'file');
                        $iconClass = match($fileType) {
                            'pdf' => 'fas fa-file-pdf text-danger',
                            'doc', 'docx' => 'fas fa-file-word text-primary',
                            'ppt', 'pptx' => 'fas fa-file-powerpoint text-warning',
                            'xls', 'xlsx' => 'fas fa-file-excel text-success',
                            'mp4', 'avi', 'mov' => 'fas fa-file-video text-info',
                            'jpg', 'jpeg', 'png' => 'fas fa-file-image text-info',
                            'zip', 'rar' => 'fas fa-file-archive text-secondary',
                            default => 'fas fa-file text-muted'
                        };
                    @endphp
                    <i class="{{ $iconClass }} fa-2x"></i>
                </div>
                
                <!-- Material Info -->
                <h6 class="card-title fw-bold mb-2">{{ $material->judul }}</h6>
                <p class="card-text text-muted small mb-3 flex-grow-1">{{ Str::limit($material->description ?? 'Tidak ada deskripsi', 80) }}</p>
                
                <!-- Subject & Category -->
                <div class="mb-3">
                    <span class="badge bg-primary me-1 mb-1">
                        <i class="fas fa-book me-1"></i>
                        {{ $material->subject->nama ?? $material->subject->name ?? 'Subject' }}
                    </span>
                    <span class="badge bg-info mb-1">
                        <i class="fas fa-tag me-1"></i>
                        {{ $material->category }}
                    </span>
                </div>
                
                <!-- Statistics -->
                <div class="d-flex justify-content-between text-muted small">
                    <span>
                        <i class="fas fa-download me-1"></i>
                        {{ $material->downloads_count ?? 0 }} downloads
                    </span>
                    <span>
                        <i class="fas fa-hdd me-1"></i>
                        {{ $material->file_size_formatted ?? 'Unknown' }}
                    </span>
                </div>
            </div>
            
            <!-- Card Actions -->
            <div class="card-footer bg-light">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <a href="{{ route('guru.materials.show', $material->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>
                        Lihat
                    </a>
                    <a href="{{ route('guru.materials.edit', $material->id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-edit me-1"></i>
                        Edit
                    </a>
                    @if($material->file)
                    <a href="{{ route('guru.materials.download', $material->id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download me-1"></i>
                        Download
                    </a>
                    @endif
                </div>
                
                <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('guru.materials.toggle-publish', $material->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn {{ $material->is_published ? 'btn-warning' : 'btn-success' }} btn-sm" 
                                title="{{ $material->is_published ? 'Sembunyikan materi dari siswa' : 'Terbitkan materi untuk siswa' }}">
                            <i class="fas {{ $material->is_published ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>
                            {{ $material->is_published ? 'Sembunyikan' : 'Terbitkan' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('guru.materials.destroy', $material->id) }}" class="d-inline" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi \"{{ addslashes($material->judul) }}\"?\n\nTindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus materi ini">
                            <i class="fas fa-trash me-1"></i>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <h5 class="card-title">Belum Ada Materi Pembelajaran</h5>
                <p class="card-text text-muted">Mulai dengan menambahkan materi pembelajaran pertama untuk siswa Anda.</p>
                <a href="{{ route('guru.materials.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Materi Pertama
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($materials->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $materials->links() }}
</div>
@endif

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Pilih aksi yang ingin dilakukan pada materi yang dipilih:</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" onclick="bulkAction('publish')">
                        <i class="fas fa-eye me-2"></i>Terbitkan Semua
                    </button>
                    <button type="button" class="btn btn-warning" onclick="bulkAction('unpublish')">
                        <i class="fas fa-eye-slash me-2"></i>Sembunyikan Semua
                    </button>
                    <button type="button" class="btn btn-danger" onclick="bulkAction('delete')">
                        <i class="fas fa-trash me-2"></i>Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const searchInput = document.getElementById('searchInput');
    const subjectFilter = document.getElementById('subjectFilter');
    const statusFilter = document.getElementById('statusFilter');
    const materialCards = document.querySelectorAll('.material-card');

    function filterMaterials() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const subjectValue = subjectFilter?.value || '';
        const statusValue = statusFilter?.value || '';
        
        materialCards.forEach(card => {
            const cardElement = card.closest('.col-xl-4');
            const title = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
            const description = card.querySelector('.card-text')?.textContent.toLowerCase() || '';
            const subjectId = card.dataset.subjectId;
            const statusBadge = card.querySelector('.badge:not(.bg-primary):not(.bg-info)');
            const isPublished = statusBadge?.textContent.includes('Diterbitkan');
            
            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            const matchesSubject = !subjectValue || subjectId == subjectValue;
            const matchesStatus = !statusValue || 
                (statusValue === 'published' && isPublished) ||
                (statusValue === 'draft' && !isPublished);
            
            if (matchesSearch && matchesSubject && matchesStatus) {
                cardElement.style.display = 'block';
            } else {
                cardElement.style.display = 'none';
            }
        });
    }

    // Event listeners
    searchInput?.addEventListener('input', filterMaterials);
    subjectFilter?.addEventListener('change', filterMaterials);
    statusFilter?.addEventListener('change', filterMaterials);
});


// Bulk actions
let selectedMaterials = [];

function toggleMaterialSelection(materialId) {
    const index = selectedMaterials.indexOf(materialId);
    if (index > -1) {
        selectedMaterials.splice(index, 1);
    } else {
        selectedMaterials.push(materialId);
    }
    
    // Update select all checkbox state
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const visibleMaterialCheckboxes = Array.from(document.querySelectorAll('.material-checkbox'))
        .filter(cb => cb.closest('.col-xl-4').style.display !== 'none');
    const allVisibleSelected = visibleMaterialCheckboxes.length > 0 && 
        visibleMaterialCheckboxes.every(cb => cb.checked);
    
    selectAllCheckbox.checked = allVisibleSelected;
    
    updateBulkActionButton();
}

function updateBulkActionButton() {
    const button = document.getElementById('bulkActionButton');
    if (selectedMaterials.length > 0) {
        button.style.display = 'block';
        button.innerHTML = `<i class="fas fa-check-square me-1"></i>Aksi Massal (${selectedMaterials.length})`;
    } else {
        button.style.display = 'none';
    }
}

function selectAllMaterials() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const materialCheckboxes = document.querySelectorAll('.material-checkbox');
    
    selectedMaterials = [];
    
    materialCheckboxes.forEach(checkbox => {
        const materialCard = checkbox.closest('.material-card');
        const isVisible = materialCard.closest('.col-xl-4').style.display !== 'none';
        
        if (isVisible) {
            checkbox.checked = selectAllCheckbox.checked;
            if (selectAllCheckbox.checked) {
                selectedMaterials.push(parseInt(checkbox.value));
            }
        }
    });
    
    updateBulkActionButton();
}

function showBulkActionModal() {
    if (selectedMaterials.length === 0) {
        alert('Pilih minimal satu materi untuk melakukan aksi massal.');
        return;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
    modal.show();
}

function bulkAction(action) {
    if (selectedMaterials.length === 0) return;
    
    let confirmMessage;
    let url;
    
    switch(action) {
        case 'publish':
            confirmMessage = `Terbitkan ${selectedMaterials.length} materi yang dipilih?`;
            url = '{{ route('guru.materials.bulk-publish') }}';
            break;
        case 'unpublish':
            confirmMessage = `Sembunyikan ${selectedMaterials.length} materi yang dipilih?`;
            url = '{{ route('guru.materials.bulk-unpublish') }}';
            break;
        case 'delete':
            confirmMessage = `Hapus ${selectedMaterials.length} materi yang dipilih?\n\nTindakan ini tidak dapat dibatalkan!`;
            url = '{{ route('guru.materials.bulk-delete') }}';
            break;
        default:
            return;
    }
    
    if (!confirm(confirmMessage)) return;
    
    // Create and submit form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
    form.appendChild(csrfInput);
    
    // Add selected materials
    selectedMaterials.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
}

// Show success/error messages
@if(session('success'))
    showBootstrapAlert('{{ session('success') }}', 'success');
@elseif(session('error'))
    showBootstrapAlert('{{ session('error') }}', 'danger');
@endif

function showBootstrapAlert(message, type = 'info') {
    const alertContainer = document.createElement('div');
    alertContainer.className = 'position-fixed top-0 end-0 p-3';
    alertContainer.style.zIndex = '1060';
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.setAttribute('role', 'alert');
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    alertContainer.appendChild(alert);
    document.body.appendChild(alertContainer);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertContainer.parentNode) {
            alertContainer.remove();
        }
    }, 5000);
}
</script>
@endpush
@endsection
