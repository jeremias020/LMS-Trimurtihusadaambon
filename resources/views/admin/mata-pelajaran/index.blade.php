@extends('admin.layouts.admin-layout')

@section('title', 'Manajemen Mata Pelajaran')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Manajemen Mata Pelajaran</h5>
            <div class="btn-group">
                <a href="{{ route('admin.mata-pelajaran.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Mata Pelajaran
                </a>
                <form action="{{ route('admin.mata-pelajaran.seed-default') }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menambahkan data mata pelajaran default?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-database me-2"></i>Seed Default
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="searchInput" class="form-label">Cari Mata Pelajaran</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Nama atau kode mata pelajaran...">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="jenisFilter" class="form-label">Filter Jenis</label>
                    <select class="form-control" id="jenisFilter">
                        <option value="">Semua Jenis</option>
                        <option value="teori">Teori</option>
                        <option value="praktikum">Praktikum</option>
                        <option value="campuran">Campuran</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="statusFilter" class="form-label">Filter Status</label>
                    <select class="form-control" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Mata Pelajaran Table -->
        <div class="table-responsive">
            <table class="table table-hover" id="mataPelajaranTable">
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th>Jenis</th>
                        <th>Jam/Minggu</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mataPelajarans as $mapel)
                    <tr class="mata-pelajaran-row" data-type="{{ $mapel->type }}" data-status="{{ $mapel->is_active ? 'active' : 'inactive' }}">
                        <td>
                            <span class="badge bg-secondary">{{ $mapel->code }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-primary bg-opacity-10 rounded p-2">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $mapel->name }}</div>
                                    <small class="text-muted">{{ $mapel->description ?? 'Tidak ada deskripsi' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($mapel->type === 'teori')
                                <span class="badge bg-info">Teori</span>
                            @elseif($mapel->type === 'praktikum')
                                <span class="badge bg-warning">Praktikum</span>
                            @else
                                <span class="badge bg-primary">Campuran</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold">{{ $mapel->sks }}</span> SKS
                        </td>
                        <td>
                            @if($mapel->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.mata-pelajaran.show', $mapel->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.mata-pelajaran.edit', $mapel->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.mata-pelajaran.toggle-status', $mapel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status mata pelajaran ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Toggle Status">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.mata-pelajaran.destroy', $mapel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
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
                        <td colspan="6" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada mata pelajaran</h5>
                                <p class="text-muted mb-3">Data mata pelajaran akan ditampilkan di sini</p>
                                <a href="{{ route('admin.mata-pelajaran.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Tambah Mata Pelajaran Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const jenisFilter = document.getElementById('jenisFilter');
    const statusFilter = document.getElementById('statusFilter');
    const mataPelajaranTable = document.getElementById('mataPelajaranTable');
    const rows = mataPelajaranTable.querySelectorAll('.mata-pelajaran-row');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const jenisValue = jenisFilter.value;
        const statusValue = statusFilter.value;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const jenis = row.dataset.type;
            const status = row.dataset.status;

            const matchesSearch = text.includes(searchTerm);
            const matchesJenis = !jenisValue || jenis === jenisValue;
            const matchesStatus = !statusValue || status === statusValue;

            row.style.display = matchesSearch && matchesJenis && matchesStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    jenisFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>
@endpush
@endsection
