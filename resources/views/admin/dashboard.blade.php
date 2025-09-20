@extends('admin.layouts.admin-layout')

@section('title', 'Dashboard Admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-success">
            <i class="fas fa-info-circle me-2"></i> Selamat datang di Dashboard Admin LMS Trimurti Husada
        </div>
        
        <!-- Quick Access Buttons -->
        <div class="mb-4">
            <h6 class="mb-3"><i class="fas fa-bolt me-2"></i>Akses Cepat</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                    <i class="fas fa-users me-2"></i>Kelola Pengguna
                </a>
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-success">
                    <i class="fas fa-school me-2"></i>Kelola Kelas
                </a>
                <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-graduation-cap me-2"></i>Kelola Jurusan
                </a>
                <a href="{{ route('admin.kriteria.index') }}" class="btn btn-warning">
                    <i class="fas fa-clipboard-list me-2"></i>Kriteria Penilaian
                </a>
                <a href="{{ route('admin.jadwal-ujian.index') }}" class="btn btn-danger">
                    <i class="fas fa-calendar-alt me-2"></i>Jadwal Ujian
                </a>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Total Pengguna</h6>
                                <h3 class="mb-0">{{ $stats['total_users'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-graduate fa-2x text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Total Siswa</h6>
                                <h3 class="mb-0">{{ $stats['total_siswa'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-chalkboard-teacher fa-2x text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Total Guru</h6>
                                <h3 class="mb-0">{{ $stats['total_guru'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-book fa-2x text-danger"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Total Materi</h6>
                                <h3 class="mb-0">{{ $stats['total_materi'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="mt-4">
            <h6 class="mb-3"><i class="fas fa-history me-2"></i>Aktivitas Terbaru</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Aktivitas</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities ?? [] as $activity)
                        <tr>
                            <td>{{ $activity->user->name }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada aktivitas terbaru</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection