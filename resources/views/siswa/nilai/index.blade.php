@extends('layouts.siswa')

@section('title', 'Nilai - LMS Trimurti Husada')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="font-weight-bold mb-4">Nilai Akademik</h2>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <form action="{{ route('siswa.nilai.index') }}" method="GET" class="search-form">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari mata pelajaran..." 
                           value="{{ request('search') }}"
                           aria-label="Cari mata pelajaran">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end">
                <div class="btn-group">
                    <button type="button" 
                            class="btn btn-outline-primary dropdown-toggle" 
                            data-bs-toggle="dropdown" 
                            aria-haspopup="true" 
                            aria-expanded="false"
                            aria-label="Filter semester">
                        <i class="fas fa-filter"></i> Filter Semester
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item {{ request('semester') == 'all' ? 'active' : '' }}" href="{{ route('siswa.nilai.index', ['semester' => 'all']) }}">Semua Semester</a></li>
                        <li><a class="dropdown-item {{ request('semester') == '1' ? 'active' : '' }}" href="{{ route('siswa.nilai.index', ['semester' => '1']) }}">Semester 1</a></li>
                        <li><a class="dropdown-item {{ request('semester') == '2' ? 'active' : '' }}" href="{{ route('siswa.nilai.index', ['semester' => '2']) }}">Semester 2</a></li>
                    </ul>
                </div>

                <!-- Export Buttons -->
                <div class="btn-group ms-2">
                    <a href="{{ route('siswa.nilai.export') }}" 
                       class="btn btn-outline-danger export-btn" 
                       title="Export ke PDF"
                       data-format="pdf">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                    <a href="{{ route('siswa.nilai.export') }}" 
                       class="btn btn-outline-success export-btn" 
                       title="Export ke Excel"
                       data-format="excel">
                        <i class="fas fa-file-excel"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Semester</th>
                                    <th>Nilai Tugas</th>
                                    <th>Nilai Praktikum</th>
                                    <th>Nilai UTS</th>
                                    <th>Nilai UAS</th>
                                    <th>Nilai Akhir</th>
                                    <th>Predikat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($scores as $subject => $scoreData)
                                    <tr>
                                        <td>{{ $subject }}</td>
                                        <td>{{ $scoreData['semester'] ?? '-' }}</td>
                                        <td>{{ $scoreData['assignment_score'] ?? '-' }}</td>
                                        <td>{{ $scoreData['practical_score'] ?? '-' }}</td>
                                        <td>{{ $scoreData['midterm_score'] ?? '-' }}</td>
                                        <td>{{ $scoreData['final_score'] ?? '-' }}</td>
                                        <td>
                                            <strong>{{ $scoreData['final_grade'] ?? '-' }}</strong>
                                        </td>
                                        <td>
                                            @if(isset($scoreData['final_grade']))
                                                @php
                                                    $grade = $scoreData['final_grade'];
                                                    $badgeClass = 'badge-secondary';
                                                    $gradeLetter = 'E';
                                                    if ($grade >= 90) {
                                                        $badgeClass = 'badge-success';
                                                        $gradeLetter = 'A';
                                                    } elseif ($grade >= 80) {
                                                        $badgeClass = 'badge-primary';
                                                        $gradeLetter = 'B';
                                                    } elseif ($grade >= 70) {
                                                        $badgeClass = 'badge-info';
                                                        $gradeLetter = 'C';
                                                    } elseif ($grade >= 60) {
                                                        $badgeClass = 'badge-warning';
                                                        $gradeLetter = 'D';
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $gradeLetter }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(Route::has('siswa.nilai.show'))
                                                <a href="{{ route('siswa.nilai.show', ['subject' => urlencode($subject), 'semester' => $scoreData['semester'] ?? 'all']) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Lihat detail nilai {{ $subject }}">
                                                    <i class="fas fa-chart-line"></i> Detail
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="alert alert-info mt-3">
                                                <i class="fas fa-info-circle"></i> Belum ada nilai yang tersedia.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(collect($scores)->count() > 0)
        <div class="row mt-4">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Rata-rata Nilai per Mata Pelajaran</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="subjectScoresChart" width="400" height="200"></canvas>
                        <div id="subjectChartFallback" class="text-center text-muted d-none mt-3">
                            <i class="fas fa-chart-bar fa-3x mb-3"></i>
                            <p>Grafik tidak dapat ditampilkan.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Distribusi Predikat</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="gradeDistributionChart" width="400" height="200"></canvas>
                        <div id="gradeChartFallback" class="text-center text-muted d-none mt-3">
                            <i class="fas fa-chart-pie fa-3x mb-3"></i>
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
@if(collect($scores)->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Loading state for search form
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            }
        });
    }

    // Loading state for export buttons
    document.querySelectorAll('.export-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const format = this.getAttribute('data-format');
            this.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            this.classList.add('disabled');
        });
    });

    // Chart untuk nilai per mata pelajaran
    const subjectCtx = document.getElementById('subjectScoresChart');
    if (subjectCtx && window.Chart) {
        const scoresData = @json($scores);
        const labels = Object.keys(scoresData || {});
        const finalGrades = labels.map(k => (scoresData[k] && scoresData[k].final_grade !== undefined && scoresData[k].final_grade !== null) ? scoresData[k].final_grade : null);
        const subjectChart = new Chart(subjectCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nilai Akhir',
                    data: finalGrades,
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
                            text: 'Nilai (0-100)'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Nilai Akhir per Mata Pelajaran'
                    }
                }
            }
        });
    } else if (subjectCtx) {
        subjectCtx.style.display = 'none';
        document.getElementById('subjectChartFallback').classList.remove('d-none');
    }

    // Chart untuk distribusi predikat
    const gradeCtx = document.getElementById('gradeDistributionChart');
    if (gradeCtx && window.Chart) {
        // Hitung distribusi predikat
        const gradeCounts = {
            'A': 0,
            'B': 0,
            'C': 0,
            'D': 0,
            'E': 0
        };

        const scoresData = @json($scores);

        Object.values(scoresData).forEach(scoreData => {
            if (scoreData.final_grade !== undefined && scoreData.final_grade !== null) {
                if (scoreData.final_grade >= 90) {
                    gradeCounts['A']++;
                } else if (scoreData.final_grade >= 80) {
                    gradeCounts['B']++;
                } else if (scoreData.final_grade >= 70) {
                    gradeCounts['C']++;
                } else if (scoreData.final_grade >= 60) {
                    gradeCounts['D']++;
                } else {
                    gradeCounts['E']++;
                }
            }
        });

        const gradeChart = new Chart(gradeCtx, {
            type: 'pie',
            data: {
                labels: ['A', 'B', 'C', 'D', 'E'],
                datasets: [{
                    data: [gradeCounts['A'], gradeCounts['B'], gradeCounts['C'], gradeCounts['D'], gradeCounts['E']],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw + ' mata pelajaran';
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Predikat Nilai'
                    }
                }
            }
        });
    } else if (gradeCtx) {
        gradeCtx.style.display = 'none';
        document.getElementById('gradeChartFallback').classList.remove('d-none');
    }
});
</script>
@endif
@endpush
