@extends('layouts.guru')

@section('title', 'Buat Penilaian')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Penilaian</h1>
        <div class="text-muted">
            Tambahkan penilaian baru untuk tugas atau praktikum
        </div>
    </div>

    <!-- Form Card -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Penilaian</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('guru.penilaian.store') }}">
                @csrf
                
                <!-- Assessment Type Selection -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="assessment_type" class="form-label font-weight-bold">Tipe Penilaian</label>
                            <select class="form-control" id="assessment_type" name="assessment_type" required onchange="toggleAssessmentType()">
                                <option value="">-- Pilih Tipe Penilaian --</option>
                                <option value="assignment">Tugas</option>
                                <option value="practical">Praktikum</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Assignment Fields -->
                <div id="assignment-fields" style="display: none;">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assignment_id" class="form-label font-weight-bold">Pilih Tugas</label>
                                <select class="form-control" id="assignment_id" name="assignment_id">
                                    <option value="">-- Pilih Tugas --</option>
                                    @foreach($assignments as $assignment)
                                        <option value="{{ $assignment->id }}">
                                            {{ $assignment->title }} - {{ $assignment->subject->name ?? 'Tidak ada mata pelajaran' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assignment_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="siswa_id" class="form-label font-weight-bold">Pilih Siswa</label>
                                <select class="form-control" id="siswa_id" name="siswa_id">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->name }} ({{ $student->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('siswa_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Practical Fields -->
                <div id="practical-fields" style="display: none;">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="practical_id" class="form-label font-weight-bold">Pilih Praktikum</label>
                                <select class="form-control" id="practical_id" name="practical_id">
                                    <option value="">-- Pilih Praktikum --</option>
                                    @foreach($practicals as $practical)
                                        <option value="{{ $practical->id }}">
                                            {{ $practical->judul }} - {{ $practical->subject->name ?? 'Tidak ada mata pelajaran' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('practical_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="siswa_id_practical" class="form-label font-weight-bold">Pilih Siswa</label>
                                <select class="form-control" id="siswa_id_practical" name="siswa_id">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->name }} ({{ $student->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('siswa_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Score Fields -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="score" class="form-label font-weight-bold">Nilai</label>
                            <input type="number" class="form-control" id="score" name="score" 
                                   min="0" max="100" step="0.01" required>
                            @error('score')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="grade" class="form-label font-weight-bold">Grade</label>
                            <select class="form-control" id="grade" name="grade">
                                <option value="">-- Otomatis --</option>
                                <option value="A">A (85-100)</option>
                                <option value="B">B (70-84)</option>
                                <option value="C">C (55-69)</option>
                                <option value="D">D (40-54)</option>
                                <option value="E">E (0-39)</option>
                            </select>
                            @error('grade')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status" class="form-label font-weight-bold">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="draft">Draft</option>
                                <option value="final">Final</option>
                            </select>
                            @error('status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Feedback Fields -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="feedback" class="form-label font-weight-bold">Feedback</label>
                            <textarea class="form-control" id="feedback" name="feedback" rows="4" 
                                      placeholder="Berikan feedback kepada siswa..."></textarea>
                            @error('feedback')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </button>
                            </div>
                            <div>
                                <button type="reset" class="btn btn-warning mr-2">
                                    <i class="fas fa-redo mr-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Simpan Penilaian
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Card -->
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-info-circle mr-2"></i>Panduan Penilaian
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-primary mb-3">Tipe Penilaian</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-tasks text-primary mr-2"></i>
                            <strong>Tugas:</strong> Untuk menilai submission tugas siswa
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-flask text-success mr-2"></i>
                            <strong>Praktikum:</strong> Untuk menilai hasil praktikum siswa
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-primary mb-3">Kriteria Nilai</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="badge badge-success mr-2">A</span>
                            <span>85 - 100 (Sangat Baik)</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge badge-primary mr-2">B</span>
                            <span>70 - 84 (Baik)</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge badge-warning mr-2">C</span>
                            <span>55 - 69 (Cukup)</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge badge-danger mr-2">D</span>
                            <span>40 - 54 (Kurang)</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge badge-dark mr-2">E</span>
                            <span>0 - 39 (Sangat Kurang)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
.text-primary {
    color: #4e73df !important;
}

.text-info {
    color: #36b9cc !important;
}

.text-success {
    color: #1cc88a !important;
}

.text-warning {
    color: #f6c23e !important;
}

.text-danger {
    color: #e74a3b !important;
}

.text-secondary {
    color: #858796 !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.badge-primary {
    background-color: #4e73df;
}

.badge-success {
    background-color: #1cc88a;
}

.badge-warning {
    background-color: #f6c23e;
}

.badge-danger {
    background-color: #e74a3b;
}

.badge-dark {
    background-color: #5a5c69;
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

.btn-secondary {
    background-color: #858796;
    border-color: #858796;
}

.btn-secondary:hover {
    background-color: #717384;
    border-color: #6c6e7e;
}

.btn-warning {
    background-color: #f6c23e;
    border-color: #f6c23e;
}

.btn-warning:hover {
    background-color: #f4b619;
    border-color: #f3b115;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.form-label {
    color: #5a5c69;
    font-weight: 600;
}

.text-danger {
    color: #e74a3b !important;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.d-flex {
    display: flex;
}

.justify-content-between {
    justify-content: space-between;
}

.font-weight-bold {
    font-weight: 700;
}

.list-unstyled {
    list-style: none;
    padding-left: 0;
}

.mr-2 {
    margin-right: 0.5rem;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.mb-3 {
    margin-bottom: 1rem;
}

.mb-4 {
    margin-bottom: 1.5rem;
}

.mt-1 {
    margin-top: 0.25rem;
}
</style>

<!-- JavaScript -->
<script>
function toggleAssessmentType() {
    const assessmentType = document.getElementById('assessment_type').value;
    const assignmentFields = document.getElementById('assignment-fields');
    const practicalFields = document.getElementById('practical-fields');
    
    if (assessmentType === 'assignment') {
        assignmentFields.style.display = 'block';
        practicalFields.style.display = 'none';
        document.getElementById('siswa_id').required = true;
        document.getElementById('siswa_id_practical').required = false;
    } else if (assessmentType === 'practical') {
        assignmentFields.style.display = 'none';
        practicalFields.style.display = 'block';
        document.getElementById('siswa_id').required = false;
        document.getElementById('siswa_id_practical').required = true;
    } else {
        assignmentFields.style.display = 'none';
        practicalFields.style.display = 'none';
        document.getElementById('siswa_id').required = false;
        document.getElementById('siswa_id_practical').required = false;
    }
}

// Auto-calculate grade based on score
document.getElementById('score').addEventListener('input', function() {
    const score = parseFloat(this.value);
    const gradeSelect = document.getElementById('grade');
    
    if (score >= 85) {
        gradeSelect.value = 'A';
    } else if (score >= 70) {
        gradeSelect.value = 'B';
    } else if (score >= 55) {
        gradeSelect.value = 'C';
    } else if (score >= 40) {
        gradeSelect.value = 'D';
    } else if (score >= 0) {
        gradeSelect.value = 'E';
    } else {
        gradeSelect.value = '';
    }
});

// Sync siswa selection
document.getElementById('siswa_id').addEventListener('change', function() {
    document.getElementById('siswa_id_practical').value = this.value;
});

document.getElementById('siswa_id_practical').addEventListener('change', function() {
    document.getElementById('siswa_id').value = this.value;
});
</script>
@endsection
