@extends('layouts.admin')

@section('title', 'Detail Praktikum')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Praktikum</h1>
            <p class="text-gray-600">Informasi lengkap praktikum {{ $practical->judul }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.practicals.edit', $practical) }}" 
               class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            @if($practical->tanggal >= now())
            <button type="button" 
                    class="px-4 py-2 {{ $practical->is_published ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-{{ $practical->is_published ? 'gray' : 'green' }}-500"
                    onclick="togglePublish({{ $practical->id }})">
                <i class="fas fa-{{ $practical->is_published ? 'eye-slash' : 'eye' }} mr-2"></i>
                {{ $practical->is_published ? 'Unpublish' : 'Publish' }}
            </button>
            @endif
            <button type="button" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                    onclick="deletePractical({{ $practical->id }})">
                <i class="fas fa-trash mr-2"></i> Hapus
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Informasi Praktikum</h2>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $practical->judul }}</h3>
                    <div class="flex items-center text-sm text-gray-500 space-x-4">
                        <div class="flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            <span>Dibuat oleh: {{ $practical->guru->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>{{ $practical->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Deskripsi Praktikum</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($practical->deskripsi)) !!}
                        </div>
                    </div>
                </div>

                @if($practical->instruksi)
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Instruksi Praktikum</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($practical->instruksi)) !!}
                        </div>
                    </div>
                </div>
                @endif

                @if($practical->tools)
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Alat & Peralatan</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($practical->tools)) !!}
                        </div>
                    </div>
                </div>
                @endif

                @if($practical->bahan)
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Bahan & Material</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($practical->bahan)) !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Participants & Scores -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800">Peserta & Nilai ({{ $practical->scores->count() }})</h3>
            </div>
            <div class="p-6">
                @if($practical->scores->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komentar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Penilaian</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($practical->scores as $score)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $score->siswa->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($score->score !== null)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $score->score }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Belum dinilai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $score->comment ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($score->created_at)
                                            {{ $score->created_at->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-500 mb-2">Belum ada peserta</p>
                        <p class="text-sm text-gray-400">Praktikum ini belum memiliki peserta</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800">Detail Informasi</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <div>
                        @if($practical->tanggal < now())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Selesai
                            </span>
                        @elseif($practical->is_published)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Draft
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu</label>
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $practical->tanggal < now() ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $practical->tanggal->format('d/m/Y H:i') }}
                        </span>
                        @if($practical->tanggal < now())
                            <p class="mt-1 text-xs text-gray-500">Praktikum telah selesai</p>
                        @else
                            <p class="mt-1 text-xs text-blue-600">{{ $practical->tanggal->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                    <p class="text-sm text-gray-900">{{ $practical->lokasi }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durasi</label>
                    <p class="text-sm text-gray-900">{{ $practical->durasi }} menit ({{ number_format($practical->durasi / 60, 1) }} jam)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Peserta</label>
                    <p class="text-sm text-gray-900">{{ $practical->scores->count() }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Peserta yang Sudah Dinilai</label>
                    <p class="text-sm text-gray-900">{{ $practical->scores->whereNotNull('score')->count() }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rata-rata Nilai</label>
                    <p class="text-sm text-gray-900">
                        @php
                            $scoredParticipants = $practical->scores->whereNotNull('score');
                            $averageScore = $scoredParticipants->count() > 0 ? $scoredParticipants->avg('score') : 0;
                        @endphp
                        {{ number_format($averageScore, 2) }}
                    </p>
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
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus praktikum ini?</p>
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
function deletePractical(id) {
    document.getElementById('deleteForm').action = '{{ route("admin.practicals.destroy", ":id") }}'.replace(':id', id);
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function togglePublish(id) {
    if (confirm('Apakah Anda yakin ingin mengubah status publikasi praktikum ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.practicals.publish", ":id") }}'.replace(':id', id);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
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
