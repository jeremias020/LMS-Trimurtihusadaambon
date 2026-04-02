@extends('admin.layouts.admin-layout')

@section('title')
    Tambah Guru
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chalkboard-teacher me-2"></i>Tambah Guru
        </h1>
        <a href="{{ route('admin.users.guru') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Form Tambah Guru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store.guru') }}" method="POST">
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
                               value="{{ old('email') }}" placeholder="guru@example.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" required 
                               value="{{ old('username') }}" placeholder="username_guru">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="{{ old('phone') }}" placeholder="081234567890">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nip" name="nip" required 
                               value="{{ old('nip') }}" placeholder="198001012020123456">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email_pribadi" class="form-label">Email Pribadi</label>
                        <input type="email" class="form-control" id="email_pribadi" name="email_pribadi" 
                               value="{{ old('email_pribadi') }}" placeholder="personal@example.com">
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

                <!-- Professional Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-briefcase me-2"></i>Informasi Profesional
                        </h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select" id="subject_id" name="subject_id" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }} ({{ $subject->code }})
                                    @if($subject->type)
                                        - {{ $subject->type }}
                                    @endif
                                    @if($subject->sks)
                                        [{{ $subject->sks }} SKS]
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih mata pelajaran yang sudah terdaftar di sistem</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                        <select class="form-control" id="pendidikan_terakhir" name="pendidikan_terakhir">
                            <option value="">Pilih Pendidikan</option>
                            <option value="SMA" {{ old('pendidikan_terakhir') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="jurusan_pendidikan" class="form-label">Jurusan Pendidikan</label>
                        <input type="text" class="form-control" id="jurusan_pendidikan" name="jurusan_pendidikan" 
                               value="{{ old('jurusan_pendidikan') }}" placeholder="Pendidikan Matematika">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tahun_mulai_kerja" class="form-label">Tahun Mulai Kerja</label>
                        <input type="number" class="form-control" id="tahun_mulai_kerja" name="tahun_mulai_kerja" 
                               value="{{ old('tahun_mulai_kerja') }}" min="1900" max="{{ date('Y') }}" 
                               placeholder="2020">
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.guru') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Simpan Guru
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
    
    // Subject dropdown enhancement
    const subjectSelect = document.getElementById('subject_id');
    if (subjectSelect) {
        subjectSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const subjectName = selectedOption.text;
            
            // Log selected subject for debugging
            console.log('Selected subject:', subjectName);
        });
    }
});
</script>
@endsection
