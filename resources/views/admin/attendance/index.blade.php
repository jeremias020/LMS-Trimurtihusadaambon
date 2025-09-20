@extends('layouts.admin')

@section('title', 'Manajemen Absensi')
@section('page-title', 'Manajemen Absensi')

@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen Absensi</li>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-warning" id="bulkUpdateBtn" disabled>
            <i class="fas fa-edit me-2"></i>Update Terpilih
        </button>
        <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Absensi
        </a>
    </div>
@endsection

@section('content')
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

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Absensi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['hadir'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Izin</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['izin'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-info-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sakit</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['sakit'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-thermometer-half fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Alpha</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['alpha'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Tingkat Kehadiran</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['attendance_rate'] }}%</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-percentage fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-filter me-2"></i>Filter Data Absensi
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.attendance.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Siswa</label>
                <select name="siswa_id" class="form-control">
                    <option value="">Semua Siswa</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('siswa_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                    <i class="fas fa-undo me-2"></i>Reset
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Attendance Table -->
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-calendar-check me-2"></i>Data Absensi
            <span class="badge bg-primary ms-2">{{ $attendances->total() }}</span>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="attendanceTable">
                <thead>
                    <tr>
                        <th width="30">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th>Siswa</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Durasi</th>
                        <th>Keterangan</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr class="attendance-row">
                        <td>
                            <div class="form-check">
                                <input class="form-check-input attendance-checkbox" type="checkbox" value="{{ $attendance->id }}">
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $attendance->siswa->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $attendance->siswa->email ?? '' }}</small>
                        </td>
                        <td>{{ $attendance->tanggal->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'hadir' => 'bg-success',
                                    'izin' => 'bg-info',
                                    'sakit' => 'bg-warning',
                                    'alpha' => 'bg-danger'
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$attendance->status] ?? 'bg-secondary' }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td>{{ $attendance->waktu_masuk ? $attendance->waktu_masuk->format('H:i') : '-' }}</td>
                        <td>{{ $attendance->waktu_keluar ? $attendance->waktu_keluar->format('H:i') : '-' }}</td>
                        <td>{{ $attendance->duration_formatted }}</td>
                        <td>{{ $attendance->keterangan ?? '-' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.attendance.show', $attendance) }}" 
                                   class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.attendance.edit', $attendance) }}" 
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger"
                                        onclick="deleteAttendance({{ $attendance->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Tidak ada data absensi</p>
                            <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Absensi Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($attendances->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="d-flex align-items-center">
                <span class="text-muted">
                    Menampilkan {{ $attendances->firstItem() }} - {{ $attendances->lastItem() }} 
                    dari {{ $attendances->total() }} hasil
                </span>
            </div>
            <div>
                {{ $attendances->links() }}
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
                <p>Apakah Anda yakin ingin menghapus data absensi ini?</p>
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

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit text-warning me-2"></i>
                    Update Massal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Update <span id="selectedCount" class="fw-bold">0</span> data absensi yang dipilih</p>
                <form id="bulkUpdateForm" method="POST" action="{{ route('admin.attendance.bulk-update') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Status Baru</label>
                        <select name="status" class="form-control" required>
                            <option value="">Pilih Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpha">Alpha</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="form-control" placeholder="Masukkan keterangan (opsional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="bulkUpdateForm" class="btn btn-warning">Update</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
.attendance-row {
    transition: background-color 0.2s;
}
.attendance-row:hover {
    background-color: rgba(0,0,0,0.02);
}
.btn-group .btn {
    margin-right: 2px;
}
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}
.border-left-secondary {
    border-left: 0.25rem solid #858796 !important;
}
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const attendanceCheckboxes = document.querySelectorAll('.attendance-checkbox');
    const bulkUpdateBtn = document.getElementById('bulkUpdateBtn');
    
    selectAllCheckbox.addEventListener('change', function() {
        attendanceCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkUpdateButton();
    });
    
    attendanceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkUpdateButton);
    });
    
    function updateBulkUpdateButton() {
        const checkedBoxes = document.querySelectorAll('.attendance-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkUpdateBtn.disabled = false;
            document.getElementById('selectedCount').textContent = count;
        } else {
            bulkUpdateBtn.disabled = true;
        }
        
        // Update select all checkbox
        selectAllCheckbox.indeterminate = count > 0 && count < attendanceCheckboxes.length;
        selectAllCheckbox.checked = count === attendanceCheckboxes.length && attendanceCheckboxes.length > 0;
    }
    
    // Bulk update
    bulkUpdateBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.attendance-checkbox:checked');
        if (checkedBoxes.length > 0) {
            // Add hidden inputs for selected IDs
            const form = document.getElementById('bulkUpdateForm');
            form.querySelectorAll('input[name="attendance_ids[]"]').forEach(input => input.remove());
            
            checkedBoxes.forEach(checkbox => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'attendance_ids[]';
                hiddenInput.value = checkbox.value;
                form.appendChild(hiddenInput);
            });
            
            new bootstrap.Modal(document.getElementById('bulkUpdateModal')).show();
        }
    });
});

function deleteAttendance(id) {
    document.getElementById('deleteForm').action = '{{ route("admin.attendance.destroy", ":id") }}'.replace(':id', id);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
