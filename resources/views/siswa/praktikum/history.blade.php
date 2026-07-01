@extends('layouts.siswa')

@section('title', 'Riwayat Praktikum')
@section('siswa-page-title', 'Riwayat Nilai Praktikum')
@section('siswa-breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('siswa.praktikum.index') }}">Praktikum</a></li>
    <li class="breadcrumb-item active">Riwayat</li>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stats-card text-center">
            <div class="h3 fw-bold text-primary mb-1">{{ $stats['total_graded'] }}</div>
            <div class="small text-muted">Total Dinilai</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stats-card text-center">
            <div class="h3 fw-bold text-success mb-1">{{ number_format($stats['average_score'], 1) }}</div>
            <div class="small text-muted">Rata-rata</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stats-card text-center">
            <div class="h3 fw-bold text-info mb-1">{{ number_format($stats['highest_score'], 1) }}</div>
            <div class="small text-muted">Tertinggi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stats-card text-center">
            <div class="h3 fw-bold text-warning mb-1">{{ number_format($stats['lowest_score'], 1) }}</div>
            <div class="small text-muted">Terendah</div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-history me-2 text-primary"></i>Riwayat Nilai</h5>
        <a href="{{ route('siswa.praktikum.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
    <div class="card-body p-0">
        @if($scores->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-flask fa-3x mb-3 opacity-50"></i>
                <p class="mb-0">Belum ada riwayat nilai praktikum.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Praktikum</th>
                            <th>Mata Pelajaran</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center">Grade</th>
                            <th>Tanggal Dinilai</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scores as $score)
                        <tr>
                            <td>
                                <a href="{{ route('siswa.praktikum.show', $score->practical_id) }}" class="fw-medium text-decoration-none">
                                    {{ $score->practical?->title ?? '—' }}
                                </a>
                            </td>
                            <td class="text-muted">{{ $score->practical?->subject?->name ?? '—' }}</td>
                            <td class="text-center">
                                @if($score->score !== null)
                                    <span class="fw-bold {{ $score->score >= 70 ? 'text-success' : 'text-danger' }}">
                                        {{ $score->score }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($score->score !== null)
                                    @php
                                        $g = $score->score >= 90 ? 'A' : ($score->score >= 80 ? 'B' : ($score->score >= 70 ? 'C' : ($score->score >= 60 ? 'D' : 'E')));
                                        $gc = ['A'=>'success','B'=>'primary','C'=>'warning','D'=>'danger','E'=>'dark'][$g];
                                    @endphp
                                    <span class="badge bg-{{ $gc }}">{{ $g }}</span>
                                @else
                                    <span class="badge bg-secondary">—</span>
                                @endif
                            </td>
                            <td class="text-muted">
                                {{ $score->graded_at ? \Carbon\Carbon::parse($score->graded_at)->format('d M Y') : $score->created_at->format('d M Y') }}
                            </td>
                            <td class="text-muted">{{ \Illuminate\Support\Str::limit($score->feedback ?? '—', 50) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3">
                {{ $scores->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
