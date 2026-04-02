@extends('layouts.admin')

@section('title', 'Jadwal')
@section('page-title', 'Manajemen Jadwal')
@section('page-subtitle', 'Buat dan kelola jadwal dengan notifikasi otomatis')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Jadwal</li>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Header Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">Jadwal</h5>
                    <p class="text-muted mb-0">Kelola jadwal dan kirim notifikasi otomatis ke guru dan siswa</p>
                </div>
                <a href="{{ route('admin.exam-schedules.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Buat Jadwal Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.exam-schedules.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Pencarian</label>
                        <input type="text" name="search" class="form-control" placeholder="Cari judul atau deskripsi..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipe</label>
                        <select name="exam_type" class="form-select">
                            <option value="">Semua</option>
                            <option value="uts" {{ request('exam_type') == 'uts' ? 'selected' : '' }}>UTS</option>
                            <option value="uas" {{ request('exam_type') == 'uas' ? 'selected' : '' }}>UAS</option>
                            <option value="quiz" {{ request('exam_type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                            <option value="praktikum" {{ request('exam_type') == 'praktikum' ? 'selected' : '' }}>Praktikum</option>
                            <option value="lainnya" {{ request('exam_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select">
                            <option value="">Semua</option>
                            @foreach(\App\Models\Kelas::all() as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <a href="{{ route('admin.exam-schedules.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedules Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Tipe</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $schedule->title }}</div>
                                @if($schedule->description)
                                    <small class="text-muted">{{ Str::limit($schedule->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $schedule->exam_type == 'uts' ? 'info' : ($schedule->exam_type == 'uas' ? 'danger' : ($schedule->exam_type == 'quiz' ? 'warning' : ($schedule->exam_type == 'praktikum' ? 'success' : 'secondary'))) }}">
                                    {{ strtoupper($schedule->exam_type) }}
                                </span>
                            </td>
                            <td>{{ $schedule->subject->name ?? '-' }}</td>
                            <td>{{ $schedule->kelas->nama ?? 'Semua Kelas' }}</td>
                            <td>
                                <div class="small">
                                    <div><i class="fas fa-calendar me-1"></i>{{ $schedule->start_time->format('d M Y') }}</div>
                                    <div><i class="fas fa-clock me-1"></i>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</div>
                                    @if($schedule->location)
                                        <div><i class="fas fa-map-marker-alt me-1"></i>{{ $schedule->location }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $schedule->status_color }}">
                                    {{ $schedule->status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.exam-schedules.show', $schedule) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.exam-schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$schedule->is_published)
                                        <form method="POST" action="{{ route('admin.exam-schedules.publish', $schedule) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Publikasikan jadwal ini? Notifikasi akan dikirim ke guru dan siswa.')">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.exam-schedules.destroy', $schedule) }}" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                <div>Belum ada jadwal</div>
                                <a href="{{ route('admin.exam-schedules.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus me-1"></i>Buat Jadwal Baru
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($schedules->hasPages())
        <div class="card-footer">
            {{ $schedules->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
