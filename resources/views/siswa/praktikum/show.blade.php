@extends('layouts.siswa')

@section('title', 'Detail Praktikum')
@section('siswa-page-title', $practical->title ?? 'Detail Praktikum')
@section('siswa-breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('siswa.praktikum.index') }}">Praktikum</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Praktikum --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-flask me-2 text-primary"></i>Informasi Praktikum</h6>
            </div>
            <div class="card-body">
                <dl class="row small mb-0">
                    <dt class="col-5 text-muted">Judul</dt>
                    <dd class="col-7 fw-medium">{{ $practical->title }}</dd>

                    <dt class="col-5 text-muted">Mata Pelajaran</dt>
                    <dd class="col-7">{{ $practical->subject?->name ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Guru</dt>
                    <dd class="col-7">{{ $practical->guru?->name ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Kelas</dt>
                    <dd class="col-7">{{ $practical->kelas?->name ?? 'Semua Kelas' }}</dd>

                    <dt class="col-5 text-muted">Tanggal</dt>
                    <dd class="col-7">{{ $practical->due_date?->format('d M Y') ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Durasi</dt>
                    <dd class="col-7">{{ $practical->durasi ? $practical->durasi . ' menit' : '—' }}</dd>

                    <dt class="col-5 text-muted">Lokasi</dt>
                    <dd class="col-7">{{ $practical->lokasi ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Tingkat</dt>
                    <dd class="col-7">{{ $practical->skill_level ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Nilai Maks</dt>
                    <dd class="col-7">{{ $practical->max_score ?? 100 }}</dd>
                </dl>

                @if($practical->description)
                    <hr>
                    <p class="small text-muted mb-1 fw-semibold">Deskripsi</p>
                    <p class="small mb-0">{{ $practical->description }}</p>
                @endif

                @if($practical->instructions)
                    <hr>
                    <p class="small text-muted mb-1 fw-semibold">Instruksi</p>
                    <p class="small mb-0" style="white-space:pre-line">{{ $practical->instructions }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Nilai Saya --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-star me-2 text-warning"></i>Nilai Saya</h6>
            </div>
            <div class="card-body">
                @if($isGraded)
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                <div class="h2 fw-bold text-primary mb-0">{{ number_format($averageScore, 1) }}</div>
                                <small class="text-muted">Nilai Rata-rata</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                @php
                                    $grade = $averageScore >= 90 ? 'A' : ($averageScore >= 80 ? 'B' : ($averageScore >= 70 ? 'C' : ($averageScore >= 60 ? 'D' : 'E')));
                                    $gc = ['A'=>'success','B'=>'primary','C'=>'warning','D'=>'danger','E'=>'dark'][$grade];
                                @endphp
                                <div class="h2 fw-bold text-{{ $gc }} mb-0">{{ $grade }}</div>
                                <small class="text-muted">Grade</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                <div class="h2 fw-bold text-info mb-0">{{ $scores->count() }}</div>
                                <small class="text-muted">Kriteria Dinilai</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 {{ $averageScore >= 70 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 rounded">
                                <div class="h6 fw-bold {{ $averageScore >= 70 ? 'text-success' : 'text-danger' }} mb-0">
                                    {{ $averageScore >= 70 ? 'LULUS' : 'TIDAK LULUS' }}
                                </div>
                                <small class="text-muted">Status</small>
                            </div>
                        </div>
                    </div>

                    @if($scores->isNotEmpty())
                        <h6 class="fw-semibold mb-3">Detail Penilaian per Kriteria</h6>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kriteria</th>
                                        <th class="text-center">Nilai</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scores as $score)
                                    <tr>
                                        <td class="small">{{ $score->criteria?->name ?? 'Kriteria ' . $loop->iteration }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $score->score >= 70 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $score->score }}
                                            </span>
                                        </td>
                                        <td class="small text-muted">{{ $score->feedback ?? '—' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-clock fa-3x mb-3 opacity-50"></i>
                        <p class="mb-0">Nilai belum tersedia.</p>
                        <small>Nilai akan muncul setelah guru memberikan penilaian.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
