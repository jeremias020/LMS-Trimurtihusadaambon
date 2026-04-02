@extends('layouts.siswa')

@section('title', 'Tugas - LMS Trimurti Husada')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="font-weight-bold mb-4">Daftar Tugas</h2>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <form action="{{ route('siswa.assignments.index') }}" method="GET" class="search-form">
                <div class="input-group search-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari tugas..." 
                           value="{{ request('search') }}"
                           aria-label="Cari tugas">
                    <button class="btn btn-primary search-btn" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end">
                <div class="btn-group">
                    <button type="button" 
                            class="btn btn-primary dropdown-toggle filter-btn" 
                            data-bs-toggle="dropdown" 
                            aria-haspopup="true" 
                            aria-expanded="false"
                            aria-label="Filter status tugas">
                        <i class="fas fa-filter"></i> Filter Status
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item {{ request('status') == 'all' ? 'active' : '' }}" href="{{ route('siswa.assignments.index', ['status' => 'all']) }}">Semua Tugas</a></li>
                        <li><a class="dropdown-item {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('siswa.assignments.index', ['status' => 'pending']) }}">Belum Dikerjakan</a></li>
                        <li><a class="dropdown-item {{ request('status') == 'submitted' ? 'active' : '' }}" href="{{ route('siswa.assignments.index', ['status' => 'submitted']) }}">Sudah Dikumpulkan</a></li>
                        <li><a class="dropdown-item {{ request('status') == 'graded' ? 'active' : '' }}" href="{{ route('siswa.assignments.index', ['status' => 'graded']) }}">Sudah Dinilai</a></li>
                    </ul>
                </div>

                <!-- Export Buttons -->
                <div class="btn-group ms-2">
                    <a href="{{ route('siswa.assignments.export') }}" 
                       class="btn btn-outline-danger export-btn" 
                       title="Export ke PDF"
                       data-format="pdf">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                    <a href="{{ route('siswa.assignments.export') }}" 
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
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Judul Tugas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Batas Waktu</th>
                                    <th>Status</th>
                                    <th>Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assignment)
                                    <tr>
                                        <td>
                                            {{ $assignment->title }}
                                            @if($assignment->deadline && $assignment->deadline->diffInHours(now()) <= 24 && $assignment->deadline->isFuture())
                                                <span class="badge bg-warning text-dark ms-1">Segera</span>
                                            @endif
                                        </td>
                                        <td>{{ $assignment->subject?->nama ?? $assignment->subject?->name ?? 'Tidak tersedia' }}</td>
                                        <td>
                                            @if($assignment->deadline)
                                                <span title="{{ $assignment->deadline->translatedFormat('l, d F Y H:i') }}">
                                                    {{ $assignment->deadline->format('d M Y H:i') }}
                                                </span>
                                                @if($assignment->deadline->isPast())
                                                    <span class="badge bg-danger ms-1">Terlambat</span>
                                                @elseif($assignment->deadline->diffInHours(now()) <= 24)
                                                    <span class="badge bg-warning text-dark ms-1">Segera</span>
                                                @endif
                                            @else
                                                <span class="text-muted">Tidak ada deadline</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $submission = $assignment->submissions->where('siswa_id', Auth::id())->first();
                                            @endphp
                                            @if($submission)
                                                <span class="badge bg-success">Terkumpul</span>
                                            @else
                                                <span class="badge bg-warning">Belum dikumpulkan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($submission && $submission->score !== null)
                                                <span class="badge bg-info">{{ $submission->score }}/100</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('siswa.assignments.show', $assignment->id) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="Lihat detail tugas">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            @if(!$submission && ($assignment->deadline?->isFuture() ?? false))
                                                <a href="{{ route('siswa.assignments.show', $assignment->id) }}#submit" 
                                                   class="btn btn-sm btn-success" 
                                                   title="Kumpulkan tugas">
                                                    <i class="fas fa-upload"></i> Kumpulkan
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="alert alert-info mt-3">
                                                <i class="fas fa-info-circle"></i> Tidak ada tugas yang tersedia.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            {{ $assignments->links() }}
        </div>
    </div>
</div>
@endsection

@push('js')
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