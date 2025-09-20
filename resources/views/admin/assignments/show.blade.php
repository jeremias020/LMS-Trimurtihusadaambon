@extends('layouts.admin')

@section('title', 'Detail Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.assignments.index') }}">Tugas & Quiz</a></li>
    <li class="breadcrumb-item active">Detail Tugas</li>
@endsection

@section('page-title', 'Detail Tugas')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.assignments.edit', $assignment) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <button type="button" class="btn btn-{{ $assignment->is_published ? 'secondary' : 'success' }}"
                onclick="togglePublish({{ $assignment->id }})">
            <i class="fas fa-{{ $assignment->is_published ? 'eye-slash' : 'eye' }} me-1"></i>
            {{ $assignment->is_published ? 'Unpublish' : 'Publish' }}
        </button>
        <button type="button" class="btn btn-danger" onclick="deleteAssignment({{ $assignment->id }})">
            <i class="fas fa-trash me-1"></i> Hapus
        </button>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Tugas</h3>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h4 class="text-primary">{{ $assignment->title }}</h4>
                    <div class="text-muted">
                        <i class="fas fa-user me-1"></i> Dibuat oleh: {{ $assignment->guru->name ?? 'N/A' }}
                        <span class="mx-2">|</span>
                        <i class="fas fa-calendar me-1"></i> {{ $assignment->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Deskripsi Tugas</h5>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($assignment->description)) !!}
                    </div>
                </div>

                @if($assignment->file)
                <div class="mb-4">
                    <h5>Lampiran</h5>
                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-paperclip me-2 text-muted"></i>
                            <span>{{ $assignment->file }}</span>
                            <a href="{{ asset('storage/assignments/' . $assignment->file) }}" 
                               class="btn btn-sm btn-outline-primary ms-auto" target="_blank">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Submissions -->
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Submissions ({{ $assignment->submissions->count() }})</h3>
            </div>
            <div class="card-body">
                @if($assignment->submissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Siswa</th>
                                    <th>Tanggal Submit</th>
                                    <th>Status</th>
                                    <th>Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignment->submissions as $submission)
                                <tr>
                                    <td>{{ $submission->siswa->name ?? 'N/A' }}</td>
                                    <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : 'Belum submit' }}</td>
                                    <td>
                                        @if($submission->submitted_at)
                                            <span class="badge bg-success">Submitted</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->score !== null)
                                            <span class="badge bg-primary">{{ $submission->score }}/{{ $assignment->max_score }}</span>
                                        @else
                                            <span class="badge bg-secondary">Belum dinilai</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->submitted_at)
                                            <a href="#" class="btn btn-sm btn-info" title="Lihat Submission">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada submission untuk tugas ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Informasi</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Status Publikasi</label>
                    <div>
                        @if($assignment->is_published)
                            <span class="badge bg-success">Dipublikasikan</span>
                        @else
                            <span class="badge bg-warning">Draft</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Deadline</label>
                    <div>
                        <span class="badge {{ $assignment->deadline < now() ? 'bg-danger' : 'bg-info' }}">
                            {{ $assignment->deadline->format('d/m/Y H:i') }}
                        </span>
                        @if($assignment->deadline < now())
                            <small class="text-danger d-block">Deadline telah lewat</small>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nilai Maksimal</label>
                    <div>{{ $assignment->max_score }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Total Submissions</label>
                    <div>{{ $assignment->submissions->count() }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Submissions yang Sudah Dinilai</label>
                    <div>{{ $assignment->submissions->whereNotNull('score')->count() }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Rata-rata Nilai</label>
                    <div>
                        @php
                            $scoredSubmissions = $assignment->submissions->whereNotNull('score');
                            $averageScore = $scoredSubmissions->count() > 0 ? $scoredSubmissions->avg('score') : 0;
                        @endphp
                        {{ number_format($averageScore, 2) }}/{{ $assignment->max_score }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus tugas ini?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
function deleteAssignment(id) {
    $('#deleteForm').attr('action', '{{ route("admin.assignments.destroy", ":id") }}'.replace(':id', id));
    $('#deleteModal').modal('show');
}

function togglePublish(id) {
    if (confirm('Apakah Anda yakin ingin mengubah status publikasi tugas ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.assignments.publish", ":id") }}'.replace(':id', id);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
