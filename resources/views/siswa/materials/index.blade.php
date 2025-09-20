@extends('layouts.siswa')

@section('title', 'Materi Pembelajaran')
@section('page-title', 'Materi Pembelajaran')
@section('page-subtitle', 'Akses dan unduh materi pembelajaran dari guru Anda')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Materi Pembelajaran</li>
@endsection

@section('content')

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card hover-lift">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-book text-white"></i>
                    </div>
                    <div class="stats-info">
                        <h5>{{ $materials->total() }}</h5>
                        <p>Total Materi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card hover-lift">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="fas fa-download text-white"></i>
                    </div>
                    <div class="stats-info">
                        <h5>{{ $downloadedCount ?? 0 }}</h5>
                        <p>Sudah Diunduh</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card hover-lift">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div class="stats-info">
                        <h5>{{ $recentCount ?? 0 }}</h5>
                        <p>Terbaru (7 hari)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card hover-lift">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-star text-white"></i>
                    </div>
                    <div class="stats-info">
                        <h5>{{ $favoriteCount ?? 0 }}</h5>
                        <p>Favorit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
@if($materials->count() > 0)
<div class="card filter-card hover-lift mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>
            Filter & Pencarian
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('siswa.materials.index') }}" method="GET">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="searchInput" class="form-label">Cari Materi</label>
                    <div class="input-group search-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" id="searchInput" class="form-control" 
                               placeholder="Cari judul materi..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="subjectFilter" class="form-label">Mata Pelajaran</label>
                    <select name="subject" id="subjectFilter" class="form-select">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($subjects ?? [] as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->nama ?? $subject->name ?? 'Subject' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="categoryFilter" class="form-label">Kategori</label>
                    <select name="category" id="categoryFilter" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="Teori" {{ request('category') == 'Teori' ? 'selected' : '' }}>Teori</option>
                        <option value="Praktik" {{ request('category') == 'Praktik' ? 'selected' : '' }}>Praktik</option>
                        <option value="Tugas" {{ request('category') == 'Tugas' ? 'selected' : '' }}>Tugas</option>
                        <option value="Ujian" {{ request('category') == 'Ujian' ? 'selected' : '' }}>Ujian</option>
                        <option value="Referensi" {{ request('category') == 'Referensi' ? 'selected' : '' }}>Referensi</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Materials Grid -->
<div class="row g-4" id="materialsContainer">
    @forelse($materials as $material)
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card h-100 shadow-sm material-card" data-subject-id="{{ $material->subject_id }}" data-category="{{ $material->category }}">
            <!-- Card Header -->
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                <span class="badge bg-success px-2 py-1">
                    <i class="fas fa-check-circle me-1"></i>Tersedia
                </span>
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
                <p class="card-text text-muted small mb-3 flex-grow-1">
                    {{ Str::limit($material->description ?? 'Tidak ada deskripsi', 80) }}
                </p>
                
                <!-- Teacher Info -->
                <div class="mb-3">
                    <small class="text-muted d-block">
                        <i class="fas fa-user me-1"></i>
                        Oleh: {{ $material->guru->name ?? $material->teacher->name ?? 'Unknown Teacher' }}
                    </small>
                </div>
                
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
                
                <!-- Download Status -->
                @if($material->downloads->where('siswa_id', Auth::id())->count() > 0)
                <div class="mt-2">
                    <small class="text-success">
                        <i class="fas fa-check-circle me-1"></i>
                        Sudah diunduh pada {{ $material->downloads->where('siswa_id', Auth::id())->first()->downloaded_at->format('d M Y H:i') }}
                    </small>
                </div>
                @endif
            </div>
            
            <!-- Card Actions -->
            <div class="card-footer bg-light">
                <div class="d-flex gap-2">
                    <a href="{{ route('siswa.materials.show', $material->id) }}" class="btn btn-outline-primary btn-sm flex-fill">
                        <i class="fas fa-eye me-1"></i>
                        Lihat Detail
                    </a>
                    @if($material->file)
                    <a href="{{ route('siswa.materials.download', $material->id) }}" class="btn btn-success btn-sm flex-fill">
                        <i class="fas fa-download me-1"></i>
                        Download
                    </a>
                    @endif
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
                <p class="card-text text-muted">
                    @if(request('search'))
                        Tidak ditemukan materi dengan kata kunci "{{ request('search') }}".
                        <br><a href="{{ route('siswa.materials.index') }}">Lihat semua materi</a>
                    @else
                        Belum ada materi pembelajaran yang tersedia saat ini.
                        <br>Silakan cek kembali nanti atau hubungi guru Anda.
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($materials->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $materials->appends(request()->query())->links() }}
</div>
@endif

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show success/error messages
    @if(session('success'))
        showBootstrapAlert('{{ session('success') }}', 'success');
    @elseif(session('error'))
        showBootstrapAlert('{{ session('error') }}', 'danger');
    @endif
    
    // Download tracking
    document.querySelectorAll('a[href*="download"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const materialId = this.href.split('/').pop();
            
            // Track download via AJAX (optional)
            fetch(`/siswa/materials/${materialId}/track-download`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            }).catch(console.error);
        });
    });
});

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
