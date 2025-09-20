@extends('layouts.guru')

@section('title', 'Buat Absensi Baru - LMS Trimurti Husada')

@section('page-title', 'Buat Absensi Baru')
@section('page-subtitle', 'Input kehadiran siswa untuk mata pelajaran tertentu')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('guru.absensi.index') }}" class="text-decoration-none">Absensi</a></li>
<li class="breadcrumb-item active" aria-current="page">Buat Baru</li>
@endsection

@section('page-actions')
<a href="{{ route('guru.absensi.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-2"></i>Kembali
</a>
@endsection

@push('css')
<style>
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
                    <i class="fas fa-clipboard-check me-2 text-primary"></i>
                    Form Buat Absensi
                </h5>
            </div>

            <form action="{{ route('guru.absensi.store') }}" method="POST" id="attendanceForm" data-old-data='@json(old() ?? [])'>
                @csrf

                <div class="card-body">
                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h5 class="fw-medium text-dark mb-3 d-flex align-items-center">
                            <i class="fas fa-info-circle me-2 text-info"></i>
                            Informasi Dasar
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="subject_id" class="form-label">Mata Pelajaran *</label>
                                <select name="subject_id" id="subject_id" class="form-select" required onchange="loadStudents()">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="class" class="form-label">Kelas *</label>
                                <select name="class" id="class" class="form-select" required onchange="loadStudents()">
                                    <option value="">Pilih Kelas</option>
                                    @foreach($classes as $classId => $className)
                                    <option value="{{ $classId }}" {{ old('class') == $classId ? 'selected' : '' }}>
                                        {{ $className }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('class')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date" class="form-label">Tanggal *</label>
                                <input type="date" name="date" id="date" class="form-control"
                                       value="{{ old('date', date('Y-m-d')) }}"
                                       max="{{ date('Y-m-d') }}" required autocomplete="off">
                                @error('date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="session" class="form-label">Sesi *</label>
                                <select name="session" id="session" class="form-select" required autocomplete="off">
                                    <option value="">Pilih Sesi</option>
                                    <option value="1" {{ old('session') == '1' ? 'selected' : '' }}>Sesi 1 (07:00 - 09:00)</option>
                                    <option value="2" {{ old('session') == '2' ? 'selected' : '' }}>Sesi 2 (09:00 - 11:00)</option>
                                    <option value="3" {{ old('session') == '3' ? 'selected' : '' }}>Sesi 3 (11:00 - 13:00)</option>
                                    <option value="4" {{ old('session') == '4' ? 'selected' : '' }}>Sesi 4 (13:00 - 15:00)</option>
                                </select>
                                @error('session')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Students Attendance -->
                    <div id="studentsSection" class="mb-4" style="display: none;">
                        <h5 class="fw-medium text-dark mb-3 d-flex align-items-center">
                            <i class="fas fa-users me-2 text-success"></i>
                            Data Kehadiran Siswa
                        </h5>

                        <div class="alert alert-info d-flex align-items-center justify-content-between mb-4">
                            <span class="small">
                                <i class="fas fa-info-circle me-1"></i>
                                Total siswa: <span id="totalStudents" class="fw-bold">0</span>
                            </span>
                            <div class="btn-group btn-group-sm">
                                <button type="button" onclick="markAll('hadir')"
                                        class="btn btn-success btn-sm">
                                    <i class="fas fa-check me-1"></i>Semua Hadir
                                </button>
                                <button type="button" onclick="markAll('alpha')"
                                        class="btn btn-danger btn-sm">
                                    <i class="fas fa-times me-1"></i>Semua Alpha
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Nama Siswa</th>
                                        <th scope="col">NIS</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Waktu</th>
                                        <th scope="col">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody id="studentsList">
                                    <!-- Students will be loaded here dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="mb-4">
                        <h5 class="fw-medium text-dark mb-3 d-flex align-items-center">
                            <i class="fas fa-sticky-note me-2 text-warning"></i>
                            Catatan Tambahan
                        </h5>
                        <div class="mb-3">
                            <label for="general_notes" class="form-label">Catatan Umum (Opsional)</label>
                            <textarea name="general_notes" id="general_notes" class="form-control" rows="3"
                                      placeholder="Masukkan catatan umum untuk absensi ini">{{ old('general_notes') }}</textarea>
                            @error('general_notes')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="{{ route('guru.absensi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" id="submitButton" class="btn btn-primary" disabled>
                        <i class="fas fa-save me-2"></i>Simpan Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set maximum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').max = today;

        // Check if we have old form data to reload
        const oldSubjectId = "{{ old('subject_id') }}";
        const oldClass = "{{ old('class') }}";

        if (oldSubjectId && oldClass) {
            setTimeout(loadStudents, 500);
        }
    });

    function loadStudents() {
        const subjectId = document.getElementById('subject_id').value;
        const classValue = document.getElementById('class').value;
        const studentsSection = document.getElementById('studentsSection');
        const submitButton = document.getElementById('submitButton');

        if (!subjectId || !classValue) {
            studentsSection.style.display = 'none';
            submitButton.disabled = true;
            return;
        }

        // Show loading state
        studentsSection.style.display = 'block';
        document.getElementById('studentsList').innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mb-0">Memuat data siswa...</p>
                    </div>
                </td>
            </tr>
        `;

        // Fetch students from server
        fetch(`/api/students?subject_id=${subjectId}&class=${classValue}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.students) {
                    renderStudentsList(data.students);
                    submitButton.disabled = false;
                } else {
                    showError('Gagal memuat data siswa: ' + (data.message || 'Data tidak tersedia'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat memuat: ' + error.message);
            });
    }

    function renderStudentsList(students) {
        const studentsList = document.getElementById('studentsList');
        const totalStudents = students.length;

        document.getElementById('totalStudents').textContent = totalStudents;

        if (totalStudents === 0) {
            studentsList.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-users-slash text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-0">Tidak ada siswa yang terdaftar untuk mata pelajaran dan kelas ini.</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';

        // Ambil data old() dari attribute
        const form = document.getElementById('attendanceForm');
        const oldData = JSON.parse(form.getAttribute('data-old-data') || '[]');

        students.forEach((student, index) => {
            const oldStatus = oldData[`attendances`]?.[index]?.['status'] || 'hadir';
            const oldTime = oldData[`attendances`]?.[index]?.['time'] || '';
            const oldNotes = oldData[`attendances`]?.[index]?.['notes'] || '';

            html += `
                <tr>
                    <td>
                        <input type="hidden" name="attendances[${index}][siswa_id]" value="${student.id}">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <img class="avatar rounded-circle"
                                     src="${student.photo_url || '/images/default-avatar.png'}"
                                     alt="${student.name}"
                                     onerror="this.src='/images/default-avatar.png'">
                            </div>
                            <div>
                                <div class="fw-medium">${student.name}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="small">${student.nis || 'N/A'}</span></td>
                    <td>
                        <select name="attendances[${index}][status]" class="form-select form-select-sm"
                                onchange="toggleTimeField(this, ${index})" required>
                            <option value="hadir" ${oldStatus === 'hadir' ? 'selected' : ''}>Hadir</option>
                            <option value="izin" ${oldStatus === 'izin' ? 'selected' : ''}>Izin</option>
                            <option value="sakit" ${oldStatus === 'sakit' ? 'selected' : ''}>Sakit</option>
                            <option value="alpha" ${oldStatus === 'alpha' ? 'selected' : ''}>Alpha</option>
                        </select>
                    </td>
                    <td>
                        <input type="time" name="attendances[${index}][waktu_masuk]"
                               class="form-control form-control-sm time-field-${index}"
                               value="${oldTime}"
                               ${oldStatus !== 'hadir' ? 'disabled' : ''}>
                    </td>
                    <td>
                        <input type="text" name="attendances[${index}][keterangan]"
                               class="form-control form-control-sm"
                               value="${oldNotes}"
                               placeholder="Keterangan (opsional)"
                               maxlength="100">
                    </td>
                </tr>
            `;
        });

        studentsList.innerHTML = html;
    }

    function toggleTimeField(selectElement, index) {
        const timeField = document.querySelector(`.time-field-${index}`);
        const status = selectElement.value;

        if (status === 'hadir') {
            timeField.disabled = false;
            timeField.required = true;
        } else {
            timeField.disabled = true;
            timeField.required = false;
            timeField.value = '';
        }
    }

    function markAll(status) {
        const statusSelects = document.querySelectorAll('select[name^="attendances"][name$="[status]"]');
        statusSelects.forEach(select => {
            select.value = status;
            const match = select.name.match(/\[(\d+)\]/);
            if (match) {
                const index = match[1];
                toggleTimeField(select, index);
            }
        });
    }

    function showError(message) {
        const studentsList = document.getElementById('studentsList');
        studentsList.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-5">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-exclamation-triangle text-danger mb-3" style="font-size: 3rem;"></i>
                        <p class="text-danger mb-3">${message}</p>
                        <button onclick="loadStudents()" class="btn btn-primary btn-sm">
                            <i class="fas fa-redo me-1"></i>Coba Lagi
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    // Validate form before submit
    document.getElementById('attendanceForm').addEventListener('submit', function(e) {
        const subjectId = document.getElementById('subject_id').value;
        const classValue = document.getElementById('class').value;

        if (!subjectId || !classValue) {
            e.preventDefault();
            alert('Silakan pilih mata pelajaran dan kelas terlebih dahulu.');
            return;
        }

        // Show loading state
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Menyimpan...
        `;
    });
</script>
@endpush
