@extends('layouts.guru')

@section('title', 'Laporan Materi')
@section('page-title', 'Laporan Materi')
@section('page-subtitle', 'Statistik materi dan download.')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('guru.laporan.index') }}" class="text-decoration-none">Laporan</a></li>
    <li class="breadcrumb-item active">Materi</li>
@endsection

@section('content')

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('guru.laporan.materi') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Dari</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $filters['start_date'] }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Sampai</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $filters['end_date'] }}">
            </div>
            <div class="col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-search me-1"></i>Filter
                </button>
                <a href="{{ route('guru.laporan.materi') }}" class="btn btn-outline-secondary btn-sm flex-fill">
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
            ['label'=>'Total Materi',  'value'=>$summaryStats['total'],          'color'=>'primary'],
            ['label'=>'Diterbitkan',   'value'=>$summaryStats['published'],      'color'=>'success'],
            ['label'=>'Total Download','value'=>$summaryStats['total_downloads'],'color'=>'info'],
            ['label'=>'Total Views',   'value'=>$summaryStats['total_views'],    'color'=>'warning'],
        ];
    @endphp
    @foreach($sItems as $s)
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100 text-center">
            <div class="card-body py-3">
                <div class="h5 fw-bold text-{{ $s['color'] }} mb-0">{{ number_format($s['value']) }}</div>
                <small class="text-muted">{{ $s['label'] }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Tabel --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">Daftar Materi <span class="badge bg-secondary ms-1">{{ $materials->total() }}</span></h6>
        <form method="POST" action="{{ route('guru.laporan.generate') }}" class="d-inline">
            @csrf
            <input type="hidden" name="type" value="materi">
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
                    <th>Judul Materi</th>
                    <th>Mata Pelajaran</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Download</th>
                    <th class="text-center">Views</th>
                    <th class="text-center">Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $m)
                <tr>
                    <td>
                        <div class="fw-semibold text-truncate" style="font-size:13px;max-width:200px;">{{ $m->title }}</div>
                    </td>
                    <td><small class="text-muted">{{ $m->subject?->name ?? '—' }}</small></td>
                    <td class="text-center">
                        @if($m->published_at)
                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25" style="font-size:10px;">Diterbitkan</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border" style="font-size:10px;">Draft</span>
                        @endif
                    </td>
                    <td class="text-center fw-semibold text-info">{{ $m->downloads_count }}</td>
                    <td class="text-center text-muted">{{ $m->views_count }}</td>
                    <td class="text-center small text-muted">{{ $m->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-book-open fa-2x mb-2 d-block opacity-40"></i>
                        Tidak ada materi pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($materials->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">{{ $materials->firstItem() }}–{{ $materials->lastItem() }} dari {{ $materials->total() }}</small>
        {{ $materials->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
