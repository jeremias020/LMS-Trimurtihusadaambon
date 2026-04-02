@extends('layouts.guru')

@section('title', 'Manajemen Tugas - LMS Trimurti Husada')
@section('page-title', 'Manajemen Tugas')
@section('page-subtitle', 'Kelola tugas dan penugasan untuk siswa')

@section('page-actions')
<a href="{{ route('guru.assignments.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>Buat Tugas
</a>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active">Manajemen Tugas</li>
@endsection

@section('content')
<!-- Enhanced Tab Navigation -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <ul class="nav nav-pills nav-justified nav-pills-custom" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $tab === 'active' ? 'active' : '' }} d-flex align-items-center justify-content-center" 
                           href="{{ route('guru.assignments.index', ['tab' => 'active'] + request()->except('tab')) }}"
                           role="tab">
                            <i class="fas fa-play me-2"></i>
                            <div class="text-start">
                                <div class="fw-bold">Tugas Aktif</div>
                                <small class="opacity-75">Sedang berjalan</small>
                            </div>
                            <span class="badge bg-white text-primary ms-2 fw-bold">{{ $totalStats['active_assignments'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $tab === 'history' ? 'active' : '' }} d-flex align-items-center justify-content-center" 
                           href="{{ route('guru.assignments.index', ['tab' => 'history'] + request()->except('tab')) }}"
                           role="tab">
                            <i class="fas fa-history me-2"></i>
                            <div class="text-start">
                                <div class="fw-bold">Riwayat Tugas</div>
                                <small class="opacity-75">Semua tugas</small>
                            </div>
                            <span class="badge bg-white text-primary ms-2 fw-bold">{{ $totalStats['total_assignments'] ?? 0 }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-4">
                <div class="text-lg-end text-center mt-3 mt-lg-0">
                    <div class="d-flex align-items-center justify-content-lg-end justify-content-center gap-3">
                        <div class="text-center">
                            <div class="text-primary fw-bold h5 mb-0">{{ $totalStats['total_submissions'] ?? 0 }}</div>
                            <small class="text-muted">Total Pengumpulan</small>
                        </div>
                        <div class="vr d-none d-lg-block"></div>
                        <div class="text-center">
                            <div class="text-success fw-bold h5 mb-0">{{ $totalStats['graded_submissions'] ?? 0 }}</div>
                            <small class="text-muted">Sudah Dinilai</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($tab === 'history')
<!-- Statistics Cards for History Tab -->
<div class="row g-4 mb-4 fade-in">
    <div class="col-xl-3 col-md-6">
        <div class="stats-card card border-0 bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-tasks fa-2x opacity-75"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="small opacity-75 text-uppercase fw-medium">Total Tugas</div>
                        <div class="h3 mb-0 fw-bold">{{ $totalStats['total_assignments'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stats-card card border-0 bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-play fa-2x opacity-75"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="small opacity-75 text-uppercase fw-medium">Tugas Aktif</div>
                        <div class="h3 mb-0 fw-bold">{{ $totalStats['active_assignments'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stats-card card border-0 bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file-upload fa-2x opacity-75"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="small opacity-75 text-uppercase fw-medium">Total Pengumpulan</div>
                        <div class="h3 mb-0 fw-bold">{{ $totalStats['total_submissions'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stats-card card border-0 bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-star fa-2x opacity-50"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="small opacity-75 text-uppercase fw-medium">Sudah Dinilai</div>
                        <div class="h3 mb-0 fw-bold">{{ $totalStats['graded_submissions'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="card border-0 shadow-lg">
    <!-- Enhanced Header with Better Visual Hierarchy -->
    <div class="card-header border-0 bg-gradient-primary text-white position-relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="position-absolute top-0 end-0 opacity-10">
            <i class="fas fa-{{ $tab === 'history' ? 'history' : 'tasks' }} fa-6x"></i>
        </div>
        
        <div class="position-relative">
            <div class="row align-items-center py-2">
                <div class="col-lg-8 col-md-7">
                    <div class="d-flex align-items-center">
                        <!-- Icon Container -->
                        <div class="bg-white bg-opacity-20 rounded-3 p-3 me-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-{{ $tab === 'history' ? 'history' : 'list' }} text-white fa-lg"></i>
                        </div>
                        
                        <!-- Title Container -->
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-bold">{{ $tab === 'history' ? 'Riwayat Tugas' : 'Daftar Tugas Aktif' }}</h4>
                            <div class="d-flex align-items-center gap-3">
                                <span class="opacity-90">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    {{ $assignments->total() ?? 0 }} tugas ditemukan
                                </span>
                                @if($tab === 'active')
                                <span class="opacity-90">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $assignments->where('deadline', '<=', now()->addDay())->count() }} deadline dekat
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-5">
                    <div class="text-end">
                        <!-- Quick Stats Cards -->
                        <div class="row g-2">
                            @if($tab === 'active')
                            <div class="col-6">
                                <div class="bg-white bg-opacity-15 rounded-3 p-2 text-center">
                                    <div class="h5 mb-0 fw-bold">{{ $totalStats['active_assignments'] ?? 0 }}</div>
                                    <small class="opacity-90">Aktif</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white bg-opacity-15 rounded-3 p-2 text-center">
                                    <div class="h5 mb-0 fw-bold">{{ $totalStats['total_submissions'] ?? 0 }}</div>
                                    <small class="opacity-90">Pengumpulan</small>
                                </div>
                            </div>
                            @else
                            <div class="col-4">
                                <div class="bg-white bg-opacity-15 rounded-3 p-2 text-center">
                                    <div class="h6 mb-0 fw-bold">{{ $totalStats['total_assignments'] ?? 0 }}</div>
                                    <small class="opacity-90 small">Total</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-white bg-opacity-15 rounded-3 p-2 text-center">
                                    <div class="h6 mb-0 fw-bold">{{ $totalStats['total_submissions'] ?? 0 }}</div>
                                    <small class="opacity-90 small">Submit</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-white bg-opacity-15 rounded-3 p-2 text-center">
                                    <div class="h6 mb-0 fw-bold">{{ $totalStats['graded_submissions'] ?? 0 }}</div>
                                    <small class="opacity-90 small">Dinilai</small>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-3 d-flex gap-2 justify-content-end">
                            <button class="btn btn-light btn-sm rounded-pill px-3" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#filterCollapse" 
                                    aria-expanded="{{ request()->hasAny(['subject_id', 'class', 'status', 'period']) ? 'true' : 'false' }}" 
                                    aria-controls="filterCollapse" 
                                    title="Toggle Filter">
                                <i class="fas fa-filter text-primary me-1"></i>
                                <span class="d-none d-sm-inline">Filter</span>
                            </button>
                            
                            @if($tab === 'active')
                            <button class="btn btn-warning btn-sm rounded-pill px-3" id="showDeadlineAlert">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <span class="d-none d-sm-inline">Deadline</span>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Collapsible Filter -->
    <div class="collapse {{ request()->hasAny(['subject_id', 'class', 'status', 'period']) ? 'show' : '' }}" id="filterCollapse">
        <div class="card-body border-bottom bg-light">
            <form method="GET" action="{{ route('guru.assignments.index') }}" id="filterForm">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-medium text-primary">
                            <i class="fas fa-book me-1"></i>Mata Pelajaran
                        </label>
                        <select name="subject_id" class="form-select form-select-sm">
                            <option value="">🔍 Semua Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-medium text-primary">
                            <i class="fas fa-users me-1"></i>Kelas
                        </label>
                        <select name="class_id" class="form-select form-select-sm">
                            <option value="">🔍 Semua Kelas</option>
                            @if(isset($classes))
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    � {{ $class->name }}
                                </option>
                                @endforeach
                            @else
                                <option value="1" {{ request('class_id') == '1' ? 'selected' : '' }}>📚 Kelas X Keperawatan</option>
                            @endif
                        </select>
                    </div>
                    
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-medium text-primary">
                            <i class="fas fa-flag me-1"></i>Status
                        </label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">🔍 Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Aktif</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✔️ Selesai</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                        </select>
                    </div>
                    
                    @if($tab === 'history')
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-medium text-primary">
                            <i class="fas fa-calendar me-1"></i>Periode
                        </label>
                        <select name="period" class="form-select form-select-sm">
                            <option value="">🔍 Semua Waktu</option>
                            <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>📅 Minggu Ini</option>
                            <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>📊 Bulan Ini</option>
                            <option value="semester" {{ request('period') == 'semester' ? 'selected' : '' }}>📈 Semester Ini</option>
                        </select>
                    </div>
                    @endif
                    
                    <div class="col-lg-{{ $tab === 'history' ? '3' : '5' }} col-md-12">
                        <label class="form-label small fw-medium">&nbsp;</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary btn-sm px-3">
                                <i class="fas fa-search me-1"></i>Cari Tugas
                            </button>
                            <a href="{{ route('guru.assignments.index', ['tab' => $tab]) }}" class="btn btn-outline-secondary btn-sm px-3">
                                <i class="fas fa-refresh me-1"></i>Reset
                            </a>
                            @if($tab === 'active')
                            <button type="button" class="btn btn-outline-warning btn-sm px-3" id="showDeadlineAlert">
                                <i class="fas fa-exclamation-triangle me-1"></i>Deadline Dekat
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <!-- Enhanced Table Container -->
        <div class="table-responsive">
            <table class="table table-hover mb-0 assignment-table">
                <thead class="table-header-custom">
                    <tr>
                        <th class="border-0 fw-bold text-uppercase">
                            <i class="fas fa-tasks text-primary me-2"></i>
                            Tugas
                        </th>
                        <th class="border-0 fw-bold text-uppercase">
                            <i class="fas fa-book text-primary me-2"></i>
                            Mata Pelajaran
                        </th>
                        <th class="border-0 fw-bold text-uppercase text-center">
                            <i class="fas fa-users text-primary me-2"></i>
                            Kelas
                        </th>
                        @if($tab === 'history')
                        <th class="border-0 fw-bold text-uppercase text-center">
                            <i class="fas fa-calendar-plus text-primary me-2"></i>
                            Dibuat
                        </th>
                        @endif
                        <th class="border-0 fw-bold text-uppercase text-center">
                            <i class="fas fa-clock text-primary me-2"></i>
                            Deadline
                        </th>
                        <th class="border-0 fw-bold text-uppercase text-center">
                            <i class="fas fa-flag text-primary me-2"></i>
                            Status
                        </th>
                        @if($tab === 'history')
                        <th class="border-0 fw-bold text-uppercase text-center">
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            Statistik
                        </th>
                        @else
                        <th class="border-0 fw-bold text-uppercase text-center">
                            <i class="fas fa-file-upload text-primary me-2"></i>
                            Pengumpulan
                        </th>
                        @endif
                        <th class="border-0 fw-bold text-uppercase text-center">
                            <i class="fas fa-cog text-primary me-2"></i>
                            Aksi
                        </th>
                    </tr>
                </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                        <tr data-subject-id="{{ $assignment->subject_id }}">
                            <td>
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-medium">{{ $assignment->title }}</h6>
                                        <div class="text-muted small">
                                            {{ \Illuminate\Support\Str::limit($assignment->description, $tab === 'history' ? 60 : 50) }}
                                        </div>
                                        @if($assignment->file)
                                        <div class="mt-1">
                                            <small class="text-info">
                                                <i class="fas fa-paperclip me-1"></i>
                                                File lampiran
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            @php
    $classSubjectData = $assignment->getClassSubject();
    $subjectName = $classSubjectData->subject_name ?? 'N/A';
    $className = $classSubjectData->class_name ?? 'N/A';
@endphp

                            <td class="text-nowrap">
                                <span class="badge bg-light text-dark border">
                                    {{ $subjectName }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    {{ $className }}
                                </span>
                            </td>
                            
                            @if($tab === 'history')
                            <td class="text-muted small text-nowrap">
                                {{ $assignment->created_at->format('d/m/Y') }}
                                <div class="text-xs opacity-75">
                                    {{ $assignment->created_at->format('H:i') }}
                                </div>
                            </td>
                            @endif
                            
                            <td class="text-muted small text-nowrap">
                                @if($assignment->deadline)
                                    {{ $assignment->deadline->format('d/m/Y') }}
                                    <div class="text-xs opacity-75">
                                        {{ $assignment->deadline->format('H:i') }}
                                    </div>
                                    @if($tab === 'history' && $assignment->deadline->isPast())
                                    <span class="badge bg-danger text-white small mt-1">Terlewat</span>
                                    @elseif($tab === 'history' && $assignment->deadline->diffInDays() <= 1)
                                    <span class="badge bg-warning text-dark small mt-1">Urgent</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            
                            <td>
                                @php
                                    $statusClass = 'secondary';
                                    $statusText = 'Draft';
                                    if ($assignment->is_published) {
                                        if ($assignment->deadline && $assignment->deadline->isPast()) {
                                            $statusClass = 'primary';
                                            $statusText = 'Selesai';
                                        } else {
                                            $statusClass = 'success';
                                            $statusText = 'Aktif';
                                        }
                                    }
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            
                            @if($tab === 'history')
                            <td>
                                <div class="small">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Pengumpulan:</span>
                                        <strong>{{ $assignment->submissions_count }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Dinilai:</span>
                                        <strong class="text-success">{{ $assignment->graded_count }}</strong>
                                    </div>
                                    @if($assignment->ungraded_count > 0)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Belum:</span>
                                        <strong class="text-danger">{{ $assignment->ungraded_count }}</strong>
                                    </div>
                                    @endif
                                    @if($assignment->average_score)
                                    <div class="d-flex justify-content-between">
                                        <span>Rata-rata:</span>
                                        <strong class="text-primary">{{ $assignment->average_score }}</strong>
                                    </div>
                                    @endif
                                    @if($assignment->completion_rate > 0)
                                    <div class="progress mt-1" style="height: 4px;">
                                        <div class="progress-bar bg-success" 
                                             style="width: {{ $assignment->completion_rate }}%"
                                             title="Tingkat penyelesaian: {{ $assignment->completion_rate }}%">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            @else
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="text-success fw-medium">{{ $assignment->submissions_count ?? 0 }}</span>
                                    @if(isset($assignment->ungraded_count) && $assignment->ungraded_count > 0)
                                    <span class="text-muted mx-1">|</span>
                                    <span class="text-danger">{{ $assignment->ungraded_count }} belum dinilai</span>
                                    @endif
                                </div>
                            </td>
                            @endif
                            
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('guru.assignments.show', $assignment->id) }}"
                                       class="btn btn-outline-primary btn-sm"
                                       title="Lihat detail tugas">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('guru.assignments.edit', $assignment->id) }}"
                                       class="btn btn-outline-secondary btn-sm"
                                       title="Edit tugas">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($assignment->submissions_count > 0)
                                    <a href="{{ route('guru.assignments.submissions', $assignment->id) }}" 
                                       class="btn btn-outline-info btn-sm"
                                       title="Lihat Pengumpulan">
                                        <i class="fas fa-file-upload"></i>
                                    </a>
                                    @endif
                                    @if($tab !== 'history' || ($tab === 'history' && $assignment->submissions_count == 0))
                                    <form action="{{ route('guru.assignments.destroy', $assignment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')"
                                                title="Hapus tugas">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $tab === 'history' ? '8' : '7' }}" class="border-0 p-0">
                                <div class="empty-state-container">
                                    <div class="empty-state-content text-center py-5">
                                        <!-- Animated Icon -->
                                        <div class="empty-icon-wrapper mb-4">
                                            <div class="empty-icon-bg">
                                                <i class="fas fa-{{ $tab === 'history' ? 'history' : 'tasks' }} fa-4x text-white"></i>
                                            </div>
                                        </div>
                                        
                                        <!-- Content -->
                                        <h3 class="text-dark mb-3 fw-bold">{{ $tab === 'history' ? 'Tidak ada riwayat tugas' : 'Belum ada tugas aktif' }}</h3>
                                        <p class="text-muted mb-4 mx-auto" style="max-width: 400px;">
                                            {{ $tab === 'history' ? 'Tugas yang Anda buat akan muncul di sini dengan statistik lengkap untuk analisis pembelajaran' : 'Mulai dengan membuat tugas pertama Anda untuk memberikan pembelajaran yang interaktif kepada siswa.' }}
                                        </p>
                                        
                                        <!-- Actions -->
                                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                                            @if($tab !== 'history')
                                            <a href="{{ route('guru.assignments.create') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">
                                                <i class="fas fa-plus me-2"></i>Buat Tugas Pertama
                                            </a>
                                            <button class="btn btn-outline-primary btn-lg rounded-pill px-4" onclick="showTutorial()">
                                                <i class="fas fa-question-circle me-2"></i>Pelajari Cara
                                            </button>
                                            @else
                                            <a href="{{ route('guru.assignments.index', ['tab' => 'active']) }}" class="btn btn-primary rounded-pill px-4">
                                                <i class="fas fa-arrow-left me-2"></i>Lihat Tugas Aktif
                                            </a>
                                            <a href="{{ route('guru.assignments.create') }}" class="btn btn-outline-primary rounded-pill px-4">
                                                <i class="fas fa-plus me-2"></i>Buat Tugas Baru
                                            </a>
                                            @endif
                                        </div>
                                        
                                        <!-- Quick Tips -->
                                        @if($tab !== 'history')
                                        <div class="mt-4 pt-3 border-top">
                                            <small class="text-muted">
                                                <i class="fas fa-lightbulb me-1"></i>
                                                Tips: Gunakan menu "Tambah Baru" di header untuk membuat tugas dengan cepat
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

                @if($assignments->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $assignments->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Clear all floats before end of content -->
<div class="clearfix"></div>
<div style="clear: both; height: 1px; overflow: hidden;"></div>

@endsection

@push('css')
<style>
/* Enhanced Tab Navigation Styling */
.nav-pills-custom .nav-link {
    border-radius: 1rem;
    font-weight: 600;
    padding: 1rem 1.5rem;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    min-height: 80px;
    display: flex !important;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.nav-pills-custom .nav-link:not(.active) {
    color: #6c757d;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
}

.nav-pills-custom .nav-link:not(.active):hover {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #0d6efd;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(13,110,253,0.2);
    border-color: #0d6efd;
}

.nav-pills-custom .nav-link.active {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: white;
    box-shadow: 0 6px 20px rgba(13,110,253,0.4);
    transform: translateY(-2px);
}

/* Gradient Header */
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%) !important;
}

/* Filter Toggle Animation */
.collapse {
    transition: all 0.3s ease-in-out;
}

/* Enhanced Form Controls */
.form-select-sm {
    border-radius: 0.75rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
}

.form-select-sm:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    transform: translateY(-1px);
}

.form-select-sm:hover {
    border-color: #6c757d;
}

/* Enhanced Badges */
.badge {
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Quick Stats Styling */
.vr {
    width: 2px;
    background: linear-gradient(to bottom, transparent, #dee2e6, transparent);
    opacity: 0.5;
}

/* Loading States */
.btn:disabled {
    opacity: 0.6;
    transform: none !important;
}

/* Hover Effects for Stats */
.text-center:hover .h5 {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}

/* Mobile Responsive Enhancements */
@media (max-width: 992px) {
    .nav-pills-custom .nav-link {
        min-height: 60px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
    }
    
    .nav-pills-custom .nav-link div {
        text-align: center;
    }
}

@media (max-width: 576px) {
    .nav-pills-custom .nav-link {
        font-size: 0.9rem;
        min-height: 50px;
        padding: 0.5rem 0.75rem;
    }
    
    .bg-gradient-primary .card-title {
        font-size: 1rem;
    }
    
    .form-select-sm {
        font-size: 0.85rem;
    }
}

/* Statistics Cards */
.stats-card {
    border-radius: 1rem;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* Enhanced Table Styling */
.assignment-table {
    border-radius: 0;
    overflow: hidden;
}

.table-header-custom {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
}

.table-header-custom th {
    padding: 1.25rem 1rem;
    font-size: 0.75rem;
    letter-spacing: 1px;
    color: #495057;
    font-weight: 700;
    position: relative;
}

.table-header-custom th:not(:last-child)::after {
    content: '';
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 1px;
    height: 60%;
    background: linear-gradient(to bottom, transparent, #dee2e6, transparent);
}

.assignment-table tbody tr {
    border-bottom: 1px solid #f1f3f4;
    transition: all 0.3s ease;
    position: relative;
}

.assignment-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.assignment-table td {
    vertical-align: middle;
    padding: 1.25rem 1rem;
    border: none;
    position: relative;
}

.assignment-table td:not(:last-child)::after {
    content: '';
    position: absolute;
    right: 0;
    top: 20%;
    height: 60%;
    width: 1px;
    background: linear-gradient(to bottom, transparent, #f0f0f0, transparent);
}

/* Enhanced Empty State */
.empty-state-container {
    background: linear-gradient(135deg, #fafbff 0%, #f0f4ff 100%);
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon-wrapper {
    position: relative;
    display: inline-block;
}

.empty-icon-bg {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.empty-icon-wrapper::before {
    content: '';
    position: absolute;
    top: -20px;
    left: -20px;
    right: -20px;
    bottom: -20px;
    border: 2px dashed #667eea;
    border-radius: 50%;
    opacity: 0.3;
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Micro-interactions */
.scale-110 {
    transform: scale(1.1) !important;
    transition: all 0.3s ease;
}

.show-actions {
    opacity: 1 !important;
    transform: translateX(0) !important;
}

.btn-group {
    opacity: 0.7;
    transform: translateX(10px);
    transition: all 0.3s ease;
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced hover effects for stats */
.bg-white.bg-opacity-15:hover {
    background-color: rgba(255, 255, 255, 0.25) !important;
    transform: scale(1.05);
    transition: all 0.3s ease;
}

/* Loading spinner enhancements */
.btn .fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Table row enhancements */
.assignment-table tbody tr {
    cursor: pointer;
}

.assignment-table tbody tr:hover .btn-group {
    opacity: 1;
    transform: translateX(0);
}

/* Header pattern animation */
.card-header .position-absolute {
    animation: pulse-slow 4s ease-in-out infinite;
}

@keyframes pulse-slow {
    0%, 100% { opacity: 0.1; transform: scale(1); }
    50% { opacity: 0.15; transform: scale(1.05); }
}

/* Badge Styling */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.4rem 0.8rem;
    border-radius: 0.5rem;
}

/* Button Group Styling */
.btn-group .btn {
    border-radius: 0.375rem !important;
    margin: 0 0.125rem;
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Progress Bar */
.progress {
    background-color: #e9ecef;
    border-radius: 0.5rem;
    height: 6px;
}

.progress-bar {
    border-radius: 0.5rem;
}

/* Form Controls */
.form-select, .form-control {
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.form-select:focus, .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.1);
}

/* Card Styling */
.card {
    border-radius: 1rem;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 1.25rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

/* Utility Classes */
.text-xs {
    font-size: 0.75rem;
}

.fw-medium {
    font-weight: 500 !important;
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-state i {
    opacity: 0.3;
    margin-bottom: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nav-pills {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .nav-pills .nav-item {
        flex: 1;
    }
    
    .nav-pills .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin: 0.125rem 0;
        border-radius: 0.375rem !important;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .stats-card .card-body {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .table td, .table th {
        padding: 0.5rem 0.25rem;
        font-size: 0.75rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

/* Animation for loading states */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in {
    animation: fadeIn 0.5s ease-out;
}

/* Custom scrollbar for table */
.table-responsive::-webkit-scrollbar {
    height: 6px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Tutorial Modal Styling */
.modal-content {
    border-radius: 1rem;
    overflow: hidden;
}

.modal-header.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.modal-header.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
}

.modal-header.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important;
}

.modal-header.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
}

.modal-header.bg-gradient-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #545b62 100%) !important;
}

/* Animation classes */
.animate__bounce {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 53%, 80%, 100% {
        transform: translateY(0);
    }
    40%, 43% {
        transform: translateY(-15px);
    }
    70% {
        transform: translateY(-7px);
    }
    90% {
        transform: translateY(-3px);
    }
}

.animate__bounceIn {
    animation: bounceIn 1s;
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale3d(0.3, 0.3, 0.3);
    }
    50% {
        opacity: 1;
    }
    60% {
        transform: scale3d(1.05, 1.05, 1.05);
    }
    80% {
        transform: scale3d(0.95, 0.95, 0.95);
    }
    100% {
        opacity: 1;
        transform: scale3d(1, 1, 1);
    }
}

.animate__fadeInUp {
    animation: fadeInUp 0.8s ease-out;
}

/* Enhanced hover effects */
.modal .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.modal .btn {
    transition: all 0.3s ease;
}

/* Progress dots */
.tutorial-progress .progress-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    transition: all 0.3s ease;
    cursor: pointer;
}

.tutorial-progress .progress-dot:hover {
    transform: scale(1.2);
}

.tutorial-progress .progress-dot.active {
    transform: scale(1.1);
    box-shadow: 0 0 10px rgba(0,123,255,0.5);
}

/* Scale effect */
.scale-105 {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

/* Enhanced table hover effects */
.table-hover-enhanced {
    background-color: #f8f9fa !important;
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

/* Force proper layout flow and prevent footer floating */
.container-fluid, .container {
    clear: both;
    width: 100%;
    display: block;
    position: relative;
}

/* Clear all floats at end of content */
.clearfix::after,
.assignment-content::after {
    content: "";
    display: table;
    clear: both;
}

/* Ensure no elements float past this point */
body::after {
    content: "";
    display: block;
    clear: both;
    height: 0;
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    // Add fade-in animation to content
    $('.card, .stats-card').addClass('fade-in');
    
    // Enhanced auto-submit form when filters change
    let filterTimeout;
    $('select[name]').on('change', function() {
        const $form = $(this).closest('form');
        if ($form.length) {
            clearTimeout(filterTimeout);
            const $select = $(this);
            
            // Enhanced visual feedback
            $select.addClass('border-primary shadow-sm');
            $select.after('<div class="filter-loading position-absolute top-50 end-0 translate-middle-y me-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div></div>');
            
            filterTimeout = setTimeout(() => {
                $form.submit();
            }, 500);
        }
    });

    // Deadline alert functionality
    $('#showDeadlineAlert').on('click', function() {
        const $btn = $(this);
        const originalText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i>Memuat...');
        
        // Find assignments with near deadlines
        const nearDeadlines = [];
        $('tbody tr').each(function() {
            const $row = $(this);
            const deadlineText = $row.find('td:nth-child(4)').text().trim();
            const titleText = $row.find('h6').text().trim();
            
            if (deadlineText && titleText && deadlineText !== '-') {
                nearDeadlines.push({ title: titleText, deadline: deadlineText });
            }
        });
        
        setTimeout(() => {
            $btn.html(originalText);
            
            if (nearDeadlines.length > 0) {
                let alertMsg = 'Tugas dengan deadline dekat:\n\n';
                nearDeadlines.slice(0, 5).forEach(item => {
                    alertMsg += `• ${item.title}\n  Deadline: ${item.deadline}\n\n`;
                });
                
                if (nearDeadlines.length > 5) {
                    alertMsg += `... dan ${nearDeadlines.length - 5} tugas lainnya`;
                }
                
                alert(alertMsg);
            } else {
                alert('✅ Tidak ada tugas dengan deadline mendesak!');
            }
        }, 1000);
    });
    
    // Enhanced filter collapse handling
    $('#filterCollapse').on('show.bs.collapse', function() {
        $('[data-bs-target="#filterCollapse"] i').removeClass('fa-filter').addClass('fa-times');
    }).on('hide.bs.collapse', function() {
        $('[data-bs-target="#filterCollapse"] i').removeClass('fa-times').addClass('fa-filter');
    });
    
    // Filter form enhancements
    $('.form-select-sm').on('focus', function() {
        $(this).addClass('shadow-sm scale-105');
    }).on('blur', function() {
        $(this).removeClass('shadow-sm scale-105');
        $('.filter-loading').remove();
    });
    
    // Initialize tooltips with better config
    if ($.fn.tooltip) {
        $('[title], [data-bs-title]').tooltip({
            delay: { show: 500, hide: 100 },
            placement: 'top',
            trigger: 'hover focus'
        });
    }
    
    // Enhanced loading state for forms
    $('form').on('submit', function(e) {
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        
        if ($submitBtn.length && !$submitBtn.prop('disabled')) {
            const originalText = $submitBtn.html();
            const loadingText = '<i class="fas fa-spinner fa-spin me-2"></i>Memuat...';
            
            $submitBtn.html(loadingText)
                     .prop('disabled', true)
                     .addClass('loading');
            
            // Add loading overlay to form
            $form.append('<div class="loading-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white bg-opacity-75" style="z-index: 1000;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            
            // Fallback timeout
            setTimeout(() => {
                $submitBtn.html(originalText)
                         .prop('disabled', false)
                         .removeClass('loading');
                $form.find('.loading-overlay').remove();
            }, 10000);
        }
    });
    
    // Enhanced tab transitions with smooth animations
    $('.nav-pills .nav-link').on('click', function(e) {
        const $link = $(this);
        if (!$link.hasClass('active')) {
            // Add loading spinner to clicked tab
            $link.append('<i class="fas fa-spinner fa-spin ms-2 tab-spinner"></i>');
            
            // Fade out current content
            $('.table-responsive, .empty-state').fadeOut(200, function() {
                // Content will fade in after page load
            });
        }
    });
    
    // Smart delete confirmation with context
    $('form[action*="destroy"]').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $row = $form.closest('tr');
        const title = $row.find('h6').text().trim() || 'tugas ini';
        const $submissionCell = $row.find('td').eq({{ $tab === "history" ? "6" : "5" }});
        const submissionsText = $submissionCell.text().trim();
        
        let confirmMessage = `Apakah Anda yakin ingin menghapus "${title}"?`;
        
        // Check for submissions
        const hasSubmissions = submissionsText.includes('Pengumpulan:') && 
                              parseInt(submissionsText.match(/\d+/)?.[0] || '0') > 0;
        
        if (hasSubmissions) {
            confirmMessage += '\n\n⚠️ PERINGATAN: Tugas ini memiliki pengumpulan dari siswa. Menghapus tugas akan menghapus SEMUA pengumpulan dan nilai yang terkait.\n\nTindakan ini tidak dapat dibatalkan!';
        }
        
        if (confirm(confirmMessage)) {
            // Add visual feedback
            $row.addClass('table-danger');
            const $deleteBtn = $form.find('button[type="submit"]');
            $deleteBtn.html('<i class="fas fa-spinner fa-spin"></i>')
                     .prop('disabled', true);
            
            // Submit form
            $form.off('submit').submit();
        }
    });
    
    // Enhanced button interactions
    $('.btn-group .btn').hover(
        function() {
            $(this).addClass('shadow-sm');
        },
        function() {
            $(this).removeClass('shadow-sm');
        }
    );
    
    // Stats cards hover effect
    $('.stats-card').hover(
        function() {
            $(this).find('i').addClass('fa-bounce');
        },
        function() {
            $(this).find('i').removeClass('fa-bounce');
        }
    );
    
    // Progress bar animation
    $('.progress-bar').each(function() {
        const $bar = $(this);
        const width = $bar.css('width');
        $bar.css('width', '0').animate({ width: width }, 1000);
    });
    
    // Table row click for mobile
    if (window.innerWidth <= 768) {
        $('tbody tr').on('click', function(e) {
            if (!$(e.target).closest('.btn-group').length) {
                const $actionBtns = $(this).find('.btn-group');
                $actionBtns.toggleClass('d-flex flex-column');
            }
        });
    }
    
    // Auto-refresh functionality (optional)
    let refreshInterval;
    const enableAutoRefresh = () => {
        refreshInterval = setInterval(() => {
            if (document.visibilityState === 'visible') {
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.set('auto_refresh', '1');
                
                // Subtle refresh indicator
                $('.card-header h5').append(' <i class="fas fa-sync fa-spin text-muted ms-2" style="font-size: 0.8em;"></i>');
                
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        }, 300000); // 5 minutes
    };
    
    // Uncomment to enable auto-refresh
    // enableAutoRefresh();
    
    // Cleanup on page unload
    $(window).on('beforeunload', function() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + N for new assignment
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            window.location.href = '{{ route("guru.assignments.create") }}';
        }
        
        // Ctrl/Cmd + H for history tab
        if ((e.ctrlKey || e.metaKey) && e.key === 'h') {
            e.preventDefault();
            window.location.href = '{{ route("guru.assignments.index", ["tab" => "history"]) }}';
        }
        
        // Ctrl/Cmd + A for active tab
        if ((e.ctrlKey || e.metaKey) && e.key === 'a' && e.shiftKey) {
            e.preventDefault();
            window.location.href = '{{ route("guru.assignments.index", ["tab" => "active"]) }}';
        }
    });
    
    // Enhanced tutorial function for empty state
    window.showTutorial = function() {
        const tutorialSteps = [
            {
                title: '🚀 Selamat Datang di Manajemen Tugas',
                content: 'Mari mulai membuat tugas pertama Anda dengan panduan singkat ini.',
                icon: 'fas fa-graduation-cap',
                color: 'primary'
            },
            {
                title: '📝 Langkah 1: Klik Buat Tugas',
                content: 'Klik tombol "Buat Tugas Baru" di bagian atas untuk memulai membuat tugas.',
                icon: 'fas fa-plus-circle',
                color: 'success'
            },
            {
                title: '✏️ Langkah 2: Isi Detail Tugas',
                content: 'Masukkan judul menarik, deskripsi yang jelas, pilih mata pelajaran, dan tentukan deadline yang realistis.',
                icon: 'fas fa-edit',
                color: 'info'
            },
            {
                title: '📎 Langkah 3: Lampiran (Opsional)',
                content: 'Upload file pendukung seperti rubrik penilaian, contoh, atau materi tambahan jika diperlukan.',
                icon: 'fas fa-paperclip',
                color: 'warning'
            },
            {
                title: '🎯 Langkah 4: Pilih Target',
                content: 'Tentukan kelas dan siswa yang akan menerima tugas ini. Anda bisa memilih semua kelas atau kelas tertentu.',
                icon: 'fas fa-users',
                color: 'secondary'
            },
            {
                title: '✅ Langkah 5: Publikasikan',
                content: 'Review semua informasi, lalu klik "Publikasikan" untuk mengirim tugas kepada siswa. Siswa akan langsung menerima notifikasi.',
                icon: 'fas fa-paper-plane',
                color: 'success'
            }
        ];
        
        let currentStep = 0;
        showTutorialStep(currentStep);
        
        function showTutorialStep(step) {
            if (step >= tutorialSteps.length) {
                showTutorialComplete();
                return;
            }
            
            const tutorial = tutorialSteps[step];
            const isLast = step === tutorialSteps.length - 1;
            const progress = ((step + 1) / tutorialSteps.length) * 100;
            
            const modal = `
                <div class="modal fade" id="tutorialModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow-lg overflow-hidden">
                            <div class="modal-header bg-gradient-${tutorial.color} text-white border-0 position-relative">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                        <i class="${tutorial.icon} fa-2x"></i>
                                    </div>
                                    <div>
                                        <h4 class="modal-title mb-1">${tutorial.title}</h4>
                                        <small class="text-white-50">Langkah ${step + 1} dari ${tutorialSteps.length}</small>
                                    </div>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="position-absolute bottom-0 start-0 w-100 bg-white bg-opacity-20" style="height: 4px;">
                                    <div class="bg-white" style="height: 100%; width: ${progress}%; transition: width 0.3s ease;"></div>
                                </div>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <p class="fs-5 mb-3 text-muted">${tutorial.content}</p>
                                        <div class="d-flex align-items-center text-success mb-3">
                                            <i class="fas fa-lightbulb me-2"></i>
                                            <small><strong>Tips:</strong> ${getTipForStep(step)}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="bg-light rounded-3 p-4">
                                            <i class="${tutorial.icon} fa-4x text-${tutorial.color} mb-3 animation-bounce"></i>
                                            <div class="small text-muted">Langkah ${step + 1}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Progress indicators -->
                                <div class="d-flex justify-content-center mt-4">
                                    ${tutorialSteps.map((_, index) => `
                                        <div class="mx-1">
                                            <div class="rounded-circle ${index <= step ? 'bg-primary' : 'bg-light'} border-0" style="width: 12px; height: 12px; transition: all 0.3s ease;"></div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <div class="d-flex justify-content-between w-100">
                                    <div>
                                        ${step > 0 ? `<button type="button" class="btn btn-outline-secondary" id="prevStep">
                                            <i class="fas fa-arrow-left me-2"></i>Sebelumnya
                                        </button>` : '<div></div>'}
                                    </div>
                                    <div>
                                        ${!isLast ? `<button type="button" class="btn btn-outline-secondary me-2" id="skipTutorial">
                                            Lewati Tutorial
                                        </button>` : ''}
                                        <button type="button" class="btn btn-${isLast ? 'success' : 'primary'} btn-lg" id="nextStep">
                                            ${isLast ? '<i class="fas fa-rocket me-2"></i>Mulai Membuat Tugas!' : `Selanjutnya <i class="fas fa-arrow-right ms-2"></i>`}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal and add new one
            $('#tutorialModal').remove();
            $('body').append(modal);
            
            // Show modal with animation
            const $modal = $('#tutorialModal');
            $modal.modal('show');
            
            // Add bounce animation to icon
            setTimeout(() => {
                $modal.find('.animation-bounce').addClass('animate__animated animate__bounce');
            }, 500);
            
            // Event handlers
            $('#nextStep').on('click', function() {
                $modal.modal('hide');
                if (isLast) {
                    // Redirect to create assignment page
                    setTimeout(() => {
                        window.location.href = '{{ route("guru.assignments.create") }}';
                    }, 300);
                } else {
                    setTimeout(() => showTutorialStep(step + 1), 300);
                }
            });
            
            $('#prevStep').on('click', function() {
                $modal.modal('hide');
                setTimeout(() => showTutorialStep(step - 1), 300);
            });
            
            $('#skipTutorial').on('click', function() {
                $modal.modal('hide');
                setTimeout(() => {
                    if (confirm('Apakah Anda yakin ingin melewati tutorial dan langsung membuat tugas?')) {
                        window.location.href = '{{ route("guru.assignments.create") }}';
                    }
                }, 300);
            });
        }
        
        function showTutorialComplete() {
            const completeModal = `
                <div class="modal fade" id="tutorialCompleteModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg text-center">
                            <div class="modal-body p-5">
                                <div class="mb-4">
                                    <i class="fas fa-check-circle fa-5x text-success animate__animated animate__bounceIn"></i>
                                </div>
                                <h3 class="text-success mb-3">Tutorial Selesai!</h3>
                                <p class="text-muted mb-4">Anda siap untuk membuat tugas pertama. Ingat, Anda selalu bisa mengakses bantuan melalui menu atau hubungi admin jika ada pertanyaan.</p>
                                <button type="button" class="btn btn-success btn-lg" id="startCreating">
                                    <i class="fas fa-rocket me-2"></i>Mulai Membuat Tugas
                                </button>
                                <button type="button" class="btn btn-outline-secondary ms-2" data-bs-dismiss="modal">
                                    Nanti Saja
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#tutorialCompleteModal').remove();
            $('body').append(completeModal);
            $('#tutorialCompleteModal').modal('show');
            
            $('#startCreating').on('click', function() {
                window.location.href = '{{ route("guru.assignments.create") }}';
            });
        }
        
        function getTipForStep(step) {
            const tips = [
                'Tugas yang baik membantu siswa belajar lebih efektif',
                'Judul yang menarik akan membuat siswa lebih tertarik mengerjakan',
                'Deskripsi yang jelas mengurangi kebingungan siswa',
                'File lampiran membantu siswa memahami ekspektasi Anda',
                'Pilih kelas yang tepat untuk memastikan tugas sampai ke siswa yang benar',
                'Pastikan semua informasi sudah benar sebelum dipublikasikan'
            ];
            return tips[step] || 'Ikuti langkah-langkah dengan cermat';
        }
    };
    
    // Enhanced table row interactions
    $('.assignment-table tbody tr').hover(
        function() {
            $(this).find('.btn-group').addClass('show-actions');
        },
        function() {
            $(this).find('.btn-group').removeClass('show-actions');
        }
    );
    
    // Smooth transitions for stats cards
    $('.bg-white.bg-opacity-15').hover(
        function() {
            $(this).addClass('scale-110 shadow-sm');
        },
        function() {
            $(this).removeClass('scale-110 shadow-sm');
        }
    );
    
    // Enhanced loading for action buttons
    $('.btn-group .btn').on('click', function(e) {
        if (!$(this).hasClass('btn-danger')) {
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<i class="fas fa-spinner fa-spin"></i>');
            
            // Reset after a delay (in real app, this would be after the action completes)
            setTimeout(() => {
                $btn.html(originalHtml);
            }, 1000);
        }
    });
    
    // Add shimmer effect to empty state
    if ($('.empty-state-container').length) {
        setTimeout(() => {
            $('.empty-state-content').addClass('fade-in-up');
        }, 300);
    }
    
    // Counter animation for stats
    function animateCounter($element, target) {
        const startValue = 0;
        const duration = 1000;
        const increment = target / (duration / 16);
        let current = startValue;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            $element.text(Math.floor(current));
        }, 16);
    }
    
    // Animate stats on page load
    $('.h5, .h6').each(function() {
        const $this = $(this);
        const target = parseInt($this.text()) || 0;
        if (target > 0) {
            $this.text('0');
            setTimeout(() => {
                animateCounter($this, target);
            }, 500);
        }
    });
    
    console.log('📚 Enhanced Assignment Management System loaded!');
    console.log('💡 Keyboard shortcuts: Ctrl+N (New), Ctrl+H (History), Ctrl+Shift+A (Active)');
    console.log('✨ Micro-interactions and animations enabled!');
    
    // Add some helpful logging
    if (window.location.search.includes('tab=history')) {
        console.log('📊 History tab active - showing all assignment statistics');
    } else {
        console.log('⚡ Active tab loaded - showing current assignments');
    }
});
</script>
@endpush
