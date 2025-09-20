@extends('layouts.siswa')

@section('title', 'Praktikum - LMS Trimurti Husada')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="font-weight-bold mb-4">Jadwal Praktikum</h2>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <form action="{{ route('siswa.praktikum.index') }}" method="GET" class="search-form">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari praktikum..."
                           value="{{ request('search') }}"
                           aria-label="Cari praktikum">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end">
                <div class="btn-group">
                    <button type="button"
                            class="btn btn-outline-primary dropdown-toggle"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            aria-label="Filter praktikum">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item {{ request('status') == 'all' ? 'active' : '' }}" href="{{ route('siswa.praktikum.index', ['status' => 'all']) }}">Semua Praktikum</a></li>
                        <li><a class="dropdown-item {{ request('status') == 'upcoming' ? 'active' : '' }}" href="{{ route('siswa.praktikum.index', ['status' => 'upcoming']) }}">Akan Datang</a></li>
                        <li><a class="dropdown-item {{ request('status') == 'completed' ? 'active' : '' }}" href="{{ route('siswa.praktikum.index', ['status' => 'completed']) }}">Selesai</a></li>
                    </ul>
                </div>

                <!-- Export Buttons -->
                <div class="btn-group ms-2">
                    <a href="{{ route('siswa.praktikum.export', ['format' => 'pdf']) }}"
                       class="btn btn-outline-danger export-btn"
                       title="Export ke PDF"
                       data-format="pdf">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                    <a href="{{ route('siswa.praktikum.export', ['format' => 'excel']) }}"
                       class="btn btn-outline-success export-btn"
                       title="Export ke Excel"
                       data-format="excel">
                        <i class="fas fa-file-excel"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @if($practicals->count() > 0)
            @foreach($practicals as $practical)
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100 border-0 rounded-3 hover-card">
                        <div class="card-header bg-light border-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title mb-1">{{ $practical->title }}</h5>
                                    <small class="text-muted">Oleh: {{ optional($practical->teacher)->name ?? 'Tidak tersedia' }}</small>
                                </div>
                                @if($practical->date->isToday())
                                    <span class="badge bg-warning text-dark">Hari Ini</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-muted">{{ Str::limit($practical->description, 200) }}</p>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-calendar-alt me-1"></i> Tanggal:</strong>
                                    <span title="{{ $practical->date->translatedFormat('l, d F Y') }}">
                                        {{ $practical->date->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-clock me-1"></i> Waktu:</strong>
                                    {{ $practical->start_time }} - {{ $practical->end_time }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-map-marker-alt me-1"></i> Lokasi:</strong>
                                    {{ $practical->location }}
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-users me-1"></i> Kelas:</strong>
                                    {{ $practical->class_level ?? 'Tidak tersedia' }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Status:</strong>
                                @if($practical->date->isFuture())
                                    <span class="badge bg-info">Akan Datang</span>
                                @elseif($practical->date->isToday())
                                    <span class="badge bg-warning text-dark">Hari Ini</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <div class="d-flex justify-content-between">
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#practicalDetailModal{{ $practical->id }}"
                                        title="Lihat detail praktikum">
                                    <i class="fas fa-info-circle me-1"></i> Detail
                                </button>

                                @if($practical->materials_count > 0)
                                    <a href="#" class="btn btn-sm btn-outline-success" title="Unduh materi praktikum">
                                        <i class="fas fa-download me-1"></i> Materi ({{ $practical->materials_count }})
                                    </a>
                                @endif

                                @if($practical->scores->where('user_id', Auth::id())->count() > 0)
                                    @php
                                        $score = $practical->scores->where('user_id', Auth::id())->first();
                                    @endphp
                                    <span class="btn btn-sm btn-outline-info" title="Nilai Anda">
                                        <i class="fas fa-star me-1"></i> Nilai: {{ $score->score }}/100
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail -->
                <div class="modal fade"
                     id="practicalDetailModal{{ $practical->id }}"
                     tabindex="-1"
                     aria-labelledby="practicalDetailModalLabel{{ $practical->id }}"
                     aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="practicalDetailModalLabel{{ $practical->id }}">{{ $practical->title }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Deskripsi:</strong></p>
                                        <p>{{ $practical->description }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Detail:</strong></p>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-calendar-alt me-2"></i> <strong>Tanggal:</strong> {{ $practical->date->format('d M Y') }}</li>
                                            <li><i class="fas fa-clock me-2"></i> <strong>Waktu:</strong> {{ $practical->start_time }} - {{ $practical->end_time }}</li>
                                            <li><i class="fas fa-map-marker-alt me-2"></i> <strong>Lokasi:</strong> {{ $practical->location }}</li>
                                            <li><i class="fas fa-user-tie me-2"></i> <strong>Pengajar:</strong> {{ optional($practical->teacher)->name ?? 'Tidak tersedia' }}</li>
                                            <li><i class="fas fa-users me-2"></i> <strong>Kelas:</strong> {{ $practical->class_level ?? 'Tidak tersedia' }}</li>
                                        </ul>
                                    </div>
                                </div>

                                @if($practical->materials_count > 0)
                                    <hr>
                                    <h6>Materi Praktikum:</h6>
                                    <div class="list-group">
                                        @foreach($practical->materials as $material)
                                            <a href="{{ route('siswa.materials.download', $material->id) }}"
                                               class="list-group-item list-group-item-action"
                                               title="Unduh {{ $material->title }}">
                                                <i class="fas fa-file-download me-2"></i> {{ $material->title }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                @if($practical->scores->where('user_id', Auth::id())->count() > 0)
                                    <hr>
                                    <h6>Penilaian:</h6>
                                    @php
                                        $score = $practical->scores->where('user_id', Auth::id())->first();
                                    @endphp
                                    <div class="alert alert-info">
                                        <p class="mb-1"><strong>Nilai:</strong> {{ $score->score }}/100</p>
                                        @if($score->feedback)
                                            <p class="mb-0"><strong>Feedback:</strong> {{ $score->feedback }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                @if($practical->materials_count > 0)
                                    <a href="#" class="btn btn-primary">Unduh Semua Materi</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info text-center p-5">
                    <i class="fas fa-flask fa-3x mb-3"></i>
                    <h4>Belum ada jadwal praktikum</h4>
                    <p class="mb-0">Silakan cek kembali nanti atau hubungi guru Anda.</p>
                </div>
            </div>
        @endif
    </div>

    <div class="row mt-4">
        <div class="col-12">
            {{ $practicals->links() }}
        </div>
    </div>
</div>
@endsection

@push('js')
<style>
.hover-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Loading state for search form
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            }
        });
    }

    // Loading state for export buttons
    document.querySelectorAll('.export-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const format = this.getAttribute('data-format');
            this.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            this.classList.add('disabled');
        });
    });
});
</script>
@endpush
@endsection
