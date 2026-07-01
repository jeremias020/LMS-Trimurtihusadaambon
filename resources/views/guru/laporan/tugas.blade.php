@extends('layouts.guru')

@section('title', 'Laporan Tugas')
@section('page-title', 'Laporan Tugas')
@section('page-subtitle', 'Status pengumpulan dan penilaian tugas.')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('guru.laporan.index') }}" class="text-decoration-none">Laporan</a></li>
    <li class="breadcrumb-item active">Tugas</li>
@endsection

@section('content')

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('guru.laporan.tugas') }}" class="row g-2 align-items-end">
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
                <a href="{{ route('guru.laporan.tugas') }}" class="btn btn-outline-secondary btn-sm flex-fill">
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
            ['label'=>'Total Tugas',     'value'=>$summaryStats['total_tugas'],      'color'=>'primary'],
            ['label'=>'Total Submission','value'=>$summaryStats['total_submissions'],'color'=>'info'],
            ['label'=>'Sudah Dinilai',   'value'=>$summaryStats['graded'],           'color'=>'success'],
            ['label'=>'Belum Dinilai',   'value'=>$summaryStats['pending'],          'color'=>'warning'],
            ['label'=>'Rata-rata Nilai', 'value'=>$summaryStats['avg_score'],        'color'=>'primary'],
        ];
    @endphp
    @foreach($sItems as $s)
    <div class="col-6 col-md-auto" style="min-width:140px;">
        <div class="card border-0 shadow-sm h-100 text-center">
            <div class="card-body py-3 px-2">
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
        <h6 class="mb-0 fw-semibold">Daftar Tugas <span class="badge bg-secondary ms-1">{{ $assignments->total() }}</span></h6>
        <form method="POST" action="{{ route('guru.laporan.generate') }}" class="d-inline">
            @csrf
            <input type="hidden" name="type" value="tugas">
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
                    <th>Judul Tugas</th>
                    <th>Mata Pelajaran</th>
                    <th class="text-center">Deadline</th>
                    <th class="text-center">Total Submit</th>
                    <th class="text-center">Dinilai</th>
                    <th class="text-center">Belum</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $a)
                <tr>
                    <td>
                        <div class="fw-semibold" style="font-size:13px;">{{ $a->title }}</div>
                        <small class="text-muted">{{ \Str::limit($a->description ?? '', 50) }}</small>
                    </td>
                    <td><small class="text-muted">{{ $a->subject?->name ?? '—' }}</small></td>
                    <td class="text-center small">
                        {{ $a->deadline?->format('d M Y') ?? '—' }}
                        @if($a->deadline?->isPast())
                            <span class="badge bg-secondary d-block mt-1" style="font-size:10px;">Berakhir</span>
                        @endif
                    </td>
                    <td class="text-center fw-semibold">{{ $a->submissions_count }}</td>
                    <td class="text-center text-success fw-semibold">{{ $a->graded_count }}</td>
                    <td class="text-center text-danger fw-semibold">{{ $a->pending_count }}</td>
                    <td class="text-center">
                        @if($a->is_published)
                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25" style="font-size:10px;">Aktif</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border" style="font-size:10px;">Draft</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-tasks fa-2x mb-2 d-block opacity-40"></i>
                        Tidak ada tugas pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($assignments->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">{{ $assignments->firstItem() }}–{{ $assignments->lastItem() }} dari {{ $assignments->total() }}</small>
        {{ $assignments->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
