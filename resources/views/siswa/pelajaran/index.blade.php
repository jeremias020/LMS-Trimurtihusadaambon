@extends('layouts.siswa')

@section('title', 'Mata Pelajaran')
@section('siswa-page-title', 'Mata Pelajaran')
@section('page-subtitle', 'Daftar mata pelajaran yang tersedia untuk Anda')

@section('siswa-breadcrumb')
    <li class="breadcrumb-item active">Mata Pelajaran</li>
@endsection

@section('content')

@if($siswaData)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3 small">
            <div class="col-md-6">
                <div class="d-flex flex-column gap-1">
                    <div><span class="text-muted" style="min-width:90px;display:inline-block;">Nama</span><span class="fw-semibold">{{ $siswaData['name'] }}</span></div>
                    <div><span class="text-muted" style="min-width:90px;display:inline-block;">NIS/NISN</span><span class="fw-semibold">{{ $siswaData['nis_nip'] ?? '—' }}</span></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex flex-column gap-1">
                    <div><span class="text-muted" style="min-width:90px;display:inline-block;">Kelas</span><span class="fw-semibold">{{ $siswaData['kelas'] }}</span></div>
                    <div><span class="text-muted" style="min-width:90px;display:inline-block;">Jurusan</span><span class="fw-semibold">{{ $siswaData['jurusan'] }}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($subjects->count() > 0)
    <div class="row g-3">
        @foreach($subjects as $subject)
            <div class="col-xl-4 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="rounded-3 flex-shrink-0 p-3"
                                 style="background:{{ ['#dbeafe','#dcfce7','#fef9c3','#fce7f3','#ede9fe'][($loop->index % 5)] }};">
                                <i class="fas fa-graduation-cap fa-lg"
                                   style="color:{{ ['#3b82f6','#22c55e','#eab308','#ec4899','#8b5cf6'][($loop->index % 5)] }};"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <h6 class="fw-semibold mb-1">{{ $subject->name }}</h6>
                                @if($subject->code)
                                    <small class="text-muted me-2"><i class="fas fa-tag me-1"></i>{{ $subject->code }}</small>
                                @endif
                                @if($subject->jurusan)
                                    <small class="text-muted">{{ $subject->jurusan->name }}</small>
                                @endif
                            </div>
                        </div>
                        @if($subject->description)
                            <p class="text-muted small mb-3">{{ Str::limit($subject->description, 90) }}</p>
                        @endif
                        <div class="row g-2 text-center mb-3">
                            <div class="col-4">
                                <div class="rounded-3 bg-primary bg-opacity-10 py-2">
                                    <div class="fw-bold text-primary">{{ $subject->material_count }}</div>
                                    <small class="text-muted" style="font-size:11px;">Materi</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="rounded-3 bg-success bg-opacity-10 py-2">
                                    <div class="fw-bold text-success">{{ $subject->assignment_count }}</div>
                                    <small class="text-muted" style="font-size:11px;">Tugas</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="rounded-3 bg-warning bg-opacity-10 py-2">
                                    <div class="fw-bold text-warning">{{ $subject->practical_count }}</div>
                                    <small class="text-muted" style="font-size:11px;">Praktikum</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <small class="text-muted">Total: <strong>{{ $subject->total_activities }}</strong> aktivitas</small>
                            <a href="{{ route('siswa.pelajaran.show', $subject->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-book-open fa-3x text-muted opacity-25 mb-3 d-block"></i>
            <h5 class="text-muted">Belum Ada Mata Pelajaran</h5>
            <p class="text-muted small mb-3">
                @if(isset($siswaData['kelas']) && $siswaData['kelas'] === 'Belum ada kelas')
                    Anda belum terdaftar di kelas. Hubungi admin.
                @else
                    Belum ada mata pelajaran yang tersedia.
                @endif
            </p>
        </div>
    </div>
@endif

@endsection
