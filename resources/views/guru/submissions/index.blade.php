@extends('layouts.guru')

@section('title', 'Submissions - SMK Kesehatan Trimurti Husada')

@section('page-title', 'Submissions')
@section('page-subtitle', 'Kelola semua submission tugas dan praktikum dari siswa')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Submissions</li>
@endsection

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-file-upload text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Submissions</h6>
                        <h3 class="mb-0">{{ $stats['total_submissions'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-clock text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Belum Dinilai</h6>
                        <h3 class="mb-0">{{ $stats['pending_grading'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Sudah Dinilai</h6>
                        <h3 class="mb-0">{{ $stats['graded'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-star text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Rata-rata Nilai</h6>
                        <h3 class="mb-0">{{ $stats['average_score'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-bold">Daftar Submissions</h5>
            
            <!-- Filters -->
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-1"></i>
                        @if(request('type'))
                            {{ request('type') == 'assignment' ? 'Tugas' : 'Praktikum' }}
                        @else
                            Semua Tipe
                        @endif
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('guru.submissions.index') }}">Semua Tipe</a></li>
                        <li><a class="dropdown-item" href="{{ route('guru.submissions.index', ['type' => 'assignment']) }}">Tugas</a></li>
                        <li><a class="dropdown-item" href="{{ route('guru.submissions.index', ['type' => 'practical']) }}">Praktikum</a></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-sort-amount-down me-1"></i>
                        @if(request('status'))
                            {{ ucfirst(request('status')) }}
                        @else
                            Semua Status
                        @endif
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('guru.submissions.index') }}">Semua Status</a></li>
                        <li><a class="dropdown-item" href="{{ route('guru.submissions.index', ['status' => 'submitted']) }}">Submitted</a></li>
                        <li><a class="dropdown-item" href="{{ route('guru.submissions.index', ['status' => 'graded']) }}">Graded</a></li>
                        <li><a class="dropdown-item" href="{{ route('guru.submissions.index', ['status' => 'returned']) }}">Returned</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        @if($allSubmissions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>Siswa</th>
                            <th>Tipe</th>
                            <th>Judul</th>
                            <th>Tanggal Submit</th>
                            <th>Status</th>
                            <th>Nilai</th>
                            <th style="width: 120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allSubmissions as $index => $submission)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <span class="text-primary fw-bold">{{ substr($submission->siswa->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $submission->siswa->name }}</div>
                                        <small class="text-muted">{{ $submission->siswa->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if(isset($submission->assignment))
                                    <span class="badge bg-info">Tugas</span>
                                @else
                                    <span class="badge bg-success">Praktikum</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium">
                                    @if(isset($submission->assignment))
                                        {{ $submission->assignment->title }}
                                    @elseif(isset($submission->practical))
                                        {{ $submission->practical->judul }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y H:i') : '-' }}</span>
                            </td>
                            <td>
                                @if(is_null($submission->score))
                                    <span class="badge bg-warning">Belum Dinilai</span>
                                @else
                                    <span class="badge bg-success">Sudah Dinilai</span>
                                @endif
                            </td>
                            <td>
                                @if(is_null($submission->score))
                                    <span class="text-muted">-</span>
                                @else
                                    <span class="fw-bold text-primary">{{ $submission->score }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('guru.submissions.show', $submission->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(is_null($submission->score))
                                        <a href="{{ route('guru.penilaian.edit', $submission->id) }}" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-star"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="empty-state">
                    <div class="empty-state-icon mb-3">
                        <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted mb-3">Belum Ada Submissions</h5>
                    <p class="text-muted mb-4">Submissions dari siswa akan muncul di sini</p>
                    
                    @if(!request('type') && !request('status'))
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('guru.assignments.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Buat Tugas Baru
                            </a>
                            <a href="{{ route('guru.praktikum.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i> Buat Praktikum Baru
                            </a>
                        </div>
                    @else
                        <a href="{{ route('guru.submissions.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-times me-1"></i> Hapus Filter
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@push('js')
<script>
$(document).ready(function() {
    // Auto refresh page setiap 5 menit untuk update submission baru
    setTimeout(function() {
        location.reload();
    }, 300000); // 5 menit
    
    // Tooltip untuk buttons
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Highlight baris yang belum dinilai
    $('tbody tr').each(function() {
        const scoreCell = $(this).find('td:nth-child(7)').text().trim();
        if (scoreCell === '-') {
            $(this).addClass('table-warning bg-opacity-25');
        }
    });
});
</script>
@endpush