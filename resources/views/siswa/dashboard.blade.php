@extends('siswa.layouts.siswa-layout')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="font-weight-bold mb-4">Dashboard Siswa</h2>
            <p class="text-muted">Selamat datang, <strong>{{ auth()->user()->name }}</strong>! Berikut ringkasan aktivitas belajar Anda.</p>
        </div>
    </div>

    <div class="row">
        <!-- Statistik Ringkas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card hover-lift">
                <div class="card-body">
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stats-info">
                        <h5 class="stats-count">{{ $newMaterialsCount }}</h5>
                        <p class="stats-label">Materi Baru</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card hover-lift">
                <div class="card-body">
                    <div class="stats-icon bg-success">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stats-info">
                        <h5 class="stats-count">{{ $pendingAssignmentsCount }}</h5>
                        <p class="stats-label">Tugas Belum Selesai</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card hover-lift">
                <div class="card-body">
                    <div class="stats-icon bg-info">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div class="stats-info">
                        <h5 class="stats-count">{{ $upcomingPracticalsCount }}</h5>
                        <p class="stats-label">Praktikum Mendatang</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card hover-lift">
                <div class="card-body">
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stats-info">
                        <h5 class="stats-count">{{ $attendancePercentage }}%</h5>
                        <p class="stats-label">Kehadiran (Bulan Ini)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Materi Terbaru -->
        <div class="col-lg-6 mb-4">
            <div class="card hover-lift">
                <div class="card-header d-flex flex-row align-items-center justify-content-between">
                    <h6 class="card-title mb-0 fw-bold">Materi Terbaru</h6>
                    <div class="btn-group">
                        <a href="{{ route('siswa.materials.index') }}" class="btn btn-sm btn-primary" title="Lihat semua materi">
                            <i class="fas fa-eye me-1"></i> Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentMaterials->count() > 0)
                        <div class="list-group material-list">
                            @foreach($recentMaterials as $material)
                                <a href="{{ route('siswa.materials.show', $material->id) }}"
                                   class="list-group-item list-group-item-action flex-column align-items-start"
                                   title="Lihat detail materi: {{ $material->title }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            {{ $material->judul }}
                                            @if($material->created_at->diffInDays(now()) <= 7)
                                                <span class="badge bg-primary ms-2">Baru</span>
                                            @endif
                                        </h6>
                                        <small class="text-muted">{{ $material->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1 text-muted">{{ Str::limit($material->description, 100) }}</p>
                                    <small class="text-muted">Oleh: {{ optional($material->guru)->name ?? 'Tidak tersedia' }}</small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada materi terbaru</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tugas Terdekat -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tugas Deadline Terdekat</h6>
                    <div class="btn-group">
                        <a href="{{ route('siswa.assignments.index') }}" class="btn btn-sm btn-outline-primary" title="Lihat semua tugas">
                            <i class="fas fa-eye me-1"></i> Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($upcomingAssignments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($upcomingAssignments as $assignment)
                                <a href="{{ route('siswa.assignments.show', $assignment->id) }}"
                                   class="list-group-item list-group-item-action flex-column align-items-start"
                                   title="Lihat detail tugas: {{ $assignment->title }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $assignment->title }}</h6>
                                        <small class="text-danger">Batas: {{ $assignment->deadline->format('d M Y') }}</small>
                                    </div>
                                    <p class="mb-1 text-muted">{{ Str::limit($assignment->description, 100) }}</p>
                                    <small>
                                        Status:
                                        @if($assignment->submissions->where('siswa_id', Auth::id())->count() > 0)
                                            <span class="text-success fw-bold">✓ Terkumpul</span>
                                        @else
                                            <span class="text-warning fw-bold">⚠ Belum dikumpulkan</span>
                                        @endif
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada tugas dengan deadline terdekat</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(isset($averageScores) && count($averageScores) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rata-rata Nilai per Mata Pelajaran</h6>
                </div>
                <div class="card-body">
                    <canvas id="scoresChart" width="400" height="200"></canvas>
                    <div id="chartFallback" class="text-center text-muted d-none mt-3">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <p>Grafik tidak dapat ditampilkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('js')
<style>
.hover-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Loading state for export buttons
    document.querySelectorAll('.export-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const format = this.getAttribute('data-format');
            this.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            this.classList.add('disabled');
        });
    });

    // Chart untuk nilai rata-rata
    const ctx = document.getElementById('scoresChart');
    if (ctx && window.Chart) {
        const scoresChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json(array_keys($averageScores ?? [])),
                datasets: [{
                    label: 'Rata-rata Nilai',
                    data: @json(array_values($averageScores ?? [])),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Nilai Rata-rata (0-100)'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Rata-rata Nilai per Mata Pelajaran'
                    }
                }
            }
        });
    } else if (ctx) {
        ctx.style.display = 'none';
        document.getElementById('chartFallback').classList.remove('d-none');
    }
});
</script>
@endpush
