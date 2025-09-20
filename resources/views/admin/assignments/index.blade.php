@extends('layouts.admin')

@section('title', 'Manajemen Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tugas & Quiz</li>
@endsection

@section('page-title', 'Manajemen Tugas & Quiz')

@section('page-actions')
    <a href="{{ route('admin.assignments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Tugas
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Tugas & Quiz</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-danger" id="bulkDeleteBtn" disabled>
                        <i class="fas fa-trash me-1"></i> Hapus Terpilih
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="assignmentsTable">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Judul Tugas</th>
                                <th>Guru</th>
                                <th>Tanggal Deadline</th>
                                <th>Nilai Maksimal</th>
                                <th>Status</th>
                                <th>Submissions</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input assignment-checkbox" 
                                           value="{{ $assignment->id }}">
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $assignment->title }}</div>
                                    <small class="text-muted">{{ Str::limit($assignment->description, 50) }}</small>
                                </td>
                                <td>{{ $assignment->guru->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $assignment->deadline < now() ? 'bg-danger' : 'bg-info' }}">
                                        {{ $assignment->deadline->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                                <td>{{ $assignment->max_score }}</td>
                                <td>
                                    @if($assignment->is_published)
                                        <span class="badge bg-success">Dipublikasikan</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $assignment->submissions->count() }} submission(s)
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.assignments.show', $assignment) }}" 
                                           class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.assignments.edit', $assignment) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-{{ $assignment->is_published ? 'secondary' : 'success' }}"
                                                onclick="togglePublish({{ $assignment->id }})" 
                                                title="{{ $assignment->is_published ? 'Unpublish' : 'Publish' }}">
                                            <i class="fas fa-{{ $assignment->is_published ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="deleteAssignment({{ $assignment->id }})" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-tasks fa-3x mb-3"></i>
                                        <p>Tidak ada tugas yang ditemukan.</p>
                                        <a href="{{ route('admin.assignments.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i> Tambah Tugas Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($assignments->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $assignments->links() }}
                    </div>
                @endif
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

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus <span id="selectedCount">0</span> tugas yang dipilih?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="bulkDeleteForm" method="POST" action="{{ route('admin.assignments.bulk-delete') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Hapus Semua</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#assignmentsTable').DataTable({
        "paging": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
        }
    });

    // Select All functionality
    $('#selectAll').change(function() {
        $('.assignment-checkbox').prop('checked', this.checked);
        updateBulkDeleteButton();
    });

    $('.assignment-checkbox').change(function() {
        updateBulkDeleteButton();
    });

    function updateBulkDeleteButton() {
        const checkedBoxes = $('.assignment-checkbox:checked');
        const bulkDeleteBtn = $('#bulkDeleteBtn');
        
        if (checkedBoxes.length > 0) {
            bulkDeleteBtn.prop('disabled', false);
            $('#selectedCount').text(checkedBoxes.length);
        } else {
            bulkDeleteBtn.prop('disabled', true);
        }
    }

    // Bulk delete
    $('#bulkDeleteBtn').click(function() {
        const checkedBoxes = $('.assignment-checkbox:checked');
        if (checkedBoxes.length > 0) {
            // Add hidden inputs for selected IDs
            const form = $('#bulkDeleteForm');
            form.find('input[name="assignment_ids[]"]').remove();
            
            checkedBoxes.each(function() {
                form.append('<input type="hidden" name="assignment_ids[]" value="' + $(this).val() + '">');
            });
            
            $('#bulkDeleteModal').modal('show');
        }
    });
});

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
