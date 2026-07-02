@extends('layouts.siswa')

@section('title', 'Nilai Praktikum')
@section('siswa-page-title', 'Nilai Praktikum')
@section('page-subtitle', 'Rekap nilai seluruh sesi praktikum Anda')

@section('siswa-breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('siswa.nilai.index') }}">Nilai</a></li>
    <li class="breadcrumb-item active">Praktikum</li>
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
                <div class="h3 fw-bold text-info mb-1">{{ $stats['total_scores'] }}</div>
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
        <h6 class="mb-0 fw-semibold"><i class="fas fa-flask me-2 text-warning"></i>Daftar Nilai Praktikum</h6>
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
                        <th>Judul Praktikum</th>
                        <th>Mata Pelajaran</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Grade</th>
                        <th>Feedback</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scores as $i => $item)
                        @php
                            $score      = (float)($item->score ?? 0);
                            $grade      = $score >= 90 ? 'A' : ($score >= 80 ? 'B' : ($score >= 70 ? 'C' : ($score >= 60 ? 'D' : 'E')));
                            $gradeColor = ['A'=>'success','B'=>'primary','C'=>'info','D'=>'warning','E'=>'danger'][$grade];
                            $feedbackRaw = $item->feedback ?? null;
                            $feedbackArr = ($feedbackRaw && str_starts_with(trim($feedbackRaw), '[')) ? json_decode($feedbackRaw, true) : null;
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted">{{ $scores->firstItem() + $i }}</td>
                            <td>
                                <div class="fw-semibold">{{ $item->practical?->title ?? '—' }}</div>
                                @if($item->practical?->kelas)
                                    <small class="text-muted">{{ $item->practical->kelas->name }}</small>
                                @endif
                            </td>
                            <td class="text-muted">{{ $item->practical?->subject?->name ?? $item->practical?->subject?->nama ?? '—' }}</td>
                            <td class="text-center">
                                <span class="fw-bold {{ $score >= 70 ? 'text-success' : 'text-danger' }}">{{ $score }}</span>
                            </td>
                            <td class="text-center"><span class="badge bg-{{ $gradeColor }}">{{ $grade }}</span></td>
                            <td>
                                @if($feedbackArr)
                                    <small class="text-muted">{{ count($feedbackArr) }} poin SOP terpenuhi</small>
                                @elseif($feedbackRaw)
                                    <small class="text-muted">{{ Str::limit($feedbackRaw, 60) }}</small>
                                @else
                                    <small class="text-muted">—</small>
                                @endif
                            </td>
                            <td class="text-muted">
                                {{ $item->graded_at ? \Carbon\Carbon::parse($item->graded_at)->format('d M Y') : $item->created_at?->format('d M Y') ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-flask fa-3x text-muted opacity-50 mb-3 d-block"></i>
                                <h6 class="text-muted">Belum ada nilai praktikum</h6>
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
