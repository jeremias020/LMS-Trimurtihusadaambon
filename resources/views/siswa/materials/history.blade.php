@extends('layouts.siswa')

@section('title', 'Riwayat Unduhan Materi')
@section('siswa-page-title', 'Riwayat Unduhan Materi')
@section('siswa-breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('siswa.materials.index') }}">Materi</a></li>
    <li class="breadcrumb-item active">Riwayat Unduhan</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-history me-2 text-primary"></i>Riwayat Unduhan</h5>
        <a href="{{ route('siswa.materials.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
    <div class="card-body p-0">
        @if($downloads->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-download fa-3x mb-3 opacity-50"></i>
                <p class="mb-0">Belum ada riwayat unduhan.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Judul Materi</th>
                            <th>Mata Pelajaran</th>
                            <th>Tanggal Unduh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($downloads as $dl)
                        <tr>
                            <td class="fw-medium">{{ $dl->material?->title ?? '—' }}</td>
                            <td class="text-muted">{{ $dl->material?->subject?->name ?? '—' }}</td>
                            <td class="text-muted">
                                {{ $dl->downloaded_at ? \Carbon\Carbon::parse($dl->downloaded_at)->format('d M Y H:i') : $dl->created_at->format('d M Y H:i') }}
                            </td>
                            <td>
                                @if($dl->material)
                                    <a href="{{ route('siswa.materials.download', $dl->material_id) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download me-1"></i>Unduh Lagi
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3">
                {{ $downloads->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
