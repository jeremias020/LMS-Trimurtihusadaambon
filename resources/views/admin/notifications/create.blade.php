@extends('layouts.admin')

@section('title', 'Kirim Notifikasi')
@section('page-title', 'Kirim Notifikasi')
@section('page-subtitle', 'Kirim notifikasi ke guru, siswa, atau user tertentu')

@section('page-actions')
    <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-paper-plane me-2 text-primary"></i>Form Kirim Notifikasi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.notifications.store') }}" method="POST" id="notifForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="Judul notifikasi..." required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pesan <span class="text-danger">*</span></label>
                        <textarea name="message" rows="4"
                                  class="form-control @error('message') is-invalid @enderror"
                                  placeholder="Isi notifikasi..." required>{{ old('message') }}</textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipe</label>
                            <select name="tipe" class="form-select">
                                <option value="info"         {{ old('tipe') == 'info'         ? 'selected' : '' }}>ℹ️ Info</option>
                                <option value="announcement" {{ old('tipe') == 'announcement' ? 'selected' : '' }}>📢 Pengumuman</option>
                                <option value="warning"      {{ old('tipe') == 'warning'      ? 'selected' : '' }}>⚠️ Peringatan</option>
                                <option value="success"      {{ old('tipe') == 'success'      ? 'selected' : '' }}>✅ Sukses</option>
                                <option value="error"        {{ old('tipe') == 'error'        ? 'selected' : '' }}>❌ Error</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">URL Aksi <span class="text-muted">(opsional)</span></label>
                            <input type="url" name="action_url" class="form-control"
                                   value="{{ old('action_url') }}" placeholder="https://...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kirim ke <span class="text-danger">*</span></label>
                        <select name="target" id="targetSelect" class="form-select @error('target') is-invalid @enderror" required>
                            <option value="">-- Pilih Penerima --</option>
                            <option value="semua" {{ old('target') == 'semua' ? 'selected' : '' }}>🌐 Semua (Guru + Siswa)</option>
                            <option value="guru"  {{ old('target') == 'guru'  ? 'selected' : '' }}>👩‍🏫 Semua Guru</option>
                            <option value="siswa" {{ old('target') == 'siswa' ? 'selected' : '' }}>🎓 Semua Siswa</option>
                            <option value="user"  {{ old('target') == 'user'  ? 'selected' : '' }}>👤 Pilih User Tertentu</option>
                        </select>
                        @error('target') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- User tertentu --}}
                    <div class="mb-3 d-none" id="userSelectWrap">
                        <label class="form-label fw-semibold">Pilih User <span class="text-danger">*</span></label>
                        <select name="target_users[]" id="userSelect"
                                class="form-select @error('target_users') is-invalid @enderror"
                                multiple size="8">
                            @php
                                $oldUsers = old('target_users', []);
                                $currentRole = '';
                            @endphp
                            @foreach($users as $user)
                                @if($currentRole !== $user->role)
                                    @if($currentRole !== '') </optgroup> @endif
                                    @php $currentRole = $user->role; @endphp
                                    <optgroup label="{{ ucfirst($user->role) }}">
                                @endif
                                <option value="{{ $user->id }}"
                                        {{ in_array($user->id, $oldUsers) ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                            @if($currentRole !== '') </optgroup> @endif
                        </select>
                        @error('target_users') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Tahan Ctrl (Windows) / Cmd (Mac) untuk pilih lebih dari satu</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-paper-plane me-1"></i>Kirim Notifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
const targetSelect = document.getElementById('targetSelect');
const userWrap     = document.getElementById('userSelectWrap');
const userSelect   = document.getElementById('userSelect');

function toggleUserSelect() {
    const show = targetSelect.value === 'user';
    userWrap.classList.toggle('d-none', !show);
    userSelect.required = show;
}

targetSelect.addEventListener('change', toggleUserSelect);
toggleUserSelect(); // init

document.getElementById('notifForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';
});
</script>
@endpush

@endsection
