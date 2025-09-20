@extends('layouts.guru')

@section('title', 'Edit Materi Pembelajaran')
@section('page-title', 'Edit Materi Pembelajaran')
@section('page-subtitle', 'Perbarui informasi dan konten materi pembelajaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('guru.materials.index') }}">Materi Pembelajaran</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Materi</li>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('guru.materials.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali
        </a>
        <a href="{{ route('guru.materials.show', $material->id) }}" class="btn btn-outline-info">
            <i class="fas fa-eye me-2"></i>
            Lihat Detail
        </a>
    </div>
@endsection

@section('content')
<div class="material-edit-form">

<!-- Material Info Card -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-edit me-2"></i>
            Edit: {{ $material->judul }}
        </h5>
        <small class="opacity-75">Terakhir diperbarui: {{ $material->updated_at->format('d M Y H:i') }}</small>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <strong>Mata Pelajaran:</strong><br>
                <span class="badge bg-primary">{{ $material->subject->nama ?? $material->subject->name ?? 'Tidak Diketahui' }}</span>
            </div>
            <div class="col-md-3">
                <strong>Kategori:</strong><br>
                <span class="badge bg-info">{{ $material->category }}</span>
            </div>
            <div class="col-md-3">
                <strong>Status:</strong><br>
                <span class="badge {{ $material->is_published ? 'bg-success' : 'bg-secondary' }}">
                    {{ $material->is_published ? 'Diterbitkan' : 'Draft' }}
                </span>
            </div>
            <div class="col-md-3">
                <strong>Ukuran File:</strong><br>
                <span class="text-muted">{{ $material->file_size_formatted ?? 'Tidak ada' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Edit Form -->
<div class="card">
    <form action="{{ route('guru.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data" id="materialForm">
        @csrf
        @method('PUT')

        <div class="card-body">
            <!-- Informasi Dasar -->
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="text-primary mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Dasar Materi
                    </h4>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="judul" class="form-label">
                        <i class="fas fa-heading me-1"></i>
                        Judul Materi *
                    </label>
                    <input type="text" name="judul" id="judul" class="form-control"
                           value="{{ old('judul', $material->judul) }}" required placeholder="Masukkan judul materi">
                    @error('judul')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="subject_id" class="form-label">
                        <i class="fas fa-book me-1"></i>
                        Mata Pelajaran *
                    </label>
                    <select name="subject_id" id="subject_id" class="form-select" required>
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $material->subject_id) == $subject->id ? 'selected' : '' }}>
                            {{ $subject->nama ?? $subject->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">
                        <i class="fas fa-tag me-1"></i>
                        Kategori *
                    </label>
                    <select name="category" id="category" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $key => $value)
                        <option value="{{ $key }}" {{ old('category', $material->category) == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="is_published" class="form-label">
                        <i class="fas fa-eye me-1"></i>
                        Status Publikasi
                    </label>
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" id="is_published" class="form-check-input" value="1"
                               {{ old('is_published', $material->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">
                            <span class="published-text">{{ $material->is_published ? 'Diterbitkan' : 'Draft' }}</span>
                        </label>
                    </div>
                    <small class="text-muted">Materi yang diterbitkan dapat dilihat oleh siswa</small>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="text-success mb-3">
                        <i class="fas fa-align-left me-2"></i>
                        Deskripsi Materi
                    </h4>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <label for="description" class="form-label">
                        <i class="fas fa-file-alt me-1"></i>
                        Deskripsi Singkat *
                    </label>
                    <textarea name="description" id="description" class="form-control" rows="3" required
                              placeholder="Masukkan deskripsi singkat tentang materi ini...">{{ old('description', $material->description) }}</textarea>
                    @error('description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Deskripsi akan ditampilkan dalam preview materi untuk siswa</small>
                </div>
            </div>

            <!-- Upload File -->
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="text-warning mb-3">
                        <i class="fas fa-upload me-2"></i>
                        File Materi
                    </h4>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-8 mb-3">
                    <label for="file" class="form-label">
                        <i class="fas fa-paperclip me-1"></i>
                        Upload File Baru (Opsional)
                    </label>
                    
                    @if($material->file)
                    <div class="alert alert-info d-flex align-items-center mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <div class="flex-grow-1">
                            <strong>File saat ini:</strong> {{ $material->file }}<br>
                            <small class="text-muted">Ukuran: {{ $material->file_size_formatted ?? 'Unknown' }}</small>
                        </div>
                        <a href="{{ route('guru.materials.download', $material->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i>Preview
                        </a>
                    </div>
                    @endif
                    
                    <input type="file" name="file" id="file" class="form-control"
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar,.mp4,.avi,.mov,.jpg,.jpeg,.png">
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>
                        Format yang didukung: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR, MP4, AVI, MOV, JPG, JPEG, PNG (Maks: 50MB)
                        <br><em>Biarkan kosong jika tidak ingin mengubah file</em>
                    </div>
                    @error('file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-cog me-1"></i>
                        Pengaturan File
                    </label>
                    <div class="d-grid gap-2">
                        <div class="form-check">
                            <input type="hidden" name="allow_download" value="0">
                            <input type="checkbox" name="allow_download" id="allow_download" class="form-check-input" value="1"
                                   {{ old('allow_download', $material->allow_download ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_download">
                                <i class="fas fa-download me-1"></i>
                                Izinkan download
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="hidden" name="allow_preview" value="0">
                            <input type="checkbox" name="allow_preview" id="allow_preview" class="form-check-input" value="1"
                                   {{ old('allow_preview', $material->allow_preview ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_preview">
                                <i class="fas fa-eye me-1"></i>
                                Izinkan preview
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Field yang ditandai dengan (*) wajib diisi
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('guru.materials.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

</div>

@push('css')
<style>
.material-edit-form {
    min-height: calc(100vh - 200px);
    padding-bottom: 3rem;
}

.card-body {
    max-height: none;
    overflow: visible;
}

.container-fluid, .container {
    overflow-x: hidden;
}

.form-section {
    margin-bottom: 1.5rem;
}

.section-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.form-control, .form-select {
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 > * {
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Material Edit Form - DOM loaded');
    
    const form = document.getElementById('materialForm');
    const submitBtn = document.getElementById('submitBtn');
    const publishCheckbox = document.getElementById('is_published');
    const publishText = document.querySelector('.published-text');
    
    console.log('Form elements found:', {
        form: !!form,
        submitBtn: !!submitBtn,
        publishCheckbox: !!publishCheckbox,
        publishText: !!publishText
    });
    
    // Update publish status text
    if (publishCheckbox && publishText) {
        publishCheckbox.addEventListener('change', function() {
            publishText.textContent = this.checked ? 'Diterbitkan' : 'Draft';
        });
    }
    
    // File size validation
    const fileInput = document.getElementById('file');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const maxSize = 50 * 1024 * 1024; // 50MB
                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar. Maksimal 50MB.');
                    this.value = '';
                    return;
                }
                
                // Show file info
                const fileInfo = document.createElement('div');
                fileInfo.className = 'alert alert-success mt-2';
                fileInfo.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    File dipilih: <strong>${file.name}</strong> (${(file.size / 1024 / 1024).toFixed(2)} MB)
                `;
                
                // Remove existing file info
                const existingInfo = this.parentNode.querySelector('.alert-success');
                if (existingInfo) {
                    existingInfo.remove();
                }
                
                this.parentNode.appendChild(fileInfo);
            }
        });
    }
    
    // Form validation
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submit initiated');
            
            const judul = document.getElementById('judul')?.value.trim() || '';
            const subjectId = document.getElementById('subject_id')?.value || '';
            const category = document.getElementById('category')?.value || '';
            const description = document.getElementById('description')?.value.trim() || '';
        
        if (!judul) {
            alert('Judul materi wajib diisi!');
            e.preventDefault();
            return;
        }
        
        if (!subjectId) {
            alert('Mata pelajaran wajib dipilih!');
            e.preventDefault();
            return;
        }
        
        if (!category) {
            alert('Kategori wajib dipilih!');
            e.preventDefault();
            return;
        }
        
        if (!description) {
            alert('Deskripsi wajib diisi!');
            e.preventDefault();
            return;
        }
        
        // Prevent double submit
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <div class="spinner-border spinner-border-sm me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Menyimpan...
            `;
        }
        });
    }
    
    // Show success/error messages
    @if(session('success'))
        showBootstrapAlert('{{ session('success') }}', 'success');
    @elseif(session('error'))
        showBootstrapAlert('{{ session('error') }}', 'danger');
    @endif
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
