@extends('admin.layouts.admin-layout')

@section('title')
    Tambah Siswa
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-graduate me-2"></i>Tambah Siswa
        </h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Form Tambah Siswa</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store.siswa') }}" method="POST">
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
                               value="{{ old('email') }}" placeholder="siswa@example.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" required 
                               value="{{ old('username') }}" placeholder="username_siswa">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="{{ old('phone') }}" placeholder="081234567890">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nis" name="nis" required 
                               value="{{ old('nis') }}" placeholder="2021001">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nisn" name="nisn" required 
                               value="{{ old('nisn') }}" placeholder="0087654321">
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

                <!-- Academic Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>Informasi Akademik
                        </h6>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="kelas_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select class="form-control" id="kelas_id" name="kelas_id" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="major" class="form-label">Jurusan <span class="text-danger">*</span></label>
                        <select class="form-control" id="major" name="major" required>
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusans as $jurusan)
                                <option value="{{ $jurusan->nama }}" {{ old('major') == $jurusan->nama ? 'selected' : '' }}>
                                    {{ $jurusan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tahun_ajaran" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" required 
                               value="{{ old('tahun_ajaran') ?? date('Y') . '/' . (date('Y') + 1) }}" 
                               placeholder="2024/2025">
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-user me-2"></i>Informasi Pribadi
                        </h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" 
                               value="{{ old('tempat_lahir') }}" placeholder="Kota Kelahiran">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                               value="{{ old('tanggal_lahir') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2" 
                                  placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                    </div>
                </div>

                <!-- Parent Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-users me-2"></i>Informasi Orang Tua
                        </h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nama_ortu" class="form-label">Nama Orang Tua</label>
                        <input type="text" class="form-control" id="nama_ortu" name="nama_ortu" 
                               value="{{ old('nama_ortu') }}" placeholder="Nama orang tua/wali">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="no_telepon_ortu" class="form-label">Telepon Orang Tua</label>
                        <input type="tel" class="form-control" id="no_telepon_ortu" name="no_telepon_ortu" 
                               value="{{ old('no_telepon_ortu') }}" placeholder="081234567891">
                    </div>
                </div>

                <!-- Health Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-heartbeat me-2"></i>Informasi Kesehatan
                        </h6>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="golongan_darah" class="form-label">Golongan Darah</label>
                        <select class="form-control" id="golongan_darah" name="golongan_darah">
                            <option value="">Pilih Golongan Darah</option>
                            <option value="A" {{ old('golongan_darah') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('golongan_darah') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ old('golongan_darah') == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ old('golongan_darah') == 'O' ? 'selected' : '' }}>O</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="riwayat_penyakit" class="form-label">Riwayat Penyakit</label>
                        <input type="text" class="form-control" id="riwayat_penyakit" name="riwayat_penyakit" 
                               value="{{ old('riwayat_penyakit') }}" placeholder="Penyakit bawaan">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="alergi" class="form-label">Alergi</label>
                        <input type="text" class="form-control" id="alergi" name="alergi" 
                               value="{{ old('alergi') }}" placeholder="Alergi obat/makanan">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="info_kesehatan" class="form-label">Info Kesehatan Lain</label>
                        <input type="text" class="form-control" id="info_kesehatan" name="info_kesehatan" 
                               value="{{ old('info_kesehatan') }}" placeholder="Informasi kesehatan lain">
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Simpan Siswa
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
