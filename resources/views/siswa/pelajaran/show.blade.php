@extends('siswa.layouts.app')

@section('title', 'Detail Pelajaran')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('siswa.dashboard') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('siswa.pelajaran.index') }}">Pelajaran</a>
        </li>
        <li class="breadcrumb-item active">{{ $subject->name }}</li>
    </ol>

    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $subject->name }}</h1>
        <div class="text-muted">
            Detail mata pelajaran
        </div>
    </div>

    <!-- Subject Info Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Mata Pelajaran</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama:</strong> {{ $subject->name }}</p>
                    @if($subject->code)
                        <p><strong>Kode:</strong> {{ $subject->code }}</p>
                    @endif
                    @if($subject->jurusan)
                        <p><strong>Jurusan:</strong> {{ $subject->jurusan->name }}</p>
                    @endif
                </div>
                <div class="col-md-6">
                    @if($subject->description)
                        <p><strong>Deskripsi:</strong></p>
                        <p class="text-muted">{{ $subject->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Activities Tabs -->
    <div class="card shadow">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="activitiesTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="materials-tab" data-toggle="tab" href="#materials" role="tab" aria-controls="materials" aria-selected="true">
                        <i class="fas fa-book mr-2"></i>Materi ({{ $materials->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="assignments-tab" data-toggle="tab" href="#assignments" role="tab" aria-controls="assignments" aria-selected="false">
                        <i class="fas fa-tasks mr-2"></i>Tugas ({{ $assignments->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="practicals-tab" data-toggle="tab" href="#practicals" role="tab" aria-controls="practicals" aria-selected="false">
                        <i class="fas fa-flask mr-2"></i>Praktikum ({{ $practicals->count() }})
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <!-- Tab Content -->
            <div class="tab-content" id="activitiesTabContent">
                <!-- Materials Tab -->
                <div class="tab-pane fade show active" id="materials" role="tabpanel" aria-labelledby="materials-tab">
                    @if($materials->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Judul Materi</th>
                                        <th>Tanggal Publish</th>
                                        <th>Ukuran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $material)
                                        <tr>
                                            <td>{{ $material->title ?? 'Materi ' . $loop->index }}</td>
                                            <td>{{ $material->published_at ? $material->published_at->format('d M Y') : '-' }}</td>
                                            <td>{{ $material->file_size ? number_format($material->file_size / 1024, 2) . ' KB' : '-' }}</td>
                                            <td>
                                                @if($material->file_path)
                                                    <a href="{{ route('siswa.materials.download', $material->id) }}" class="btn btn-sm btn-info" target="_blank">
                                                        <i class="fas fa-download mr-1"></i>Unduh
                                                    </a>
                                                @endif
                                                <a href="{{ route('siswa.materials.show', $material->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye mr-1"></i>Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-alt fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada materi untuk mata pelajaran ini.</p>
                        </div>
                    @endif
                </div>

                <!-- Assignments Tab -->
                <div class="tab-pane fade" id="assignments" role="tabpanel" aria-labelledby="assignments-tab">
                    @if($assignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Judul Tugas</th>
                                        <th>Deadline</th>
                                        <th>Status</th>
                                        <th>Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignments as $assignment)
                                        <tr>
                                            <td>{{ $assignment->title }}</td>
                                            <td>{{ $assignment->due_date ? $assignment->due_date->format('d M Y H:i') : '-' }}</td>
                                            <td>
                                                @if($assignment->submissions->where('siswa_id', Auth::id())->count() > 0)
                                                    <span class="badge badge-success">Sudah Dikumpulkan</span>
                                                @else
                                                    <span class="badge badge-warning">Belum Dikumpulkan</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $submission = $assignment->submissions->where('siswa_id', Auth::id())->first();
                                                @endphp
                                                @if($submission && $submission->score)
                                                    <span class="badge badge-primary">{{ $submission->score }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('siswa.assignments.show', $assignment->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye mr-1"></i>Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-tasks fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada tugas untuk mata pelajaran ini.</p>
                        </div>
                    @endif
                </div>

                <!-- Practicals Tab -->
                <div class="tab-pane fade" id="practicals" role="tabpanel" aria-labelledby="practicals-tab">
                    @if($practicals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Judul Praktikum</th>
                                        <th>Tanggal</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                        <th>Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($practicals as $practical)
                                        <tr>
                                            <td>{{ $practical->judul }}</td>
                                            <td>{{ $practical->date ? $practical->date->format('d M Y') : '-' }}</td>
                                            <td>{{ $practical->lokasi ?? '-' }}</td>
                                            <td>
                                                @if($practical->scores->where('siswa_id', Auth::id())->count() > 0)
                                                    <span class="badge badge-success">Sudah Dikerjakan</span>
                                                @else
                                                    <span class="badge badge-warning">Belum Dikerjakan</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $score = $practical->scores->where('siswa_id', Auth::id())->first();
                                                @endphp
                                                @if($score && $score->score)
                                                    <span class="badge badge-primary">{{ $score->score }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('siswa.praktikum.show', $practical->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye mr-1"></i>Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-flask fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada praktikum untuk mata pelajaran ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
.breadcrumb {
    background-color: transparent;
    padding: 0.75rem 0;
    margin-bottom: 1rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: #858796;
}

.nav-tabs .nav-link {
    color: #858796;
    border: 1px solid transparent;
    border-bottom-left-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

.nav-tabs .nav-link.active {
    color: #4e73df;
    background-color: #fff;
    border-color: #dddfeb #dddfeb #4e73df;
}

.tab-content > .active {
    display: block;
}

.badge-success {
    background-color: #1cc88a;
}

.badge-warning {
    background-color: #f6c23e;
}

.badge-primary {
    background-color: #4e73df;
}

.table th {
    background-color: #f8f9fc;
    border-top: 1px solid #dee2e6;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endsection
