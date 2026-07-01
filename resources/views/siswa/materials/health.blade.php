@extends('layouts.siswa')

@section('title', 'Materi Kesehatan')
@section('siswa-page-title', 'Materi Kesehatan')
@section('siswa-breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('siswa.materials.index') }}">Materi</a></li>
    <li class="breadcrumb-item active">Kesehatan</li>
@endsection

@section('content')
<div class="row g-4">
    @forelse($materials as $material)
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-start mb-3">
                    <div class="rounded bg-success bg-opacity-10 p-2 me-3">
                        <i class="fas fa-heartbeat text-success fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <h6 class="fw-semibold mb-1 text-truncate">{{ $material->title }}</h6>
                        <small class="text-muted">{{ $material->subject?->name ?? '—' }}</small>
                    </div>
                </div>
                <p class="small text-muted mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($material->content ?? ''), 80) }}</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('siswa.materials.show', $material->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="fas fa-eye me-1"></i>Lihat
                    </a>
                    @if($material->file_url)
                    <a href="{{ route('siswa.materials.download', $material->id) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-download"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted">
            <i class="fas fa-book-medical fa-3x mb-3 opacity-50"></i>
            <p class="mb-0">Belum ada materi kesehatan tersedia.</p>
            <a href="{{ route('siswa.materials.index') }}" class="btn btn-outline-primary btn-sm mt-3">
                Lihat Semua Materi
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($materials->hasPages())
<div class="mt-4">
    {{ $materials->links('vendor.pagination.bootstrap-5') }}
</div>
@endif
@endsection
