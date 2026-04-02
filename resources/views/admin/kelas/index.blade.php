@extends('admin.layouts.admin-layout')

@section('title', 'Manajemen Kelas')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Manajemen Kelas</h5>
            <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Kelas
            </a>
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-school text-primary fs-3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small fw-semibold text-uppercase tracking-wider">Total Kelas</div>
                                <div class="h3 mb-0 fw-bold text-dark">{{ $kelas->count() ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-user-friends text-success fs-3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small fw-semibold text-uppercase tracking-wider">Total Siswa</div>
                                <div class="h3 mb-0 fw-bold text-dark">{{ $totalSiswa ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-graduation-cap text-info fs-3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small fw-semibold text-uppercase tracking-wider">Keperawatan</div>
                                <div class="h3 mb-0 fw-bold text-dark">{{ $kelasKeperawatan ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-pills text-warning fs-3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small fw-semibold text-uppercase tracking-wider">Farmasi</div>
                                <div class="h3 mb-0 fw-bold text-dark">{{ $kelasFarmasi ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="searchInput" class="form-label">Cari Kelas</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Nama kelas, kode, atau jurusan...">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="gradeFilter" class="form-label">Filter Tingkat</label>
                    <select class="form-control" id="gradeFilter">
                        <option value="">Semua Tingkat</option>
                        <option value="X">Kelas X</option>
                        <option value="XI">Kelas XI</option>
                        <option value="XII">Kelas XII</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="majorFilter" class="form-label">Filter Jurusan</label>
                    <select class="form-control" id="majorFilter">
                        <option value="">Semua Jurusan</option>
                        <option value="Keperawatan">Keperawatan</option>
                        <option value="Farmasi">Farmasi</option>
                        <option value="Analis Kesehatan">Analis Kesehatan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Kelas Table -->
        <div class="table-responsive">
            <table class="table table-hover" id="kelasTable">
                <thead class="table-dark">
                    <tr>
                        <th>Kelas</th>
                        <th>Kode</th>
                        <th>Tingkat</th>
                        <th>Jurusan</th>
                        <th>Kapasitas</th>
                        <th>Wali Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $kls)
                    <tr class="kelas-row" data-grade="{{ $kls->grade }}" data-major="{{ $kls->major }}">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-primary bg-opacity-10 rounded p-2">
                                        <i class="fas fa-school text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $kls->name }}</div>
                                    <small class="text-muted">{{ $kls->description ?? 'Tidak ada deskripsi' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $kls->code }}</span>
                        </td>
                        <td>{{ $kls->grade }}</td>
                        <td>
                            @if($kls->major === 'Keperawatan')
                                <span class="badge bg-info">{{ $kls->major }}</span>
                            @elseif($kls->major === 'Farmasi')
                                <span class="badge bg-warning">{{ $kls->major }}</span>
                            @else
                                <span class="badge bg-success">{{ $kls->major }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold">{{ $kls->capacity }}</span> siswa
                        </td>
                        <td>
                            @if($kls->guru_id && $kls->guru)
                                <div class="d-flex align-items-center">
                                    <img src="{{ $kls->guru->photo_url ?? asset('images/default-avatar.png') }}" 
                                         class="rounded-circle me-2" width="24" height="24" alt="Wali Kelas">
                                    <span class="small">{{ $kls->guru->name }}</span>
                                </div>
                            @else
                                <span class="text-muted">Belum ditentukan</span>
                            @endif
                        </td>
                        <td>{{ $kls->academic_year }}</td>
                        <td>
                            @if($kls->status === 'active')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.kelas.show', $kls->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.kelas.edit', $kls->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kelas.destroy', $kls->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
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
                        <td colspan="9" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-school fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada kelas</h5>
                                <p class="text-muted mb-3">Data kelas akan ditampilkan di sini</p>
                                <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Tambah Kelas Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($kelas instanceof \Illuminate\Pagination\LengthAwarePaginator && $kelas->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <small class="text-muted">
                    Menampilkan {{ $kelas->firstItem() }} - {{ $kelas->lastItem() }} dari {{ $kelas->total() }} kelas
                </small>
            </div>
            <div>
                {{ $kelas->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search and filter functionality
    const searchInput = document.getElementById('searchInput');
    const gradeFilter = document.getElementById('gradeFilter');
    const majorFilter = document.getElementById('majorFilter');
    const kelasRows = document.querySelectorAll('.kelas-row');
    
    function filterKelas() {
        const searchTerm = searchInput.value.toLowerCase();
        const gradeValue = gradeFilter.value;
        const majorValue = majorFilter.value;
        
        let hasVisibleRows = false;
        
        kelasRows.forEach(row => {
            const name = row.querySelector('td:first-child').textContent.toLowerCase();
            const code = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const grade = row.getAttribute('data-grade');
            const major = row.getAttribute('data-major');
            
            const matchesSearch = name.includes(searchTerm) || code.includes(searchTerm) || major.toLowerCase().includes(searchTerm);
            const matchesGrade = !gradeValue || grade === gradeValue;
            const matchesMajor = !majorValue || major === majorValue;
            
            if (matchesSearch && matchesGrade && matchesMajor) {
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
    if (searchInput) searchInput.addEventListener('input', filterKelas);
    if (gradeFilter) gradeFilter.addEventListener('change', filterKelas);
    if (majorFilter) majorFilter.addEventListener('change', filterKelas);
});
</script>
@endpush