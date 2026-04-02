@extends('admin.layouts.admin-layout')

@section('title')
    Tambah Administrator
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-shield me-2"></i>Tambah Administrator
        </h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Form Tambah Administrator</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store.admin') }}" method="POST">
                @csrf
                
                <!-- Basic Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                        </h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               value="{{ old('name') }}" placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               value="{{ old('email') }}" placeholder="admin@example.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" required 
                               value="{{ old('username') }}" placeholder="username_admin">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="{{ old('phone') }}" placeholder="081234567890">
                    </div>
                </div>

                <!-- Password -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-lock me-2"></i>Keamanan
                        </h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required 
                               placeholder="Minimal 8 karakter">
                        <small class="text-muted">Minimal 8 karakter</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required 
                               placeholder="Ulangi password">
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-user me-2"></i>Informasi Profil
                        </h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="birth_date" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" 
                               value="{{ old('birth_date') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control" id="address" name="address" rows="3" 
                                  placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Administrator
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;
        
        // Update strength indicator (you can add visual feedback here)
    });
    
    // Password confirmation validation
    passwordConfirmInput.addEventListener('input', function() {
        if (this.value !== passwordInput.value) {
            this.setCustomValidity('Password tidak cocok');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endsection
