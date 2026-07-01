@extends('layouts.guru')

@section('title', 'Pengumpulan — ' . $assignment->title)
@section('page-title', 'Pengumpulan Tugas')
@section('page-subtitle', $assignment->title)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('guru.assignments.index') }}" class="text-decoration-none">Tugas</a></li>
    <li class="breadcrumb-item"><a href="{{ route('guru.assignments.show', $assignment->id) }}" class="text-decoration-none">{{ \Str::limit($assignment->title, 25) }}</a></li>
    <li class="breadcrumb-item active">Pengumpulan</li>
@endsection

@section('page-actions')
<a href="{{ route('guru.assignments.show', $assignment->id) }}" class="btn btn-outline-secondary btn-sm">
    <i class="fas fa-arrow-left me-1"></i>Kembali
</a>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Info Tugas --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <h6 class="fw-semibold mb-1">{{ $assignment->title }}</h6>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    @if($assignment->subject)
                        <span class="badge bg-primary">{{ $assignment->subject->name }}</span>
                    @endif
                    @if($assignment->kelas)
                        <span class="badge bg-secondary">{{ $assignment->kelas->name }}</span>
                    @endif
                    <span class="badge {{ $assignment->is_published ? 'bg-success' : 'bg-secondary' }}">
                        {{ $assignment->is_published ? 'Aktif' : 'Draft' }}
                    </span>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>Deadline:
                    <strong>{{ $assignment->deadline?->format('d M Y H:i') ?? '—' }}</strong>
                    @if($assignment->deadline?->isPast())
                        <span class="badge bg-danger ms-1">Berakhir</span>
                    @else
                        <span class="badge bg-success ms-1">Aktif</span>
                    @endif
                </small>
            </div>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="rounded-3 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;">
                    <i class="fas fa-file-upload text-primary"></i>
                </div>
                <div>
                    <div class="h5 fw-bold mb-0">{{ $stats['total_submissions'] }}</div>
                    <small class="text-muted">Total Pengumpulan</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="rounded-3 bg-success bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;">
                    <i class="fas fa-check-circle text-success"></i>
                </div>
                <div>
                    <div class="h5 fw-bold mb-0">{{ $stats['graded_count'] }}</div>
                    <small class="text-muted">Sudah Dinilai</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="rounded-3 bg-info bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;">
                    <i class="fas fa-star text-info"></i>
                </div>
                <div>
                    <div class="h5 fw-bold mb-0">{{ $stats['average_score'] }}</div>
                    <small class="text-muted">Rata-rata Nilai</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Pengumpulan --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
        <h6 class="mb-0 fw-semibold">
            Daftar Pengumpulan
            <span class="badge bg-secondary ms-1">{{ $submissions->total() }}</span>
        </h6>
        {{-- Filter status --}}
        <div class="d-flex gap-2">
            <a href="{{ route('guru.assignments.submissions', $assignment->id) }}"
               class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">Semua</a>
            <a href="{{ route('guru.assignments.submissions', [$assignment->id, 'status' => 'ungraded']) }}"
               class="btn btn-sm {{ request('status') === 'ungraded' ? 'btn-warning' : 'btn-outline-secondary' }}">Belum Dinilai</a>
            <a href="{{ route('guru.assignments.submissions', [$assignment->id, 'status' => 'graded']) }}"
               class="btn btn-sm {{ request('status') === 'graded' ? 'btn-success' : 'btn-outline-secondary' }}">Dinilai</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Siswa</th>
                    <th class="text-center">Dikumpulkan</th>
                    <th class="text-center">File</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Nilai</th>
                    <th class="text-center" style="width:9rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $sub)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:34px;height:34px;font-size:12px;font-weight:700;color:#1d4ed8;">
                                {{ strtoupper(substr($sub->siswa?->name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:13px;">{{ $sub->siswa?->name ?? '—' }}</div>
                                <small class="text-muted">{{ $sub->siswa?->email ?? '' }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center small text-muted">
                        {{ $sub->submitted_at?->format('d M Y H:i') ?? $sub->created_at->format('d M Y H:i') }}
                        @if($sub->submitted_at && $assignment->deadline && $sub->submitted_at->gt($assignment->deadline))
                            <span class="badge bg-danger d-block mt-1" style="font-size:10px;">Terlambat</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($sub->file_url || $sub->file_path)
                            @php $filePath = $sub->file_url ?: ('assignment_submissions/' . $sub->file_path); @endphp
                            <a href="{{ Storage::url($filePath) }}" target="_blank"
                               class="btn btn-sm btn-outline-primary" title="Download">
                                <i class="fas fa-download me-1"></i>Unduh
                            </a>
                        @elseif($sub->submission_text)
                            <span class="badge bg-light text-dark border">Teks</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($sub->score !== null)
                            <span class="badge bg-success">Dinilai</span>
                        @else
                            <span class="badge bg-warning text-dark">Belum</span>
                        @endif
                    </td>
                    <td class="text-center fw-bold {{ $sub->score !== null ? ($sub->score >= 70 ? 'text-success' : 'text-danger') : 'text-muted' }}">
                        {{ $sub->score ?? '—' }}
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('guru.submissions.show', $sub->id) }}"
                               class="btn btn-outline-info" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-outline-primary btn-grade" title="Beri Nilai"
                                    data-submission-id="{{ $sub->id }}"
                                    data-siswa-name="{{ $sub->siswa?->name }}"
                                    data-score="{{ $sub->score ?? '' }}"
                                    data-feedback="{{ $sub->feedback ?? '' }}"
                                    data-max="{{ $assignment->max_score }}"
                                    data-bs-toggle="modal" data-bs-target="#gradeModal">
                                <i class="fas fa-star"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-40"></i>
                        <span class="d-block">Belum ada pengumpulan untuk tugas ini.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($submissions->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">{{ $submissions->firstItem() }}–{{ $submissions->lastItem() }} dari {{ $submissions->total() }}</small>
        {{ $submissions->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- Grade Modal --}}
<div class="modal fade" id="gradeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-star me-2"></i>Beri Nilai &mdash; <span id="gradeSiswaName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="gradeForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nilai (0 – <span id="gradeMax">{{ $assignment->max_score }}</span>) <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="score" id="gradeScore"
                               class="form-control" min="0" step="0.5" required>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Feedback (Opsional)</label>
                        <textarea name="feedback" id="gradeFeedback"
                                  class="form-control" rows="3"
                                  placeholder="Komentar untuk siswa…"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="gradeSaveBtn">
                        <i class="fas fa-save me-1"></i>Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
var assignmentId = {{ $assignment->id }};

document.getElementById('gradeModal').addEventListener('show.bs.modal', function (e) {
    var btn = e.relatedTarget;
    var subId = btn.dataset.submissionId;

    document.getElementById('gradeSiswaName').textContent  = btn.dataset.siswaName ?? '—';
    document.getElementById('gradeScore').value            = btn.dataset.score    ?? '';
    document.getElementById('gradeScore').max              = btn.dataset.max      ?? 100;
    document.getElementById('gradeMax').textContent        = btn.dataset.max      ?? 100;
    document.getElementById('gradeFeedback').value         = btn.dataset.feedback ?? '';
    document.getElementById('gradeSaveBtn').disabled       = false;
    document.getElementById('gradeSaveBtn').innerHTML      = '<i class="fas fa-save me-1"></i>Simpan Nilai';

    // Route: guru/assignments/{assignment}/submissions/{submission}/grade
    document.getElementById('gradeForm').action =
        '{{ url("guru/assignments") }}/' + assignmentId +
        '/submissions/' + subId + '/grade';
});

document.getElementById('gradeForm').addEventListener('submit', function () {
    var btn = document.getElementById('gradeSaveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';
});
</script>
@endpush
