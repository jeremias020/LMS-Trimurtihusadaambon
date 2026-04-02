@extends('layouts.guru')

@section('title', 'Laporan Detail Praktikum - ' . ($practical->title ?? 'Praktikum'))

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Laporan Detail Praktikum</h1>
    <p class="text-gray-600">{{ $practical->title ?? 'Praktikum' }} - {{ $practical->subject->name ?? 'Mata Pelajaran' }}</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $practical->title ?? 'Praktikum' }}</h2>
                <p class="text-gray-600">{{ $practical->subject->name ?? 'Mata Pelajaran' }} - Kelas {{ $practical->class ?? '-' }}</p>
            </div>
            <div class="flex space-x-2">
                <button onclick="window.print()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 flex items-center transition" title="Cetak laporan">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" role="img" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"></path>
                    </svg>
                    Cetak
                </button>
                <button onclick="exportPDF(event)" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center transition" title="Ekspor ke PDF">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" role="img" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Ekspor PDF
                </button>
            </div>
        </div>
    </div>

    <div class="px-6 py-4">
        <!-- Practical Information -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ $practical->participants_count ?? 0 }}</div>
                <div class="text-sm text-blue-800">Total Peserta</div>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ $practical->scores_count ?? 0 }}</div>
                <div class="text-sm text-green-800">Telah Dinilai</div>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-purple-600">{{ $practical->average_score ?? 0 }}/100</div>
                <div class="text-sm text-purple-800">Nilai Rata-rata</div>
            </div>
        </div>

        <!-- Score Distribution -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Nilai</h3>
            <canvas id="scoreDistributionChart" height="150"></canvas>
        </div>

        <!-- Participants List -->
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Peserta dan Nilai</h3>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($participants ?? [] as $participant)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ $participant->student->avatar_url ?? asset('images/default-avatar.png') }}" alt="{{ $participant->student->name ?? 'Siswa' }}" onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $participant->student->name ?? 'Nama tidak tersedia' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $participant->student->nis ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $participant->student->class ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $attendanceStatus = $participant->attendance_status ?? null;
                                $attendanceClass = [
                                    'present' => 'bg-green-100 text-green-800',
                                    'late' => 'bg-yellow-100 text-yellow-800',
                                    'absent' => 'bg-red-100 text-red-800',
                                    'excused' => 'bg-blue-100 text-blue-800'
                                ][$attendanceStatus] ?? 'bg-gray-100 text-gray-800';
                                
                                $attendanceText = $attendanceStatus ? ucfirst($attendanceStatus) : 'Belum Absen';
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $attendanceClass }}">
                                {{ $attendanceText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($participant->score)
                            <span class="font-medium {{ ($participant->score ?? 0) >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $participant->score }}/100
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $participant->score ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $participant->score ? 'Telah Dinilai' : 'Belum Dinilai' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada peserta praktikum
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Score Distribution Chart
    const scoreCtx = document.getElementById('scoreDistributionChart');
    if (scoreCtx) {
        const scoreData = @json($scoreDistribution ?? [0, 0, 0, 0, 0, 0]);

        if (typeof Chart !== 'undefined') {
            new Chart(scoreCtx, {
                type: 'bar',
                data: {
                    labels: ['0-49', '50-59', '60-69', '70-79', '80-89', '90-100'],
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: scoreData,
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
    }
});

function exportPDF(event) {
    const button = event.target.closest('button');
    button.disabled = true;
    button.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div> Mengekspor...';

    setTimeout(() => {
        alert('Mengekspor laporan praktikum ke PDF...');
        button.disabled = false;
        button.innerHTML = `
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" role="img" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Ekspor PDF
        `;
    }, 2000);
}
</script>

<style>
@media print {
    .bg-gray-300, .bg-blue-600 {
        display: none !important;
    }

    body * {
        visibility: hidden;
    }
    
    .bg-white, .bg-white * {
        visibility: visible;
    }
    
    .bg-white {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    
    .bg-gray-50 {
        background-color: #f9fafb !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    table {
        page-break-inside: avoid;
    }
    
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
}
</style>
@endsection