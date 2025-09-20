@extends('layouts.admin')

@section('title', 'Detail Materi Pembelajaran')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Materi Pembelajaran</h1>
            <p class="text-gray-600">Informasi lengkap dan statistik materi</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.materials.edit', $material) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Edit Materi
            </a>
            <a href="{{ route('admin.materials.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Material Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Informasi Materi</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Materi</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $material->judul }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Guru</label>
                        <p class="text-sm text-gray-900">{{ $material->teacher->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $material->subject->name ?? 'N/A' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $material->category }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
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
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dibuat</label>
                        <p class="text-sm text-gray-900">{{ $material->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
                
                @if($material->description)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $material->description }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- File Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Informasi File</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-file-{{ $material->file_type === 'pdf' ? 'pdf' : ($material->file_type === 'doc' || $material->file_type === 'docx' ? 'word' : 'alt') }} text-gray-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama File</label>
                                <p class="text-sm text-gray-900 font-medium">{{ $material->file }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe File</label>
                                <p class="text-sm text-gray-900 uppercase">{{ $material->file_type }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran File</label>
                                <p class="text-sm text-gray-900">{{ number_format($material->file_size / 1024, 1) }} KB</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">MIME Type</label>
                                <p class="text-sm text-gray-900">{{ $material->mime_type }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Download History -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Riwayat Download</h2>
            </div>
            <div class="p-6">
                @if($downloads->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Download</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($downloads as $download)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $download->siswa->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $download->siswa->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $download->downloaded_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $download->ip_address }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($downloads->hasPages())
                    <div class="mt-4">
                        {{ $downloads->links() }}
                    </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-download text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada riwayat download</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Statistik</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="fas fa-download text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Total Download</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_downloads']) }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i class="fas fa-calendar-week text-green-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Download Minggu Ini</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['last_week_downloads']) }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <i class="fas fa-users text-purple-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Downloader Unik</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['unique_downloaders']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Aksi</h2>
            </div>
            <div class="p-6 space-y-3">
                <form action="{{ route('admin.materials.publish', $material) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white {{ $material->is_published ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $material->is_published ? 'focus:ring-yellow-500' : 'focus:ring-green-500' }}">
                        <i class="fas fa-{{ $material->is_published ? 'eye-slash' : 'eye' }} mr-2"></i>
                        {{ $material->is_published ? 'Sembunyikan' : 'Publikasikan' }}
                    </button>
                </form>
                
                <a href="{{ route('admin.materials.edit', $material) }}" 
                   class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Materi
                </a>
                
                <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Materi
                    </button>
                </form>
            </div>
        </div>

        <!-- Material Info -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Informasi Tambahan</h2>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">ID Materi:</span>
                    <span class="text-sm font-medium text-gray-900">#{{ $material->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Terakhir Diupdate:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $material->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Path File:</span>
                    <span class="text-sm font-medium text-gray-900 break-all">{{ $material->file_path }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
