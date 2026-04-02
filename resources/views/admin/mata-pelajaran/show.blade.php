@extends('admin.layouts.admin-layout')

@section('title', 'Detail Mata Pelajaran')

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <div class="d-flex align-items-center">
            <i class="fas fa-eye me-2"></i>
            <h5 class="mb-0">Detail Mata Pelajaran: {{ $mataPelajaran->nama }}</h5>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Success Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Detail Information -->
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Informasi Mata Pelajaran</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted">Kode</label>
                                <p class="form-control-plaintext fw-bold">
                                    <span class="badge bg-secondary">{{ $mataPelajaran->kode }}</span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted">Nama</label>
                                <p class="form-control-plaintext fw-bold">{{ $mataPelajaran->nama }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted">Jenis</label>
                                <p class="form-control-plaintext">
                                    @if($mataPelajaran->jenis === 'umum')
                                        <span class="badge bg-info">Umum</span>
                                    @else
                                        <span class="badge bg-warning">Kejuruan</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted">Jam per Minggu</label>
                                <p class="form-control-plaintext fw-bold">{{ $mataPelajaran->jam_per_minggu }} jam</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted">Status</label>
                                <p class="form-control-plaintext">
                                    @if($mataPelajaran->status)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted">Dibuat</label>
                                <p class="form-control-plaintext">{{ $mataPelajaran->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Deskripsi</label>
                            <p class="form-control-plaintext">
                                {{ $mataPelajaran->deskripsi ?: 'Tidak ada deskripsi' }}
                            </p>
                        </div>

                        @if($mataPelajaran->updated_at->gt($mataPelajaran->created_at))
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label text-muted">Terakhir Diperbarui</label>
                                <p class="form-control-plaintext">{{ $mataPelajaran->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Aksi Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.mata-pelajaran.edit', $mataPelajaran->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Edit Mata Pelajaran
                            </a>
                            
                            <form action="{{ route('admin.mata-pelajaran.toggle-status', $mataPelajaran->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status mata pelajaran ini?')">
                                @csrf
                                <button type="submit" class="btn btn-secondary w-100">
                                    <i class="fas fa-power-off me-2"></i>
                                    {{ $mataPelajaran->status ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Statistik</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3 mb-3">
                                <i class="fas fa-book text-primary fs-1"></i>
                            </div>
                            <h6 class="text-muted">Mata Pelajaran</h6>
                            <p class="h4 text-primary mb-0">{{ $mataPelajaran->nama }}</p>
                            <small class="text-muted">Kode: {{ $mataPelajaran->kode }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Jurusan (if any) -->
        @if($mataPelajaran->jurusan->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Jurusan Terkait</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($mataPelajaran->jurusan as $jurusan)
                            <div class="col-md-4 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-graduation-cap text-primary fs-3 mb-2"></i>
                                        <h6 class="mb-1">{{ $jurusan->nama }}</h6>
                                        <small class="text-muted">{{ $jurusan->kode }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
