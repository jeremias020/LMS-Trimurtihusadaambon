@extends('layouts.siswa')

@section('title', 'Edit Profil - LMS Trimurti Husada')

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
        <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary btn-sm">
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
                <div class="avatar avatar-xxl bg-primary bg-gradient rounded-circle mx-auto mb-3 position-relative">
                    @if(auth()->user()->siswa?->foto)
                        <img src="{{ asset('storage/' . auth()->user()->siswa->foto) }}" alt="Profile" class="rounded-circle" style="width: 5rem; height: 5rem; object-fit: cover;">
                    @else
                        <span class="text-white fs-1 fw-bold">
                            {{ substr(auth()->user()->name ?? 'S', 0, 1) }}
                        </span>
                    @endif
                </div>
                <h5 class="fw-bold text-dark">{{ auth()->user()->name ?? 'Nama Belum Diset' }}</h5>
                <p class="text-muted mb-2">{{ auth()->user()->email ?? 'Email Belum Diset' }}</p>
                <span class="badge bg-success bg-opacity-10 text-success">
                    <i class="fas fa-graduation-cap me-1"></i>
                    Siswa
                </span>
                <hr class="my-3">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="text-primary fw-bold fs-5">
                            {{ \Carbon\Carbon::parse(auth()->user()->created_at ?? now())->format('M Y') }}
                        </div>
                        <small class="text-muted">Bergabung</small>
                    </div>
                    <div class="col-6">
                        <div class="text-success fw-bold fs-5">
                            @if(auth()->user()->last_login_at)
                                {{ \Carbon\Carbon::parse(auth()->user()->last_login_at)->diffForHumans() }}
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
                <form action="{{ route('siswa.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Photo Upload -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">
                            <i class="fas fa-camera text-primary me-1"></i>
                            Foto Profil
                        </label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-lg bg-primary bg-gradient rounded-circle">
                                @if(auth()->user()->siswa?->foto)
                                    <img src="{{ asset('storage/' . auth()->user()->siswa->foto) }}" alt="Profile" class="rounded-circle" style="width: 3rem; height: 3rem; object-fit: cover;">
                                @else
                                    <span class="text-white fw-bold">{{ substr(auth()->user()->name ?? 'S', 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                       id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-medium">
                                <i class="fas fa-user text-primary me-1"></i>
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg border-2 @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', auth()->user()->name) }}" 
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
                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                                   placeholder="nama@email.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nisn" class="form-label fw-medium">
                                <i class="fas fa-id-card text-primary me-1"></i>
                                NISN <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg border-2 @error('nisn') is-invalid @enderror" 
                                   id="nisn" name="nisn" value="{{ old('nisn', auth()->user()->siswa->nisn ?? '') }}" 
                                   placeholder="Masukkan NISN" required>
                            @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="kelas" class="form-label fw-medium">
                                <i class="fas fa-school text-primary me-1"></i>
                                Kelas
                            </label>
                            <input type="text" class="form-control form-control-lg border-2" 
                                   id="kelas" value="{{ auth()->user()->siswa?->kelas?->name ?? '-' }}" readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="jurusan" class="form-label fw-medium">
                                <i class="fas fa-graduation-cap text-primary me-1"></i>
                                Jurusan
                            </label>
                            <input type="text" class="form-control form-control-lg border-2" 
                                   id="jurusan" value="{{ auth()->user()->siswa?->major ?? auth()->user()->siswa?->kelas?->major ?? '-' }}" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tahun_ajaran" class="form-label fw-medium">
                                <i class="fas fa-calendar-alt text-primary me-1"></i>
                                Tahun Ajaran
                            </label>
                            <input type="text" class="form-control form-control-lg border-2" 
                                   id="tahun_ajaran" value="{{ auth()->user()->siswa?->tahun_ajaran ?? '-' }}" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label fw-medium">
                                <i class="fas fa-phone text-primary me-1"></i>
                                Nomor HP
                            </label>
                            <input type="tel" class="form-control form-control-lg border-2 @error('no_hp') is-invalid @enderror" 
                                   id="no_hp" name="no_hp" value="{{ old('no_hp', auth()->user()->siswa->no_hp ?? '') }}" 
                                   placeholder="08xxxxxxxxxx">
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label fw-medium">
                                <i class="fas fa-venus-mars text-primary me-1"></i>
                                Jenis Kelamin
                            </label>
                            <select class="form-select form-select-lg border-2 @error('jenis_kelamin') is-invalid @enderror" 
                                    id="jenis_kelamin" name="jenis_kelamin">
                                <option value="">Pilih jenis kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', auth()->user()->siswa?->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', auth()->user()->siswa?->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label fw-medium">
                                <i class="fas fa-birthday-cake text-primary me-1"></i>
                                Tanggal Lahir
                            </label>
                            <input type="date" class="form-control form-control-lg border-2 @error('tanggal_lahir') is-invalid @enderror" 
                                   id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', auth()->user()->siswa->tanggal_lahir) }}">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-medium">
                            <i class="fas fa-map-marker-alt text-primary me-1"></i>
                            Alamat
                        </label>
                        <textarea class="form-control border-2 @error('alamat') is-invalid @enderror" 
                                  id="alamat" name="alamat" rows="3" 
                                  placeholder="Masukkan alamat lengkap">{{ old('alamat', auth()->user()->siswa->alamat ?? '') }}</textarea>
                        @error('alamat')
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
                                <small class="text-muted">Ubah password secara berkala untuk keamanan</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Password Change Form -->
                <div class="border-top pt-4">
                    <h6 class="fw-medium mb-3">Ubah Password</h6>
                    <form action="{{ route('siswa.profile.update') }}" method="POST" id="passwordForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" placeholder="Masukkan password saat ini">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Masukkan password baru">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password baru">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-1"></i>Ubah Password
                        </button>
                    </form>
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

// Handle password form submission
document.getElementById('passwordForm').addEventListener('submit', function() {
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengubah...';
    
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 2000);
});

// Image preview function
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.querySelector('.avatar img');
            if (img) {
                img.src = e.target.result;
            } else {
                const avatar = document.querySelector('.avatar');
                avatar.innerHTML = `<img src="${e.target.result}" alt="Profile" class="rounded-circle" style="width: 3rem; height: 3rem; object-fit: cover;">`;
            }
        }
        reader.readAsDataURL(file);
    }
}

// Reset form function
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
        document.getElementById('profileForm').reset();
        // Re-populate with original values
        document.getElementById('name').value = '{{ auth()->user()->name }}';
        document.getElementById('email').value = '{{ auth()->user()->email }}';
        document.getElementById('nisn').value = '{{ auth()->user()->siswa?->nisn ?? '' }}';
        document.getElementById('no_hp').value = '{{ auth()->user()->siswa?->no_hp ?? '' }}';
        document.getElementById('jenis_kelamin').value = '{{ auth()->user()->siswa?->jenis_kelamin ?? '' }}';
        document.getElementById('tanggal_lahir').value = '{{ auth()->user()->siswa?->tanggal_lahir ?? '' }}';
        document.getElementById('alamat').value = '{{ auth()->user()->siswa?->alamat ?? '' }}';
    }
}

// Form validation feedback
document.addEventListener('DOMContentLoaded', function() {
    // Add real-time validation feedback
    const forms = document.querySelectorAll('#profileForm, #passwordForm');
    
    forms.forEach(form => {
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

.avatar-lg {
    width: 3rem;
    height: 3rem;
    font-size: 1.25rem;
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
