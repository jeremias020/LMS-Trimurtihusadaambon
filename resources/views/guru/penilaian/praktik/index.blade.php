@extends('layouts.guru')

@section('title', 'Penilaian Praktik Berbasis SOP')
@section('page-title', 'Penilaian Praktik')
@section('page-subtitle', 'Nilai siswa menggunakan checklist SOP.')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('guru/penilaian') }}" class="text-decoration-none">Penilaian</a></li>
    <li class="breadcrumb-item active">Penilaian Praktik</li>
@endsection

@section('content')

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

{{-- Cara kerja --}}
<div class="alert alert-info border-0 shadow-sm mb-4">
    <div class="d-flex gap-3 align-items-start">
        <i class="fas fa-info-circle fs-5 mt-1 flex-shrink-0"></i>
        <div>
            <strong>Cara Kerja Penilaian Praktik:</strong>
            <ol class="mb-0 mt-1 ps-3">
                <li>Klik <strong>"Atur SOP"</strong> → isi poin-poin checklist beserta bobot % → Simpan</li>
                <li>Klik <strong>"Mulai Nilai"</strong> → centang poin SOP yang terpenuhi per siswa</li>
                <li>Nilai dihitung otomatis dari jumlah bobot poin yang dicentang</li>
            </ol>
        </div>
    </div>
</div>

{{-- Daftar Praktikum --}}
@if($practicals->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5 text-muted">
        <i class="fas fa-flask fa-2x mb-2 d-block opacity-40"></i>
        <span class="d-block mb-2">Belum ada praktikum.</span>
        @if(Route::has('guru.praktikum.create'))
        <a href="{{ route('guru.praktikum.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Buat Praktikum
        </a>
        @endif
    </div>
</div>
@else
<div class="row g-4">
    @foreach($practicals as $p)
    @php
        $hasSop  = !empty($p->sop_list);
        $pct     = $p->total_siswa > 0 ? round($p->dinilai / $p->total_siswa * 100) : 0;
        $allDone = $p->total_siswa > 0 && $p->dinilai >= $p->total_siswa;
    @endphp
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                <div class="overflow-hidden">
                    <h6 class="fw-semibold text-truncate mb-0">{{ $p->title }}</h6>
                    <small class="text-muted">
                        {{ $p->subject?->name ?? '—' }}
                        @if($p->kelas) · {{ $p->kelas->name }} @endif
                    </small>
                </div>
                @if($allDone)
                    <span class="badge bg-success-subtle text-success border border-success border-opacity-25 flex-shrink-0">Selesai</span>
                @elseif($hasSop)
                    <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25 flex-shrink-0">Belum Selesai</span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary border flex-shrink-0">Belum Ada SOP</span>
                @endif
            </div>

            <div class="card-body">
                {{-- Progress penilaian --}}
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Progress Penilaian</span>
                    <span>{{ $p->dinilai }}/{{ $p->total_siswa }} siswa</span>
                </div>
                <div class="progress mb-3" style="height:6px;">
                    <div class="progress-bar {{ $allDone ? 'bg-success' : 'bg-primary' }}"
                         style="width:{{ $pct }}%;"></div>
                </div>

                {{-- SOP info --}}
                @if($hasSop)
                <div class="small text-muted mb-0">
                    <i class="fas fa-list-check me-1 text-success"></i>
                    {{ count($p->sop_list) }} poin SOP ·
                    Total bobot: {{ array_sum(array_column($p->sop_list, 'bobot')) }}%
                </div>
                @else
                <div class="small text-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    SOP belum diatur — penilaian tidak dapat dilakukan.
                </div>
                @endif
            </div>

            <div class="card-footer bg-white border-top d-flex gap-2">
                {{-- Tombol Atur SOP → halaman baru (bukan modal) --}}
                <a href="{{ url('guru/penilaian/praktik/' . $p->id . '/sop') }}"
                   class="btn btn-outline-secondary btn-sm flex-fill">
                    <i class="fas fa-list me-1"></i>{{ $hasSop ? 'Edit SOP' : 'Atur SOP' }}
                </a>
                {{-- Tombol Nilai --}}
                @if($hasSop)
                <a href="{{ url('guru/penilaian/praktik/' . $p->id . '/nilai') }}"
                   class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-star me-1"></i>Mulai Nilai
                </a>
                @else
                <button class="btn btn-secondary btn-sm flex-fill" disabled title="Atur SOP dulu">
                    <i class="fas fa-lock me-1"></i>Mulai Nilai
                </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
