@extends('layouts.siswa')

@section('title', 'Riwayat Tugas')
@section('siswa-page-title', 'Riwayat Pengumpulan Tugas')
@section('siswa-breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('siswa.assignments.index') }}">Tugas</a></li>
    <li class="breadcrumb-item active">Riwayat</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-history me-2 text-primary"></i>Riwayat Pengumpulan</h5>
        <a href="{{ route('siswa.assignments.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
    <div class="card-body p-0">
        @if($submissions->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                <p class="mb-0">Belum ada riwayat pengumpulan tugas.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Judul Tugas</th>
                            <th>Guru</th>
                            <th>Dikumpulkan</th>
                            <th>Nilai</th>
                            <th>Status</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $sub)
                        <tr>
                            <td>
                                <a href="{{ route('siswa.assignments.show', $sub->assignment_id) }}" class="fw-medium text-decoration-none">
                                    {{ $sub->assignment?->title ?? '—' }}
                                </a>
                            </td>
                            <td class="text-muted">{{ $sub->assignment?->guru?->name ?? '—' }}</td>
                            <td class="text-muted">
                                {{ $sub->submitted_at ? \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y H:i') : $sub->created_at->format('d M Y H:i') }}
                            </td>
                            <td>
                                @if($sub->score !== null)
                                    @php
                                        $g = $sub->score >= 90 ? 'A' : ($sub->score >= 80 ? 'B' : ($sub->score >= 70 ? 'C' : ($sub->score >= 60 ? 'D' : 'E')));
                                        $gc = ['A'=>'success','B'=>'primary','C'=>'warning','D'=>'danger','E'=>'dark'][$g];
                                    @endphp
                                    <span class="fw-bold text-{{ $gc }}">{{ $sub->score }}</span>
                                    <span class="badge bg-{{ $gc }} ms-1">{{ $g }}</span>
                                @else
                                    <span class="text-muted">Belum dinilai</span>
                                @endif
                            </td>
                            <td>
                                @if($sub->score !== null)
                                    <span class="badge bg-success">Dinilai</span>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ \Illuminate\Support\Str::limit($sub->feedback ?? '—', 50) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3">
                {{ $submissions->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
