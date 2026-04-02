@extends('siswa.layouts.siswa-layout')

@section('title', 'Export Absensi - LMS Trimurti Husada')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2 class="mb-0">Export Absensi Bulan {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}</h2>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Hari Kerja</span>
                        <strong>{{ $stats['working_days'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Hadir</span>
                        <strong>{{ $stats['present'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Izin/Sakit</span>
                        <strong>{{ $stats['permission'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Alpa</span>
                        <strong>{{ $stats['absent'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
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
                            <td>{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d M Y') }}</td>
                            <td>{{ $attendance->subject?->name ?? '-' }}</td>
                            <td>{{ ucfirst($attendance->status) }}</td>
                            <td>
                                @if($attendance->status === 'hadir')
                                    {{ $attendance->waktu_masuk?->format('H:i') ?? '-' }} - {{ $attendance->waktu_keluar?->format('H:i') ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $attendance->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Tidak ada data untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
