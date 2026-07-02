@extends('layouts.siswa')

@section('title', 'Rekap Absensi')
@section('siswa-page-title', 'Rekap Absensi')
@section('page-subtitle', 'Pantau kehadiran Anda per bulan')

@section('siswa-breadcrumb')
    <li class="breadcrumb-item active">Absensi</li>
@endsection

@section('page-actions')
    <a href="{{ route('siswa.absensi.export', ['month' => $month, 'year' => $year]) }}"
       class="btn btn-outline-success btn-sm">
        <i class="fas fa-download me-1"></i>Export CSV
    </a>
@endsection

@section('content')

@php
    $pct         = $monthlyStats['attendance_rate'];
    $hadir       = $monthlyStats['hadir'];
    $izin        = $monthlyStats['izin'];
    $sakit       = $monthlyStats['sakit'];
    $alpa        = $monthlyStats['alpa'];
    $total       = $monthlyStats['total'];
    $workingDays = $monthlyStats['working_days'];
    $namaBulan   = \Carbon\Carbon::createFromDate($year, $month, 1)->locale('id')->monthName;
    $pctColor    = $pct >= 80 ? 'success' : ($pct >= 60 ? 'warning' : 'danger');
    $pctLabel    = $pct >= 80 ? 'Kehadiran Baik' : ($pct >= 60 ? 'Perlu Ditingkatkan' : 'Kehadiran Rendah');
@endphp

