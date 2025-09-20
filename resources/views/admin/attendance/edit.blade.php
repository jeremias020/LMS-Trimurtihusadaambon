@extends('layouts.admin')

@section('title', 'Edit Absensi')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Data Absensi</h1>
    <p class="text-gray-600">Perbarui data absensi siswa SMK Kesehatan Trimurti Husada</p>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Form Edit Absensi</h2>
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

        <form action="{{ route('admin.attendance.update', $attendance) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <div>
                        <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Siswa <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('siswa_id') border-red-500 @enderror" 
                                id="siswa_id" name="siswa_id" required>
                            <option value="">Pilih Siswa</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('siswa_id', $attendance->siswa_id) == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal') border-red-500 @enderror" 
                               id="tanggal" name="tanggal" value="{{ old('tanggal', $attendance->tanggal->format('Y-m-d')) }}" required>
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" 
                                id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="hadir" {{ old('status', $attendance->status) == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="izin" {{ old('status', $attendance->status) == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ old('status', $attendance->status) == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="alpha" {{ old('status', $attendance->status) == 'alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror" 
                                  id="keterangan" name="keterangan" rows="3" 
                                  placeholder="Masukkan keterangan (opsional)">{{ old('keterangan', $attendance->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="waktu_masuk" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Masuk
                        </label>
                        <input type="time" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('waktu_masuk') border-red-500 @enderror" 
                               id="waktu_masuk" name="waktu_masuk" 
                               value="{{ old('waktu_masuk', $attendance->waktu_masuk ? $attendance->waktu_masuk->format('H:i') : '') }}">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak hadir</p>
                        @error('waktu_masuk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="waktu_keluar" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Keluar
                        </label>
                        <input type="time" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('waktu_keluar') border-red-500 @enderror" 
                               id="waktu_keluar" name="waktu_keluar" 
                               value="{{ old('waktu_keluar', $attendance->waktu_keluar ? $attendance->waktu_keluar->format('H:i') : '') }}">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak keluar</p>
                        @error('waktu_keluar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Data Display -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-800 mb-3">Data Saat Ini:</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Siswa:</span>
                                <span class="font-medium">{{ $attendance->siswa->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal:</span>
                                <span class="font-medium">{{ $attendance->tanggal->format('d F Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium capitalize">{{ $attendance->status }}</span>
                            </div>
                            @if($attendance->waktu_masuk)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Waktu Masuk:</span>
                                <span class="font-medium">{{ $attendance->waktu_masuk->format('H:i') }}</span>
                            </div>
                            @endif
                            @if($attendance->waktu_keluar)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Waktu Keluar:</span>
                                <span class="font-medium">{{ $attendance->waktu_keluar->format('H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Informasi Status:</h4>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li><strong>Hadir:</strong> Siswa hadir di sekolah</li>
                            <li><strong>Izin:</strong> Siswa tidak hadir dengan izin</li>
                            <li><strong>Sakit:</strong> Siswa tidak hadir karena sakit</li>
                            <li><strong>Alpha:</strong> Siswa tidak hadir tanpa keterangan</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-yellow-800 mb-2">Catatan:</h4>
                        <ul class="text-xs text-yellow-700 space-y-1">
                            <li>• Waktu masuk dan keluar hanya untuk status "Hadir"</li>
                            <li>• Durasi akan dihitung otomatis jika kedua waktu diisi</li>
                            <li>• Keterangan dapat diisi untuk semua status</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.attendance.show', $attendance) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                </a>
                <a href="{{ route('admin.attendance.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const waktuMasukInput = document.getElementById('waktu_masuk');
    const waktuKeluarInput = document.getElementById('waktu_keluar');
    
    function toggleTimeInputs() {
        const isHadir = statusSelect.value === 'hadir';
        waktuMasukInput.disabled = !isHadir;
        waktuKeluarInput.disabled = !isHadir;
        
        if (!isHadir) {
            waktuMasukInput.value = '';
            waktuKeluarInput.value = '';
        }
    }
    
    statusSelect.addEventListener('change', toggleTimeInputs);
    
    // Initialize on page load
    toggleTimeInputs();
    
    // Set default time if status is hadir and no time is set
    if (statusSelect.value === 'hadir' && !waktuMasukInput.value) {
        waktuMasukInput.value = '07:00';
    }
});
</script>
@endpush
