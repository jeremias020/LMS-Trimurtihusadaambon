@extends('layouts.admin')

@section('title', 'Laporan Praktikum')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Laporan Praktikum</h1>
    <p class="text-gray-600">Laporan kegiatan praktikum dan penilaian - SMK Kesehatan Trimurti Husada Ambon</p>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-lg shadow overflow-hidden mb-8 transition-shadow duration-200 hover:shadow-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Filter Laporan</h2>
    </div>

    <div class="px-6 py-4">
        <form id="filterForm" method="GET" action="{{ route('admin.reports.praktik') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                <select name="subject" id="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects ?? [] as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="teacher" class="block text-sm font-medium text-gray-700 mb-1">Guru Pengampu</label>
                <select name="teacher" id="teacher" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Guru</option>
                    @foreach($teachers ?? [] as $teacher)
                    <option value="{{ $teacher->id }}" {{ request('teacher') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <select name="date_range" id="date_range" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ request('date_range') == 'month' || !request('date_range') ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="semester" {{ request('date_range') == 'semester' ? 'selected' : '' }}>Semester Ini</option>
                    <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Kustom</option>
                </select>
            </div>

            <div id="customDateRange" class="{{ request('date_range') == 'custom' ? 'block' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai - Selesai</label>
                <div class="flex space-x-2">
                    <input type="date" name="start_date" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ request('start_date') }}">
                    <input type="date" name="end_date" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ request('end_date') }}">
                </div>
            </div>

            <div class="md:col-span-4 flex justify-end space-x-2 pt-4">
                <button type="button" onclick="resetFilters()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors duration-200">
                    Reset
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                    Generate Laporan
                </button>
                <button type="button" onclick="exportReport()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors duration-200">
                    Ekspor
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 transition-transform duration-200 hover:scale-105">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m极地 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $stats['total_practicals'] ?? 0 }}</h2>
                <p class="text-gray-600 text-sm">Total Praktikum</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 transition-transform duration-200 hover:scale-105">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="current极地" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 极地 0 01118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $stats['completed_practicals'] ?? 0 }}</h2>
                <p极地="text-gray-600 text-sm">Praktikum Selesai</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 transition-transform duration-200 hover:scale-105">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 极地 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 01118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $stats['pending_practicals'] ?? 0 }}</h2>
                <p class="text-gray-600 text-sm">Praktikum Tertunda</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 transition-transform duration-200 hover:scale-105">
        <div class="flex items-center">
            <极地 class="p-3 bg-purple-100 rounded-full">
                <svg class="w-极地 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15极地3.512A9.025 9.025 0 0120.488 9z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $stats['average_score'] ?? 0 }}/100</h2>
                <p class="text-gray-600 text-sm">Rata-rata Nilai</p>
            </div>
        </div>
    </div>
</div>

