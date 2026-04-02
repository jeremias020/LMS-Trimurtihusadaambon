@extends('admin.layouts.admin-layout')

@section('title', 'Detail Kriteria Penilaian')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.kriteria-penilaian.index') }}">Kriteria Penilaian</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Detail Kriteria: {{ $kriteriaPenilaian->nama }}
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.kriteria-penilaian.edit', $kriteriaPenilaian->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.kriteria-penilaian.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-2 text-muted small">Nama Kriteria</div>
                            <div class="h6 mb-0">{{ $kriteriaPenilaian->nama }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2 text-muted small">Kategori</div>
                            <div>
                                @switch($kriteriaPenilaian->kategori)
                                    @case('persiapan')<span class="badge bg-info">Persiapan</span>@break
                                    @case('pelaksanaan')<span class="badge bg-primary">Pelaksanaan</span>@break
                                    @case('hasil')<span class="badge bg-success">Hasil</span>@break
                                    @case('sikap')<span class="badge bg-warning text-dark">Sikap</span>@break
                                    @default <span class="badge bg-secondary">-</span>
                                @endswitch
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2 text-muted small">Bobot</div>
                            <div class="h6 mb-0">{{ number_format((float)$kriteriaPenilaian->bobot * 100, 0) }}%</div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2 text-muted small">Mata Praktik</div>
                            <div class="h6 mb-0">{{ $kriteriaPenilaian->mata_praktik }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2 text-muted small">Tingkat Kelas</div>
                            <div class="h6 mb-0">{{ $kriteriaPenilaian->tingkat_kelas }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2 text-muted small">Status</div>
                            <div>
                                @if($kriteriaPenilaian->status)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-4">
                        <div class="mb-2 text-muted small">Deskripsi</div>
                        <div>{{ $kriteriaPenilaian->deskripsi ?: '-' }}</div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-muted small">SOP Checklist ({{ is_array($kriteriaPenilaian->sop_checklist) ? count($kriteriaPenilaian->sop_checklist) : 0 }})</div>
                        </div>
                        @if(is_array($kriteriaPenilaian->sop_checklist) && count($kriteriaPenilaian->sop_checklist))
                            <ol class="list-group list-group-numbered">
                                @foreach($kriteriaPenilaian->sop_checklist as $item)
                                    <li class="list-group-item d-flex align-items-start">
                                        <i class="fas fa-check text-success me-2 mt-1"></i>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <div class="text-muted fst-italic">Belum ada checklist</div>
                        @endif
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.kriteria-penilaian.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.kriteria-penilaian.edit', $kriteriaPenilaian->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.kriteria-penilaian.destroy', $kriteriaPenilaian->id) }}" method="POST" onsubmit="return confirm('Hapus kriteria ini? Tindakan tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
