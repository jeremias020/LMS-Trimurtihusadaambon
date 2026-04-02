@extends('layouts.guru')

@section('title', 'Laporan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Laporan</h1>
    <p class="text-gray-600">Akses berbagai laporan mengajar</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Attendance Reports Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="ml-4 text-lg font-semibold text-gray-800">Laporan Absensi</h3>
            </div>
            <p class="text-gray-600 mb-4">Laporan kehadiran siswa per mata pelajaran</p>
            <div class="space-y-2">
                <a href="{{ route('guru.laporan.absensi') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Absensi Harian">
                    • Laporan Absensi Harian
                </a>
                <a href="{{ route('guru.laporan.absensi.bulanan') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Absensi Bulanan">
                    • Laporan Absensi Bulanan
                </a>
                <a href="{{ route('guru.laporan.absensi.semester') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Absensi Semester">
                    • Laporan Absensi Semester
                </a>
            </div>
        </div>
    </div>

    <!-- Practical Reports Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <h3 class="ml-4 text-lg font-semibold text-gray-800">Laporan Praktikum</h3>
            </div>
            <p class="text-gray-600 mb-4">Laporan kegiatan praktikum dan penilaian</p>
            <div class="space-y-2">
                <a href="{{ route('guru.reports.practical') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Praktikum">
                    • Laporan Praktikum
                </a>
            </div>
        </div>
    </div>

    <!-- Assignment Reports Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="ml-4 text-lg font-semibold text-gray-800">Laporan Tugas</h3>
            </div>
            <p class="text-gray-600 mb-4">Laporan penyelesaian dan penilaian tugas</p>
            <div class="space-y-2">
                <a href="{{ route('guru.laporan.tugas') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Penyelesaian Tugas">
                    • Laporan Penyelesaian Tugas
                </a>
                <a href="{{ route('guru.laporan.tugas.nilai') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Nilai Tugas">
                    • Laporan Nilai Tugas
                </a>
                <a href="{{ route('guru.laporan.tugas.terlambat') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Keterlambatan">
                    • Laporan Keterlambatan
                </a>
            </div>
        </div>
    </div>

    <!-- Grade Reports Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="ml-4 text-lg font-semibold text-gray-800">Laporan Nilai</h3>
            </div>
            <p class="text-gray-600 mb-4">Laporan nilai siswa per mata pelajaran</p>
            <div class="space-y-2">
                <a href="{{ route('guru.laporan.nilai') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Nilai Harian">
                    • Laporan Nilai Harian
                </a>
                <a href="{{ route('guru.laporan.nilai.mid') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Nilai MID">
                    • Laporan Nilai MID
                </a>
                <a href="{{ route('guru.laporan.nilai.semester') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Nilai Semester">
                    • Laporan Nilai Semester
                </a>
            </div>
        </div>
    </div>

    <!-- Student Reports Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-pink-100 rounded-full">
                    <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="ml-4 text-lg font-semibold text-gray-800">Laporan Siswa</h3>
            </div>
            <p class="text-gray-600 mb-4">Laporan perkembangan individual siswa</p>
            <div class="space-y-2">
                <a href="{{ route('guru.laporan.siswa') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Perkembangan Siswa">
                    • Laporan Perkembangan Siswa
                </a>
                <a href="{{ route('guru.laporan.siswa.detail') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Detail Siswa">
                    • Laporan Detail Siswa
                </a>
                <a href="{{ route('guru.laporan.siswa.prestasi') }}" class="block text-blue-600 hover:text-blue-800" aria-label="Laporan Prestasi Siswa">
                    • Laporan Prestasi Siswa
                </a>
            </div>
        </div>
    </div>

    <!-- Export Reports Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-indigo-100 rounded-full">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </div>
                <h3 class="ml-4 text-lg font-semibold text-gray-800">Ekspor Laporan</h3>
            </div>
            <p class="text-gray-600 mb-4">Ekspor laporan dalam berbagai format</p>
            <div class="space-y-3">
                <button onclick="exportReport('pdf')" class="w-full bg-red-100 text-red-800 py-2 px-4 rounded-lg text-sm font-medium hover:bg-red-200 transition">
                    Ekspor ke PDF
                </button>
                <button onclick="exportReport('excel')" class="w-full bg-green-100 text-green-800 py-2 px-4 rounded-lg text-sm font-medium hover:bg-green-200 transition">
                    Ekspor ke Excel
                </button>
                <button onclick="exportReport('csv')" class="w-full bg-blue-100 text-blue-800 py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-200 transition">
                    Ekspor ke CSV
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Statistics -->
<div class="mt-8 bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Cepat</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center p-4 bg-blue-50 rounded-lg">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_students'] ?? 0 }}</div>
            <div class="text-sm text-blue-800">Total Siswa</div>
        </div>
        <div class="text-center p-4 bg-green-50 rounded-lg">
            <div class="text-2xl font-bold text-green-600">{{ $stats['attendance_rate'] ?? 0 }}%</div>
            <div class="text-sm text-green-800">Rata-rata Kehadiran</div>
        </div>
        <div class="text-center p-4 bg-yellow-50 rounded-lg">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['average_score'] ?? 0 }}</div>
            <div class="text-sm text-yellow-800">Rata-rata Nilai</div>
        </div>
        <div class="text-center p-4 bg-purple-50 rounded-lg">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['completed_assignments'] ?? 0 }}</div>
            <div class="text-sm text-purple-800">Tugas Selesai</div>
        </div>
    </div>
</div>

<script>
function exportReport(format) {
    // Show export options modal
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
    modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ekspor Laporan</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Jenis Laporan</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="exportType" autocomplete="off">
                        <option value="attendance">Laporan Absensi</option>
                        <option value="practical">Laporan Praktikum</option>
                        <option value="assignment">Laporan Tugas</option>
                        <option value="grade">Laporan Nilai</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="exportPeriod" autocomplete="off">
                        <option value="day">Hari Ini</option>
                        <option value="week">Minggu Ini</option>
                        <option value="month">Bulan Ini</option>
                        <option value="semester">Semester Ini</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeExportModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button onclick="confirmExport('${format}')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Ekspor
                    </button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

function closeExportModal() {
    const modal = document.querySelector('.fixed.inset-0');
    if (modal) {
        modal.remove();
    }
}

function confirmExport(format) {
    const button = event.target;
    button.disabled = true;
    button.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div> Mengekspor...';

    const type = document.getElementById('exportType').value;
    const period = document.getElementById('exportPeriod').value;
    
    // Simulate API call
    setTimeout(() => {
        alert(`Mengekspor laporan ${type} untuk periode ${period} dalam format ${format.toUpperCase()}`);
        closeExportModal();
        
        // Simulate download
        const link = document.createElement('a');
        link.href = '#'; // Would be actual download URL in real implementation
        link.download = `laporan-${type}-${period}.${format}`;
        link.click();

        // Restore button
        button.disabled = false;
        button.innerHTML = 'Ekspor';
    }, 1500);
}
</script>

<style>
.bg-blue-100 { background-color: #dbeafe; }
.bg-green-100 { background-color: #dcfce7; }
.bg-yellow-100 { background-color: #fef3c7; }
.bg-purple-100 { background-color: #e9d5ff; }
.bg-pink-100 { background-color: #fce7f3; }
.bg-indigo-100 { background-color: #e0e7ff; }

.text-blue-600 { color: #2563eb; }
.text-green-600 { color: #16a34a; }
.text-yellow-600 { color: #ca8a04; }
.text-purple-600 { color: #9333ea; }
.text-pink-600 { color: #db2777; }
.text-indigo-600 { color: #4f46e5; }
</style>
@endsection