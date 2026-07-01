@extends('layouts.admin')

@section('title', 'Edit Administrator - ' . $user->name)
@section('page-title', 'Edit Administrator')
@section('page-subtitle', $user->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.admins') }}">Administrator</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Edit: {{ $user->name }}</h5>
    </div>
    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong><i class="fas fa-exclamation-circle me-2"></i>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.users.update.modern', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Informasi Dasar --}}
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h6 class="text-muted fw-semibold"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username', $user->username) }}" required>
                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telepon</label>
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $user->phone) }}">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Status --}}
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h6 class="text-muted fw-semibold"><i class="fas fa-toggle-on me-2"></i>Status Akun</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ old('is_active', $user->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $user->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            {{-- Password --}}
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h6 class="text-muted fw-semibold"><i class="fas fa-lock me-2"></i>Ubah Password <small class="fw-normal">(opsional)</small></h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password Baru</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Kosongkan jika tidak ingin mengubah" autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePw1">
                            <i class="fas fa-eye" id="iconPw1"></i>
                        </button>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <small class="text-muted">Minimal 8 karakter</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-control"
                               placeholder="Ulangi password baru" autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePw2">
                            <i class="fas fa-eye" id="iconPw2"></i>
                        </button>
                    </div>
                    <div id="pwMatchMsg" class="small mt-1"></div>
                </div>
            </div>

            {{-- Informasi Profil (opsional) --}}
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h6 class="text-muted fw-semibold"><i class="fas fa-user me-2"></i>Informasi Profil <small class="fw-normal">(opsional)</small></h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                           value="{{ old('birth_date') }}">
                    @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                              rows="2">{{ old('address') }}</textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.users.admins') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(btnId, inputId, iconId) {
    document.getElementById(btnId).addEventListener('click', function () {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        input.type  = input.type === 'password' ? 'text' : 'password';
        icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    });
}
togglePassword('togglePw1', 'password', 'iconPw1');
togglePassword('togglePw2', 'password_confirmation', 'iconPw2');

const pw  = document.getElementById('password');
const pwc = document.getElementById('password_confirmation');
const msg = document.getElementById('pwMatchMsg');

function checkMatch() {
    if (!pwc.value) { msg.textContent = ''; return; }
    if (pw.value === pwc.value) {
        msg.innerHTML = '<span class="text-success"><i class="fas fa-check me-1"></i>Password cocok</span>';
        pwc.classList.remove('is-invalid'); pwc.classList.add('is-valid');
    } else {
        msg.innerHTML = '<span class="text-danger"><i class="fas fa-times me-1"></i>Password tidak cocok</span>';
        pwc.classList.remove('is-valid'); pwc.classList.add('is-invalid');
    }
}
if (pw) pw.addEventListener('input', checkMatch);
if (pwc) pwc.addEventListener('input', checkMatch);

document.querySelector('form')?.addEventListener('submit', function (e) {
    if (pw.value && pw.value !== pwc.value) {
        e.preventDefault();
        msg.innerHTML = '<span class="text-danger"><i class="fas fa-times me-1"></i>Password tidak cocok</span>';
        pwc.classList.add('is-invalid');
        pwc.focus();
        return;
    }
    const btn = document.getElementById('submitBtn');
    if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...'; }
});
</script>
@endpush