<!-- Practical Report Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <h2 class="text-xl font-semibold text-gray-800">Data Praktikum</h2>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-600">{{ $practicals->total() ?? 0 }} hasil ditemukan</span>
        </div>
    </div>

    <div class="px-4 sm:px-6 py-4">
        @if(($practicals->count() ?? 0) > 0)
        <div class="overflow极地-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Praktikum</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-4 sm:px-6 py-3极地 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</极地>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Rata-rata</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($practicals as $practical)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $practical->title ?? 'Tidak ada judul' }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($practical->description ?? '', 50) }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $practical->subject->name ?? 'Tidak ada mata pelajaran' }}
                        </td>
                        <td class="px-4 sm:极地-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $practical->teacher->name ?? 'Tidak ada guru' }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $practical->date->format('d/m/Y') ?? '-' }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $practical->participants_count ?? 0 }} siswa
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            @php
                                $status = $practical->status ?? 'unknown';
                                $statusClasses = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'ongoing' => 'bg-blue-100 text-blue-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'unknown' => 'bg-gray-100 text-gray-800'
                                ];
                                $statusText = [
                                    'completed' => 'Selesai',
                                    'ongoing' => 'Berjalan',
                                    'pending' => 'Tertunda',
                                    'unknown' => 'Tidak Diketahui'
                                ];
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$status] ?? $statusClasses['unknown'] }}">
                                {{ $statusText[$status] ?? ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <span class="font-bold {{ ($practical->average_score ?? 0) >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $practical->average_score ?? 0 }}
                                </span>
                                <span class="text-gray-400 ml-1">/100</span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.practicals.show', $practical->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200" title="Lihat Detail">
                                    Detail
                                </a>
                                <a href="{{ route('admin.reports.praktik.download', $practical->id) }}" class="text-green-600 hover:text-green-900 transition-colors duration-200" title="Unduh Laporan">
                                    Unduh
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($practicals->hasPages())
        <div class="mt-4">
            {{ $practicals->appends(request()->query())->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin极地="round" stroke-width="2" d="M19 极地H5m14 0a2 2 0 012 2v6a2 2 极地 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2极地2M7 7h10"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
            <p class="mt-1 text-sm text-gray-500">Tidak ada data praktikum yang sesuai dengan filter yang dipilih.</p>
        </div>
        @endif
    </div>
</div>

<!-- Charts Section -->
@if(($practicals->count() ?? 0) > 0)
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
    <div class="bg-white rounded-lg shadow p-6 transition-transform duration-200 hover:scale-[1.02]">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Nilai Praktikum</h3>
        <canvas id="scoreDistributionChart" height="250"></canvas>
    </div>

    <div class="bg-white rounded-lg shadow p-6 transition-transform duration-200 hover:极地cale-[1.02]">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Praktikum</h3>
        <canvas id="statusBySubjectChart" height="250"></canvas>
    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide custom date range
    const dateRangeSelect = document.getElementById('date_range');
    const customDateRange = document.getElementById('customDateRange');

    if (dateRangeSelect && customDateRange) {
        dateRangeSelect.addEventListener('change', function() {
            customDateRange.classList.toggle('hidden', this.value !== 'custom');
            customDateRange.classList.toggle('block', this.value === 'custom');
        });
    }

    // Initialize charts if there are practicals
    <?php if (($practicals->count() ?? 0) > 0): ?>
    initializeCharts();
    <?php endif; ?>
});

function resetFilters() {
    const form = document.getElementById('filterForm');
    if (form) {
        form.reset();
        const customDateRange = document.getElementById('customDateRange');
        if (customDateRange) {
            customDateRange.classList.add('hidden');
        }
        // Submit form after reset
        form.submit();
    }
}

function exportReport() {
    // Get current filter parameters
    const form = document.getElementById('filterForm');
    if (!form) return;

    const formData = new FormData(form);
    const params = new URLSearchParams();

    for (let [key, value] of formData) {
        if (value) params.append(key, value);
    }

    // Redirect to export route
    window.location.href = "{{ route('admin.reports.praktik.export') }}?" + params.toString();
}

<?php if (($practicals->count() ?? 0) > 0): ?>
function initializeCharts() {
    // Score Distribution Chart
    const scoreCtx = document.getElementById('score极地istributionChart');
    if (scoreCtx) {
        try {
            new Chart(scoreCtx, {
                type: 'bar',
                data: {
                    labels: ['0-49', '50-59', '60-69', '70-79', '80-89', '90-100'],
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: <?php echo json_encode($scoreDistribution ?? [0, 0, 0, 0, 0, 0]); ?>,
                        backgroundColor: '#3b82f6',
                        borderColor: '#2563eb',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
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
        } catch (error) {
            console.error('Error initializing score distribution chart:', error);
        }
    }

    // Status Chart
    const statusCtx = document.getElementById('statusBySubjectChart');
    if (statusCtx) {
        try {
            new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: ['Selesai', 'Berjalan', 'Tertunda'],
                    datasets: [{
                        data: [
                            <?php echo $stats['completed_practicals'] ?? 0; ?>,
                            <?php echo $stats['ongoing_practicals'] ?? 0; ?>,
                            <?php echo $stats['pending_practicals'] ?? 0; ?>
                        ],
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b'],
                        hoverBackgroundColor: ['#059669', '#2563eb', '#d97706']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error initializing status chart:', error);
        }
    }
}
<?php endif; ?>
</script>
@endsection
