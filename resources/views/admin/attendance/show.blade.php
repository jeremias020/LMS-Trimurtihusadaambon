@extends('layouts.admin')

@section('title', 'Detail Absensi')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Detail Absensi</h1>
    <p class="text-gray-600">Informasi lengkap data absensi siswa</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Informasi Absensi</h2>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama Siswa</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->siswa->name ?? 'N/A' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->siswa->email ?? 'N/A' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->tanggal->format('d F Y') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @php
                                $statusColors = [
                                    'hadir' => 'bg-green-100 text-green-800',
                                    'izin' => 'bg-blue-100 text-blue-800',
                                    'sakit' => 'bg-yellow-100 text-yellow-800',
                                    'alpha' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$attendance->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Waktu Masuk</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $attendance->waktu_masuk ? $attendance->waktu_masuk->format('H:i') : '-' }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Waktu Keluar</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $attendance->waktu_keluar ? $attendance->waktu_keluar->format('H:i') : '-' }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Durasi</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->duration_formatted }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->keterangan ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Student Information -->
        @if($attendance->siswa)
        <div class="bg-white rounded-lg shadow mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Informasi Siswa</h2>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->siswa->name }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->siswa->email }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Role</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($attendance->siswa->role) }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->siswa->created_at->format('d F Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Actions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Aksi</h3>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('admin.attendance.edit', $attendance) }}" 
                   class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <i class="fas fa-edit mr-2"></i> Edit Absensi
                </a>
                
                <a href="{{ route('admin.attendance.index') }}" 
                   class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
                
                <button type="button" onclick="deleteAttendance({{ $attendance->id }})" 
                        class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <i class="fas fa-trash mr-2"></i> Hapus Absensi
                </button>
            </div>
        </div>

        <!-- Status Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Status</h3>
            </div>
            <div class="p-6">
                @php
                    $statusInfo = [
                        'hadir' => [
                            'color' => 'text-green-600',
                            'icon' => 'fas fa-check-circle',
                            'description' => 'Siswa hadir di sekolah pada tanggal yang ditentukan'
                        ],
                        'izin' => [
                            'color' => 'text-blue-600',
                            'icon' => 'fas fa-info-circle',
                            'description' => 'Siswa tidak hadir dengan izin yang sah'
                        ],
                        'sakit' => [
                            'color' => 'text-yellow-600',
                            'icon' => 'fas fa-exclamation-triangle',
                            'description' => 'Siswa tidak hadir karena sakit'
                        ],
                        'alpha' => [
                            'color' => 'text-red-600',
                            'icon' => 'fas fa-times-circle',
                            'description' => 'Siswa tidak hadir tanpa keterangan'
                        ]
                    ];
                    $currentStatus = $statusInfo[$attendance->status] ?? $statusInfo['alpha'];
                @endphp
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="{{ $currentStatus['icon'] }} {{ $currentStatus['color'] }} text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($attendance->status) }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $currentStatus['description'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Information -->
        @if($attendance->waktu_masuk || $attendance->waktu_keluar)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Waktu</h3>
            </div>
            <div class="p-6">
                @if($attendance->waktu_masuk)
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm font-medium text-gray-500">Masuk:</span>
                    <span class="text-sm text-gray-900">{{ $attendance->waktu_masuk->format('H:i') }}</span>
                </div>
                @endif
                
                @if($attendance->waktu_keluar)
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm font-medium text-gray-500">Keluar:</span>
                    <span class="text-sm text-gray-900">{{ $attendance->waktu_keluar->format('H:i') }}</span>
                </div>
                @endif
                
                @if($attendance->waktu_masuk && $attendance->waktu_keluar)
                <div class="border-t border-gray-200 pt-2 mt-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Durasi:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $attendance->duration_formatted }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- System Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Sistem</h3>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Dibuat:</span>
                    <span class="text-sm text-gray-900">{{ $attendance->created_at->format('d/m/Y H:i') }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Diperbarui:</span>
                    <span class="text-sm text-gray-900">{{ $attendance->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div class="mt-2 px-7 py-3">
                <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus data absensi ini?</p>
                    <p class="text-xs text-red-500 mt-1">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <div class="flex space-x-3">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300" onclick="closeDeleteModal()">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
function deleteAttendance(id) {
    document.getElementById('deleteForm').action = '{{ route("admin.attendance.destroy", ":id") }}'.replace(':id', id);
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const deleteModal = document.getElementById('deleteModal');
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
});
</script>
@endpush
