@extends('layouts.guru')

@section('title', 'Buat Praktikum Baru - SMK Kesehatan Trimurti Husada')

@push('css')
<style>
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background-color: #fff;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-input:invalid {
        border-color: #ef4444;
    }
    
    .form-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border: 1px solid #d1d5db;
        border-radius: 0.25rem;
        background-color: #fff;
        cursor: pointer;
    }
    
    .form-checkbox:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .btn-primary {
        background-color: #3b82f6;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.375rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
    }
    
    .btn-secondary {
        background-color: #6b7280;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.375rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .btn-secondary:hover {
        background-color: #4b5563;
        color: white;
        text-decoration: none;
    }
    
    .text-red-600 {
        color: #dc2626;
    }
    
    .text-gray-500 {
        color: #6b7280;
    }
    
    .text-gray-700 {
        color: #374151;
    }
    
    .text-xs {
        font-size: 0.75rem;
    }
    
    .text-sm {
        font-size: 0.875rem;
    }
    
    .grid {
        display: grid;
    }
    
    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    .gap-6 {
        gap: 1.5rem;
    }
    
    .gap-2 {
        gap: 0.5rem;
    }
    
    .space-y-8 > * + * {
        margin-top: 2rem;
    }
    
    .space-x-3 > * + * {
        margin-left: 0.75rem;
    }
    
    .bg-blue-50 {
        background-color: #eff6ff;
    }
    
    .bg-green-50 {
        background-color: #f0fdf4;
    }
    
    .bg-yellow-50 {
        background-color: #fefce8;
    }
    
    .bg-orange-50 {
        background-color: #fff7ed;
    }
    
    .bg-purple-50 {
        background-color: #faf5ff;
    }
    
    .bg-red-50 {
        background-color: #fef2f2;
    }
    
    .bg-gray-50 {
        background-color: #f9fafb;
    }
    
    .text-blue-800 {
        color: #1e40af;
    }
    
    .text-green-800 {
        color: #166534;
    }
    
    .text-yellow-800 {
        color: #92400e;
    }
    
    .text-orange-800 {
        color: #9a3412;
    }
    
    .text-purple-800 {
        color: #6b21a8;
    }
    
    .text-red-800 {
        color: #991b1b;
    }
    
    .text-gray-800 {
        color: #1f2937;
    }
    
    .rounded-lg {
        border-radius: 0.5rem;
    }
    
    .rounded {
        border-radius: 0.25rem;
    }
    
    .shadow {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    
    .overflow-hidden {
        overflow: hidden;
    }
    
    .flex {
        display: flex;
    }
    
    .items-center {
        align-items: center;
    }
    
    .justify-end {
        justify-content: flex-end;
    }
    
    .px-6 {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
    
    .py-4 {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    .px-3 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .py-2 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    
    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .p-4 {
        padding: 1rem;
    }
    
    .mb-6 {
        margin-bottom: 1.5rem;
    }
    
    .mb-4 {
        margin-bottom: 1rem;
    }
    
    .mb-2 {
        margin-bottom: 0.5rem;
    }
    
    .mt-1 {
        margin-top: 0.25rem;
    }
    
    .mt-2 {
        margin-top: 0.5rem;
    }
    
    .mr-2 {
        margin-right: 0.5rem;
    }
    
    .ml-3 {
        margin-left: 0.75rem;
    }
    
    .border-b {
        border-bottom-width: 1px;
    }
    
    .border-t {
        border-top-width: 1px;
    }
    
    .border-gray-200 {
        border-color: #e5e7eb;
    }
    
    .w-4 {
        width: 1rem;
    }
    
    .h-4 {
        height: 1rem;
    }
    
    .w-5 {
        width: 1.25rem;
    }
    
    .h-5 {
        height: 1.25rem;
    }
    
    .w-8 {
        width: 2rem;
    }
    
    .h-8 {
        height: 2rem;
    }
    
    .flex-1 {
        flex: 1 1 0%;
    }
    
    .flex-shrink-0 {
        flex-shrink: 0;
    }
    
    .bg-red-500 {
        background-color: #ef4444;
    }
    
    .bg-purple-500 {
        background-color: #8b5cf6;
    }
    
    .bg-yellow-500 {
        background-color: #eab308;
    }
    
    .hover\:bg-red-600:hover {
        background-color: #dc2626;
    }
    
    .hover\:bg-purple-600:hover {
        background-color: #7c3aed;
    }
    
    .hover\:bg-yellow-600:hover {
        background-color: #ca8a04;
    }
    
    .bg-red-100 {
        background-color: #fee2e2;
    }
    
    .bg-yellow-100 {
        background-color: #fef3c7;
    }
    
    .text-red-700 {
        color: #b91c1c;
    }
    
    .text-yellow-700 {
        color: #a16207;
    }
    
    .font-medium {
        font-weight: 500;
    }
    
    .font-bold {
        font-weight: 700;
    }
    
    .font-semibold {
        font-weight: 600;
    }
    
    .text-2xl {
        font-size: 1.5rem;
        line-height: 2rem;
    }
    
    .text-xl {
        font-size: 1.25rem;
        line-height: 1.75rem;
    }
    
    .text-lg {
        font-size: 1.125rem;
        line-height: 1.75rem;
    }
    
    @media (min-width: 768px) {
        .md\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        
        .md\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>
@endpush

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Buat Praktikum Baru</h1>
    <p class="text-gray-600">Buat kegiatan praktikum baru untuk siswa</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
        <h2 class="text-xl font-semibold text-blue-800">Formulir Praktikum</h2>
        <p class="text-sm text-blue-600 mt-1">Lengkapi informasi praktikum dengan benar</p>
    </div>

    <form action="{{ route('guru.praktikum.store') }}" method="POST" enctype="multipart/form-data" id="practicalForm">
        @csrf

        <div class="px-6 py-4 space-y-8">
            <!-- Informasi Dasar -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-blue-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Dasar Praktikum
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="judul" class="form-label">Judul Praktikum *</label>
                        <input type="text" name="judul" id="judul" class="form-input"
                               value="{{ old('judul') }}" placeholder="Contoh: Praktikum Anatomi Tulang" required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subject_id" class="form-label">Mata Pelajaran *</label>
                        <select name="subject_id" id="subject_id" class="form-input" required>
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

                    <div class="form-group">
                        <label for="kelas_id" class="form-label">Kelas *</label>
                        <select name="kelas_id" id="kelas_id" class="form-input" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} - {{ $kelas->jurusan }}
                            </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tanggal" class="form-label">Tanggal Praktikum *</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-input"
                               value="{{ old('tanggal') }}" required>
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Waktu dan Durasi -->
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-green-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Waktu dan Durasi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="waktu_mulai" class="form-label">Waktu Mulai *</label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-input"
                               value="{{ old('waktu_mulai') }}" required>
                        @error('waktu_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="waktu_selesai" class="form-label">Waktu Selesai *</label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-input"
                               value="{{ old('waktu_selesai') }}" required>
                        @error('waktu_selesai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="durasi" class="form-label">Durasi (menit) *</label>
                        <input type="number" name="durasi" id="durasi" class="form-input"
                               value="{{ old('durasi', 90) }}" min="15" max="480" required>
                        @error('durasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Lokasi dan Level -->
            <div class="bg-orange-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-orange-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Lokasi dan Level
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="lokasi" class="form-label">Lokasi Praktikum *</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-input"
                               value="{{ old('lokasi') }}" placeholder="Contoh: Lab Anatomi Lt.2" required>
                        @error('lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="skill_level" class="form-label">Level Kesulitan *</label>
                        <select name="skill_level" id="skill_level" class="form-input" required>
                            <option value="">Pilih Level Kesulitan</option>
                            @foreach($skillLevels as $key => $value)
                            <option value="{{ $key }}" {{ old('skill_level') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                        @error('skill_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Deskripsi dan Skor -->
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-yellow-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Deskripsi dan Penilaian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="deskripsi" class="form-label">Deskripsi Praktikum *</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-input" rows="4"
                                  placeholder="Jelaskan secara detail tentang praktikum ini..." required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="max_score" class="form-label">Skor Maksimum *</label>
                        <input type="number" name="max_score" id="max_score" class="form-input"
                               value="{{ old('max_score', 100) }}" min="1" max="1000" required>
                        <p class="text-xs text-gray-500 mt-1">Skor maksimum yang bisa diperoleh siswa</p>
                        @error('max_score')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Bahan dan Alat -->
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-purple-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Bahan dan Alat Praktikum
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Bahan -->
                    <div class="form-group">
                        <label class="form-label">Bahan yang Diperlukan *</label>
                        <div id="bahanContainer">
                            <div class="bahan-item flex gap-2 mb-2">
                                <input type="text" name="bahan[]" class="form-input flex-1" 
                                       placeholder="Nama bahan" required>
                                <button type="button" onclick="removeBahan(this)" 
                                        class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">×</button>
                            </div>
                        </div>
                        <button type="button" onclick="addBahan()" 
                                class="mt-2 px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">+ Tambah Bahan</button>
                        @error('bahan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alat -->
                    <div class="form-group">
                        <label class="form-label">Alat yang Diperlukan *</label>
                        <div id="toolsContainer">
                            <div class="tools-item flex gap-2 mb-2">
                                <input type="text" name="tools[]" class="form-input flex-1" 
                                       placeholder="Nama alat" required>
                                <button type="button" onclick="removeTools(this)" 
                                        class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">×</button>
                            </div>
                        </div>
                        <button type="button" onclick="addTools()" 
                                class="mt-2 px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">+ Tambah Alat</button>
                        @error('tools')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Prosedur dan Keselamatan -->
            <div class="bg-red-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-red-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Prosedur dan Keselamatan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Instruksi -->
                    <div class="form-group">
                        <label class="form-label">Langkah Kerja/Instruksi *</label>
                        <div id="instruksiContainer">
                            <div class="instruksi-item flex gap-2 mb-2">
                                <span class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-sm font-medium text-red-700">1</span>
                                <input type="text" name="instruksi[]" class="form-input flex-1" 
                                       placeholder="Langkah kerja" required>
                                <button type="button" onclick="removeInstruksi(this)" 
                                        class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">×</button>
                            </div>
                        </div>
                        <button type="button" onclick="addInstruksi()" 
                                class="mt-2 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">+ Tambah Langkah</button>
                        @error('instruksi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keselamatan -->
                    <div class="form-group">
                        <label class="form-label">Prosedur Keselamatan *</label>
                        <div id="keselamatanContainer">
                            <div class="keselamatan-item flex gap-2 mb-2">
                                <span class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center text-sm font-medium text-yellow-700">⚠</span>
                                <input type="text" name="keselamatan[]" class="form-input flex-1" 
                                       placeholder="Prosedur keselamatan" required>
                                <button type="button" onclick="removeKeselamatan(this)" 
                                        class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">×</button>
                            </div>
                        </div>
                        <button type="button" onclick="addKeselamatan()" 
                                class="mt-2 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">+ Tambah Keselamatan</button>
                        @error('keselamatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status Publikasi -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-blue-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Status Publikasi
                </h3>
                <div class="form-group">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_published" value="1" class="form-checkbox h-5 w-5 text-blue-600" {{ old('is_published') ? 'checked' : '' }}>
                        <span class="ml-3 text-gray-700">Publikasikan praktikum ini (siswa dapat melihat dan mengerjakan)</span>
                    </label>
                    <p class="text-sm text-gray-500 mt-2">
                        Jika tidak dicentang, praktikum akan disimpan sebagai draft dan tidak akan terlihat oleh siswa.
                    </p>
                    @error('is_published')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('guru.praktikum.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Batal
            </a>
            <button type="submit" class="btn-primary" id="submitBtn">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Praktikum
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.getElementById('tanggal');
    if (dateInput) {
        dateInput.min = today;
    }

    // Auto calculate waktu_selesai based on waktu_mulai and durasi
    const waktuMulai = document.getElementById('waktu_mulai');
    const durasi = document.getElementById('durasi');
    const waktuSelesai = document.getElementById('waktu_selesai');

    function calculateEndTime() {
        if (waktuMulai.value && durasi.value) {
            const [hours, minutes] = waktuMulai.value.split(':').map(Number);
            const durationMinutes = parseInt(durasi.value);
            
            const startTime = new Date();
            startTime.setHours(hours, minutes, 0, 0);
            
            const endTime = new Date(startTime.getTime() + durationMinutes * 60000);
            const endHours = String(endTime.getHours()).padStart(2, '0');
            const endMinutes = String(endTime.getMinutes()).padStart(2, '0');
            
            waktuSelesai.value = `${endHours}:${endMinutes}`;
        }
    }

    if (waktuMulai && durasi && waktuSelesai) {
        waktuMulai.addEventListener('change', calculateEndTime);
        durasi.addEventListener('input', calculateEndTime);
    }

    // Dynamic form functions
    window.addBahan = function() {
        const container = document.getElementById('bahanContainer');
        const newItem = document.createElement('div');
        newItem.className = 'bahan-item flex gap-2 mb-2';
        newItem.innerHTML = `
            <input type="text" name="bahan[]" class="form-input flex-1" placeholder="Nama bahan" required>
            <button type="button" onclick="removeBahan(this)" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">×</button>
        `;
        container.appendChild(newItem);
    };

    window.removeBahan = function(button) {
        const container = document.getElementById('bahanContainer');
        if (container.children.length > 1) {
            button.parentElement.remove();
        }
    };

    window.addTools = function() {
        const container = document.getElementById('toolsContainer');
        const newItem = document.createElement('div');
        newItem.className = 'tools-item flex gap-2 mb-2';
        newItem.innerHTML = `
            <input type="text" name="tools[]" class="form-input flex-1" placeholder="Nama alat" required>
            <button type="button" onclick="removeTools(this)" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">×</button>
        `;
        container.appendChild(newItem);
    };

    window.removeTools = function(button) {
        const container = document.getElementById('toolsContainer');
        if (container.children.length > 1) {
            button.parentElement.remove();
        }
    };

    window.addInstruksi = function() {
        const container = document.getElementById('instruksiContainer');
        const itemCount = container.children.length + 1;
        const newItem = document.createElement('div');
        newItem.className = 'instruksi-item flex gap-2 mb-2';
        newItem.innerHTML = `
            <span class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-sm font-medium text-red-700">${itemCount}</span>
            <input type="text" name="instruksi[]" class="form-input flex-1" placeholder="Langkah kerja" required>
            <button type="button" onclick="removeInstruksi(this)" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">×</button>
        `;
        container.appendChild(newItem);
    };

    window.removeInstruksi = function(button) {
        const container = document.getElementById('instruksiContainer');
        if (container.children.length > 1) {
            button.parentElement.remove();
            // Update numbering
            Array.from(container.children).forEach((item, index) => {
                const numberSpan = item.querySelector('span');
                if (numberSpan) {
                    numberSpan.textContent = index + 1;
                }
            });
        }
    };

    window.addKeselamatan = function() {
        const container = document.getElementById('keselamatanContainer');
        const newItem = document.createElement('div');
        newItem.className = 'keselamatan-item flex gap-2 mb-2';
        newItem.innerHTML = `
            <span class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center text-sm font-medium text-yellow-700">⚠</span>
            <input type="text" name="keselamatan[]" class="form-input flex-1" placeholder="Prosedur keselamatan" required>
            <button type="button" onclick="removeKeselamatan(this)" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">×</button>
        `;
        container.appendChild(newItem);
    };

    window.removeKeselamatan = function(button) {
        const container = document.getElementById('keselamatanContainer');
        if (container.children.length > 1) {
            button.parentElement.remove();
        }
    };

    // Loading state on submit
    const form = document.getElementById('practicalForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
        });
    }
});
</script>
@endpush
@endsection
