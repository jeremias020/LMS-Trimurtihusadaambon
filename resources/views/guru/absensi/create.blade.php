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
                                <label for="type" class="form-label">Tipe Absensi *</label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="regular" {{ old('type', 'regular') == 'regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="praktik" {{ old('type') == 'praktik' ? 'selected' : '' }}>Praktik</option>
                                </select>
                                @error('type')
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

                        <!-- Attendance Info Header -->
                        <div class="alert alert-primary mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Mata Pelajaran:</strong><br>
                                    <span id="selectedSubjectName">-</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Kelas:</strong><br>
                                    <span id="selectedClassName">-</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Tanggal:</strong><br>
                                    <span id="selectedDate">-</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Total Siswa:</strong><br>
                                    <span id="totalStudents" class="fw-bold">0</span>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info d-flex align-items-center justify-content-between mb-4">
                            <span class="small">
                                <i class="fas fa-info-circle me-1"></i>
                                Gunakan tombol di bawah untuk mengatur status semua siswa
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
    // Students data from controller
    const studentsData = @json($siswas ?? []);
    
    // Debug: Log students data
    console.log('Students Data:', studentsData);
    console.log('Students Count:', studentsData.length);
    
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
        const dateValue = document.getElementById('date').value;
        const studentsSection = document.getElementById('studentsSection');
        const submitButton = document.getElementById('submitButton');

        console.log('Loading students for class:', classValue);
        console.log('Available students:', studentsData.length);

        if (!classValue) {
            studentsSection.style.display = 'none';
            submitButton.disabled = true;
            return;
        }

        // Update header information
        updateAttendanceHeader(subjectId, classValue, dateValue);

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

        // Filter students from existing data
        setTimeout(() => {
            const filteredStudents = studentsData.filter(student => {
                console.log('Checking student:', student.name, 'class_id:', student.class_id, 'kelas:', student.kelas);
                return student.class_id == classValue || student.kelas?.id == classValue;
            });

            console.log('Filtered students:', filteredStudents.length);
            console.log('Filtered students data:', filteredStudents);

            if (filteredStudents.length > 0) {
                renderStudentsList(filteredStudents);
                submitButton.disabled = false;
            } else {
                showError('Tidak ada siswa di kelas yang dipilih');
            }
        }, 300);
    }

    function updateAttendanceHeader(subjectId, classValue, dateValue) {
        // Get subject name
        const subjectSelect = document.getElementById('subject_id');
        const subjectName = subjectSelect.options[subjectSelect.selectedIndex]?.text || '-';
        
        // Get class name
        const classSelect = document.getElementById('class');
        const className = classSelect.options[classSelect.selectedIndex]?.text || '-';
        
        // Format date
        let formattedDate = '-';
        if (dateValue) {
            const date = new Date(dateValue);
            formattedDate = date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
        
        // Update header elements
        document.getElementById('selectedSubjectName').textContent = subjectName;
        document.getElementById('selectedClassName').textContent = className;
        document.getElementById('selectedDate').textContent = formattedDate;
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

        // Get current time for default time input
        const currentTime = new Date().toTimeString().slice(0, 5);

        students.forEach((student, index) => {
            const oldStatus = oldData[`attendances`]?.[index]?.['status'] || 'hadir';
            const oldTime = oldData[`attendances`]?.[index]?.['waktu_masuk'] || currentTime;
            const oldNotes = oldData[`attendances`]?.[index]?.['keterangan'] || '';

            // Get NIS from student data or use default
            const nis = student.nis_nip || student.nis || 'N/A';
            const photoUrl = student.avatar || student.photo_url || '/images/default-avatar.png';
            const className = student.kelas?.name || 'Kelas ' + (student.class_id || '-');

            html += `
                <tr>
                    <td>
                        <input type="hidden" name="attendances[${index}][siswa_id]" value="${student.id}">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <img class="avatar rounded-circle"
                                     src="${photoUrl}"
                                     alt="${student.name}"
                                     onerror="this.src='/images/default-avatar.png'">
                            </div>
                            <div>
                                <div class="fw-medium">${student.name}</div>
                                <small class="text-muted">${className}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="small">${nis}</span></td>
                    <td>
                        <select name="attendances[${index}][status]" class="form-select form-select-sm"
                                onchange="toggleTimeField(this, ${index})" required>
                            <option value="present" ${oldStatus === 'present' ? 'selected' : ''}>Hadir</option>
                            <option value="sick" ${oldStatus === 'sick' ? 'selected' : ''}>Sakit</option>
                            <option value="permission" ${oldStatus === 'permission' ? 'selected' : ''}>Izin</option>
                            <option value="alpha" ${oldStatus === 'alpha' ? 'selected' : ''}>Alpha</option>
                        </select>
                    </td>
                    <td>
                        <input type="time" name="attendances[${index}][waktu_masuk]"
                               class="form-control form-control-sm time-field-${index}"
                               value="${oldTime}"
                               ${oldStatus !== 'present' ? 'disabled' : ''}>
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

        if (status === 'present') {
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
