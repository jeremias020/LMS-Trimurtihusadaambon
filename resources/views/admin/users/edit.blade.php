@extends('layouts.admin')

@section('title', 'Edit Pengguna - ' . $user->name)

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Pengguna</h1>
    <p class="text-gray-600">Perbarui informasi pengguna - SMK Kesehatan Trimurti Husada Ambon</p>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Edit: {{ $user->name }}</h2>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data"
          data-user-nip="{{ old('nip', $user->nip) }}"
          data-user-subject="{{ old('subject', $user->subject) }}"
          data-user-nis="{{ old('nis', $user->nis) }}"
          data-user-class="{{ old('class', $user->class) }}"
          data-user-birth-date="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}"
          data-user-address="{{ old('address', $user->address) }}">
        @csrf
        @method('PUT')

        <div class="px-6 py-4 space-y-6">
            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pribadi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email *</label>
                        <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <input type="tel" name="phone" id="phone" class="form-input" value="{{ old('phone', $user->phone) }}"
                               pattern="^\+62\d{9,12}$" placeholder="+628123456789">
                        <p class="text-xs text-gray-500 mt-1">Format: +62 diikuti 9-12 digit angka</p>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="avatar" class="form-label">Foto Profil</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" alt="{{ $user->name }}" class="h-16 w-16 rounded-full object-cover">
                            </div>
                            <div class="flex-1">
                                <input type="file" name="avatar" id="avatar" class="form-input" accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maks: 2MB</p>
                                @error('avatar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div id="avatarPreview" class="mt-2 hidden">
                            <img src="" alt="Preview" class="h-20 w-20 rounded-full object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="role" class="form-label">Role *</label>
                        <select name="role" id="role" class="form-input" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="siswa" {{ old('role', $user->role) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="is_active" class="form-label">Status Akun</label>
                        <select name="is_active" id="is_active" class="form-input">
                            <option value="1" {{ old('is_active', $user->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', $user->is_active) == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-input" placeholder="Kosongkan jika tidak ingin mengubah" minlength="8">
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>
                </div>
            </div>

            <!-- Additional Information (Conditional based on role) -->
            <div id="additionalInfo">
                <!-- This will be populated based on selected role using JavaScript -->
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn-primary">
                Perbarui Pengguna
            </button>
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
                avatarPreview.classList.add('hidden');
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Format file harus JPG, PNG, atau GIF');
                this.value = '';
                avatarPreview.classList.add('hidden');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                avatarPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            avatarPreview.classList.add('hidden');
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
        const userClass = form.dataset.userClass || '';
        const userBirthDate = form.dataset.userBirthDate || '';
        const userAddress = form.dataset.userAddress || '';

        switch(role) {
            case 'guru':
                html = `
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Guru</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="nip" class="form-label">NIP *</label>
                                <input type="text" name="nip" id="nip" class="form-input" value="${userNip}" required>
                                @error('nip')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="subject" class="form-label">Mata Pelajaran *</label>
                                <select name="subject" id="subject" class="form-input" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    <option value="Keperawatan Dasar" ${userSubject == 'Keperawatan Dasar' ? 'selected' : ''}>Keperawatan Dasar</option>
                                    <option value="Anatomi Fisiologi" ${userSubject == 'Anatomi Fisiologi' ? 'selected' : ''}>Anatomi Fisiologi</option>
                                    <option value="Farmakologi" ${userSubject == 'Farmakologi' ? 'selected' : ''}>Farmakologi</option>
                                    <option value="Gizi Kesehatan" ${userSubject == 'Gizi Kesehatan' ? 'selected' : ''}>Gizi Kesehatan</option>
                                    <option value="Kesehatan Lingkungan" ${userSubject == 'Kesehatan Lingkungan' ? 'selected' : ''}>Kesehatan Lingkungan</option>
                                    <option value="Praktik Klinik" ${userSubject == 'Praktik Klinik' ? 'selected' : ''}>Praktik Klinik</option>
                                </select>
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                `;
                break;

            case 'siswa':
                html = `
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Siswa</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="nis" class="form-label">NIS *</label>
                                <input type="text" name="nis" id="nis" class="form-input" value="${userNis}" required>
                                @error('nis')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="class" class="form-label">Kelas *</label>
                                <select name="class" id="class" class="form-input" required>
                                    <option value="">Pilih Kelas</option>
                                    <option value="X Keperawatan" ${userClass == 'X Keperawatan' ? 'selected' : ''}>X Keperawatan</option>
                                    <option value="XI Keperawatan" ${userClass == 'XI Keperawatan' ? 'selected' : ''}>XI Keperawatan</option>
                                    <option value="XII Keperawatan" ${userClass == 'XII Keperawatan' ? 'selected' : ''}>XII Keperawatan</option>
                                    <option value="X Farmasi" ${userClass == 'X Farmasi' ? 'selected' : ''}>X Farmasi</option>
                                    <option value="XI Farmasi" ${userClass == 'XI Farmasi' ? 'selected' : ''}>XI Farmasi</option>
                                    <option value="XII Farmasi" ${userClass == 'XII Farmasi' ? 'selected' : ''}>XII Farmasi</option>
                                </select>
                                @error('class')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="birth_date" class="form-label">Tanggal Lahir *</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-input" value="${userBirthDate}" required max="{{ date('Y-m-d') }}">
                                @error('birth_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="address" class="form-label">Alamat *</label>
                                <textarea name="address" id="address" class="form-input" rows="3" required>${userAddress}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
