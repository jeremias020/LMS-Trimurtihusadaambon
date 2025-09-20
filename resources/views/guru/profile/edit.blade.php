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
                <div class="avatar avatar-xxl bg-primary bg-gradient rounded-circle mx-auto mb-3">
                    <span class="text-white fs-1 fw-bold">
                        {{ substr($user->name ?? 'G', 0, 1) }}
                    </span>
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

    <!-- Edit Form -->
    <div class="col-lg-8 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-edit text-primary me-2"></i>
                    Edit Data Profil
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.profile.update') }}" method="POST" id="profileForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-medium">
                                <i class="fas fa-user text-primary me-1"></i>
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg border-2 @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" 
                                   placeholder="Masukkan nama lengkap" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-medium">
                                <i class="fas fa-envelope text-primary me-1"></i>
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control form-control-lg border-2 @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" 
                                   placeholder="nama@email.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-medium">
                                <i class="fas fa-phone text-primary me-1"></i>
                                Nomor Telepon
                            </label>
                            <input type="tel" class="form-control form-control-lg border-2 @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label fw-medium">
                                <i class="fas fa-venus-mars text-primary me-1"></i>
                                Jenis Kelamin
                            </label>
                            <select class="form-select form-select-lg border-2 @error('gender') is-invalid @enderror" 
                                    id="gender" name="gender">
                                <option value="">Pilih jenis kelamin</option>
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
                                <i class="fas fa-birthday-cake text-primary me-1"></i>
                                Tanggal Lahir
                            </label>
                            <input type="date" class="form-control form-control-lg border-2 @error('birth_date') is-invalid @enderror" 
                                   id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-medium">
                            <i class="fas fa-map-marker-alt text-primary me-1"></i>
                            Alamat
                        </label>
                        <textarea class="form-control border-2 @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" 
                                  placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                            <i class="fas fa-undo me-1"></i> Reset
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
                            <div class="avatar avatar-md bg-success bg-gradient rounded-circle me-3">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <div class="fw-medium text-success">Email Terverifikasi</div>
                                <small class="text-muted">Email Anda sudah terverifikasi</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-info bg-opacity-10 rounded-3 mb-3">
                            <div class="avatar avatar-md bg-info bg-gradient rounded-circle me-3">
                                <i class="fas fa-key text-white"></i>
                            </div>
                            <div>
                                <div class="fw-medium text-info">Ubah Password</div>
                                <small class="text-muted">Hubungi administrator untuk mengubah password</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Informasi:</strong> Untuk mengubah password atau pengaturan keamanan lainnya, 
                        silakan hubungi administrator sistem.
                    </div>
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
    
    function validateField(field) {
        const value = field.value.trim();
        
        // Remove existing validation classes
        field.classList.remove('is-valid', 'is-invalid');
        
        if (field.hasAttribute('required') && value === '') {
            field.classList.add('is-invalid');
            return false;
        }
        
        if (field.type === 'email' && value !== '') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                field.classList.add('is-invalid');
                return false;
            }
        }
        
        if (field.type === 'tel' && value !== '') {
            const phoneRegex = /^[0-9+\-\s()]+$/;
            if (!phoneRegex.test(value)) {
                field.classList.add('is-invalid');
                return false;
            }
        }
        
        if (value !== '') {
            field.classList.add('is-valid');
        }
        
        return true;
    }
});
</script>

<style>
/* Custom CSS for enhanced styling */
.avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.avatar-md {
    width: 2.5rem;
    height: 2.5rem;
    font-size: 1rem;
}

.avatar-xxl {
    width: 5rem;
    height: 5rem;
    font-size: 2rem;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.form-control.is-valid {
    border-color: #198754;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.8-.79-.79-.79L1.5 6.12l.8.81z'/%3e%3c/svg%3e");
}

.form-control.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 1.4 1.4M7.2 7.4 5.8 6'/%3e%3c/svg%3e");
}

.border-2 {
    border-width: 2px !important;
}

.bg-gradient {
    background: linear-gradient(135deg, var(--bs-primary), var(--bs-info)) !important;
}

@media (max-width: 768px) {
    .avatar-xxl {
        width: 4rem;
        height: 4rem;
        font-size: 1.5rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>
@endsection