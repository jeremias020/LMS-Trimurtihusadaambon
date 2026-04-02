@extends('siswa.layouts.siswa-layout')

@section('title', 'Rekam Medis - LMS Trimurti Husada')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="font-weight-bold mb-4">Rekam Medis (Sakit & Izin)</h2>
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
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicalRecords as $record)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($record->tanggal)->format('d M Y') }}</td>
                                        <td>
                                            @if($record->status == 'sakit')
                                                <span class="badge bg-warning">Sakit</span>
                                            @elseif($record->status == 'izin')
                                                <span class="badge bg-info">Izin</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $record->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $record->keterangan ?? '-' }}</td>
                                        <td>{{ $record->created_at?->format('d M Y H:i') ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="alert alert-info mt-3">
                                                <i class="fas fa-info-circle"></i> Tidak ada data rekam medis.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($medicalRecords->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $medicalRecords->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
