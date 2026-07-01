@extends('layouts.guru')

@section('title', 'Laporan Penilaian Praktik')
@section('page-title', 'Laporan Penilaian Praktik')
@section('page-subtitle', 'Rekap nilai praktik berbasis SOP.')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('guru.laporan.index') }}" class="text-decoration-none">Laporan</a></li>
    <li class="breadcrumb-item active">Penilaian Praktik</li>
@endsection

@section('content')

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('guru.laporan.praktik') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Dari</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $filters['start_date'] }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Sampai</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $filters['end_date'] }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold mb-1">Kelas</label>
                <select name="kelas_id" class="form-select form-select-sm">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $id => $name)
                        <option value="{{ $id }}" {{ $filters['kelas_id'] == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-search me-1"></i>Filter
                </button>
                <a href="{{ route('guru.laporan.praktik') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                    <i class="fas fa-undo me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @php
        $sItems = [
            ['label'=>'Total Dinilai','value'=>$summaryStats['total'],     'color'=>'primary'],
            ['label'=>'Rata-rata Nilai','value'=>$summaryStats['avg_score'],'color'=>'info'],
            ['label'=>'Lulus (≥70)',  'value'=>$summaryStats['lulus'],     'color'=>'success'],
            ['label'=>'Tidak Lulus',  'value'=>$summaryStats['tidak_lulus'],'color'=>'danger'],
        ];
    @endphp
    @foreach($sItems as $s)
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100 text-center">
            <div class="card-body py-3">
                <div class="h5 fw-bold text-{{ $s['color'] }} mb-0">{{ $s['value'] }}</div>
                <small class="text-muted">{{ $s['label'] }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Tabel --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">Data Penilaian <span class="badge bg-secondary ms-1">{{ $nilaiList->total() }}</span></h6>
        <form method="POST" action="{{ route('guru.laporan.generate') }}" class="d-inline">
            @csrf
            <input type="hidden" name="type" value="praktik">
            <input type="hidden" name="start_date" value="{{ $filters['start_date'] }}">
            <input type="hidden" name="end_date" value="{{ $filters['end_date'] }}">
            <button type="submit" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-download me-1"></i>CSV
            </button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Siswa</th>
                    <th>Praktikum</th>
                    <th>Mata Pelajaran</th>
                    <th class="text-center">Nilai</th>
                    <th class="text-center">Grade</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilaiList as $n)
                @php
                    $score = (float)($n->score ?? 0);
                    $g  = $score >= 90 ? 'A' : ($score >= 80 ? 'B' : ($score >= 70 ? 'C' : ($score >= 60 ? 'D' : 'E')));
                    $gc = $g === 'A' ? 'success' : ($g === 'B' ? 'primary' : ($g === 'C' ? 'info' : ($g === 'D' ? 'warning' : 'danger')));
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold" style="font-size:13px;">{{ $n->siswa?->name ?? '—' }}</div>
                    </td>
                    <td><small>{{ $n->practical?->title ?? '—' }}</small></td>
                    <td><small class="text-muted">{{ $n->practical?->subject?->name ?? '—' }}</small></td>
                    <td class="text-center fw-bold text-{{ $gc }}">{{ $score }}</td>
                    <td class="text-center"><span class="badge bg-{{ $gc }}">{{ $g }}</span></td>
                    <td class="text-center">
                        @if($score >= 70)
                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25" style="font-size:10px;">Lulus</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25" style="font-size:10px;">Tidak Lulus</span>
                        @endif
                    </td>
                    <td class="text-center small text-muted">
                        {{ $n->graded_at?->format('d/m/Y') ?? $n->updated_at->format('d/m/Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-flask fa-2x mb-2 d-block opacity-40"></i>
                        Tidak ada data penilaian praktik pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($nilaiList->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">{{ $nilaiList->firstItem() }}–{{ $nilaiList->lastItem() }} dari {{ $nilaiList->total() }}</small>
        {{ $nilaiList->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
