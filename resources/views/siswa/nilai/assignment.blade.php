@extends('layouts.siswa')

@section('title', 'Nilai Tugas')
@section('siswa-page-title', 'Nilai Tugas')
@section('page-subtitle', 'Rekap nilai seluruh tugas yang sudah dinilai')

@section('siswa-breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('siswa.nilai.index') }}">Nilai</a></li>
    <li class="breadcrumb-item active">Tugas</li>
@endsection

@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <div class="h3 fw-bold text-primary mb-1">{{ $stats['average_score'] }}</div>
                <small class="text-muted">Rata-rata</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <div class="h3 fw-bold text-info mb-1">{{ $stats['total_graded'] }}</div>
                <small class="text-muted">Total Dinilai</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <div class="h3 fw-bold text-success mb-1">{{ $stats['highest_score'] }}</div>
                <small class="text-muted">Tertinggi</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <div class="h3 fw-bold text-danger mb-1">{{ $stats['lowest_score'] }}</div>
                <small class="text-muted">Terendah</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold"><i class="fas fa-tasks me-2 text-success"></i>Daftar Nilai Tugas</h6>
        <a href="{{ route('siswa.nilai.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Judul Tugas</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Grade</th>
                        <th>Feedback</th>
                        <th>Dinilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scores as $i => $item)
                        @php
                            $score      = (float)($item->score ?? 0);
                            $grade      = $score >= 90 ? 'A' : ($score >= 80 ? 'B' : ($score >= 70 ? 'C' : ($score >= 60 ? 'D' : 'E')));
                            $gradeColor = ['A'=>'success','B'=>'primary','C'=>'info','D'=>'warning','E'=>'danger'][$grade];
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted">{{ $scores->firstItem() + $i }}</td>
                            <td>
                                <a href="{{ route('siswa.assignments.show', $item->assignment_id) }}"
                                   class="fw-semibold text-decoration-none text-dark">
                                    {{ $item->assignment?->title ?? '—' }}
                                </a>
                            </td>
                            <td class="text-muted">{{ $item->assignment?->subject?->nama ?? $item->assignment?->subject?->name ?? '—' }}</td>
                            <td class="text-muted">{{ $item->assignment?->guru?->name ?? '—' }}</td>
                            <td class="text-center">
                                <span class="fw-bold {{ $score >= 70 ? 'text-success' : 'text-danger' }}">{{ $score }}</span>
                            </td>
                            <td class="text-center"><span class="badge bg-{{ $gradeColor }}">{{ $grade }}</span></td>
                            <td>
                                @if($item->feedback)
                                    <small class="text-muted">{{ Str::limit($item->feedback, 60) }}</small>
                                @else
                                    <small class="text-muted">—</small>
                                @endif
                            </td>
                            <td class="text-muted">{{ $item->updated_at?->format('d M Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-tasks fa-3x text-muted opacity-50 mb-3 d-block"></i>
                                <h6 class="text-muted">Belum ada tugas yang dinilai</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($scores->hasPages())
        <div class="card-footer bg-white border-top">{{ $scores->links() }}</div>
    @endif
</div>

@endsection
