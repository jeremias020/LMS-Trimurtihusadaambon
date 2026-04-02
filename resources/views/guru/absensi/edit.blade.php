@extends('layouts.guru')

@section('title', 'Edit Absensi - LMS Trimurti Husada')

@section('page-title', 'Edit Absensi')
@section('page-subtitle', 'Perbarui data kehadiran siswa')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('guru.absensi.index') }}" class="text-decoration-none">Absensi</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('page-actions')
<a href="{{ route('guru.absensi.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-2"></i>Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2 text-primary"></i>
                    Form Edit Absensi
                </h5>
            </div>

            <form action="{{ route('guru.absensi.update', $absensi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Siswa</label>
                            <div class="form-control bg-light">
                                {{ $absensi->siswa->name ?? '-' }}
                                @if(!empty($absensi->siswa?->kelas?->name))
                                    <span class="text-muted">(Kelas {{ $absensi->siswa->kelas->name }})</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <div class="form-control bg-light">{{ $absensi->tanggal?->format('d/m/Y') ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status *</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="hadir" {{ old('status', $absensi->status) === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="izin" {{ old('status', $absensi->status) === 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="sakit" {{ old('status', $absensi->status) === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="alpha" {{ old('status', $absensi->status) === 'alpha' ? 'selected' : '' }}>Alpha</option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="waktu_masuk" class="form-label">Waktu Masuk</label>
                            <input type="time" name="waktu_masuk" id="waktu_masuk" class="form-control"
                                   value="{{ old('waktu_masuk', $absensi->waktu_masuk?->format('H:i')) }}" autocomplete="off">
                            @error('waktu_masuk')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="waktu_keluar" class="form-label">Waktu Keluar</label>
                            <input type="time" name="waktu_keluar" id="waktu_keluar" class="form-control"
                                   value="{{ old('waktu_keluar', $absensi->waktu_keluar?->format('H:i')) }}" autocomplete="off">
                            @error('waktu_keluar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3" maxlength="500"
                                      placeholder="Keterangan (opsional)">{{ old('keterangan', $absensi->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="{{ route('guru.absensi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function toggleTimeField() {
        const status = document.getElementById('status').value;
        const waktuMasukField = document.getElementById('waktu_masuk');
        const waktuKeluarField = document.getElementById('waktu_keluar');

        if (status === 'hadir') {
            waktuMasukField.disabled = false;
            waktuMasukField.required = true;
            waktuKeluarField.disabled = false;
            waktuKeluarField.required = true;
        } else {
            waktuMasukField.disabled = true;
            waktuMasukField.required = false;
            waktuMasukField.value = '';
            waktuKeluarField.disabled = true;
            waktuKeluarField.required = false;
            waktuKeluarField.value = '';
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleTimeField();
        const statusField = document.getElementById('status');
        if (statusField) {
            statusField.addEventListener('change', toggleTimeField);
        }
    });
</script>
@endpush
