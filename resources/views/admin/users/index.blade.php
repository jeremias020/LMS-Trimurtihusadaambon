@extends('admin.layouts.admin-layout')

@section('title')
    Manajemen Pengguna
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Manajemen Pengguna</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.separated') }}" class="btn btn-info">
                    <i class="fas fa-table-columns me-2"></i>Tampilan Terpisah
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Pengguna
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-users text-primary fs-3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small fw-semibold text-uppercase tracking-wider">Total Pengguna</div>
                                <div class="h3 mb-0 fw-bold text-dark">{{ $users->total() ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Admin
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\User::where('role', 'admin')->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Guru
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\User::where('role', 'guru')->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Siswa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\User::where('role', 'siswa')->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Management Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Data Pengguna</h6>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm" type="button" id="resetFiltersBtn">
                <i class="fas fa-undo me-1"></i> Reset
            </button>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="collapse" id="filtersCollapse">
        <div class="card-body border-bottom">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Cari Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Nama, email, NIP/NIS..." id="searchInput">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter Role</label>
                    <select class="form-control" id="roleFilter">
                        <option value="">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter Status</label>
                    <select class="form-control" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bulk Actions -->
    <div class="card-body border-bottom d-none" id="bulkActionsContainer">
        <form id="bulkForm" method="POST" action="{{ route('admin.users.bulk-action') }}" class="d-flex align-items-center gap-3">
            @csrf
            <span id="selectedCount" class="text-muted small">0 pengguna dipilih</span>
            <select name="action" id="bulkAction" class="form-control form-control-sm" style="width: auto;" required>
                <option value="">Pilih Aksi</option>
                <option value="activate">Aktifkan</option>
                <option value="deactivate">Nonaktifkan</option>
                <option value="delete">Hapus</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-check me-1"></i>Terapkan
            </button>
            <button type="button" id="cancelBulk" class="btn btn-secondary btn-sm">
                <i class="fas fa-times me-1"></i>Batal
            </button>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="usersTable">
                <thead class="table-dark">
                    <tr>
                        <th width="50">
                            <div class="form-check">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </div>
                        </th>
                        <th>Pengguna</th>
                        <th>Email/Kontak</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Kelas/Jurusan</th>
                        <th>Bergabung</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    @forelse($users as $user)
                    <tr class="user-row" data-role="{{ $user->role }}" data-status="{{ $user->status }}" data-kelas="{{ $user->kelas?->name ?? '' }}" data-jurusan="{{ $user->jurusan?->nama ?? ($user->kelas?->major ?? '') }}">
                        <td>
                            <div class="form-check">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox form-check-input">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle me-3" src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('images/default-avatar.svg') }}" alt="{{ $user->name }}" width="40" height="40" style="object-fit: cover;">
                                <div>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <small class="text-muted">
                                        @if($user->username)
                                            Username: {{ $user->username }}
                                        @else
                                            ID: {{ $user->id }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $user->email }}</div>
                            @if($user->phone)
                            <small class="text-muted">{{ $user->phone }}</small>
                            @endif
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-primary">Admin</span>
                            @elseif($user->role === 'guru')
                                <span class="badge bg-success">Guru</span>
                            @elseif($user->role === 'siswa')
                                <span class="badge bg-info">Siswa</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($user->status === 'active')
                                <span class="badge bg-success">Aktif</span>
                            @elseif($user->status === 'inactive')
                                <span class="badge bg-secondary">Nonaktif</span>
                            @elseif($user->status === 'suspended')
                                <span class="badge bg-danger">Suspended</span>
                            @else
                                <span class="badge bg-light text-dark">{{ ucfirst($user->status) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($user->role === 'siswa')
                                <div class="small">
                                    <div class="fw-semibold">{{ $user->kelas?->name ?? '-' }}</div>
                                    <div class="text-muted">{{ $user->jurusan?->nama ?? ($user->kelas?->major ?? '-') }}</div>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $user->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada pengguna</h5>
                                <p class="text-muted mb-0">Data pengguna akan ditampilkan di sini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <small class="text-muted">
                    Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                </small>
            </div>
            <div>
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>

</div>
@endsection

@push('styles')
<style>
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

.text-xs {
    font-size: 0.75rem;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 1px;
}

.btn-group .btn {
    margin-right: 0;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.badge {
    font-weight: 500;
}

.gap-3 {
    gap: 1rem;
}

#usersTable {
    font-size: 0.9rem;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable - Simplified version without external dependencies
    // if ($.fn.DataTable) {
    //     $('#usersTable').DataTable({
    //         "order": [[ 5, "desc" ]],
    //         "pageLength": 25,
    //         "responsive": true,
    //         "columnDefs": [
    //             { "orderable": false, "targets": [0, 6] },
    //             { "searchable": false, "targets": [0, 6] }
    //         ]
    //     });
    // }
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');
    const userRows = document.querySelectorAll('.user-row');
    
    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleValue = roleFilter.value;
        const statusValue = statusFilter.value;
        
        let hasVisibleRows = false;
        
        userRows.forEach(row => {
            const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const kelas = (row.getAttribute('data-kelas') || '').toLowerCase();
            const jurusan = (row.getAttribute('data-jurusan') || '').toLowerCase();
            const role = row.getAttribute('data-role');
            const status = row.getAttribute('data-status');
            
            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm) || kelas.includes(searchTerm) || jurusan.includes(searchTerm);
            const matchesRole = !roleValue || role === roleValue;
            const matchesStatus = !statusValue || status === statusValue;
            
            if (matchesSearch && matchesRole && matchesStatus) {
                row.style.display = '';
                hasVisibleRows = true;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        const noResultsRow = document.getElementById('noResultsRow');
        if (noResultsRow) {
            noResultsRow.style.display = hasVisibleRows ? 'none' : '';
        }
    }
    
    // Event listeners for filters
    if (searchInput) searchInput.addEventListener('input', filterUsers);
    if (roleFilter) roleFilter.addEventListener('change', filterUsers);
    if (statusFilter) statusFilter.addEventListener('change', filterUsers);
    if (resetFiltersBtn) resetFiltersBtn.addEventListener('click', function() {
        if (searchInput) searchInput.value = '';
        if (roleFilter) roleFilter.value = '';
        if (statusFilter) statusFilter.value = '';
        filterUsers();
    });
    
    // Bulk actions functionality
    const selectAll = document.getElementById('selectAll');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCount = document.getElementById('selectedCount');
    const bulkForm = document.getElementById('bulkForm');
    const cancelBulk = document.getElementById('cancelBulk');
    
    function updateBulkActions() {
        const selectedCountValue = document.querySelectorAll('.user-checkbox:checked').length;
        if (selectedCount) {
            selectedCount.textContent = `${selectedCountValue} pengguna dipilih`;
        }
        
        if (bulkActionsContainer) {
            if (selectedCountValue > 0) {
                bulkActionsContainer.classList.remove('d-none');
            } else {
                bulkActionsContainer.classList.add('d-none');
            }
        }
    }
    
    // Select all functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }
    
    // Individual checkbox functionality
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();
            
            // Update select all checkbox
            const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
            if (selectAll) {
                selectAll.checked = checkedCount === userCheckboxes.length;
                selectAll.indeterminate = checkedCount > 0 && checkedCount < userCheckboxes.length;
            }
        });
    });
    
    // Cancel bulk selection
    if (cancelBulk) {
        cancelBulk.addEventListener('click', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAll) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }
            updateBulkActions();
        });
    }
    
    // Bulk form submission
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
            const action = document.getElementById('bulkAction').value;
            
            if (selectedUsers.length === 0) {
                e.preventDefault();
                showAlert('Pilih setidaknya satu pengguna untuk melakukan aksi bulk', 'warning');
                return;
            }
            
            if (!action) {
                e.preventDefault();
                showAlert('Pilih aksi yang akan dilakukan', 'warning');
                return;
            }
            
            let confirmMessage = '';
            switch(action) {
                case 'activate':
                    confirmMessage = `Aktifkan ${selectedUsers.length} pengguna yang dipilih?`;
                    break;
                case 'deactivate':
                    confirmMessage = `Nonaktifkan ${selectedUsers.length} pengguna yang dipilih?`;
                    break;
                case 'delete':
                    confirmMessage = `Hapus ${selectedUsers.length} pengguna yang dipilih? Tindakan ini tidak dapat dibatalkan!`;
                    break;
            }
            
            if (!confirm(confirmMessage)) {
                e.preventDefault();
            }
        });
    }
    
    // Initialize bulk actions state
    updateBulkActions();
    
    // Alert helper function
    function showAlert(message, type = 'info') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insert alert at the top of the content
        const firstCard = document.querySelector('.row.mb-4');
        if (firstCard) {
            firstCard.insertAdjacentHTML('beforebegin', alertHtml);
        }
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert:not(.show)');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }
});
</script>
@endpush
