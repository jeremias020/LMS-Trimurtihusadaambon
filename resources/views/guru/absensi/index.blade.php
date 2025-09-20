@extends('layouts.guru')

@section('title', 'Manajemen Absensi - LMS Trimurti Husada')

@section('page-title', 'Manajemen Absensi')
@section('page-subtitle', 'Kelola kehadiran siswa')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Absensi</li>
@endsection

@section('page-actions')
<a href="{{ route('guru.absensi.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>Buat Absensi
</a>
@endsection

@push('css')
<style>
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            object-fit: cover;
        }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Daftar Absensi
                </h5>
            </div>

            <div class="card-body">
                <!-- Filter and Search -->
                <div class="mb-4 row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" placeholder="Cari siswa..." class="form-control" id="searchInput" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="subjectFilter" autocomplete="off">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="classFilter" autocomplete="off">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $classId => $className)
                            <option value="{{ $classId }}">{{ $className }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" id="dateFilter" value="{{ date('Y-m-d') }}" autocomplete="off">
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="stats-card text-center bg-success bg-opacity-10 border border-success border-opacity-25">
                            <div class="h3 mb-1 text-success">{{ $stats['hadir'] ?? 0 }}</div>
                            <div class="small text-success fw-medium">Hadir</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stats-card text-center bg-info bg-opacity-10 border border-info border-opacity-25">
                            <div class="h3 mb-1 text-info">{{ $stats['izin'] ?? 0 }}</div>
                            <div class="small text-info fw-medium">Izin</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stats-card text-center bg-warning bg-opacity-10 border border-warning border-opacity-25">
                            <div class="h3 mb-1 text-warning">{{ $stats['sakit'] ?? 0 }}</div>
                            <div class="small text-warning fw-medium">Sakit</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stats-card text-center bg-danger bg-opacity-10 border border-danger border-opacity-25">
                            <div class="h3 mb-1 text-danger">{{ $stats['alpha'] ?? 0 }}</div>
                            <div class="small text-danger fw-medium">Alpha</div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Siswa</th>
                                <th scope="col">Kelas</th>
                                <th scope="col">Mata Pelajaran</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Waktu</th>
                                <th scope="col">Status</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                            <tr class="attendance-row" data-subject="{{ $attendance->siswa->kelas_id ?? '' }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <img class="avatar rounded-circle"
                                                 src="{{ $attendance->siswa->photo_url ?? asset('images/default-avatar.png') }}"
                                                 alt="{{ $attendance->siswa->name }}"
                                                 onerror="this.src='/images/default-avatar.png'">
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $attendance->siswa->name }}</div>
                                            <div class="small text-muted">NIS: {{ $attendance->siswa->siswa->nis ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-dark">{{ $attendance->siswa->kelas->name ?? 'Tidak diketahui' }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">-</span>
                                </td>
                                <td>
                                    <span class="small text-muted">{{ $attendance->tanggal->format('d/m/Y') }}</span>
                                </td>
                                <td>
                                    <span class="small">
                                    @if($attendance->waktu_masuk && $attendance->waktu_keluar)
                                        {{ $attendance->waktu_masuk->format('H:i') }} - {{ $attendance->waktu_keluar->format('H:i') }}
                                    @elseif($attendance->waktu_masuk)
                                        {{ $attendance->waktu_masuk->format('H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'hadir' => 'bg-success',
                                            'izin' => 'bg-info',
                                            'sakit' => 'bg-warning',
                                            'alpha' => 'bg-danger'
                                        ];
                                        $statusText = [
                                            'hadir' => 'Hadir',
                                            'izin' => 'Izin',
                                            'sakit' => 'Sakit',
                                            'alpha' => 'Alpha'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusClasses[$attendance->status] ?? 'bg-secondary' }}">
                                        {{ $statusText[$attendance->status] ?? $attendance->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="small text-muted">{{ $attendance->keterangan ?? '-' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('guru.absensi.edit', $attendance->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-4">
                                        <i class="fas fa-clipboard-list text-muted mb-3" style="font-size: 3rem;"></i>
                                        <h5 class="text-muted mb-2">Belum ada data absensi</h5>
                                        <p class="text-muted mb-4">Mulai dengan membuat absensi untuk hari ini.</p>
                                        <a href="{{ route('guru.absensi.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Buat Absensi
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($attendances->hasPages())
            <div class="card-footer">
                {{ $attendances->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const subjectFilter = document.getElementById('subjectFilter');
        const classFilter = document.getElementById('classFilter');
        const dateFilter = document.getElementById('dateFilter');
        const attendanceRows = document.querySelectorAll('.attendance-row');

        function filterAttendances() {
            const searchTerm = searchInput.value.toLowerCase();
            const subjectValue = subjectFilter.value;
            const classValue = classFilter.value;
            const dateValue = dateFilter.value;

            attendanceRows.forEach(row => {
                const studentName = row.querySelector('td:first-child').textContent.toLowerCase();
                const subject = row.dataset.subject;
                const classText = row.querySelector('td:nth-child(2)').textContent;
                const date = row.querySelector('td:nth-child(4)').textContent;

                // Convert date from dd/mm/yyyy to yyyy-mm-dd
                const dateParts = date.split('/');
                const rowDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;

                const matchesSearch = studentName.includes(searchTerm);
                const matchesSubject = !subjectValue || subject === subjectValue;
                const matchesClass = !classValue || classText.includes(classValue);
                const matchesDate = !dateValue || rowDate === dateValue;

                row.style.display = (matchesSearch && matchesSubject && matchesClass && matchesDate) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterAttendances);
        subjectFilter.addEventListener('change', filterAttendances);
        classFilter.addEventListener('change', filterAttendances);
        dateFilter.addEventListener('change', filterAttendances);

        // Initialize filters from URL parameters if present
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('subject')) {
            subjectFilter.value = urlParams.get('subject');
        }
        if (urlParams.has('class')) {
            classFilter.value = urlParams.get('class');
        }
        if (urlParams.has('date')) {
            dateFilter.value = urlParams.get('date');
        }
        if (urlParams.has('search')) {
            searchInput.value = urlParams.get('search');
        }

        // Apply initial filters
        filterAttendances();
    });
</script>
@endpush
