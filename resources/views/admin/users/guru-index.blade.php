@extends('admin.layouts.admin-layout')

@section('title')
    Manajemen Guru
@endsection

@section('page-title')
    Manajemen Guru
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chalkboard-teacher me-2"></i>Manajemen Guru
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.separated') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('admin.users.create.guru') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Tambah Guru
            </a>
        </div>
    </div>

    <!-- Guru Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-chalkboard-teacher me-2"></i>Data Guru
            </h6>
            <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="Cari guru..." id="guruSearch" style="width: 200px;">
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="resetGuruSearch()">
                    <i class="fas fa-undo"></i>
                </button>
                <button class="btn btn-outline-primary btn-sm" type="button" onclick="exportGuruData()">
                    <i class="fas fa-download me-1"></i>Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="guruTable" width="100%" cellspacing="0">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama Lengkap</th>
                            <th>NIP</th>
                            <th>Email</th>
                            <th>Mata Pelajaran</th>
                            <th>Pendidikan</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $guruNo = 1; @endphp
                        @foreach($gurus as $guru)
                        <tr class="guru-row">
                            <td class="text-center">{{ $guruNo++ }}</td>
                            <td class="text-center">
                                <img src="{{ $guru->photo_url }}" class="rounded-circle" width="40" height="40" 
                                     style="object-fit: cover;" onerror="this.src='{{ asset('images/default-avatar.png') }}';">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $guru->name }}</div>
                                <small class="text-muted">{{ $guru->username }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $guru->guruProfile->nip ?? '-' }}</span>
                            </td>
                            <td>{{ $guru->email }}</td>
                            <td>{{ $guru->guruProfile->mata_pelajaran ?? '-' }}</td>
                            <td>{{ $guru->guruProfile->pendidikan_terakhir ?? '-' }}</td>
                            <td>{{ $guru->phone ?? '-' }}</td>
                            <td class="text-center">
                                @if($guru->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.edit', $guru->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-info" onclick="viewGuruDetail({{ $guru->id }})" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" onclick="toggleGuruStatus({{ $guru->id }})" title="Toggle Status">
                                        <i class="fas fa-toggle-on"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteGuru({{ $guru->id }})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($gurus->isEmpty())
            <div class="text-center py-4">
                <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada data guru</h5>
                <p class="text-muted">Tambahkan guru pertama untuk memulai</p>
                <a href="{{ route('admin.users.create.guru') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Tambah Guru
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Guru Search
document.getElementById('guruSearch').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('.guru-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});

function resetGuruSearch() {
    document.getElementById('guruSearch').value = '';
    const rows = document.querySelectorAll('.guru-row');
    rows.forEach(row => row.style.display = '');
}

function viewGuruDetail(guruId) {
    // Implement view detail functionality
    window.location.href = `/admin/users/${guruId}/edit`;
}

function toggleGuruStatus(guruId) {
    if (confirm('Apakah Anda yakin ingin mengubah status guru ini?')) {
        // Implement toggle status functionality
        fetch(`/admin/users/${guruId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal mengubah status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status');
        });
    }
}

function deleteGuru(guruId) {
    if (confirm('Apakah Anda yakin ingin menghapus guru ini?')) {
        // Implement delete functionality
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${guruId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function exportGuruData() {
    // Implement export functionality
    alert('Export data guru akan segera tersedia');
}
</script>
@endsection
