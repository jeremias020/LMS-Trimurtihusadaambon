@extends('admin.layouts.admin-layout')

@section('title')
    Manajemen Pengguna
@endsection

@section('page-title')
    Manajemen Pengguna Terpisah
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Pengguna</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.create.admin') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Admin
            </a>
            <a href="{{ route('admin.users.create.guru') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Tambah Guru
            </a>
            <a href="{{ route('admin.users.create.siswa') }}" class="btn btn-warning">
                <i class="fas fa-plus me-2"></i>Tambah Siswa
            </a>
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
                            <div class="h3 mb-0 fw-bold text-dark">{{ \App\Models\UserCentral::count() }}</div>
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
                                {{ \App\Models\UserCentral::where('role', 'admin')->count() }}
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
                                {{ \App\Models\UserCentral::where('role', 'guru')->count() }}
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
                                {{ \App\Models\UserCentral::where('role', 'siswa')->count() }}
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

    <!-- Admin Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user-shield me-2"></i>Data Administrator
            </h6>
            <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="Cari admin..." id="adminSearch" style="width: 200px;">
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="resetAdminSearch()">
                    <i class="fas fa-undo"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="adminTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $admins = \App\Models\UserCentral::where('role', 'admin')->get();
                            $adminNo = 1;
                        @endphp
                        @foreach($admins as $admin)
                        <tr class="admin-row">
                            <td>{{ $adminNo++ }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $admin->photo_url }}" class="rounded-circle me-2" width="32" height="32">
                                    <div>
                                        <div class="fw-bold">{{ $admin->name }}</div>
                                        @if($admin->adminProfile)
                                            <small class="text-muted">{{ $admin->adminProfile->address }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->username }}</td>
                            <td>{{ $admin->phone ?? '-' }}</td>
                            <td>
                                @if($admin->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.edit', $admin->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteUser({{ $admin->id }}, 'admin')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Guru Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-chalkboard-teacher me-2"></i>Data Guru
            </h6>
            <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="Cari guru..." id="guruSearch" style="width: 200px;">
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="resetGuruSearch()">
                    <i class="fas fa-undo"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="guruTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Email</th>
                            <th>Mata Pelajaran</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $gurus = \App\Models\UserCentral::where('role', 'guru')->get();
                            $guruNo = 1;
                        @endphp
                        @foreach($gurus as $guru)
                        <tr class="guru-row">
                            <td>{{ $guruNo++ }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $guru->photo_url }}" class="rounded-circle me-2" width="32" height="32">
                                    <div>
                                        <div class="fw-bold">{{ $guru->name }}</div>
                                        @if($guru->guruProfile)
                                            <small class="text-muted">{{ $guru->guruProfile->pendidikan_terakhir }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $guru->guruProfile->nip ?? '-' }}</td>
                            <td>{{ $guru->email }}</td>
                            <td>{{ $guru->guruProfile->mata_pelajaran ?? '-' }}</td>
                            <td>{{ $guru->phone ?? '-' }}</td>
                            <td>
                                @if($guru->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.edit', $guru->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteUser({{ $guru->id }}, 'guru')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Siswa Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-user-graduate me-2"></i>Data Siswa
            </h6>
            <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="Cari siswa..." id="siswaSearch" style="width: 200px;">
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="resetSiswaSearch()">
                    <i class="fas fa-undo"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="siswaTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Email</th>
                            <th>Kelas</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $siswas = \App\Models\UserCentral::where('role', 'siswa')->get();
                            $siswaNo = 1;
                        @endphp
                        @foreach($siswas as $siswa)
                        <tr class="siswa-row">
                            <td>{{ $siswaNo++ }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $siswa->photo_url }}" class="rounded-circle me-2" width="32" height="32">
                                    <div>
                                        <div class="fw-bold">{{ $siswa->name }}</div>
                                        @if($siswa->siswaProfile)
                                            <small class="text-muted">{{ $siswa->siswaProfile->major }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $siswa->siswaProfile->nis ?? '-' }}</td>
                            <td>{{ $siswa->siswaProfile->nisn ?? '-' }}</td>
                            <td>{{ $siswa->email }}</td>
                            <td>{{ $siswa->siswaProfile->kelas->name ?? '-' }}</td>
                            <td>{{ $siswa->phone ?? '-' }}</td>
                            <td>
                                @if($siswa->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.edit', $siswa->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteUser({{ $siswa->id }}, 'siswa')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Admin Search
document.getElementById('adminSearch').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('.admin-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});

function resetAdminSearch() {
    document.getElementById('adminSearch').value = '';
    const rows = document.querySelectorAll('.admin-row');
    rows.forEach(row => row.style.display = '');
}

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

// Siswa Search
document.getElementById('siswaSearch').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('.siswa-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});

function resetSiswaSearch() {
    document.getElementById('siswaSearch').value = '';
    const rows = document.querySelectorAll('.siswa-row');
    rows.forEach(row => row.style.display = '');
}

// Delete User
function deleteUser(userId, role) {
    if (confirm('Apakah Anda yakin ingin menghapus ' + role + ' ini?')) {
        // Implement delete functionality
        console.log('Deleting user:', userId, role);
        // You can add AJAX call here
    }
}
</script>
@endsection
