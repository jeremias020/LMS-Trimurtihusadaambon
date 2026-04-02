@extends('siswa.layouts.app')

@section('title', 'Daftar Pelajaran')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Pelajaran</h1>
        <div class="text-muted">
            Mata pelajaran yang tersedia untuk Anda
        </div>
    </div>

    <!-- Student Info Card -->
    @if($siswaData)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Siswa</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama:</strong> {{ $siswaData['name'] }}</p>
                    <p><strong>NIS:</strong> {{ $siswaData['nis_nip'] ?? '-' }}</p>
                    <p><strong>Email:</strong> {{ $siswaData['email'] }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Kelas:</strong> {{ $siswaData['kelas'] }}</p>
                    <p><strong>Jurusan:</strong> {{ $siswaData['jurusan'] }}</p>
                    <p><strong>Wali Kelas:</strong> {{ $siswaData['wali_kelas'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Subjects Grid -->
    @if($subjects->count() > 0)
        <div class="row">
            @foreach($subjects as $subject)
                <div class="col-xl-4 col-lg-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">{{ $subject->name }}</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('siswa.pelajaran.show', $subject->id) }}">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Subject Info -->
                            <div class="mb-3">
                                <p class="text-muted small mb-2">
                                    @if($subject->jurusan)
                                        Jurusan: {{ $subject->jurusan->name }}
                                    @endif
                                </p>
                                @if($subject->description)
                                    <p class="text-muted">{{ Str::limit($subject->description, 100) }}</p>
                                @endif
                            </div>

                            <!-- Activities Summary -->
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="mb-2">
                                        <i class="fas fa-book fa-2x text-info"></i>
                                    </div>
                                    <div class="small text-gray-500">{{ $subject->material_count }}</div>
                                    <div class="text-xs text-gray-500">Materi</div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-2">
                                        <i class="fas fa-tasks fa-2x text-warning"></i>
                                    </div>
                                    <div class="small text-gray-500">{{ $subject->assignment_count }}</div>
                                    <div class="text-xs text-gray-500">Tugas</div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-2">
                                        <i class="fas fa-flask fa-2x text-success"></i>
                                    </div>
                                    <div class="small text-gray-500">{{ $subject->practical_count }}</div>
                                    <div class="text-xs text-gray-500">Praktikum</div>
                                </div>
                            </div>

                            <!-- Total Activities -->
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Total Aktivitas:</span>
                                    <span class="badge badge-primary badge-pill">{{ $subject->total_activities }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-book-open fa-4x text-gray-300"></i>
            </div>
            <h5 class="text-gray-400 mb-3">Belum Ada Pelajaran</h5>
            <p class="text-gray-500 mb-4">
                Belum ada mata pelajaran yang tersedia untuk Anda.
                @if($siswaData['kelas'] === 'Belum ada kelas')
                    Silakan hubungi admin untuk ditugaskan ke kelas terlebih dahulu.
                @endif
            </p>
            @if($siswaData['kelas'] === 'Belum ada kelas')
                <a href="{{ route('siswa.profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-user-edit mr-2"></i>Update Profil
                </a>
            @endif
        </div>
    @endif
</div>

<!-- Custom Styles -->
<style>
.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-primary {
    color: #4e73df !important;
}

.text-gray-500 {
    color: #858796 !important;
}

.text-gray-400 {
    color: #b7b7b7 !important;
}

.text-info {
    color: #36b9cc !important;
}

.text-warning {
    color: #f6c23e !important;
}

.text-success {
    color: #1cc88a !important;
}

.badge-primary {
    background-color: #4e73df;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.dropdown-menu-right {
    right: 0;
    left: auto;
}
</style>
@endsection