{{-- Periode Banner + Filter --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
            <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="text-white">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Periode: {{ ucfirst($namaBulan) }} {{ $year }}
                    </h6>
                    <small class="opacity-75">{{ $workingDays }} hari kerja di bulan ini</small>
                </div>
                <form action="{{ route('siswa.absensi.index') }}" method="GET"
                      class="d-flex align-items-center gap-2 flex-wrap">
                    <select name="month" class="form-select form-select-sm" style="min-width:130px;">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate(null, $i, 1)->locale('id')->monthName }}
                            </option>
                        @endfor
                    </select>
                    <select name="year" class="form-select form-select-sm" style="min-width:90px;">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="btn btn-light btn-sm px-3">
                        <i class="fas fa-search me-1"></i>Lihat
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- 4 stat cards --}}
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-3 p-3 bg-success bg-opacity-10 flex-shrink-0">
                    <i class="fas fa-check-circle text-success fa-lg"></i>
                </div>
                <div>
                    <div class="h3 fw-bold text-success mb-0">{{ $hadir }}</div>
                    <small class="text-muted">Hadir</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-3 p-3 bg-info bg-opacity-10 flex-shrink-0">
                    <i class="fas fa-clock text-info fa-lg"></i>
                </div>
                <div>
                    <div class="h3 fw-bold text-info mb-0">{{ $izin }}</div>
                    <small class="text-muted">Izin</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10 flex-shrink-0">
                    <i class="fas fa-thermometer-half text-warning fa-lg"></i>
                </div>
                <div>
                    <div class="h3 fw-bold text-warning mb-0">{{ $sakit }}</div>
                    <small class="text-muted">Sakit</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-3 p-3 bg-danger bg-opacity-10 flex-shrink-0">
                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                </div>
                <div>
                    <div class="h3 fw-bold text-danger mb-0">{{ $alpa }}</div>
                    <small class="text-muted">Alpa</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Persentase + Grafik --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-chart-pie me-2 text-primary"></i>Persentase Kehadiran</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center py-4">
                <div class="position-relative mb-3" style="width:120px;height:120px;">
                    <svg viewBox="0 0 36 36" width="120" height="120">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e9ecef" stroke-width="3"/>
                        <circle cx="18" cy="18" r="15.9" fill="none"
                                stroke="{{ $pct >= 80 ? '#198754' : ($pct >= 60 ? '#ffc107' : '#dc3545') }}"
                                stroke-width="3"
                                stroke-dasharray="{{ min($pct, 100) }}, 100"
                                stroke-linecap="round"
                                transform="rotate(-90 18 18)"/>
                    </svg>
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <div class="h4 fw-bold mb-0 text-{{ $pctColor }}">{{ $pct }}%</div>
                    </div>
                </div>
                <div class="badge bg-{{ $pctColor }} bg-opacity-10 text-{{ $pctColor }} px-3 py-2 mb-2">{{ $pctLabel }}</div>
                <small class="text-muted">{{ $hadir }} hadir dari {{ $workingDays }} hari kerja</small>
                @if($total > 0)
                    <div class="progress w-100 mt-3" style="height:14px;border-radius:8px;">
                        @if($hadir > 0)<div class="progress-bar bg-success" style="width:{{ round($hadir/$total*100) }}%" title="Hadir {{ $hadir }}"></div>@endif
                        @if($izin > 0)<div class="progress-bar bg-info" style="width:{{ round($izin/$total*100) }}%" title="Izin {{ $izin }}"></div>@endif
                        @if($sakit > 0)<div class="progress-bar bg-warning" style="width:{{ round($sakit/$total*100) }}%" title="Sakit {{ $sakit }}"></div>@endif
                        @if($alpa > 0)<div class="progress-bar bg-danger" style="width:{{ round($alpa/$total*100) }}%" title="Alpa {{ $alpa }}"></div>@endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-chart-bar me-2 text-info"></i>Grafik Kehadiran</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:200px;">
                @if($total > 0)
                    <canvas id="attendanceChart" style="max-height:200px;"></canvas>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-chart-bar fa-3x opacity-25 mb-2 d-block"></i>
                        <small>Tidak ada data untuk periode ini</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Tabel Detail --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">
            <i class="fas fa-list-alt me-2 text-primary"></i>
            Detail Absensi — {{ ucfirst($namaBulan) }} {{ $year }}
        </h6>
        <span class="badge bg-secondary">{{ $total }} catatan</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Mata Pelajaran</th>
                        <th class="text-center">Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        @php $st = strtolower($attendance->status ?? ''); @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $attendance->date->format('d M Y') }}</div>
                                <small class="text-muted">{{ $attendance->date->locale('id')->dayName }}</small>
                            </td>
                            <td>
                                @if($attendance->subject)
                                    <span class="fw-semibold">{{ $attendance->subject->name ?? $attendance->subject->nama ?? '—' }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(in_array($st, ['hadir', 'present']))
                                    <span class="badge bg-success px-3">Hadir</span>
                                @elseif($st === 'izin')
                                    <span class="badge bg-info text-white px-3">Izin</span>
                                @elseif(in_array($st, ['sakit', 'sick']))
                                    <span class="badge bg-warning text-dark px-3">Sakit</span>
                                @else
                                    <span class="badge bg-danger px-3">Alpa</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $attendance->note ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                <h6 class="text-muted">Tidak ada data absensi</h6>
                                <small class="text-muted">untuk {{ ucfirst($namaBulan) }} {{ $year }}</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($total > 0)
    <div class="card-footer bg-white border-top">
        <div class="row g-2 small text-muted">
            <div class="col-auto"><span class="badge bg-success me-1">{{ $hadir }}</span>Hadir</div>
            <div class="col-auto"><span class="badge bg-info me-1">{{ $izin }}</span>Izin</div>
            <div class="col-auto"><span class="badge bg-warning text-dark me-1">{{ $sakit }}</span>Sakit</div>
            <div class="col-auto"><span class="badge bg-danger me-1">{{ $alpa }}</span>Alpa</div>
            <div class="col-auto ms-auto fw-semibold text-dark">Total: {{ $total }}</div>
        </div>
    </div>
    @endif
</div>

@push('js')
@if($total > 0)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('attendanceChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
            datasets: [{
                label: 'Jumlah',
                data: [{{ $hadir }}, {{ $izin }}, {{ $sakit }}, {{ $alpa }}],
                backgroundColor: ['rgba(25,135,84,.8)','rgba(13,202,240,.8)','rgba(255,193,7,.8)','rgba(220,53,69,.8)'],
                borderColor: ['rgba(25,135,84,1)','rgba(13,202,240,1)','rgba(255,193,7,1)','rgba(220,53,69,1)'],
                borderWidth: 2, borderRadius: 8,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,.04)' } },
                x: { ticks: { font: { weight: '600' } }, grid: { display: false } }
            },
            animation: { duration: 600 }
        }
    });
});
</script>
@endif
@endpush

@endsection
