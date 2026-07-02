@extends('layouts.siswa')

@section('title', 'Materi Pembelajaran')
@section('siswa-page-title', 'Materi Pembelajaran')
@section('page-subtitle', 'Akses materi pelajaran yang diberikan oleh guru')

@section('siswa-breadcrumb')
    <li class="breadcrumb-item active">Materi</li>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-primary bg-opacity-10 flex-shrink-0">
                    <i class="fas fa-book text-primary"></i>
                </div>
                <div>
                    <div class="h5 fw-bold mb-0">{{ $materials->total() }}</div>
                    <small class="text-muted">Total Materi</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-success bg-opacity-10 flex-shrink-0">
                    <i class="fas fa-download text-success"></i>
                </div>
                <div>
                    <div class="h5 fw-bold mb-0">{{ $downloadedCount ?? 0 }}</div>
                    <small class="text-muted">Diunduh</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-info bg-opacity-10 flex-shrink-0">
                    <i class="fas fa-clock text-info"></i>
                </div>
                <div>
                    <div class="h5 fw-bold mb-0">{{ $recentCount ?? 0 }}</div>
                    <small class="text-muted">Terbaru (7 hari)</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10 flex-shrink-0">
                    <i class="fas fa-star text-warning"></i>
                </div>
                <div>
                    <div class="h5 fw-bold mb-0">{{ $favoriteCount ?? 0 }}</div>
                    <small class="text-muted">Favorit</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('siswa.materials.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Cari Materi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Mata Pelajaran</label>
                    <select name="subject" class="form-select">
                        <option value="">Semua</option>
                        @foreach($subjects ?? [] as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->nama ?? $subject->name ?? 'Subject' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua</option>
                        <option value="Teori" {{ request('category') == 'Teori' ? 'selected' : '' }}>Teori</option>
                        <option value="Praktik" {{ request('category') == 'Praktik' ? 'selected' : '' }}>Praktik</option>
                        <option value="Referensi" {{ request('category') == 'Referensi' ? 'selected' : '' }}>Referensi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Cari</button>
                </div>
            </div>
            @if(request()->hasAny(['search','subject','category']))
                <div class="mt-2">
                    <a href="{{ route('siswa.materials.index') }}" class="btn btn-link btn-sm p-0 text-muted">
                        <i class="fas fa-times me-1"></i>Reset Filter
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>

{{-- Grid Materi --}}
<div class="row g-3">
    @forelse($materials as $material)
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    @php
                        $ext = strtolower(pathinfo($material->file_url ?? '', PATHINFO_EXTENSION));
                        $icon = match($ext) {
                            'pdf'              => ['fas fa-file-pdf', 'text-danger'],
                            'doc','docx'       => ['fas fa-file-word', 'text-primary'],
                            'ppt','pptx'       => ['fas fa-file-powerpoint', 'text-warning'],
                            'xls','xlsx'       => ['fas fa-file-excel', 'text-success'],
                            'mp4','avi','mov'  => ['fas fa-file-video', 'text-info'],
                            'jpg','jpeg','png' => ['fas fa-file-image', 'text-info'],
                            'zip','rar'        => ['fas fa-file-archive', 'text-secondary'],
                            default            => ['fas fa-file', 'text-muted'],
                        };
                    @endphp
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-3 bg-light p-3 flex-shrink-0">
                            <i class="{{ $icon[0] }} {{ $icon[1] }} fa-lg"></i>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <h6 class="fw-semibold mb-1 text-truncate">{{ $material->title }}</h6>
                            <small class="text-muted">{{ $material->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($material->content ?? 'Tidak ada deskripsi.', 90) }}</p>
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-book me-1"></i>{{ $material->subject?->nama ?? $material->subject?->name ?? 'Umum' }}
                        </span>
                        @if($material->category)
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $material->category }}</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between text-muted small mb-3">
                        <span><i class="fas fa-user me-1"></i>{{ $material->guru?->name ?? 'Guru' }}</span>
                        <span><i class="fas fa-download me-1"></i>{{ $material->downloads_count ?? 0 }}</span>
                    </div>
                    @if($material->downloads->where('siswa_id', Auth::id())->count() > 0)
                        <div class="text-success small mb-2"><i class="fas fa-check-circle me-1"></i>Sudah diunduh</div>
                    @endif
                </div>
                <div class="card-footer bg-light border-top d-flex gap-2">
                    <a href="{{ route('siswa.materials.show', $material->id) }}" class="btn btn-outline-primary btn-sm flex-fill">
                        <i class="fas fa-eye me-1"></i>Detail
                    </a>
                    @if($material->file_url)
                        <a href="{{ route('siswa.materials.download', $material->id) }}" class="btn btn-success btn-sm flex-fill">
                            <i class="fas fa-download me-1"></i>Unduh
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted opacity-50 mb-3 d-block"></i>
                    <h5 class="text-muted">Belum Ada Materi</h5>
                    <p class="text-muted small">
                        @if(request('search'))
                            Tidak ada materi dengan kata kunci "<strong>{{ request('search') }}</strong>".
                            <br><a href="{{ route('siswa.materials.index') }}">Lihat semua materi</a>
                        @else
                            Belum ada materi yang tersedia saat ini.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($materials->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $materials->appends(request()->query())->links() }}
    </div>
@endif

@endsection
