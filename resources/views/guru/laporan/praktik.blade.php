@extends('layouts.guru')

@section('title', 'Laporan Praktikum')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Laporan Praktikum</h1>
    <p class="text-gray-600">Laporan kegiatan praktikum dan penilaian siswa</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Filter Laporan</h2>
    </div>
    
    <div class="px-6 py-4">
        <form id="filterForm" method="GET" action="{{ route('guru.laporan.praktik') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                <select name="subject" id="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select name="class" id="class" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kelas</option>
                    <option value="X" {{ request('class') == 'X' ? 'selected' : '' }}>Kelas X</option>
                    <option value="XI" {{ request('class') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                    <option value="XII" {{ request('class') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                </select>
            </div>
            
            <div>
                <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <select name="date_range" id="date_range" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ request('date_range') == 'month' || !request('date_range') ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="semester" {{ request('date_range') == 'semester' ? 'selected' : '' }}>Semester Ini</option>
                    <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Kustom</option>
                </select>
            </div>
            
            <div id="customDateRange" style="display: {{ request('date_range') == 'custom' ? 'block' : 'none' }};">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai - Selesai</label>
                <div class="flex space-x-2">
                    <input type="date" name="start_date" value="{{ request('start_date') ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="date" name="end_date" value="{{ request('end_date') ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="md:col-span-4 flex justify-end space-x-2">
                <button type="button" onclick="resetFilters()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Reset
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Generate Laporan
                </button>
                <button type="button" onclick="exportReport()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    Ekspor
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $stats['total_practicals'] ?? 0 }}</h2>
                <p class="text-gray-600">Total Praktikum</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $stats['completed_practicals'] ?? 0 }}</h2>
                <p class="text-gray-600">Praktikum Selesai</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $stats['average_score'] ?? 0 }}/100</h2>
                <p class="text-gray-600">Rata-rata Nilai</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-full">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $stats['total_participants'] ?? 0 }}</h2>
                <p class="text-gray-600">Total Peserta</p>
            </div>
        </div>
    </div>
</div>

<!-- Practical Report Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Data Praktikum</h2>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-600">{{ ($practicals ?? collect())->count() }} hasil ditemukan</span>
        </div>
    </div>

    <div class="px-6 py-4">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Praktikum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Rata-rata</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($practicals ?? [] as $practical)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $practical->title ?? 'Tidak ada judul' }}</div>
                            <div class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($practical->description ?? '', 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $practical->subject->name ?? 'Tidak ada mata pelajaran' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $practical->class ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $practical->date ? $practical->date->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $practical->participants_count ?? 0 }} siswa
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $status = $practical->status ?? 'pending';
                                $statusClass = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'ongoing' => 'bg-blue-100 text-blue-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800'
                                ][$status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <span class="font-bold {{ ($practical->average_score ?? 0) >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $practical->average_score ?? 0 }}
                                </span>
                                <span class="text-gray-400 ml-1">/100</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('guru.praktikum.show', $practical->id) }}" class="text-blue-600 hover:text-blue-900" title="Detail praktikum">
                                    Detail
                                </a>
                                <a href="{{ route('guru.laporan.praktik.detail', $practical->id) }}" class="text-green-600 hover:text-green-900" title="Lihat laporan detail">
                                    Laporan
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data praktikum
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($practicals) && $practicals->hasPages())
        <div class="mt-4">
            {{ $practicals->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Nilai Praktikum</h3>
        <canvas id="scoreDistributionChart" height="250"></canvas>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Praktikum per Mata Pelajaran</h3>
        <canvas id="statusBySubjectChart" height="250"></canvas>
    </div>
</div>

<!-- Top Performers -->
@if(isset($topPerformers) && $topPerformers->count() > 0)
<div class="bg-white rounded-lg shadow p-6 mt-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Siswa Berprestasi</h3>
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Praktikum Diselesaikan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Rata-rata</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($topPerformers as $index => $student)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $student->avatar_url ?? asset('images/default-avatar.png') }}" alt="{{ $student->name }}" onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $student->name ?? 'Nama tidak tersedia' }}</div>
                                <div class="text-sm text-gray-500">{{ $student->nis ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $student->class ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $student->completed_count ?? 0 }} dari {{ $student->total_count ?? 0 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="font-bold {{ ($student->average_score ?? 0) >= 75 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $student->average_score ?? 0 }}/100
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $rankClass = [
                                0 => 'bg-yellow-100 text-yellow-800',
                                1 => 'bg-gray-100 text-gray-800',
                                2 => 'bg-orange-100 text-orange-800'
                            ][$index] ?? 'bg-blue-100 text-blue-800';
                        @endphp
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rankClass }}">
                            #{{ $index + 1 }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide custom date range
    const dateRangeSelect = document.getElementById('date_range');
    const customDateRange = document.getElementById('customDateRange');
    
    if (dateRangeSelect && customDateRange) {
        dateRangeSelect.addEventListener('change', function() {
            customDateRange.style.display = this.value === 'custom' ? 'block' : 'none';
        });
    }

    // Initialize charts with data from controller
    @if(isset($scoreDistribution) && is_array($scoreDistribution) && isset($statusData) && is_array($statusData))
        const scoreCtx = document.getElementById('scoreDistributionChart');
        if (scoreCtx) {
            new Chart(scoreCtx, {
                type: 'bar',
                 {
                    labels: ['0-49', '50-59', '60-69', '70-79', '80-89', '90-100'],
                    datasets: [{
                        label: 'Jumlah Siswa',
                         @json($scoreDistribution),
                        backgroundColor: '#3b82f6',
                        borderColor: '#2563eb',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Siswa'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Rentang Nilai'
                            }
                        }
                    }
                }
            });
        }

        const statusCtx = document.getElementById('statusBySubjectChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'pie',
                 {
                    labels: ['Selesai', 'Berjalan', 'Tertunda'],
                    datasets: [{
                         @json($statusData),
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b'],
                        hoverBackgroundColor: ['#059669', '#2563eb', '#d97706']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    @endif
});

function resetFilters() {
    const form = document.getElementById('filterForm');
    if (form) {
        form.reset();
        const customDateRange = document.getElementById('customDateRange');
        if (customDateRange) {
            customDateRange.style.display = 'none';
        }
        window.location.href = "{{ route('guru.laporan.praktik') }}";
    }
}

function exportReport() {
    alert('Fitur ekspor akan segera tersedia');
}
</script>
@endsection