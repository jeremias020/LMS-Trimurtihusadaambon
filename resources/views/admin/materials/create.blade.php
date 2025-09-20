@extends('layouts.admin')

@section('title', 'Tambah Materi Pembelajaran')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Materi Pembelajaran</h1>
            <p class="text-gray-600">Tambahkan materi pembelajaran baru untuk siswa</p>
        </div>
        <a href="{{ route('admin.materials.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Judul Materi -->
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Materi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="judul" 
                           name="judul" 
                           value="{{ old('judul') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('judul') border-red-500 @enderror"
                           placeholder="Masukkan judul materi"
                           required>
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guru -->
                <div>
                    <label for="guru_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Guru <span class="text-red-500">*</span>
                    </label>
                    <select id="guru_id" 
                            name="guru_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('guru_id') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Guru</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('guru_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('guru_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mata Pelajaran -->
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <select id="subject_id" 
                            name="subject_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subject_id') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $key => $value)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- File Upload -->
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        File Materi <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload file</span>
                                    <input id="file" 
                                           name="file" 
                                           type="file" 
                                           class="sr-only" 
                                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar,.mp4,.avi,.mov,.jpg,.jpeg,.png"
                                           required>
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR, MP4, AVI, MOV, JPG, JPEG, PNG (Max 50MB)
                            </p>
                        </div>
                    </div>
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Publikasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Publikasi</label>
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_published" 
                               name="is_published" 
                               value="1"
                               {{ old('is_published') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_published" class="ml-2 block text-sm text-gray-900">
                            Publikasikan materi ini
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Jika dicentang, materi akan langsung dapat diakses oleh siswa
                    </p>
                </div>

                <!-- File Preview -->
                <div id="filePreview" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preview File</label>
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i id="fileIcon" class="fas fa-file text-2xl text-gray-400"></i>
                            </div>
                            <div class="ml-3">
                                <p id="fileName" class="text-sm font-medium text-gray-900"></p>
                                <p id="fileSize" class="text-sm text-gray-500"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Deskripsi Materi
            </label>
            <textarea id="description" 
                      name="description" 
                      rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                      placeholder="Masukkan deskripsi materi (opsional)">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('admin.materials.index') }}" class="btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save mr-2"></i>
                Simpan Materi
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const filePreview = document.getElementById('filePreview');
    const fileIcon = document.getElementById('fileIcon');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Show preview
            filePreview.classList.remove('hidden');
            
            // Set file name
            fileName.textContent = file.name;
            
            // Set file size
            const sizeInMB = (file.size / 1024 / 1024).toFixed(2);
            fileSize.textContent = sizeInMB + ' MB';
            
            // Set file icon based on type
            const extension = file.name.split('.').pop().toLowerCase();
            const iconMap = {
                'pdf': 'fa-file-pdf',
                'doc': 'fa-file-word',
                'docx': 'fa-file-word',
                'ppt': 'fa-file-powerpoint',
                'pptx': 'fa-file-powerpoint',
                'xls': 'fa-file-excel',
                'xlsx': 'fa-file-excel',
                'txt': 'fa-file-alt',
                'zip': 'fa-file-archive',
                'rar': 'fa-file-archive',
                'mp4': 'fa-file-video',
                'avi': 'fa-file-video',
                'mov': 'fa-file-video',
                'jpg': 'fa-file-image',
                'jpeg': 'fa-file-image',
                'png': 'fa-file-image'
            };
            
            const iconClass = iconMap[extension] || 'fa-file';
            fileIcon.className = `fas ${iconClass} text-2xl text-gray-400`;
        } else {
            filePreview.classList.add('hidden');
        }
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const file = fileInput.files[0];
        if (!file) {
            e.preventDefault();
            alert('Silakan pilih file materi');
            return;
        }

        // Check file size (50MB limit)
        if (file.size > 50 * 1024 * 1024) {
            e.preventDefault();
            alert('Ukuran file terlalu besar. Maksimal 50MB');
            return;
        }

        // Check file type
        const allowedTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'rar', 'mp4', 'avi', 'mov', 'jpg', 'jpeg', 'png'];
        const extension = file.name.split('.').pop().toLowerCase();
        if (!allowedTypes.includes(extension)) {
            e.preventDefault();
            alert('Tipe file tidak didukung. Silakan pilih file yang sesuai');
            return;
        }
    });
});
</script>
@endpush
@endsection
