@extends('layouts.guru')

@section('title', 'Penilaian Otomatis SOP')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Penilaian Otomatis SOP</h1>
            <p class="text-muted mb-0">Sistem penilaian praktik berdasarkan Standar Operasional Prosedur</p>
        </div>
        <div>
            <a href="{{ route('guru.penilaian.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Student & Practical Selection -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user-graduate mr-2"></i>Pilih Siswa dan Praktik
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="student_id" class="form-label font-weight-bold">Siswa *</label>
                        <select name="student_id" id="student_id" class="form-control" required>
                            <option value="">Pilih Siswa</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}" 
                                    data-name="{{ $student->name }}"
                                    data-class="{{ $student->siswa->kelas->name ?? 'N/A' }}"
                                    data-nis="{{ $student->siswa->nis ?? 'N/A' }}">
                                {{ $student->name }} - {{ $student->siswa->nis ?? 'N/A' }} - {{ $student->siswa->kelas->name ?? 'N/A' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="practical_id" class="form-label font-weight-bold">Praktik *</label>
                        <select name="practical_id" id="practical_id" class="form-control" required>
                            <option value="">Pilih Praktik</option>
                            @foreach($practicals as $practical)
                            <option value="{{ $practical->id }}" 
                                    data-title="{{ $practical->judul }}"
                                    data-subject="{{ $practical->subject->name ?? 'N/A' }}"
                                    data-max-score="{{ $practical->max_score }}"
                                    data-class="{{ $practical->kelas->name ?? 'N/A' }}">
                                {{ $practical->judul }} - {{ $practical->subject->name ?? 'N/A' }} ({{ $practical->kelas->name ?? 'N/A' }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assessment Form -->
    <form id="autoAssessmentForm" method="POST" action="{{ route('guru.penilaian.auto-criteria.save') }}">
        @csrf
        
        <!-- Practical Info Card -->
        <div class="card shadow mb-4" id="practicalInfo" style="display: none;">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Praktik
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Judul:</strong> <span id="practicalTitle">-</span></p>
                        <p class="mb-2"><strong>Mata Pelajaran:</strong> <span id="practicalSubject">-</span></p>
                        <p class="mb-2"><strong>Nilai Maksimum:</strong> <span id="practicalMaxScore">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Tanggal:</strong> <input type="date" name="assessment_date" class="form-control" required></p>
                        <p class="mb-2"><strong>Feedback:</strong> <textarea name="feedback" class="form-control" rows="3" placeholder="Berikan feedback untuk siswa..."></textarea></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SOP Assessment Criteria -->
        <div class="card shadow mb-4" id="assessmentCriteria" style="display: none;">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clipboard-check mr-2"></i>Kriteria SOP
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Persiapan SOP -->
                    <div class="col-md-4">
                        <h6 class="text-center mb-3 text-info">
                            <i class="fas fa-clipboard-list mr-2"></i>Persiapan SOP (35%)
                        </h6>
                        <div class="list-group">
                            <label class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <input type="checkbox" name="kriteria_nilai[prep_1]" value="1" class="mr-2">
                                        Ceklis daftar bahan
                                    </span>
                                    <small class="text-muted">10%</small>
                                </div>
                            </label>
                            <label class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <input type="checkbox" name="kriteria_nilai[prep_2]" value="1" class="mr-2">
                                        Persiapan alat kerja
                                    </span>
                                    <small class="text-muted">10%</small>
                                </div>
                            </label>
                            <label class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <input type="checkbox" name="kriteria_nilai[prep_3]" value="1" class="mr-2">
                                        Pemahaman SOP
                                    </span>
                                    <small class="text-muted">15%</small>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Pelaksanaan SOP -->
                    <div class="col-md-4">
                        <h6 class="text-center mb-3 text-success">
                            <i class="fas fa-play-circle mr-2"></i>Pelaksanaan SOP (45%)
                        </h6>
                        <div class="list-group">
                            <label class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <input type="checkbox" name="kriteria_nilai[exec_1]" value="1" class="mr-2">
                                        Mengikuti prosedur
                                    </span>
                                    <small class="text-muted">20%</small>
                                </div>
                            </label>
                            <label class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <input type="checkbox" name="kriteria_nilai[exec_2]" value="1" class="mr-2">
                                        Keamanan kerja
                                    </span>
                                    <small class="text-muted">15%</small>
                                </div>
                            </label>
                            <label class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <input type="checkbox" name="kriteria_nilai[exec_3]" value="1" class="mr-2">
                                        Dokumentasi proses
                                    </span>
                                    <small class="text-muted">10%</small>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Evaluasi SOP -->
                    <div class="col-md-4">
                        <h6 class="text-center mb-3 text-warning">
                            <i class="fas fa-check-circle mr-2"></i>Evaluasi SOP (20%)
                        </h6>
                        <div class="list-group">
                            <label class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <input type="checkbox" name="kriteria_nilai[eval_1]" value="1" class="mr-2">
                                        Hasil sesuai standar
                                    </span>
                                    <small class="text-muted">15%</small>
                                </div>
                            </label>
                            <label class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <input type="checkbox" name="kriteria_nilai[eval_2]" value="1" class="mr-2">
                                        Laporan evaluasi
                                    </span>
                                    <small class="text-muted">5%</small>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Score Display -->
        <div class="card shadow mb-4" id="scoreDisplay" style="display: none;">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-calculator mr-2"></i>Hasil Penilaian SOP
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card border-left-primary">
                            <div class="card-body">
                                <h5 class="text-primary" id="prepScore">0%</h5>
                                <small>Persiapan SOP</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-left-success">
                            <div class="card-body">
                                <h5 class="text-success" id="execScore">0%</h5>
                                <small>Pelaksanaan SOP</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-left-warning">
                            <div class="card-body">
                                <h5 class="text-warning" id="evalScore">0%</h5>
                                <small>Evaluasi SOP</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-left-info">
                            <div class="card-body">
                                <h5 class="text-info" id="totalScore">0</h5>
                                <small>Total Nilai</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h4 class="text-center">
                        <span class="badge badge-primary badge-lg" id="gradeDisplay">-</span>
                    </h4>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center" id="submitSection" style="display: none;">
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="fas fa-save mr-2"></i>Simpan Penilaian SOP
            </button>
        </div>
    </form>
</div>

<!-- Custom Styles -->
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.text-primary {
    color: #4e73df !important;
}

.text-success {
    color: #1cc88a !important;
}

.text-warning {
    color: #f6c23e !important;
}

.text-info {
    color: #36b9cc !important;
}

.badge-primary {
    background-color: #4e73df;
}

.badge-lg {
    font-size: 1.25rem;
    padding: 0.5rem 1rem;
}

.list-group-item {
    border: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
    margin-bottom: 0.25rem;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.list-group-item:hover {
    background-color: #f8f9fc;
    border-color: #4e73df;
}

.list-group-item-action {
    display: block;
    text-decoration: none;
    color: #495057;
}

.font-weight-bold {
    font-weight: 700;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
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
</style>

<!-- JavaScript -->
<script>
$(document).ready(function() {
    let selectedPractical = null;
    
    // Handle practical selection
    $('#practical_id').change(function() {
        const practicalId = $(this).val();
        const practical = $('#practical_id option[value="' + practicalId + '"]');
        
        if (practicalId) {
            selectedPractical = {
                id: practicalId,
                title: practical.data('title'),
                subject: practical.data('subject'),
                maxScore: practical.data('max-score')
            };
            
            // Show practical info
            $('#practicalInfo').show();
            $('#practicalTitle').text(selectedPractical.title);
            $('#practicalSubject').text(selectedPractical.subject);
            $('#practicalMaxScore').text(selectedPractical.maxScore);
            
            // Show assessment criteria
            $('#assessmentCriteria').show();
            
            // Show submit button
            $('#submitSection').show();
        } else {
            $('#practicalInfo').hide();
            $('#assessmentCriteria').hide();
            $('#scoreDisplay').hide();
            $('#submitSection').hide();
        }
    });
    
    // Handle criteria selection
    $('input[name^="kriteria_nilai"]').change(function() {
        calculateScore();
    });
    
    function calculateScore() {
        if (!selectedPractical) return;
        
        const criteriaWeights = {
            'prep_1': 0.10, 'prep_2': 0.10, 'prep_3': 0.15,
            'exec_1': 0.20, 'exec_2': 0.15, 'exec_3': 0.10,
            'eval_1': 0.15, 'eval_2': 0.05
        };
        
        let prepScore = 0;
        let execScore = 0;
        let evalScore = 0;
        let totalWeightedScore = 0;
        
        // Calculate scores for each category
        $('input[name^="kriteria_nilai[prep_"]').each(function() {
            if (this.checked) {
                prepScore += 100 * criteriaWeights[this.name];
            }
        });
        
        $('input[name^="kriteria_nilai[exec_"]').each(function() {
            if (this.checked) {
                execScore += 100 * criteriaWeights[this.name];
            }
        });
        
        $('input[name^="kriteria_nilai[eval_"]').each(function() {
            if (this.checked) {
                evalScore += 100 * criteriaWeights[this.name];
            }
        });
        
        // Calculate total weighted score
        Object.keys(criteriaWeights).forEach(function(key) {
            if ($('input[name="kriteria_nilai[' + key + ']"]').prop('checked')) {
                totalWeightedScore += 100 * criteriaWeights[key];
            }
        });
        
        // Update display
        $('#prepScore').text(Math.round(prepScore) + '%');
        $('#execScore').text(Math.round(execScore) + '%');
        $('#evalScore').text(Math.round(evalScore) + '%');
        $('#totalScore').text(totalWeightedScore.toFixed(1));
        
        // Calculate grade
        let grade = 'E';
        if (totalWeightedScore >= 90) grade = 'A';
        else if (totalWeightedScore >= 80) grade = 'B';
        else if (totalWeightedScore >= 70) grade = 'C';
        else if (totalWeightedScore >= 60) grade = 'D';
        
        $('#gradeDisplay').text(grade);
        
        // Show score display if any criteria is selected
        if (totalWeightedScore > 0) {
            $('#scoreDisplay').show();
        }
    }
});
</script>
@endsection
