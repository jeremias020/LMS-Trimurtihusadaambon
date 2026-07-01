@extends('layouts.guru')

@section('title', 'Absensi Massal')
@section('page-title', 'Absensi Massal')
@section('page-subtitle', 'Catat absensi untuk seluruh kelas sekaligus')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('guru.absensi.index') }}">Absensi</a></li>
    <li class="breadcrumb-item active">Massal</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0 fw-semibold">Form Absensi Massal</h5></div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('guru.absensi.bulk') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-medium">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                           value="{{ old('date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Kelas <span class="text-danger">*</span></label>
                    <select name="class" class="form-select @error('class') is-invalid @enderror" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($classes as $id => $name)
                            <option value="{{ $id }}" {{ old('class') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('class')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="">Pilih Status</option>
                        <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="alpha" {{ old('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-medium">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control"
                           value="{{ old('keterangan') }}" placeholder="Opsional">
                </div>

                <div class="col-12">
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-2"></i>
                        Absensi massal akan mencatat status yang sama untuk semua siswa di kelas yang dipilih.
                        Siswa yang sudah memiliki absensi pada tanggal tersebut akan dilewati.
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('guru.absensi.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan Absensi Massal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
