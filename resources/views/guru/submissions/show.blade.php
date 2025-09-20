@extends('layouts.guru')

@section('title', 'Detail Submission - SMK Kesehatan Trimurti Husada')

@section('page-title', 'Detail Submission')
@section('page-subtitle', 'Lihat detail submission dari siswa')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('guru.submissions') }}">Submissions</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Submission Detail Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold">Detail Submission</h5>
                    
                    <div class="d-flex gap-2">
                        @if(is_null($submission->score))
                            <a href="{{ route('guru.penilaian.edit', $submission->id) }}" class="btn btn-success">
                                <i class="fas fa-star me-1"></i> Beri Nilai
                            </a>
                        @else
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i> Sudah Dinilai
                            </span>
                        @endif
                        
                        <a href="{{ route('guru.submissions') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Student Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                <span class="text-primary fw-bold fs-3">{{ substr($submission->siswa->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $submission->siswa->name }}</h5>
                                <p class="text-muted mb-0">{{ $submission->siswa->email }}</p>
                                @if($submission->siswa->kelas_id)
                                    <small class="badge bg-info">{{ $submission->siswa->kelas->nama_kelas ?? 'Kelas tidak diketahui' }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-md-end">
                            <p class="mb-1"><strong>Tanggal Submit:</strong> {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : '-' }}</p>
                            <p class="mb-1"><strong>Status:</strong> 
                                @if(is_null($submission->score))
                                    <span class="badge bg-warning">Belum Dinilai</span>
                                @else
                                    <span class="badge bg-success">Sudah Dinilai</span>
                                @endif
                            </p>
                            @if(!is_null($submission->score))
                                <p class="mb-0"><strong>Nilai:</strong> <span class="fs-4 fw-bold text-primary">{{ $submission->score }}</span></p>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Assignment/Practical Info -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">
                        @if(isset($submission->assignment))
                            <i class="fas fa-tasks text-info me-2"></i>Informasi Tugas
                        @else
                            <i class="fas fa-flask text-success me-2"></i>Informasi Praktikum
                        @endif
                    </h6>
                    
                    <div class="bg-light rounded p-3">
                        @if(isset($submission->assignment))
                            <h5>{{ $submission->assignment->title }}</h5>
                            <p class="text-muted mb-2">{{ $submission->assignment->description }}</p>
                            <div class="row text-sm">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Deadline:</strong> {{ $submission->assignment->due_date ? \Carbon\Carbon::parse($submission->assignment->due_date)->format('d M Y, H:i') : '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Max Score:</strong> {{ $submission->assignment->max_score ?? 100 }}</p>
                                </div>
                            </div>
                        @elseif(isset($submission->practical))
                            <h5>{{ $submission->practical->judul }}</h5>
                            <p class="text-muted mb-2">{{ $submission->practical->deskripsi }}</p>
                            <div class="row text-sm">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Tanggal Praktikum:</strong> {{ $submission->practical->tanggal ? \Carbon\Carbon::parse($submission->practical->tanggal)->format('d M Y') : '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Max Score:</strong> {{ $submission->practical->max_score ?? 100 }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <hr>

                <!-- Submission Content -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-file-alt text-primary me-2"></i>Isi Submission
                    </h6>
                    
                    @if($submission->content)
                        <div class="border rounded p-3 mb-3">
                            <div class="submission-content">
                                {!! nl2br(e($submission->content)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- File Attachments -->
                    @if($submission->file_path)
                        <div class="mb-3">
                            <h6 class="fw-semibold">File Lampiran:</h6>
                            <div class="border rounded p-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-paperclip text-muted me-2"></i>
                                    <div class="flex-grow-1">
                                        <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="text-decoration-none fw-medium">
                                            {{ basename($submission->file_path) }}
                                        </a>
                                        @if($submission->file_size)
                                            <span class="text-muted ms-2">({{ number_format($submission->file_size / 1024, 1) }} KB)</span>
                                        @endif
                                    </div>
                                    <a href="{{ Storage::url($submission->file_path) }}" class="btn btn-sm btn-outline-primary" download>
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!$submission->content && !$submission->file_path)
                        <div class="text-muted text-center py-3">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Tidak ada konten submission
                        </div>
                    @endif
                </div>

                @if($submission->feedback)
                    <hr>
                    
                    <!-- Feedback -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-comment text-success me-2"></i>Feedback Guru
                        </h6>
                        
                        <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded p-3">
                            <p class="mb-0">{{ $submission->feedback }}</p>
                            @if($submission->graded_at)
                                <small class="text-muted">Dinilai pada: {{ $submission->graded_at->format('d M Y, H:i') }}</small>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(is_null($submission->score))
                        <a href="{{ route('guru.penilaian.edit', $submission->id) }}" class="btn btn-success">
                            <i class="fas fa-star me-2"></i>Beri Nilai
                        </a>
                    @else
                        <a href="{{ route('guru.penilaian.edit', $submission->id) }}" class="btn btn-outline-success">
                            <i class="fas fa-edit me-2"></i>Edit Nilai
                        </a>
                    @endif
                    
                    @if($submission->file_path)
                        <a href="{{ Storage::url($submission->file_path) }}" class="btn btn-outline-primary" download>
                            <i class="fas fa-download me-2"></i>Download File
                        </a>
                    @endif
                    
                    <a href="{{ route('guru.submissions') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>Lihat Semua Submissions
                    </a>
                </div>
            </div>
        </div>

        <!-- Submission Timeline -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold">Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @if($submission->submitted_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Submission Diterima</h6>
                                <p class="timeline-subtitle text-muted">{{ $submission->submitted_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($submission->graded_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Telah Dinilai</h6>
                                <p class="timeline-subtitle text-muted">{{ $submission->graded_at->format('d M Y, H:i') }}</p>
                                <p class="timeline-subtitle text-muted">Nilai: <span class="fw-bold">{{ $submission->score }}</span></p>
                            </div>
                        </div>
                    @endif

                    @if(!$submission->graded_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Menunggu Penilaian</h6>
                                <p class="timeline-subtitle text-muted">Belum dinilai</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
.avatar-lg {
    width: 4rem;
    height: 4rem;
}

.submission-content {
    line-height: 1.6;
    max-height: 400px;
    overflow-y: auto;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.25rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    margin-left: 0.5rem;
}

.timeline-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.timeline-subtitle {
    font-size: 0.75rem;
    margin-bottom: 0.25rem;
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    // Highlight important elements
    $('.submission-content a').addClass('text-primary text-decoration-underline');
    
    // Auto-expand content that's too long
    $('.submission-content').each(function() {
        if ($(this).height() > 400) {
            $(this).addClass('expandable');
            $('<button class="btn btn-sm btn-outline-secondary mt-2">Lihat Selengkapnya</button>')
                .insertAfter($(this))
                .click(function() {
                    $(this).prev().removeClass('expandable').css('max-height', 'none');
                    $(this).remove();
                });
        }
    });
});
</script>
@endpush