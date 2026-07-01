@extends('layouts.admin')

@section('title', 'Edit Guru - ' . $user->name)
@section('page-title', 'Edit Guru')
@section('page-subtitle', $user->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.guru') }}">Guru</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Edit: {{ $user->name }}</h5>
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

                <!-- Informasi Guru -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3"><i class="fas fa-briefcase me-2"></i>Informasi Guru</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror"
                               id="nip" name="nip" value="{{ old('nip', $guruProfile?->nip) }}"
                               placeholder="Nomor Induk Pegawai (opsional)">
                        @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="subject_id" class="form-label">Mata Pelajaran</label>
                        <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id">
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                                @php
                                    $selected = old('subject_id')
                                        ? old('subject_id') == $subject->id
                                        : ($guruProfile && $guruProfile->mata_pelajaran == $subject->name);
                                @endphp
                                <option value="{{ $subject->id }}" {{ $selected ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                    @if($subject->code) ({{ $subject->code }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email_pribadi" class="form-label">Email Pribadi</label>
                        <input type="email" class="form-control @error('email_pribadi') is-invalid @enderror"
                               id="email_pribadi" name="email_pribadi"
                               value="{{ old('email_pribadi', $guruProfile?->email_pribadi) }}">
                        @error('email_pribadi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                        <select class="form-select @error('pendidikan_terakhir') is-invalid @enderror"
                                id="pendidikan_terakhir" name="pendidikan_terakhir">
                            <option value="">Pilih Pendidikan</option>
                            @foreach(['SMA','D3','S1','S2','S3'] as $edu)
                                <option value="{{ $edu }}" {{ old('pendidikan_terakhir', $guruProfile?->pendidikan_terakhir) == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                            @endforeach
                        </select>
                        @error('pendidikan_terakhir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="jurusan_pendidikan" class="form-label">Jurusan Pendidikan</label>
                        <input type="text" class="form-control @error('jurusan_pendidikan') is-invalid @enderror"
                               id="jurusan_pendidikan" name="jurusan_pendidikan"
                               value="{{ old('jurusan_pendidikan', $guruProfile?->jurusan_pendidikan) }}">
                        @error('jurusan_pendidikan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tahun_mulai_kerja" class="form-label">Tahun Mulai Kerja</label>
                        <input type="number" class="form-control @error('tahun_mulai_kerja') is-invalid @enderror"
                               id="tahun_mulai_kerja" name="tahun_mulai_kerja"
                               min="1900" max="{{ date('Y') }}"
                               value="{{ old('tahun_mulai_kerja', $guruProfile?->tahun_mulai_kerja) }}">
                        @error('tahun_mulai_kerja')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                               value="{{ old('tempat_lahir', $guruProfile?->tempat_lahir) }}">
                        @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                               id="tanggal_lahir" name="tanggal_lahir"
                               value="{{ old('tanggal_lahir', $guruProfile?->tanggal_lahir?->format('Y-m-d')) }}">
                        @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin', $guruProfile?->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $guruProfile?->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                  id="alamat" name="alamat" rows="2">{{ old('alamat', $guruProfile?->alamat) }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.users.guru') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
