@extends('layouts.guru')

@section('title', 'Laporan Absensi')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Laporan Absensi</h1>
    <p class="text-gray-600">Laporan kehadiran siswa per mata pelajaran</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Filter Laporan</h2>
    </div>

    <div class="px-6 py-4">
        <form id="filterForm" method="GET" action="{{ route('guru.laporan.absensi') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="form-group">
                <label for="subject" class="form-label">Mata Pelajaran</label>
                <select name="subject" id="subject" class="form-input">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="class" class="form-label">Kelas</label>
                <select name="class" id="class" class="form-input">
                    <option value="">Semua Kelas</option>
                    <option value="X" {{ request('class') == 'X' ? 'selected' : '' }}>Kelas X</option>
                    <option value="XI" {{ request('class') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                    <option value="XII" {{ request('class') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                </select>
            </div>

            <div class="form-group">
                <label for="month" class="form-label">Bulan</label>
                <select name="month" id="month" class="form-input">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->locale('id')->monthName }}
                    </option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="year" class="form-label">Tahun</label>
                <select name="year" id="year" class="form-input">
                    <option value="">Semua Tahun</option>
                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                    <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                    @endfor
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end space-x-2">
                <button type="button" onclick="resetFilters()" class="btn-secondary">
                    Reset
                </button>
                <button type="submit" class="btn-primary">
                    Generate Laporan
                </button>
                <button type="button" onclick="exportReport()" class="btn-success">
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
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $stats['present_count'] ?? 0 }}</h2>
                <p class="text-gray-600">Hadir</p>
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
                <h2 class="text-2xl font-bold text-gray-800">{{ $stats['late_count'] ?? 0 }}</h2>
                <p class="text-gray-600">Terlambat</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $stats['absent_count'] ?? 0 }}</h2>
                <p class="text-gray-600">Tidak Hadir</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $stats['attendance_rate'] ?? 0 }}%</h2>
                <p class="text-gray-600">Tingkat Kehadiran</p>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Summary -->
<div class="bg-white rounded-lg shadow overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Ringkasan Kehadiran</h2>
    </div>
    <div class="px-6 py-4">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pertemuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terlambat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tidak Hadir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($attendanceSummary as $summary)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $summary->subject_name ?? 'Tidak ada data' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $summary->class ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $summary->total_sessions ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $summary->present_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $summary->late_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $summary->absent_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $attendanceRate = $summary->attendance_rate ?? 0;
                                $bgColor = $attendanceRate >= 90 ? 'bg-green-100 text-green-800' : ($attendanceRate >= 75 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bgColor }}">
                                {{ $attendanceRate }}%
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data absensi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Student Attendance Details -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Detail Kehadiran Siswa</h2>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-600">{{ $students->total() ?? 0 }} siswa ditemukan</span>
        </div>
    </div>

    <div class="px-6 py-4">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terlambat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tidak Hadir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($students as $student)
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
                            {{ $student->subject_name ?? 'Tidak ada mata pelajaran' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $student->present_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $student->late_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $student->absent_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $student->excused_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $attendanceRate = $student->attendance_rate ?? 0;
                                $bgColor = $attendanceRate >= 90 ? 'bg-green-100 text-green-800' : ($attendanceRate >= 75 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bgColor }}">
                                {{ $attendanceRate }}%
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data siswa
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="mt-4">
            {{ $students->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Charts Placeholder -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Kehadiran</h3>
        <p class="text-gray-500">Chart.js dinonaktifkan sementara</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 text-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Kehadiran Bulanan</h3>
        <p class="text-gray-500">Chart.js dinonaktifkan sementara</p>
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filterForm').reset();
    document.getElementById('filterForm').submit();
}

function exportReport() {
    alert('Fitur ekspor akan segera tersedia');
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Halaman laporan absensi dimuat');
});
</script>

<style>
@media print {
    .btn-secondary, .btn-primary, .btn-success {
        display: none !important;
    }

    .bg-gray-50, .bg-green-100, .bg-yellow-100, .bg-red-100, .bg-blue-100 {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .shadow {
        box-shadow: none !important;
    }

    .rounded-lg {
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }
}
</style>
@endsection