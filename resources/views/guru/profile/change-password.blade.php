@extends('layouts.guru')

@section('title', 'Ubah Password')
@section('page-title', 'Ubah Password')
@section('page-subtitle', 'Perbarui kata sandi akun Anda.')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('guru.profile.edit') }}" class="text-decoration-none">Profil Saya</a></li>
    <li class="breadcrumb-item active">Ubah Password</li>
@endsection

@section('content')

<div class="card border-0 shadow-sm" style="max-width:560px;">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0"><i class="fas fa-key me-2"></i>Ubah Password</h5>
    </div>
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('guru.profile.change-password.post') }}" method="POST" id="cpForm">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Password Saat Ini <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="password" name="current_password" id="currentPwd"
                           class="form-control @error('current_password') is-invalid @enderror"
                           required autocomplete="current-password">
                    <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="currentPwd">
                        <i class="fas fa-eye"></i>
                    </button>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Password Baru <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="password" name="password" id="newPwd"
                           class="form-control @error('password') is-invalid @enderror"
                           required minlength="8" autocomplete="new-password"
                           oninput="checkStrength(this)">
                    <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="newPwd">
                        <i class="fas fa-eye"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Strength indicator --}}
                <div class="progress mt-2" style="height:4px;" id="strengthBar" style="display:none;">
                    <div id="strengthFill" class="progress-bar" style="width:0%;transition:width .3s;"></div>
                </div>
                <small id="strengthLabel" class="text-muted"></small>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Konfirmasi Password Baru <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="confirmPwd"
                           class="form-control" required autocomplete="new-password"
                           oninput="checkMatch()">
                    <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="confirmPwd">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <small id="matchLabel" class="mt-1 d-block"></small>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('guru.profile.edit') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save me-1"></i>Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Toggle show/hide password
document.querySelectorAll('.toggle-pwd').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var target = document.getElementById(this.dataset.target);
        var icon   = this.querySelector('i');
        if (target.type === 'password') {
            target.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            target.type = 'password';
            icon.className = 'fas fa-eye';
        }
    });
});

// Password strength
function checkStrength(input) {
    var val = input.value;
    var bar = document.getElementById('strengthFill');
    var lbl = document.getElementById('strengthLabel');
    var pct = 0, color = 'bg-danger', text = '';

    if (val.length >= 8)  pct += 25;
    if (/[A-Z]/.test(val)) pct += 25;
    if (/[0-9]/.test(val)) pct += 25;
    if (/[^a-zA-Z0-9]/.test(val)) pct += 25;

    if (pct <= 25)      { color = 'bg-danger';  text = 'Sangat lemah'; }
    else if (pct <= 50) { color = 'bg-warning'; text = 'Lemah'; }
    else if (pct <= 75) { color = 'bg-info';    text = 'Cukup'; }
    else                { color = 'bg-success'; text = 'Kuat'; }

    bar.style.width    = pct + '%';
    bar.className      = 'progress-bar ' + color;
    lbl.textContent    = val.length > 0 ? text : '';
    lbl.className      = 'mt-1 d-block ' + (pct >= 75 ? 'text-success' : 'text-muted');
}

// Password match
function checkMatch() {
    var pwd     = document.getElementById('newPwd').value;
    var confirm = document.getElementById('confirmPwd').value;
    var lbl     = document.getElementById('matchLabel');

    if (!confirm) { lbl.textContent = ''; return; }

    if (pwd === confirm) {
        lbl.textContent = '✓ Password cocok';
        lbl.className   = 'mt-1 d-block text-success small';
    } else {
        lbl.textContent = '✗ Password tidak cocok';
        lbl.className   = 'mt-1 d-block text-danger small';
    }
}

document.getElementById('cpForm').addEventListener('submit', function(e) {
    var pwd     = document.getElementById('newPwd').value;
    var confirm = document.getElementById('confirmPwd').value;

    if (pwd !== confirm) {
        e.preventDefault();
        alert('Konfirmasi password tidak cocok.');
        return;
    }

    var btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';
});
</script>
@endpush
