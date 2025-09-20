@extends('layouts.admin')

@section('title', 'Manajemen Praktikum')
@section('page-title', 'Manajemen Praktikum')

@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen Praktikum</li>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-danger" id="bulkDeleteBtn" disabled>
            <i class="fas fa-trash me-2"></i>Hapus Terpilih
        </button>
        <a href="{{ route('admin.practicals.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Praktikum
        </a>
    </div>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ $practicals->total() }}</h5>
                        <p class="card-text">Total Praktikum</p>
                    </div>
                    <i class="fas fa-flask fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ $practicals->where('is_published', true)->where('tanggal', '>=', now())->count() }}</h5>
                        <p class="card-text">Aktif</p>
                    </div>
                    <i class="fas fa-eye fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ $practicals->where('is_published', false)->count() }}</h5>
                        <p class="card-text">Draft</p>
                    </div>
                    <i class="fas fa-eye-slash fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ $practicals->where('tanggal', '<', now())->count() }}</h5>
                        <p class="card-text">Selesai</p>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alerts -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Practicals Table -->
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-flask me-2"></i>Daftar Praktikum
            <span class="badge badge-primary ms-2">{{ $practicals->total() }}</span>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="practicalsTable">
                <thead>
                    <tr>
                        <th width="30">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th>Judul Praktikum</th>
                        <th>Guru</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Peserta</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($practicals as $practical)
                    <tr class="practical-row">
                        <td>
                            <div class="form-check">
                                <input class="form-check-input practical-checkbox" type="checkbox" value="{{ $practical->id }}">
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $practical->judul }}</div>
                            <small class="text-muted">{{ Str::limit($practical->deskripsi, 50) }}</small>
                        </td>
                        <td>{{ $practical->guru->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $practical->tanggal < now() ? 'bg-secondary' : 'bg-primary' }}">
                                {{ $practical->tanggal->format('d/m/Y H:i') }}
                            </span>
                        </td>
                        <td>{{ $practical->lokasi }}</td>
                        <td>{{ $practical->durasi }} menit</td>
                        <td>
                            @if($practical->tanggal < now())
                                <span class="badge bg-secondary">Selesai</span>
                            @elseif($practical->is_published)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-warning">Draft</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $practical->scores->count() }} peserta</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.practicals.show', $practical) }}" 
                                   class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.practicals.edit', $practical) }}" 
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($practical->tanggal >= now())
                                <button type="button" 
                                        class="btn btn-sm {{ $practical->is_published ? 'btn-secondary' : 'btn-success' }}"
                                        onclick="togglePublish({{ $practical->id }})" 
                                        title="{{ $practical->is_published ? 'Unpublish' : 'Publish' }}">
                                    <i class="fas fa-{{ $practical->is_published ? 'eye-slash' : 'eye' }}"></i>
                                </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-danger"
                                        onclick="deletePractical({{ $practical->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-flask fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Tidak ada praktikum yang ditemukan</p>
                            <p class="text-sm text-muted mb-4">Mulai dengan membuat praktikum pertama Anda</p>
                            <a href="{{ route('admin.practicals.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Praktikum Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($practicals->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="d-flex align-items-center">
                <span class="text-muted">
                    Menampilkan {{ $practicals->firstItem() }} - {{ $practicals->lastItem() }} 
                    dari {{ $practicals->total() }} hasil
                </span>
            </div>
            <div>
                {{ $practicals->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus praktikum ini?</p>
                <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
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
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus Massal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus <span id="selectedCount" class="fw-bold">0</span> praktikum yang dipilih?</p>
                <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="bulkDeleteForm" method="POST" action="{{ route('admin.practicals.bulk-delete') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Hapus Semua</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
.practical-row {
    transition: background-color 0.2s;
}
.practical-row:hover {
    background-color: rgba(0,0,0,0.02);
}
.btn-group .btn {
    margin-right: 2px;
}
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const practicalCheckboxes = document.querySelectorAll('.practical-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    selectAllCheckbox.addEventListener('change', function() {
        practicalCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkDeleteButton();
    });
    
    practicalCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteButton);
    });
    
    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.practical-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkDeleteBtn.disabled = false;
            document.getElementById('selectedCount').textContent = count;
        } else {
            bulkDeleteBtn.disabled = true;
        }
        
        // Update select all checkbox
        selectAllCheckbox.indeterminate = count > 0 && count < practicalCheckboxes.length;
        selectAllCheckbox.checked = count === practicalCheckboxes.length && practicalCheckboxes.length > 0;
    }
    
    // Bulk delete
    bulkDeleteBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.practical-checkbox:checked');
        if (checkedBoxes.length > 0) {
            // Add hidden inputs for selected IDs
            const form = document.getElementById('bulkDeleteForm');
            form.querySelectorAll('input[name="practical_ids[]"]').forEach(input => input.remove());
            
            checkedBoxes.forEach(checkbox => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'practical_ids[]';
                hiddenInput.value = checkbox.value;
                form.appendChild(hiddenInput);
            });
            
            new bootstrap.Modal(document.getElementById('bulkDeleteModal')).show();
        }
    });
});

function deletePractical(id) {
    document.getElementById('deleteForm').action = '{{ route("admin.practicals.destroy", ":id") }}'.replace(':id', id);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function togglePublish(id) {
    if (confirm('Apakah Anda yakin ingin mengubah status publikasi praktikum ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.practicals.publish", ":id") }}'.replace(':id', id);
        
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
