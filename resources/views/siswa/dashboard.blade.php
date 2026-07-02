@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')
@section('siswa-page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang kembali, ' . Auth::user()->name)

@section('content')

{{-- Welcome Banner --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 text-white" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        @php $student = Auth::user()->siswa; @endphp
                        <h4 class="fw-bold mb-1">Halo, {{ Auth::user()->name }}! 👋</h4>
                        <p class="mb-2 opacity-90">{{ now()->translatedFormat('l, d F Y') }}</p>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            @if($student)
                                <span class="badge bg-white bg-opacity-25 text-white px-3 py-2">
                                    <i class="fas fa-id-card me-1"></i>NIS: {{ $student->nis ?? '—' }}
                                </span>
                                <span class="badge bg-white bg-opacity-25 text-white px-3 py-2">
                                    <i class="fas fa-school me-1"></i>{{ $student->kelas->name ?? 'Belum ada kelas' }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('siswa.profile.edit') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-user-edit me-1"></i>Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-primary bg-opacity-10 flex-shrink-0">
                    <i class="fas fa-book text-primary fa-lg"></i>
                </div>
                <div>
                    <div class="h4 fw-bold mb-0">{{ $stats['total_materials'] ?? 0 }}</div>
                    <small class="text-muted">Total Materi</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-success bg-opacity-10 flex-shrink-0">
                    <i class="fas fa-tasks text-success fa-lg"></i>
                </div>
                <div>
                    <div class="h4 fw-bold mb-0">{{ $stats['completed_assignments'] ?? 0 }}</div>
                    <small class="text-muted">Tugas Selesai</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10 flex-shrink-0">
                    <i class="fas fa-calendar-check text-warning fa-lg"></i>
                </div>
                <div>
                    <div class="h4 fw-bold mb-0">{{ $stats['attendance_percentage'] ?? 0 }}%</div>
                    <small class="text-muted">Kehadiran</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-info bg-opacity-10 flex-shrink-0">
                    <i class="fas fa-chart-line text-info fa-lg"></i>
                </div>
                <div>
                    <div class="h4 fw-bold mb-0">{{ $stats['average_score'] ?? 0 }}</div>
                    <small class="text-muted">Nilai Rata-rata</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Access --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-bolt me-2 text-warning"></i>Akses Cepat</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('siswa.materials.index') }}" class="text-decoration-none">
                            <div class="border rounded-3 p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center gap-2 hover-card">
                                <div class="rounded-circle p-3 bg-primary bg-opacity-10">
                                    <i class="fas fa-book text-primary fa-lg"></i>
                                </div>
                                <div class="fw-semibold text-dark small">Materi</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('siswa.assignments.index') }}" class="text-decoration-none">
                            <div class="border rounded-3 p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center gap-2 hover-card">
                                <div class="rounded-circle p-3 bg-success bg-opacity-10">
                                    <i class="fas fa-tasks text-success fa-lg"></i>
                                </div>
                                <div class="fw-semibold text-dark small">Tugas</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('siswa.reports.practical') }}" class="text-decoration-none">
                            <div class="border rounded-3 p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center gap-2 hover-card">
                                <div class="rounded-circle p-3 bg-info bg-opacity-10">
                                    <i class="fas fa-flask text-info fa-lg"></i>
                                </div>
                                <div class="fw-semibold text-dark small">Praktikum</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('siswa.nilai.index') }}" class="text-decoration-none">
                            <div class="border rounded-3 p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center gap-2 hover-card">
                                <div class="rounded-circle p-3 bg-warning bg-opacity-10">
                                    <i class="fas fa-chart-bar text-warning fa-lg"></i>
                                </div>
                                <div class="fw-semibold text-dark small">Nilai</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Materials & Upcoming Deadlines --}}
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-book-open me-2 text-primary"></i>Materi Terbaru</h6>
                <a href="{{ route('siswa.materials.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if(isset($recentMaterials) && count($recentMaterials) > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($recentMaterials as $material)
                            <li class="list-group-item px-4 py-3">
                                <div class="d-flex gap-3 align-items-start">
                                    <div class="rounded-2 bg-primary bg-opacity-10 p-2 flex-shrink-0">
                                        <i class="fas fa-file-alt text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="fw-semibold text-truncate" style="max-width:220px;">{{ $material->title }}</div>
                                        <small class="text-muted">{{ $material->created_at ? $material->created_at->diffForHumans() : '—' }}</small>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 opacity-50 d-block"></i>
                        <small>Belum ada materi baru</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-clock me-2 text-danger"></i>Deadline Mendatang</h6>
                <a href="{{ route('siswa.assignments.index') }}" class="btn btn-outline-danger btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if(isset($upcomingDeadlines) && count($upcomingDeadlines) > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($upcomingDeadlines as $deadline)
                            <li class="list-group-item px-4 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-3 align-items-start">
                                        <div class="rounded-2 p-2 flex-shrink-0 {{ $deadline->type === 'assignment' ? 'bg-success bg-opacity-10' : 'bg-info bg-opacity-10' }}">
                                            <i class="{{ $deadline->type === 'assignment' ? 'fas fa-tasks text-success' : 'fas fa-flask text-info' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="max-width:200px;">{{ $deadline->title }}</div>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($deadline->deadline)->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                    <span class="badge {{ $deadline->days_left <= 2 ? 'bg-danger' : ($deadline->days_left <= 7 ? 'bg-warning text-dark' : 'bg-success') }}">
                                        {{ $deadline->days_left == 0 ? 'Hari ini' : $deadline->days_left . ' hari' }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-check-circle fa-2x mb-2 opacity-50 d-block"></i>
                        <small>Tidak ada deadline mendatang</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
.hover-card { transition: all .2s ease; cursor: pointer; }
.hover-card:hover { background-color: #f8fafc; border-color: #3b82f6 !important; }
</style>
@endpush

@endsection
