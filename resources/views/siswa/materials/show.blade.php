@extends('layouts.siswa')

@section('title', 'Detail Materi')
@section('page-title', 'Detail Materi')
@section('page-subtitle', 'Informasi lengkap materi pembelajaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('siswa.materials.index') }}">Materi Pembelajaran</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $material->judul }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Material Detail Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $material->judul }}</h5>
                    <span class="badge bg-light text-primary">
                        <i class="fas fa-tag me-1"></i>{{ $material->category }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <!-- File Type and Size -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            @php
                                $extension = strtolower(pathinfo($material->file ?? '', PATHINFO_EXTENSION));
                                $iconClass = match($extension) {
                                    'pdf' => 'fas fa-file-pdf text-danger',
                                    'doc', 'docx' => 'fas fa-file-word text-primary',
                                    'ppt', 'pptx' => 'fas fa-file-powerpoint text-warning',
                                    'xls', 'xlsx' => 'fas fa-file-excel text-success',
                                    'mp4', 'avi', 'mov' => 'fas fa-file-video text-info',
                                    'jpg', 'jpeg', 'png' => 'fas fa-file-image text-info',
                                    'zip', 'rar' => 'fas fa-file-archive text-secondary',
                                    default => 'fas fa-file text-muted'
                                };
                            @endphp
                            <i class="{{ $iconClass }} fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-0">{{ $material->file ?? 'Tidak ada file' }}</h6>
                                <small class="text-muted">Ukuran tidak tersedia</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-md-end">
                            <span class="badge bg-primary me-2">
                                <i class="fas fa-book me-1"></i>{{ $material->subject?->nama ?? $material->subject?->name ?? 'Mata Pelajaran' }}
                            </span>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ optional($material->created_at)->format('d M Y H:i') ?? '-' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Deskripsi Materi</h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $material->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-eye text-primary fa-2x mb-2"></i>
                            <h5 class="mb-0">{{ $material->views_count ?? 0 }}</h5>
                            <small class="text-muted">Dilihat</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-download text-success fa-2x mb-2"></i>
                            <h5 class="mb-0">{{ $material->downloads_count ?? 0 }}</h5>
                            <small class="text-muted">Diunduh</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-users text-info fa-2x mb-2"></i>
                            <h5 class="mb-0">{{ $material->downloads_count ?? 0 }}</h5>
                            <small class="text-muted">Pengunduh</small>
                        </div>
                    </div>
                </div>

                <!-- Download Status -->
                @if($isDownloaded)
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>
                        <strong>Anda sudah mengunduh materi ini</strong>
                        <br>
                        <small>Waktu: Data tersimpan</small>
                    </div>
                </div>
                @endif
            </div>

            <!-- Card Footer -->
            <div class="card-footer bg-light">
                <div class="d-flex gap-2">
                    <a href="{{ route('siswa.materials.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    @if($material->file)
                    <a href="{{ route('siswa.materials.download', $material->id) }}" class="btn btn-success">
                        <i class="fas fa-download me-1"></i> Download Materi
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Teacher Info Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="fas fa-user-tie me-2"></i>Informasi Guru
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($material->guru?->foto)
                        <img src="{{ asset('storage/' . $material->guru->foto) }}" alt="Teacher" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-tie text-white fa-2x"></i>
                        </div>
                    @endif
                </div>
                <h6 class="fw-bold mb-1">{{ $material->guru?->name ?? 'Unknown Teacher' }}</h6>
                <small class="text-muted">{{ $material->guru?->email ?? 'No Email' }}</small>
            </div>
        </div>

        <!-- Related Materials -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="fas fa-book me-2"></i>Materi Terkait
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">Materi terkait akan segera tersedia</p>
            </div>
        </div>
    </div>
</div>
@endsection
