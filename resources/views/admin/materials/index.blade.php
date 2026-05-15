@extends('layouts.admin')

@section('title', 'Manajemen Materi Pembelajaran')

@section('page-title', 'Materi Pembelajaran')
@section('page-subtitle', 'Kelola materi dari guru SMK Kesehatan Trimurti Husada.')

@section('page-actions')
    <a href="{{ route('admin.materials.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Tambah materi
    </a>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
                <i class="fas fa-book text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Materi</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_materials']) }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
                <i class="fas fa-eye text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Dipublikasikan</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['published_materials']) }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
                <i class="fas fa-eye-slash text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Disembunyikan</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['unpublished_materials']) }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
                <i class="fas fa-download text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Download</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_downloads']) }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-red-100 rounded-lg">
                <i class="fas fa-hdd text-red-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Downloads</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_downloads'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-2">
        <h2 class="text-xl font-semibold text-gray-800 mb-0">Daftar materi</h2>
    </div>

    <div class="px-6 py-4">
        <!-- Filter and Search -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="flex-1">
                <input type="text" 
                       id="searchInput" 
                       placeholder="Cari materi..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex gap-2">
                <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="published">Dipublikasikan</option>
                    <option value="unpublished">Disembunyikan</option>
                </select>
                <select id="categoryFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Kategori</option>
                    <option value="Teori">Teori</option>
                    <option value="Praktikum">Praktikum</option>
                    <option value="Modul">Modul</option>
                    <option value="Handout">Handout</option>
                    <option value="Lembar Kerja">Lembar Kerja</option>
                    <option value="Video">Video</option>
                    <option value="Referensi">Referensi</option>
                </select>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="mb-4 flex items-center gap-4">
            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <label for="selectAll" class="text-sm text-gray-600">Pilih Semua</label>
            <button id="bulkDeleteBtn" class="btn-danger text-sm" disabled>
                <i class="fas fa-trash mr-1"></i>
                Hapus Terpilih
            </button>
        </div>

        <!-- Materials Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAllHeader" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Download</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($materials as $material)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="material-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $material->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-file-{{ $material->file_type === 'pdf' ? 'pdf' : ($material->file_type === 'doc' || $material->file_type === 'docx' ? 'word' : 'alt') }} text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($material->judul, 50) }}</div>
                                    <div class="text-sm text-gray-500">{{ number_format($material->file_size / 1024, 1) }} KB</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $material->teacher->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $material->teacher->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $material->subject->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $material->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($material->is_published)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-eye mr-1"></i>
                                    Dipublikasikan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-eye-slash mr-1"></i>
                                    Disembunyikan
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-download mr-1 text-gray-400"></i>
                                {{ number_format($material->downloads_count) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $material->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.materials.show', $material) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.materials.edit', $material) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.materials.publish', $material) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-{{ $material->is_published ? 'yellow' : 'green' }}-600 hover:text-{{ $material->is_published ? 'yellow' : 'green' }}-900" 
                                            title="{{ $material->is_published ? 'Sembunyikan' : 'Publikasikan' }}">
                                        <i class="fas fa-{{ $material->is_published ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-book text-4xl mb-4"></i>
                                <p class="text-lg font-medium">Belum ada materi pembelajaran</p>
                                <p class="text-sm">Mulai dengan menambahkan materi pembelajaran pertama</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($materials->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $materials->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllHeaderCheckbox = document.getElementById('selectAllHeader');
    const materialCheckboxes = document.querySelectorAll('.material-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.material-checkbox:checked');
        bulkDeleteBtn.disabled = checkedBoxes.length === 0;
        bulkDeleteBtn.textContent = checkedBoxes.length > 0 ? 
            `Hapus Terpilih (${checkedBoxes.length})` : 'Hapus Terpilih';
    }

    selectAllCheckbox.addEventListener('change', function() {
        materialCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        selectAllHeaderCheckbox.checked = this.checked;
        updateBulkDeleteButton();
    });

    selectAllHeaderCheckbox.addEventListener('change', function() {
        materialCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        selectAllCheckbox.checked = this.checked;
        updateBulkDeleteButton();
    });

    materialCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteButton);
    });

    // Bulk delete functionality
    bulkDeleteBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.material-checkbox:checked');
        if (checkedBoxes.length === 0) return;

        if (confirm(`Apakah Anda yakin ingin menghapus ${checkedBoxes.length} materi yang dipilih?`)) {
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            
            fetch('{{ route("admin.materials.bulk-delete") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ ids: ids })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus materi');
            });
        }
    });

    // Search and filter functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const categoryValue = categoryFilter.value;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const title = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const status = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
            const category = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

            const matchesSearch = title.includes(searchTerm);
            const matchesStatus = !statusValue || 
                (statusValue === 'published' && status.includes('dipublikasikan')) ||
                (statusValue === 'unpublished' && status.includes('disembunyikan'));
            const matchesCategory = !categoryValue || category.includes(categoryValue.toLowerCase());

            row.style.display = (matchesSearch && matchesStatus && matchesCategory) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    categoryFilter.addEventListener('change', filterTable);
});
</script>
@endpush
@endsection
