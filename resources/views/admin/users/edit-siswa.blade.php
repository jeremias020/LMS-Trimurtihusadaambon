@extends('layouts.admin')

@section('title', 'Edit Siswa - ' . $user->name)
@section('page-title', 'Edit Siswa')
@section('page-subtitle', $user->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.siswa') }}">Siswa</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Edit: {{ $user->name }}</h5>
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

                <!-- Informasi Dasar -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" required value="{{ old('name', $user->name) }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" required value="{{ old('email', $user->email) }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                               id="username" name="username" required value="{{ old('username', $user->username) }}">
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Status Akun -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3"><i class="fas fa-toggle-on me-2"></i>Status Akun</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $user->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', $user->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <!-- Keamanan -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3"><i class="fas fa-lock me-2"></i>Ubah Password (opsional)</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" minlength="8">
                        <small class="text-muted">Minimal 8 karakter</small>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                    </div>
                </div>

                <!-- Informasi Akademik -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3"><i class="fas fa-graduation-cap me-2"></i>Informasi Akademik</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nis') is-invalid @enderror"
                               id="nis" name="nis" required value="{{ old('nis', $siswaProfile?->nis) }}">
                        @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nisn" class="form-label">NISN</label>
                        <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                               id="nisn" name="nisn" value="{{ old('nisn', $siswaProfile?->nisn) }}">
                        @error('nisn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="kelas_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id" name="kelas_id" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id', $siswaProfile?->kelas_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="major" class="form-label">Jurusan <span class="text-danger">*</span></label>
                        <select class="form-select @error('major') is-invalid @enderror" id="major" name="major" required>
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusans as $jurusan)
                                <option value="{{ $jurusan->name }}" {{ old('major', $siswaProfile?->major) == $jurusan->name ? 'selected' : '' }}>
                                    {{ $jurusan->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('major')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tahun_ajaran" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror"
                               id="tahun_ajaran" name="tahun_ajaran" required
                               value="{{ old('tahun_ajaran', $siswaProfile?->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1)) }}"
                               placeholder="2024/2025">
                        @error('tahun_ajaran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Informasi Pribadi -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3"><i class="fas fa-user me-2"></i>Informasi Pribadi</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                               id="tempat_lahir" name="tempat_lahir"
                               value="{{ old('tempat_lahir', $siswaProfile?->tempat_lahir) }}">
                        @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                               id="tanggal_lahir" name="tanggal_lahir"
                               value="{{ old('tanggal_lahir', $siswaProfile?->tanggal_lahir?->format('Y-m-d')) }}">
                        @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin', $siswaProfile?->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $siswaProfile?->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                  id="alamat" name="alamat" rows="2">{{ old('alamat', $siswaProfile?->alamat) }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Informasi Orang Tua -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3"><i class="fas fa-users me-2"></i>Informasi Orang Tua</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nama_ortu" class="form-label">Nama Orang Tua</label>
                        <input type="text" class="form-control @error('nama_ortu') is-invalid @enderror"
                               id="nama_ortu" name="nama_ortu"
                               value="{{ old('nama_ortu', $siswaProfile?->nama_ortu) }}">
                        @error('nama_ortu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="no_telepon_ortu" class="form-label">Telepon Orang Tua</label>
                        <input type="tel" class="form-control @error('no_telepon_ortu') is-invalid @enderror"
                               id="no_telepon_ortu" name="no_telepon_ortu"
                               value="{{ old('no_telepon_ortu', $siswaProfile?->no_telepon_ortu) }}">
                        @error('no_telepon_ortu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Informasi Kesehatan -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3"><i class="fas fa-heartbeat me-2"></i>Informasi Kesehatan</h6>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="golongan_darah" class="form-label">Golongan Darah</label>
                        <select class="form-select @error('golongan_darah') is-invalid @enderror" id="golongan_darah" name="golongan_darah">
                            <option value="">Pilih</option>
                            @foreach(['A','B','AB','O'] as $gd)
                                <option value="{{ $gd }}" {{ old('golongan_darah', $siswaProfile?->golongan_darah) == $gd ? 'selected' : '' }}>{{ $gd }}</option>
                            @endforeach
                        </select>
                        @error('golongan_darah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="riwayat_penyakit" class="form-label">Riwayat Penyakit</label>
                        <input type="text" class="form-control @error('riwayat_penyakit') is-invalid @enderror"
                               id="riwayat_penyakit" name="riwayat_penyakit"
                               value="{{ old('riwayat_penyakit', $siswaProfile?->riwayat_penyakit) }}">
                        @error('riwayat_penyakit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="alergi" class="form-label">Alergi</label>
                        <input type="text" class="form-control @error('alergi') is-invalid @enderror"
                               id="alergi" name="alergi"
                               value="{{ old('alergi', $siswaProfile?->alergi) }}">
                        @error('alergi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="info_kesehatan" class="form-label">Info Kesehatan Lain</label>
                        <input type="text" class="form-control @error('info_kesehatan') is-invalid @enderror"
                               id="info_kesehatan" name="info_kesehatan"
                               value="{{ old('info_kesehatan', $siswaProfile?->info_kesehatan) }}">
                        @error('info_kesehatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.users.siswa') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-warning" id="submitBtnSiswa">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelector('form')?.addEventListener('submit', function () {
    var btn = document.getElementById('submitBtnSiswa');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';
    }
});
</script>
@endpush
