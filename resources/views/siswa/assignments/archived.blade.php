@extends('layouts.siswa')

@section('title', 'Tugas Selesai')
@section('siswa-page-title', 'Tugas Selesai / Arsip')
@section('siswa-breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('siswa.assignments.index') }}">Tugas</a></li>
    <li class="breadcrumb-item active">Arsip</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-archive me-2 text-secondary"></i>Tugas Selesai</h5>
        <a href="{{ route('siswa.assignments.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
    <div class="card-body p-0">
        @if($assignments->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-check-circle fa-3x mb-3 opacity-50"></i>
                <p class="mb-0">Belum ada tugas yang selesai.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Judul Tugas</th>
                            <th>Deadline</th>
                            <th>Status Pengumpulan</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $assignment)
                        @php $submission = $assignment->submissions->first(); @endphp
                        <tr>
                            <td class="fw-medium">{{ $assignment->title }}</td>
                            <td class="text-muted">
                                {{ $assignment->due_date ? $assignment->due_date->format('d M Y H:i') : '—' }}
                            </td>
                            <td>
                                @if($submission)
                                    <span class="badge bg-success">Terkumpul</span>
                                @else
                                    <span class="badge bg-danger">Tidak Dikumpulkan</span>
                                @endif
                            </td>
                            <td>
                                @if($submission && $submission->score !== null)
                                    <span class="fw-bold">{{ $submission->score }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('siswa.assignments.show', $assignment->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3">
                {{ $assignments->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
