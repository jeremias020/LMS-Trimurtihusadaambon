@extends('layouts.guru')

@section('title', 'Edit Profil')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 mb-1 text-dark fw-bold">
            <i class="fas fa-user-edit text-primary me-2"></i>
            Edit Profil
        </h1>
        <p class="text-muted mb-0">Kelola informasi profil dan pengaturan akun Anda</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Terjadi kesalahan:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <!-- Profile Information Card -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-gradient bg-primary text-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-id-card me-2"></i>
                    Informasi Profil
                </h5>
            </div>
            <div class="card-body text-center">
                <!-- Profile Photo Section -->
                <div class="position-relative d-inline-block mb-3">
                    <div class="avatar avatar-xxl rounded-circle mx-auto overflow-hidden border-4 border-white shadow-lg" style="width: 120px; height: 120px;">
                        @if($user->photo)
                            <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="w-100 h-100 bg-primary bg-gradient d-flex align-items-center justify-content-center">
                                <span class="text-white fs-1 fw-bold">
                                    {{ substr($user->name ?? 'G', 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <!-- Photo Upload Button -->
                    <div class="position-absolute bottom-0 end-0">
                        <button type="button" class="btn btn-primary btn-sm rounded-circle shadow" data-bs-toggle="modal" data-bs-target="#photoUploadModal" style="width: 36px; height: 36px;">
                            <i class="fas fa-camera fs-6"></i>
                        </button>
                    </div>
                </div>
                
                <h5 class="fw-bold text-dark">{{ $user->name ?? 'Nama Belum Diset' }}</h5>
                <p class="text-muted mb-2">{{ $user->email ?? 'Email Belum Diset' }}</p>
                <span class="badge bg-success bg-opacity-10 text-success">
                    <i class="fas fa-chalkboard-teacher me-1"></i>
                    Guru
                </span>
                <hr class="my-3">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="text-primary fw-bold fs-5">
                            {{ \Carbon\Carbon::parse($user->created_at)->format('M Y') }}
                        </div>
                        <small class="text-muted">Bergabung</small>
                    </div>
                    <div class="col-6">
                        <div class="text-success fw-bold fs-5">
                            @if($user->last_login_at)
                                {{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}
                            @else
                                Belum pernah
                            @endif
                        </div>
                        <small class="text-muted">Login Terakhir</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Edit Form -->
    <div class="col-lg-8 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-edit text-primary me-2"></i>
                    Edit Informasi Profil
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.profile.update') }}" method="POST" id="profileForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-medium">
                                <i class="fas fa-user me-1"></i>
                                Nama Lengkap
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-medium">
                                <i class="fas fa-envelope me-1"></i>
                                Email
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-medium">
                                <i class="fas fa-phone me-1"></i>
                                Nomor Telepon
                            </label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label fw-medium">
                                <i class="fas fa-venus-mars me-1"></i>
                                Jenis Kelamin
                            </label>
                            <select class="form-select @error('gender') is-invalid @enderror" 
                                    id="gender" name="gender">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('gender', $user->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $user->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="birth_date" class="form-label fw-medium">
                                <i class="fas fa-calendar me-1"></i>
                                Tanggal Lahir
                            </label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                   id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label fw-medium">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Alamat
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="1">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                            <i class="fas fa-redo me-1"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">
                            <i class="fas fa-save me-1"></i>
                            <span class="btn-text">Simpan Perubahan</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Photo Upload Modal -->
<div class="modal fade" id="photoUploadModal" tabindex="-1" aria-labelledby="photoUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="photoUploadModalLabel">
                    <i class="fas fa-camera me-2"></i>
                    Upload Foto Profil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('guru.profile.update-photo') }}" method="POST" enctype="multipart/form-data" id="photoUploadForm">
                    @csrf
                    <!-- Current Photo Preview -->
                    <div class="text-center mb-4">
                        <div class="avatar avatar-xl rounded-circle mx-auto overflow-hidden border-4 border-light shadow" style="width: 100px; height: 100px;">
                            @if($user->photo)
                                <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-100 h-100 object-fit-cover" id="currentPhotoPreview">
                            @else
                                <div class="w-100 h-100 bg-primary bg-gradient d-flex align-items-center justify-content-center">
                                    <span class="text-white fs-3 fw-bold" id="currentPhotoInitial">
                                        {{ substr($user->name ?? 'G', 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <small class="text-muted d-block mt-2">Foto saat ini</small>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="photo" class="form-label fw-medium">
                            <i class="fas fa-image me-1"></i>
                            Pilih Foto Baru
                        </label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                               id="photo" name="photo" accept="image/*" onchange="previewPhoto(event)" required>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Format: JPEG, PNG, JPG, GIF (Maks: 2MB)
                        </div>
                    </div>

                    <!-- New Photo Preview -->
                    <div class="text-center mb-3" id="newPhotoPreviewContainer" style="display: none;">
                        <div class="avatar avatar-xl rounded-circle mx-auto overflow-hidden border-4 border-success shadow" style="width: 100px; height: 100px;">
                            <img id="newPhotoPreview" alt="Preview" class="w-100 h-100 object-fit-cover">
                        </div>
                        <small class="text-success d-block mt-2">
                            <i class="fas fa-check-circle me-1"></i>
                            Preview foto baru
                        </small>
                    </div>

                    <!-- Remove Photo Option -->
                    @if($user->photo)
                    <div class="mb-3">
                        <a href="{{ route('guru.profile.remove-photo') }}" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil?')">
                            <i class="fas fa-trash me-1"></i>
                            Hapus Foto Saat Ini
                        </a>
                    </div>
                    @endif
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Batal
                </button>
                <button type="submit" form="photoUploadForm" class="btn btn-primary" id="uploadPhotoBtn">
                    <i class="fas fa-upload me-1"></i>
                    <span class="btn-text">Upload Foto</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Account Security Card -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-shield-alt text-primary me-2"></i>
                    Keamanan Akun
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded-3 mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-success fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 fw-bold">Email Terverifikasi</h6>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-info bg-opacity-10 rounded-3 mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-lock text-info fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 fw-bold">Password Aman</h6>
                                <small class="text-muted">Diubah {{ $user->password_changed_at ? \Carbon\Carbon::parse($user->password_changed_at)->diffForHumans() : 'belum pernah' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('guru.password.change') }}" class="btn btn-outline-primary">
                        <i class="fas fa-key me-1"></i>
                        Ubah Password
                    </a>
                    <a href="{{ route('guru.2fa.setup') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-mobile-alt me-1"></i>
                        Pengaturan 2FA
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Handle form submission with loading state
document.getElementById('profileForm').addEventListener('submit', function() {
    const saveBtn = document.getElementById('saveBtn');
    const btnText = saveBtn.querySelector('.btn-text');
    const spinner = saveBtn.querySelector('.spinner-border');
    
    // Show loading state
    btnText.textContent = 'Menyimpan...';
    spinner.classList.remove('d-none');
    saveBtn.disabled = true;
});

// Photo upload form submission
document.getElementById('photoUploadForm').addEventListener('submit', function() {
    const uploadBtn = document.getElementById('uploadPhotoBtn');
    const btnText = uploadBtn.querySelector('.btn-text');
    const spinner = uploadBtn.querySelector('.spinner-border');
    
    // Show loading state
    btnText.textContent = 'Mengupload...';
    spinner.classList.remove('d-none');
    uploadBtn.disabled = true;
});

// Photo preview function
function previewPhoto(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('newPhotoPreviewContainer');
    const previewImg = document.getElementById('newPhotoPreview');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
}

// Reset form function
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
        document.getElementById('profileForm').reset();
        // Re-populate with original values
        @if($user)
        document.getElementById('name').value = '{{ $user->name }}';
        document.getElementById('email').value = '{{ $user->email }}';
        document.getElementById('phone').value = '{{ $user->phone ?? '' }}';
        document.getElementById('gender').value = '{{ $user->gender ?? '' }}';
        document.getElementById('birth_date').value = '{{ $user->birth_date ?? '' }}';
        document.getElementById('address').value = '{{ $user->address ?? '' }}';
        @endif
    }
}

// Form validation feedback
document.addEventListener('DOMContentLoaded', function() {
    // Add real-time validation feedback
    const form = document.getElementById('profileForm');
    const inputs = form.querySelectorAll('.form-control, .form-select');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
});

function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';
    
    // Basic validation rules
    switch(field.id) {
        case 'name':
            if (!value) {
                isValid = false;
                errorMessage = 'Nama lengkap wajib diisi';
            } else if (value.length < 3) {
                isValid = false;
                errorMessage = 'Nama minimal 3 karakter';
            }
            break;
            
        case 'email':
            if (!value) {
                isValid = false;
                errorMessage = 'Email wajib diisi';
            } else if (!/^\S+@\S+\.\S+$/.test(value)) {
                isValid = false;
                errorMessage = 'Format email tidak valid';
            }
            break;
            
        case 'phone':
            if (value && !/^\+?[\d\s-()]+$/.test(value)) {
                isValid = false;
                errorMessage = 'Format nomor telepon tidak valid';
            }
            break;
    }
    
    // Update field validation state
    if (isValid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
    } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        
        // Show error message
        let feedback = field.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            field.parentNode.appendChild(feedback);
        }
        feedback.textContent = errorMessage;
    }
}
</script>
@endsection
