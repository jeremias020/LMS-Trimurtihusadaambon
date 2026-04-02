@extends('admin.layouts.admin-layout')

@section('title')
    Manajemen Siswa
@endsection

@section('page-title')
    Manajemen Siswa
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.separated') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('admin.users.create.siswa') }}" class="btn btn-warning">
                <i class="fas fa-plus me-2"></i>Tambah Siswa
            </a>
        </div>
    </div>

    <!-- Siswa Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-user-graduate me-2"></i>Data Siswa
            </h6>
            <div class="d-flex align-items-center gap-2">
                <select class="form-control form-control-sm" id="kelasFilter" style="width: 150px;">
                    <option value="">Semua Kelas</option>
                    @foreach($siswas->pluck('siswaProfile.kelas.name')->filter()->unique()->sort() as $kelas)
                        <option value="{{ $kelas }}">{{ $kelas }}</option>
                    @endforeach
                </select>
                <select class="form-control form-control-sm" id="jurusanFilter" style="width: 150px;">
                    <option value="">Semua Jurusan</option>
                    @foreach($siswas->pluck('siswaProfile.major')->filter()->unique()->sort() as $major)
                        <option value="{{ $major }}">{{ $major }}</option>
                    @endforeach
                </select>
                <input type="text" class="form-control form-control-sm" placeholder="Cari siswa..." id="siswaSearch" style="width: 200px;">
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="resetSiswaSearch()">
                    <i class="fas fa-undo"></i>
                </button>
                <button class="btn btn-outline-primary btn-sm" type="button" onclick="exportSiswaData()">
                    <i class="fas fa-download me-1"></i>Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="siswaTable" width="100%" cellspacing="0">
                    <thead class="table-warning">
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama Lengkap</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Email</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $siswaNo = 1; @endphp
                        @foreach($siswas as $siswa)
                        <tr class="siswa-row" data-kelas="{{ $siswa->siswaProfile->kelas->name ?? '' }}" data-jurusan="{{ $siswa->siswaProfile->major ?? '' }}">
                            <td class="text-center">{{ $siswaNo++ }}</td>
                            <td class="text-center">
                                <img src="{{ $siswa->photo_url }}" class="rounded-circle" width="40" height="40" 
                                     style="object-fit: cover;" onerror="this.src='{{ asset('images/default-avatar.png') }}';">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $siswa->name }}</div>
                                <small class="text-muted">{{ $siswa->username }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $siswa->siswaProfile->nis ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $siswa->siswaProfile->nisn ?? '-' }}</span>
                            </td>
                            <td>{{ $siswa->email }}</td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $siswa->siswaProfile->kelas->name ?? '-' }}</span>
                            </td>
                            <td>{{ $siswa->siswaProfile->major ?? '-' }}</td>
                            <td>{{ $siswa->phone ?? '-' }}</td>
                            <td class="text-center">
                                @if($siswa->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.edit', $siswa->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-info" onclick="viewSiswaDetail({{ $siswa->id }})" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" onclick="toggleSiswaStatus({{ $siswa->id }})" title="Toggle Status">
                                        <i class="fas fa-toggle-on"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteSiswa({{ $siswa->id }})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($siswas->isEmpty())
            <div class="text-center py-4">
                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada data siswa</h5>
                <p class="text-muted">Tambahkan siswa pertama untuk memulai</p>
                <a href="{{ route('admin.users.create.siswa') }}" class="btn btn-warning">
                    <i class="fas fa-plus me-2"></i>Tambah Siswa
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Siswa Search
document.getElementById('siswaSearch').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('.siswa-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});

// Kelas Filter
document.getElementById('kelasFilter').addEventListener('change', function() {
    const filterValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('.siswa-row');
    
    rows.forEach(row => {
        const kelas = row.dataset.kelas.toLowerCase();
        if (filterValue === '' || kelas.includes(filterValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Jurusan Filter
document.getElementById('jurusanFilter').addEventListener('change', function() {
    const filterValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('.siswa-row');
    
    rows.forEach(row => {
        const jurusan = row.dataset.jurusan.toLowerCase();
        if (filterValue === '' || jurusan.includes(filterValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function resetSiswaSearch() {
    document.getElementById('siswaSearch').value = '';
    document.getElementById('kelasFilter').value = '';
    document.getElementById('jurusanFilter').value = '';
    const rows = document.querySelectorAll('.siswa-row');
    rows.forEach(row => row.style.display = '');
}

function viewSiswaDetail(siswaId) {
    // Implement view detail functionality
    window.location.href = `/admin/users/${siswaId}/edit`;
}

function toggleSiswaStatus(siswaId) {
    if (confirm('Apakah Anda yakin ingin mengubah status siswa ini?')) {
        // Implement toggle status functionality
        fetch(`/admin/users/${siswaId}/toggle-status`, {
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

function deleteSiswa(siswaId) {
    if (confirm('Apakah Anda yakin ingin menghapus siswa ini?')) {
        // Implement delete functionality
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${siswaId}`;
        
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

function exportSiswaData() {
    // Implement export functionality
    alert('Export data siswa akan segera tersedia');
}
</script>
@endsection
