@extends('layouts.siswa')

@section('title', 'Nilai Tugas - LMS Trimurti Husada')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2 class="mb-0">Nilai Tugas</h2>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Rata-rata</span>
                        <strong>{{ number_format($stats['average_score'] ?? $stats['assignment_avg'] ?? 0, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Dinilai</span>
                        <strong>{{ $stats['total_graded'] ?? $stats['total_graded_assignments'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Tertinggi</span>
                        <strong>{{ $stats['highest_score'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Terendah</span>
                        <strong>{{ $stats['lowest_score'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tugas</th>
                        <th>Guru</th>
                        <th>Nilai</th>
                        <th>Dinilai Pada</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scores as $item)
                        <tr>
                            <td>{{ optional($item->assignment)->title }}</td>
                            <td>{{ optional(optional($item->assignment)->guru)->name }}</td>
                            <td>{{ $item->score }}</td>
                            <td>{{ optional($item->graded_at ?? $item->updated_at)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Belum ada tugas yang dinilai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $scores->links() }}
        </div>
    </div>
</div>
@endsection
