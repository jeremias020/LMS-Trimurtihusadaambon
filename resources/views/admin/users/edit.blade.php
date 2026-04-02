@extends('admin.layouts.admin-layout')

@section('title', 'Edit Pengguna - ' . $user->name)

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold text-dark mb-1">Edit Pengguna</h1>
    <p class="text-muted">Perbarui informasi pengguna - SMK Kesehatan Trimurti Husada Ambon</p>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
<div class="alert alert-danger" role="alert">
    <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="card-title mb-0">Edit: {{ $user->name }}</h5>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data"
          data-user-nip="{{ old('nip', $user->nip) }}"
          data-user-subject="{{ old('subject', $user->subject) }}"
          data-user-nis="{{ old('nis', $user->nis) }}"
          data-user-kelas-id="{{ old('kelas_id', $user->kelas_id) }}"
          data-user-jurusan-id="{{ old('jurusan_id', $user->jurusan_id) }}"
          data-user-birth-date="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}"
          data-user-address="{{ old('address', $user->address) }}">
        @csrf
        @method('PUT')

        <div class="card-body">
            <!-- Personal Information -->
            <div class="mb-4">
                <h6 class="fw-semibold mb-3">Informasi Pribadi</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Alamat Email *</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <input type="tel" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}"
                               pattern="^\+62\d{9,12}$" placeholder="+628123456789">
                        <small class="text-muted d-block mt-1">Format: +62 diikuti 9-12 digit angka</small>
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="avatar" class="form-label">Foto Profil</label>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" alt="{{ $user->name }}" class="rounded-circle" style="width:64px;height:64px;object-fit:cover;">
                            <div class="flex-grow-1">
                                <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
                                <small class="text-muted d-block mt-1">Format: JPG, PNG, GIF. Maks: 2MB</small>
                                @error('avatar')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div id="avatarPreview" class="mt-2 d-none">
                            <img src="" alt="Preview" class="rounded-circle" style="width:80px;height:80px;object-fit:cover;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="mb-4">
                <h6 class="fw-semibold mb-3">Informasi Akun</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="role" class="form-label">Role *</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="siswa" {{ old('role', $user->role) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        </select>
                        @error('role')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="is_active" class="form-label">Status Akun</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $user->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', $user->is_active) == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('is_active')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah" minlength="8">
                        <small class="text-muted d-block mt-1">Minimal 8 karakter</small>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>
                </div>
            </div>

            <!-- Additional Information (Conditional based on role) -->
            <div id="additionalInfo" class="mb-2">
                <!-- This will be populated based on selected role using JavaScript -->
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui Pengguna</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');
    const previewImg = avatarPreview.querySelector('img');

    avatarInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                this.value = '';
                avatarPreview.classList.add('d-none');
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Format file harus JPG, PNG, atau GIF');
                this.value = '';
                avatarPreview.classList.add('d-none');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                avatarPreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            avatarPreview.classList.add('d-none');
        }
    });

    // Role-based additional information
    const roleSelect = document.getElementById('role');
    const additionalInfo = document.getElementById('additionalInfo');
    const form = document.querySelector('form');

    function updateAdditionalInfo() {
        const role = roleSelect.value;
        let html = '';

        // Get current values from data attributes or old input
        const userNip = form.dataset.userNip || '';
        const userSubject = form.dataset.userSubject || '';
        const userNis = form.dataset.userNis || '';
        const userKelasId = form.dataset.userKelasId || '';
        const userJurusanId = form.dataset.userJurusanId || '';
        const userBirthDate = form.dataset.userBirthDate || '';
        const userAddress = form.dataset.userAddress || '';

        switch(role) {
            case 'guru':
                html = `
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Informasi Guru</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nip" class="form-label">NIP *</label>
                                <input type="text" name="nip" id="nip" class="form-control" value="${userNip}" required>
                                @error('nip')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="subject" class="form-label">Mata Pelajaran *</label>
                                <select name="subject" id="subject" class="form-select" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    <option value="Keperawatan Dasar" ${userSubject == 'Keperawatan Dasar' ? 'selected' : ''}>Keperawatan Dasar</option>
                                    <option value="Anatomi Fisiologi" ${userSubject == 'Anatomi Fisiologi' ? 'selected' : ''}>Anatomi Fisiologi</option>
                                    <option value="Farmakologi" ${userSubject == 'Farmakologi' ? 'selected' : ''}>Farmakologi</option>
                                    <option value="Gizi Kesehatan" ${userSubject == 'Gizi Kesehatan' ? 'selected' : ''}>Gizi Kesehatan</option>
                                    <option value="Kesehatan Lingkungan" ${userSubject == 'Kesehatan Lingkungan' ? 'selected' : ''}>Kesehatan Lingkungan</option>
                                    <option value="Praktik Klinik" ${userSubject == 'Praktik Klinik' ? 'selected' : ''}>Praktik Klinik</option>
                                </select>
                                @error('subject')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                `;
                break;

            case 'siswa':
                const jurusanOptions = `
                    <option value="">Pilih Jurusan (opsional)</option>
                    @foreach(($jurusans ?? []) as $jur)
                        <option value="{{ $jur->id }}" ${userJurusanId == '{{ $jur->id }}' ? 'selected' : ''}>
                            {{ $jur->nama }} ({{ $jur->kode }})
                        </option>
                    @endforeach
                `;
                const kelasOptions = `
                    <option value="">Pilih Kelas</option>
                    @foreach(($kelas ?? []) as $k)
                        <option value="{{ $k->id }}" ${userKelasId == '{{ $k->id }}' ? 'selected' : ''}>
                            {{ $k->grade }} {{ $k->major }} - {{ $k->name }} ({{ $k->code }})
                        </option>
                    @endforeach
                `;

                html = `
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Informasi Siswa</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nis" class="form-label">NIS *</label>
                                <input type="text" name="nis" id="nis" class="form-control" value="${userNis}" required>
                                @error('nis')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jurusan_id" class="form-label">Jurusan</label>
                                <select name="jurusan_id" id="jurusan_id" class="form-select">
                                    ${jurusanOptions}
                                </select>
                                @error('jurusan_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="kelas_id" class="form-label">Kelas *</label>
                                <select name="kelas_id" id="kelas_id" class="form-select" required>
                                    ${kelasOptions}
                                </select>
                                @error('kelas_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label">Tanggal Lahir *</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control" value="${userBirthDate}" required max="{{ date('Y-m-d') }}">
                                @error('birth_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="address" class="form-label">Alamat *</label>
                                <textarea name="address" id="address" class="form-control" rows="3" required>${userAddress}</textarea>
                                @error('address')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                `;
                break;

            default:
                html = '';
        }

        additionalInfo.innerHTML = html;
    }

    roleSelect.addEventListener('change', updateAdditionalInfo);

    // Trigger change event on page load to show correct additional info
    updateAdditionalInfo();
});
</script>
@endsection
