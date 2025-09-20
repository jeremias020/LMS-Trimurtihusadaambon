@extends('layouts.admin')

@section('title', 'Edit Praktikum')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Praktikum</h1>
    <p class="text-gray-600">Ubah informasi praktikum {{ $practical->judul }}</p>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Form Edit Praktikum</h2>
    </div>
    <div class="p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <div>
                        <p class="font-medium">Terdapat kesalahan dalam form:</p>
                        <ul class="mt-1 list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.practicals.update', $practical) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Praktikum <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('judul') border-red-500 @enderror" 
                               id="judul" name="judul" value="{{ old('judul', $practical->judul) }}" required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Praktikum <span class="text-red-500">*</span>
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') border-red-500 @enderror" 
                                  id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi', $practical->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instruksi" class="block text-sm font-medium text-gray-700 mb-2">
                            Instruksi Praktikum
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('instruksi') border-red-500 @enderror" 
                                  id="instruksi" name="instruksi" rows="4" 
                                  placeholder="Masukkan instruksi detail untuk praktikum">{{ old('instruksi', $practical->instruksi) }}</textarea>
                        @error('instruksi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tools" class="block text-sm font-medium text-gray-700 mb-2">
                                Alat & Peralatan
                            </label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tools') border-red-500 @enderror" 
                                      id="tools" name="tools" rows="3" 
                                      placeholder="Daftar alat dan peralatan yang dibutuhkan">{{ old('tools', $practical->tools) }}</textarea>
                            @error('tools')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bahan" class="block text-sm font-medium text-gray-700 mb-2">
                                Bahan & Material
                            </label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bahan') border-red-500 @enderror" 
                                      id="bahan" name="bahan" rows="3" 
                                      placeholder="Daftar bahan dan material yang dibutuhkan">{{ old('bahan', $practical->bahan) }}</textarea>
                            @error('bahan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="guru_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Guru Penanggung Jawab <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('guru_id') border-red-500 @enderror" 
                                id="guru_id" name="guru_id" required>
                            <option value="">Pilih Guru</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" 
                                        {{ old('guru_id', $practical->guru_id) == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->name }} ({{ $guru->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal & Waktu <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal') border-red-500 @enderror" 
                               id="tanggal" name="tanggal" 
                               value="{{ old('tanggal', $practical->tanggal->format('Y-m-d\TH:i')) }}" required>
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('lokasi') border-red-500 @enderror" 
                               id="lokasi" name="lokasi" value="{{ old('lokasi', $practical->lokasi) }}" required>
                        @error('lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="durasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Durasi (menit) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('durasi') border-red-500 @enderror" 
                               id="durasi" name="durasi" 
                               value="{{ old('durasi', $practical->durasi) }}" 
                               min="30" max="480" required>
                        <p class="mt-1 text-xs text-gray-500">Minimal 30 menit, maksimal 8 jam (480 menit)</p>
                        @error('durasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                   id="is_published" name="is_published" 
                                   {{ old('is_published', $practical->is_published) ? 'checked' : '' }}>
                            <label for="is_published" class="ml-2 block text-sm text-gray-700">
                                Publikasikan Praktikum
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Centang untuk mempublikasikan praktikum kepada siswa</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.practicals.show', $practical) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                </a>
                <a href="{{ route('admin.practicals.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i> Update Praktikum
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to today
    const today = new Date().toISOString().slice(0, 16);
    document.getElementById('tanggal').setAttribute('min', today);
});
</script>
@endpush
