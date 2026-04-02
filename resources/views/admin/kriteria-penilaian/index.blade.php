@extends('admin.layouts.admin-layout')

@section('title', 'Kriteria Penilaian')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Kriteria Penilaian</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.kriteria-penilaian.create-combined') }}" class="btn btn-success">
                <i class="fas fa-layer-group me-2"></i>Tambah (Gabungan 4 Kategori)
            </a>
            <a href="{{ route('admin.kriteria-penilaian.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Kriteria
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @isset($error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endisset

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Cari Kriteria</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Nama atau deskripsi kriteria...">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Filter Kategori</label>
                <select id="kategoriFilter" class="form-control">
                    <option value="">Semua Kategori</option>
                    <option value="persiapan">Persiapan</option>
                    <option value="pelaksanaan">Pelaksanaan</option>
                    <option value="hasil">Hasil</option>
                    <option value="sikap">Sikap Profesional</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Filter Status</label>
                <select id="statusFilter" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="kriteriaTable">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Kriteria</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Weight</th>
                        <th>Max Score</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kriteria as $item)
                        @php
                            // Extract kategori dari deskripsi
                            $kategori = 'Tidak Diketahui';
                            if (preg_match('/\[(persiapan|pelaksanaan|hasil|sikap)\]/', $item->description, $matches)) {
                                $kategori = ucfirst($matches[1]);
                                if ($kategori == 'Sikap') $kategori = 'Sikap Profesional';
                            }
                            // Clean description
                            $cleanDescription = preg_replace('/\[(persiapan|pelaksanaan|hasil|sikap)\]\s*/', '', $item->description);
                        @endphp
                        <tr class="kriteria-row" data-kategori="{{ $matches[1] ?? '' }}" data-status="{{ $item->is_active ? 'active' : 'inactive' }}">
                            <td>
                                <div class="fw-semibold">{{ $item->nama ?? $item->name }}</div>
                                <small class="text-muted">ID: {{ $item->id }}</small>
                            </td>
                            <td>
                                @switch($matches[1] ?? '')
                                    @case('persiapan')
                                        <span class="badge bg-info">Persiapan</span>
                                        @break
                                    @case('pelaksanaan')
                                        <span class="badge bg-primary">Pelaksanaan</span>
                                        @break
                                    @case('hasil')
                                        <span class="badge bg-success">Hasil</span>
                                        @break
                                    @case('sikap')
                                        <span class="badge bg-warning text-dark">Sikap Profesional</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $kategori }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div>{{ \Illuminate\Support\Str::limit($cleanDescription, 80) }}</div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $item->bobot ?? $item->weight }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->max_score ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.kriteria-penilaian.show', $item->id) }}" class="btn btn-outline-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.kriteria-penilaian.edit', $item->id) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.kriteria-penilaian.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus kriteria ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <h5>Belum Ada Data Kriteria</h5>
                                    <p>Belum ada data kriteria penilaian yang tersedia. Silakan tambahkan kriteria terlebih dahulu.</p>
                                    <a href="{{ route('admin.kriteria-penilaian.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Tambah Kriteria Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kriteria instanceof \Illuminate\Contracts\Pagination\Paginator || $kriteria instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Menampilkan {{ $kriteria->firstItem() }} hingga {{ $kriteria->lastItem() }} dari {{ $kriteria->total() }} data
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        {{ $kriteria->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Summary Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card border-info summary-card">
            <div class="card-body text-center">
                <i class="fas fa-clipboard-list fa-2x text-info mb-2"></i>
                <h5 class="card-title">Persiapan</h5>
                <p class="card-text text-muted">12 kriteria</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-primary summary-card">
            <div class="card-body text-center">
                <i class="fas fa-tools fa-2x text-primary mb-2"></i>
                <h5 class="card-title">Pelaksanaan</h5>
                <p class="card-text text-muted">14 kriteria</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success summary-card">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                <h5 class="card-title">Hasil</h5>
                <p class="card-text text-muted">11 kriteria</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning summary-card">
            <div class="card-body text-center">
                <i class="fas fa-user-md fa-2x text-warning mb-2"></i>
                <h5 class="card-title">Sikap Profesional</h5>
                <p class="card-text text-muted">9 kriteria</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const kategoriFilter = document.getElementById('kategoriFilter');
    const statusFilter = document.getElementById('statusFilter');
    const kriteriaTable = document.getElementById('kriteriaTable');
    const rows = kriteriaTable.querySelectorAll('.kriteria-row');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const kategoriValue = kategoriFilter.value;
        const statusValue = statusFilter.value;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const kategori = row.dataset.kategori;
            const status = row.dataset.status;

            const matchesSearch = text.includes(searchTerm);
            const matchesKategori = !kategoriValue || kategori === kategoriValue;
            const matchesStatus = !statusValue || status === statusValue;

            row.style.display = matchesSearch && matchesKategori && matchesStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    kategoriFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
    
    // Fix pagination styling
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    paginationLinks.forEach(link => {
        link.style.padding = '0.375rem 0.75rem';
        link.style.marginLeft = '0';
        link.style.borderRadius = '0';
    });
    
    const paginationItems = document.querySelectorAll('.pagination .page-item');
    paginationItems.forEach((item, index) => {
        if (index > 0) {
            item.style.marginLeft = '2px';
        }
        item.firstElementChild.style.borderRadius = index === 0 ? '0.375rem 0 0 0.375rem' : 
                                                   index === paginationItems.length - 1 ? '0 0.375rem 0.375rem 0' : '0';
    });
});
</script>
@endpush

@push('styles')
<style>
.pagination {
    margin: 0;
    display: flex;
    list-style: none;
    padding-left: 0;
    border-radius: 0.375rem;
}

.page-link {
    position: relative;
    display: block;
    color: #0d6efd;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #dee2e6;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.page-link:hover {
    z-index: 2;
    color: #0a58ca;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.page-link:focus {
    z-index: 3;
    color: #0a58ca;
    background-color: #e9ecef;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.page-item:not(:first-child) .page-link {
    margin-left: -1px;
}

.page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

.page-item:first-child .page-link {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.page-item:last-child .page-link {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

.table-responsive {
    overflow-x: auto;
}

.card-body {
    padding: 1.25rem;
}

.summary-card {
    transition: transform 0.2s ease-in-out;
}

.summary-card:hover {
    transform: translateY(-2px);
}
</style>
@endpush
