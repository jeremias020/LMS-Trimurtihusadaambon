@extends('layouts.guru')

@section('title', 'Daftar Penilaian')

@php
// Helper function to get assessment score
function getAssessmentScore($assessment) {
    if (isset($assessment->score) && $assessment->score !== null) {
        return (float) $assessment->score;
    }
    if (isset($assessment->total_nilai) && $assessment->total_nilai !== null) {
        return (float) $assessment->total_nilai;
    }
    return null;
}
@endphp

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Penilaian</h1>
        <div class="text-muted">
            Kelola penilaian tugas dan praktikum siswa
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Penilaian
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allAssessments->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Sudah Dinilai
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allAssessments->where(function($assessment) { return getAssessmentScore($assessment) !== null; })->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Belum Dinilai
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allAssessments->where(function($assessment) { return getAssessmentScore($assessment) === null; })->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Rata-rata Nilai
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($allAssessments->where(function($assessment) { return getAssessmentScore($assessment) !== null; })->avg(function($assessment) { return getAssessmentScore($assessment); }), 1) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('guru.penilaian.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus mr-2"></i>Penilaian Manual
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('guru.penilaian.auto') }}" class="btn btn-success btn-block">
                                <i class="fas fa-magic mr-2"></i>Penilaian Otomatis
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('guru.penilaian.auto.criteria') }}" class="btn btn-info btn-block">
                                <i class="fas fa-list-check mr-2"></i>Penilaian SOP
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assessments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Penilaian</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#" onclick="exportData('excel')">
                        <i class="fas fa-file-excel mr-2"></i>Export Excel
                    </a>
                    <a class="dropdown-item" href="#" onclick="exportData('pdf')">
                        <i class="fas fa-file-pdf mr-2"></i>Export PDF
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" onclick="refreshData()">
                        <i class="fas fa-sync mr-2"></i>Refresh
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($allAssessments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="assessmentsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tipe</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Nilai</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allAssessments as $index => $assessment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($assessment->assignment_id)
                                            <span class="badge badge-primary">Tugas</span>
                                        @else
                                            <span class="badge badge-success">Praktikum</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">
                                                @if($assessment->siswa && $assessment->siswa->avatar)
                                                    <img src="{{ asset('storage/' . $assessment->siswa->avatar) }}" 
                                                         class="rounded-circle" width="32" height="32" alt="Avatar">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                         style="width: 32px; height: 32px; font-size: 12px;">
                                                        {{ substr($assessment->siswa->name ?? 'Siswa', 0, 2) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">{{ $assessment->siswa->name ?? 'Siswa Tidak Diketahui' }}</div>
                                                <div class="text-muted small">{{ $assessment->siswa->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $assessment->siswa->kelas->name ?? 'Belum ada kelas' }}
                                    </td>
                                    <td>
                                        @if($assessment->assignment_id && $assessment->assignment)
                                            {{ $assessment->assignment->subject->name ?? 'Tidak ada mata pelajaran' }}
                                        @elseif($assessment->practical_id && $assessment->practical)
                                            {{ $assessment->practical->subject->name ?? 'Tidak ada mata pelajaran' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($assessment->assignment_id && $assessment->assignment)
                                            {{ $assessment->assignment->title }}
                                        @elseif($assessment->practical_id && $assessment->practical)
                                            {{ $assessment->practical->judul }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $score = getAssessmentScore($assessment);
                                        @endphp
                                        @if($score !== null)
                                            <span class="badge badge-success">Sudah Dinilai</span>
                                        @else
                                            <span class="badge badge-warning">Belum Dinilai</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($score !== null)
                                            <span class="font-weight-bold {{ $score >= 80 ? 'text-success' : ($score >= 60 ? 'text-warning' : 'text-danger') }}">
                                                {{ $score }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($assessment->assignment_id && $assessment->assignment)
                                            {{ $assessment->assignment->due_date ? $assessment->assignment->due_date->format('d M Y H:i') : '-' }}
                                        @elseif($assessment->practical_id && $assessment->practical)
                                            {{ $assessment->practical->date ? $assessment->practical->date->format('d M Y') : '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if($assessment->assignment_id)
                                                <a href="{{ route('guru.penilaian.edit', $assessment->id) }}" 
                                                   class="btn btn-primary btn-sm" title="Edit Penilaian">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('guru.assignments.submissions', $assessment->assignment_id) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat Submission">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('guru.penilaian.edit', $assessment->id) }}" 
                                                   class="btn btn-primary btn-sm" title="Edit Penilaian">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('guru.practicals.show', $assessment->practical_id) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat Praktikum">
                                                    <i class="fas fa-flask"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-clipboard-list fa-4x text-gray-300"></i>
                    </div>
                    <h5 class="text-gray-400 mb-3">Belum Ada Penilaian</h5>
                    <p class="text-gray-500 mb-4">
                        Belum ada penilaian yang tersedia. Mulai dengan membuat penilaian baru.
                    </p>
                    <a href="{{ route('guru.penilaian.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>Buat Penilaian Baru
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.text-primary {
    color: #4e73df !important;
}

.text-success {
    color: #1cc88a !important;
}

.text-info {
    color: #36b9cc !important;
}

.text-warning {
    color: #f6c23e !important;
}

.text-danger {
    color: #e74a3b !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-400 {
    color: #b7b7b7 !important;
}

.text-gray-500 {
    color: #858796 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.badge-primary {
    background-color: #4e73df;
}

.badge-success {
    background-color: #1cc88a;
}

.badge-warning {
    background-color: #f6c23e;
}

.badge-info {
    background-color: #36b9cc;
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

.btn-success {
    background-color: #1cc88a;
    border-color: #1cc88a;
}

.btn-success:hover {
    background-color: #17a673;
    border-color: #149b5f;
}

.btn-info {
    background-color: #36b9cc;
    border-color: #36b9cc;
}

.btn-info:hover {
    background-color: #2c9faf;
    border-color: #2589b5;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.table th {
    background-color: #f8f9fc;
    border-top: 1px solid #dee2e6;
}

.btn-group-sm > .btn, .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.rounded-circle {
    border-radius: 50%;
}

.d-flex {
    display: flex;
}

.align-items-center {
    align-items: center;
}

.justify-content-center {
    justify-content: center;
}

.mr-2 {
    margin-right: 0.5rem;
}

.font-weight-bold {
    font-weight: 700;
}

.font-weight-bold {
    font-weight: 700;
}

.text-uppercase {
    text-transform: uppercase;
}

.text-xs {
    font-size: 0.75rem;
}

.text-muted {
    color: #858796;
}

.small {
    font-size: 0.875rem;
}
</style>

<!-- JavaScript -->
<script>
function getAssessmentScore(assessment) {
    if (assessment.score !== null && assessment.score !== undefined) {
        return assessment.score;
    }
    if (assessment.total_nilai !== null && assessment.total_nilai !== undefined) {
        return assessment.total_nilai;
    }
    return null;
}

function exportData(format) {
    const url = `{{ route('guru.penilaian.export') }}?format=${format}`;
    window.open(url, '_blank');
}

function refreshData() {
    location.reload();
}

// Initialize DataTables
$(document).ready(function() {
    $('#assessmentsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });
});
</script>
@endsection
