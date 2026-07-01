@extends('layouts.siswa')

@section('title', 'Edit Profil Siswa')

@push('css')
<style>
.profile-photo-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 1rem;
    margin-bottom: 2rem;
    text-align: center;
}

.photo-container {
    position: relative;
    display: inline-block;
}

.photo-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.photo-preview:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
}

.photo-placeholder {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    border: 4px solid rgba(255, 255, 255, 0.2);
}

.upload-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    cursor: pointer;
}

.photo-container:hover .upload-overlay {
    opacity: 1;
}

.upload-icon {
    color: white;
    font-size: 2rem;
}

.form-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    border: none;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control {
    border-radius: 0.5rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 0.5rem;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    border-radius: 0.5rem;
    padding: 0.75rem 2rem;
    font-weight: 600;
}

@media (max-width: 768px) {
    .profile-photo-section {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .photo-preview,
    .photo-placeholder {
        width: 120px;
        height: 120px;
    }
    
    .form-card {
        padding: 1.5rem;
    }
}
</style>
@endpush

@section('content')
<!-- Profile Photo Section -->
<div class="profile-photo-section">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-0">
                    <i class="fas fa-user-circle me-3"></i>
                    Edit Profil Siswa
                </h2>
                <p class="mb-0 mt-2 opacity-75">Perbarui informasi profil dan foto Anda</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="photo-container">
                    @if(auth()->user()->siswa?->foto)
                        <img src="{{ asset('storage/' . auth()->user()->siswa->foto) }}" 
                             alt="Profile Photo" 
                             class="photo-preview"
                             id="photoPreview">
                    @else
                        <div class="photo-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <div class="upload-overlay" onclick="document.getElementById('fotoInput').click()">
                        <i class="fas fa-camera upload-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Section -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="form-card">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form action="{{ route('siswa.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Photo Upload (Hidden) -->
                    <input type="file" 
                           id="fotoInput" 
                           name="foto" 
                           accept="image/jpeg,image/jpg,image/png" 
                           style="display: none;"
                           onchange="previewPhoto(event)">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2 text-primary"></i>
                                    Nama Lengkap
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="name" 
                                       name="name" 
                                       value="{{ auth()->user()->name }}" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    Email
                                </label>
                                <input type="email" 
                                       class="form-control form-control-lg" 
                                       id="email" 
                                       name="email" 
                                       value="{{ auth()->user()->email }}" 
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nis" class="form-label">
                                    <i class="fas fa-id-card me-2 text-primary"></i>
                                    NIS
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="nis" 
                                       name="nis" 
                                       value="{{ auth()->user()->siswa->nis ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kelas" class="form-label">
                                    <i class="fas fa-school me-2 text-primary"></i>
                                    Kelas
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="kelas" 
                                       value="{{ auth()->user()->siswa?->kelas?->name ?? 'Tidak ada' }}" 
                                       readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gender" class="form-label">
                                    <i class="fas fa-venus-mars me-2 text-primary"></i>
                                    Jenis Kelamin
                                </label>
                                <select class="form-select form-select-lg" 
                                        id="gender" 
                                        name="gender">
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="L" {{ (auth()->user()->siswa->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ (auth()->user()->siswa->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="birth_date" class="form-label">
                                    <i class="fas fa-birthday-cake me-2 text-primary"></i>
                                    Tanggal Lahir
                                </label>
                                <input type="date" 
                                       class="form-control form-control-lg" 
                                       id="birth_date" 
                                       name="birth_date" 
                                       value="{{ auth()->user()->siswa->birth_date?->format('Y-m-d') ?? '' }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="alamat" class="form-label">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                    Alamat
                                </label>
                                <textarea class="form-control form-control-lg" 
                                          id="alamat" 
                                          name="alamat" 
                                          rows="3">{{ auth()->user()->siswa->alamat ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-2 text-primary"></i>
                                    Nomor HP
                                </label>
                                <input type="tel" 
                                       class="form-control form-control-lg" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ auth()->user()->siswa->phone ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_ortu" class="form-label">
                                    <i class="fas fa-user-friends me-2 text-primary"></i>
                                    Nama Orang Tua
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="nama_ortu" 
                                       name="nama_ortu" 
                                       value="{{ auth()->user()->siswa->nama_ortu ?? '' }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_telepon_ortu" class="form-label">
                                    <i class="fas fa-phone-alt me-2 text-primary"></i>
                                    No. Telepon Orang Tua
                                </label>
                                <input type="tel" 
                                       class="form-control form-control-lg" 
                                       id="no_telepon_ortu" 
                                       name="no_telepon_ortu" 
                                       value="{{ auth()->user()->siswa->no_telepon_ortu ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">
                                    <i class="fas fa-lock me-2 text-primary"></i>
                                    Password Saat Ini
                                    <small class="text-muted">(Diperlukan jika mengubah password)</small>
                                </label>
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="current_password" 
                                       name="current_password">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-key me-2 text-primary"></i>
                                    Password Baru
                                </label>
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password" 
                                       name="password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-key me-2 text-primary"></i>
                                    Konfirmasi Password
                                </label>
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password_confirmation" 
                                       name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex gap-3 justify-content-between">
                                <div>
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                        <i class="fas fa-save me-2"></i>
                                        <span id="submitText">Simpan Perubahan</span>
                                    </button>
                                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                </div>
                                <div class="text-muted">
                                    <small>
                                        <i class="fas fa-info-circle me-1"></i>
                                        Format foto: JPEG, PNG (Maks. 2MB)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        // Check file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            event.target.value = '';
            return;
        }
        
        // Check file type
        if (!file.type.match('image.*')) {
            alert('File harus berupa gambar (JPEG/PNG).');
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            // Update or create photo preview
            const container = document.querySelector('.photo-container');
            
            // Remove existing preview
            const existingImg = container.querySelector('.photo-preview');
            const existingPlaceholder = container.querySelector('.photo-placeholder');
            if (existingImg) existingImg.remove();
            if (existingPlaceholder) existingPlaceholder.remove();
            
            // Create new image preview
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'Profile Photo Preview';
            img.className = 'photo-preview';
            img.id = 'photoPreview';
            
            // Re-add upload overlay
            const overlay = container.querySelector('.upload-overlay');
            container.insertBefore(img, overlay);
        };
        reader.readAsDataURL(file);
    }
}

// Form submission with loading state
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    // Show loading state
    submitBtn.disabled = true;
    submitText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    
    // Re-enable after 10 seconds (fallback)
    setTimeout(() => {
        submitBtn.disabled = false;
        submitText.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Perubahan';
    }, 10000);
});

// Password confirmation validation
document.getElementById('password')?.addEventListener('input', function() {
    const password = this.value;
    const confirmation = document.getElementById('password_confirmation').value;
    
    if (password && confirmation && password !== confirmation) {
        document.getElementById('password_confirmation').setCustomValidity('Password tidak cocok');
    } else {
        document.getElementById('password_confirmation').setCustomValidity('');
    }
});

// Auto-enable current password when password is filled
document.getElementById('password')?.addEventListener('focus', function() {
    const currentPassword = document.getElementById('current_password');
    if (currentPassword && !currentPassword.value) {
        currentPassword.required = true;
        currentPassword.focus();
    }
});
</script>
@endpush
