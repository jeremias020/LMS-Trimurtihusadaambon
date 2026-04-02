@extends('siswa.layouts.siswa-layout')

@section('title', 'Absensi - LMS Trimurti Husada')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="font-weight-bold mb-4">Rekap Absensi</h2>
        </div>
    </div>

    @php
        $attendancePercentage = $monthlyStats['attendance_rate'] ?? ($monthlyStats['percentage'] ?? 0);
        $breakdown = $monthlyStats['breakdown'] ?? collect();
        $hadirCount = optional($breakdown->where('status', 'hadir')->first())->count ?? 0;
        $izinCount = optional($breakdown->where('status', 'izin')->first())->count ?? 0;
        $sakitCount = optional($breakdown->where('status', 'sakit')->first())->count ?? 0;
        $alpaCount = optional($breakdown->where('status', 'alpha')->first())->count ?? 0;
    @endphp

    <div class="row mb-3">
        <div class="col-md-4">
            <form action="{{ route('siswa.absensi.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <label for="month" class="form-label">Bulan</label>
                    <select class="form-control" id="month" name="month" aria-label="Pilih bulan">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $i == ($month ?? now()->month) ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="year" class="form-label">Tahun</label>
                    <select class="form-control" id="year" name="year" aria-label="Pilih tahun">
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ $i == ($year ?? now()->year) ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h6>Persentase Kehadiran</h6>
                    <h3 class="font-weight-bold {{ $attendancePercentage >= 80 ? 'text-success' : ($attendancePercentage >= 60 ? 'text-warning' : 'text-danger') }}">
                        {{ $attendancePercentage }}%
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <div class="btn-group w-100" role="group">
                <a href="{{ route('siswa.absensi.export', ['month' => $month, 'year' => $year]) }}"
                   class="btn btn-danger flex-grow-1 export-btn"
                   title="Export ke PDF"
                   data-format="pdf">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </a>
                <a href="{{ route('siswa.absensi.export', ['month' => $month, 'year' => $year]) }}"
                   class="btn btn-success flex-grow-1 export-btn"
                   title="Export ke Excel"
                   data-format="excel">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->tanggal?->format('d M Y') ?? '-' }}</td>
                                        <td>{{ $attendance->subject?->name ?? '-' }}</td>
                                        <td>
                                            @if($attendance->status == 'hadir')
                                                <span class="badge bg-success">Hadir</span>
                                            @elseif($attendance->status == 'izin')
                                                <span class="badge bg-info">Izin</span>
                                            @elseif($attendance->status == 'sakit')
                                                <span class="badge bg-warning">Sakit</span>
                                            @else
                                                <span class="badge bg-danger">Alpa</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->status == 'hadir')
                                                {{ $attendance->waktu_masuk ?? '-' }} - {{ $attendance->waktu_keluar ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $attendance->keterangan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="alert alert-info mt-3">
                                                <i class="fas fa-info-circle"></i> Tidak ada data absensi untuk periode yang dipilih.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Kehadiran Bulanan</h6>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" width="400" height="200"></canvas>
                    <div id="chartFallback" class="text-center text-muted d-none">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <p>Grafik tidak dapat ditampilkan. Pastikan JavaScript diaktifkan.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ringkasan Kehadiran</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-success p-2">Hadir: {{ $hadirCount }}</span>
                    </div>
                    <div class="mb-3">
                        <span class="badge bg-info p-2">Izin: {{ $izinCount }}</span>
                    </div>
                    <div class="mb-3">
                        <span class="badge bg-warning p-2">Sakit: {{ $sakitCount }}</span>
                    </div>
                    <div class="mb-3">
                        <span class="badge bg-danger p-2">Alpa: {{ $alpaCount }}</span>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h5>Total: {{ $monthlyStats['total'] ?? ($hadirCount + $izinCount + $sakitCount + $alpaCount) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Loading state for filter form
    const filterForm = document.querySelector('form[action="{{ route('siswa.absensi.index') }}"]');
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memuat...';
            }
        });
    }

    // Loading state for export buttons
    document.querySelectorAll('.export-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const format = this.getAttribute('data-format');
            this.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Exporting ${format.toUpperCase()}...`;
            this.classList.add('disabled');
        });
    });

    // Chart
    const ctx = document.getElementById('attendanceChart');
    if (ctx && window.Chart) {
        const chartData = {!! json_encode([
            $hadirCount,
            $izinCount,
            $sakitCount,
            $alpaCount,
        ]) !!};

        const attendanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
                datasets: [{
                    label: 'Jumlah Kehadiran',
                    data: chartData,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    } else if (ctx) {
        // Fallback if Chart.js not loaded
        ctx.style.display = 'none';
        document.getElementById('chartFallback').classList.remove('d-none');
    }
});
</script>
@endpush
 
