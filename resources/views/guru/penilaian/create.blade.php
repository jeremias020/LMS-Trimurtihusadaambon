@extends('layouts.guru')

@section('title', 'Buat Penilaian Baru - SMK Kesehatan Trimurti Husada')

@push('css')
<style>
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background-color: #fff;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .btn-primary {
        background-color: #3b82f6;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.375rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
    }
    
    .btn-secondary {
        background-color: #6b7280;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.375rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .btn-secondary:hover {
        background-color: #4b5563;
        color: white;
        text-decoration: none;
    }
    
    /* Additional utility classes */
    .space-x-3 > * + * { margin-left: 0.75rem; }
    .space-y-8 > * + * { margin-top: 2rem; }
    .grid { display: grid; }
    .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
    .gap-6 { gap: 1.5rem; }
    @media (min-width: 768px) {
        .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    .bg-blue-50 { background-color: #eff6ff; }
    .bg-green-50 { background-color: #f0fdf4; }
    .bg-yellow-50 { background-color: #fefce8; }
    .bg-purple-50 { background-color: #faf5ff; }
    .bg-gray-50 { background-color: #f9fafb; }
    .text-blue-800 { color: #1e40af; }
    .text-green-800 { color: #166534; }
    .text-yellow-800 { color: #92400e; }
    .text-purple-800 { color: #6b21a8; }
    .text-gray-800 { color: #1f2937; }
    .text-gray-600 { color: #4b5563; }
    .text-red-600 { color: #dc2626; }
    .rounded-lg { border-radius: 0.5rem; }
    .shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
    .overflow-hidden { overflow: hidden; }
    .flex { display: flex; }
    .items-center { align-items: center; }
    .justify-end { justify-content: flex-end; }
    .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
    .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
    .p-4 { padding: 1rem; }
    .p-3 { padding: 0.75rem; }
    .mb-6 { margin-bottom: 1.5rem; }
    .mb-4 { margin-bottom: 1rem; }
    .mt-1 { margin-top: 0.25rem; }
    .mr-2 { margin-right: 0.5rem; }
    .border-b { border-bottom-width: 1px; }
    .border-t { border-top-width: 1px; }
    .border-gray-200 { border-color: #e5e7eb; }
    .w-4 { width: 1rem; }
    .h-4 { height: 1rem; }
    .w-5 { width: 1.25rem; }
    .h-5 { height: 1.25rem; }
    .font-bold { font-weight: 700; }
    .font-semibold { font-weight: 600; }
    .font-medium { font-weight: 500; }
    .text-2xl { font-size: 1.5rem; line-height: 2rem; }
    .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
    .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
    .text-sm { font-size: 0.875rem; }
    .text-xs { font-size: 0.75rem; }
    .animate-spin { animation: spin 1s linear infinite; }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Buat Penilaian Baru</h1>
    <p class="text-gray-600">Buat penilaian untuk tugas atau praktikum siswa</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
        <h2 class="text-xl font-semibold text-blue-800">Formulir Penilaian</h2>
        <p class="text-sm text-blue-600 mt-1">Lengkapi data penilaian dengan benar</p>
    </div>

    <form action="{{ route('guru.penilaian.store') }}" method="POST" id="assessmentForm">
        @csrf

        <div class="px-6 py-4 space-y-8">
            <!-- Jenis Penilaian -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-blue-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Jenis Penilaian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="type" class="form-label">Jenis Penilaian *</label>
                        <select name="type" id="type" class="form-input" required onchange="toggleAssessmentType()" aria-label="Pilih jenis penilaian">
                            <option value="">Pilih Jenis Penilaian</option>
                            <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>Tugas Teori</option>
                            <option value="practical" {{ old('type') == 'practical' ? 'selected' : '' }}>Praktikum Keterampilan</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="siswa_id" class="form-label">Siswa *</label>
                        <select name="siswa_id" id="siswa_id" class="form-input" required>
                            <option value="">Pilih Siswa</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('siswa_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} - {{ $student->kelas->nama_kelas ?? 'Tidak ada kelas' }}
                            </option>
                            @endforeach
                        </select>
                        @error('siswa_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pemilihan Aktivitas -->
            <div id="activitySection">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pilih Aktivitas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tugas Selection -->
                    <div class="form-group" id="assignmentField" style="display: none;">
                        <label for="assignment_id" class="form-label">Pilih Tugas *</label>
                        <select name="assignment_id" id="assignment_id" class="form-input">
                            <option value="">Pilih Tugas</option>
                            @foreach($assignments as $assignment)
                            <option value="{{ $assignment->id }}" data-max-score="{{ $assignment->max_score }}" {{ old('assignment_id') == $assignment->id ? 'selected' : '' }}>
                                {{ $assignment->title }} - {{ $assignment->subject->name ?? 'N/A' }}
                            </option>
                            @endforeach
                        </select>
                        @error('assignment_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Praktikum Selection -->
                    <div class="form-group" id="practicalField" style="display: none;">
                        <label for="practical_id" class="form-label">Pilih Praktikum *</label>
                        <select name="practical_id" id="practical_id" class="form-input">
                            <option value="">Pilih Praktikum</option>
                            @foreach($practicals as $practical)
                            <option value="{{ $practical->id }}" data-max-score="{{ $practical->max_score }}" {{ old('practical_id') == $practical->id ? 'selected' : '' }}>
                                {{ $practical->judul }} - {{ $practical->subject->name ?? 'N/A' }}
                            </option>
                            @endforeach
                        </select>
                        @error('practical_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>


            <!-- Detail Penilaian -->
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-yellow-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Detail Penilaian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="score" class="form-label">Nilai *</label>
                        <input type="number" name="score" id="score" class="form-input"
                               value="{{ old('score') }}" min="0" max="100" step="0.1"
                               placeholder="0-100" required>
                        <div id="percentageDisplay" class="text-sm text-gray-500 mt-1">0% dari nilai maksimal</div>
                        @error('score')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nilai Maksimal</label>
                        <div class="form-input bg-gray-50" id="max_score_display">Pilih aktivitas terlebih dahulu</div>
                        <p class="text-xs text-gray-500 mt-1">Nilai maksimal ditentukan dari aktivitas yang dipilih</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="feedback" class="form-label">Umpan Balik dan Catatan</label>
                    <textarea name="feedback" id="feedback" class="form-input" rows="4"
                              placeholder="Berikan umpan balik konstruktif untuk siswa...">{{ old('feedback') }}</textarea>
                    @error('feedback')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tanggal Penilaian -->
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-purple-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Tanggal Penilaian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="assessment_date" class="form-label">Tanggal Penilaian *</label>
                        <input type="date" name="assessment_date" id="assessment_date" class="form-input"
                               value="{{ old('assessment_date', date('Y-m-d')) }}" required>
                        @error('assessment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <p class="form-label">Info</p>
                        <div class="p-3 bg-white rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">Nilai otomatis terpublikasi</span>
                            </p>
                            <p class="text-xs text-gray-500">Siswa dapat langsung melihat nilai setelah disimpan</p>
                        </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('guru.penilaian.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Batal
            </a>
            <button type="submit" class="btn-primary" id="submitBtn">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Penilaian
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleAssessmentType() {
    const type = document.getElementById('type').value;
    const assignmentField = document.getElementById('assignmentField');
    const practicalField = document.getElementById('practicalField');
    const assignmentSelect = document.getElementById('assignment_id');
    const practicalSelect = document.getElementById('practical_id');
    const maxScoreDisplay = document.getElementById('max_score_display');
    const scoreInput = document.getElementById('score');

    if (type === 'assignment') {
        assignmentField.style.display = 'block';
        practicalField.style.display = 'none';
        assignmentSelect.required = true;
        practicalSelect.required = false;
        if (maxScoreDisplay) maxScoreDisplay.textContent = 'Pilih tugas terlebih dahulu';
    } else if (type === 'practical') {
        assignmentField.style.display = 'none';
        practicalField.style.display = 'block';
        assignmentSelect.required = false;
        practicalSelect.required = true;
        if (maxScoreDisplay) maxScoreDisplay.textContent = 'Pilih praktikum terlebih dahulu';
    } else {
        assignmentField.style.display = 'none';
        practicalField.style.display = 'none';
        assignmentSelect.required = false;
        practicalSelect.required = false;
        if (maxScoreDisplay) maxScoreDisplay.textContent = 'Pilih aktivitas terlebih dahulu';
    }
    
    // Reset score
    if (scoreInput) {
        scoreInput.setAttribute('max', '1000');
        scoreInput.value = '';
    }
}

function updateMaxScore(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const maxScore = selectedOption.getAttribute('data-max-score') || 100;
    const maxScoreDisplay = document.getElementById('max_score_display');
    const scoreInput = document.getElementById('score');
    
    if (maxScoreDisplay) {
        maxScoreDisplay.textContent = maxScore;
    }
    
    if (scoreInput) {
        scoreInput.setAttribute('max', maxScore);
        
        // Validate current score against new max
        if (parseFloat(scoreInput.value) > parseFloat(maxScore)) {
            scoreInput.value = maxScore;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize toggle
    toggleAssessmentType();

    // Set max date to today
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.getElementById('assessment_date');
    if (dateInput) {
        dateInput.max = today;
    }

    // Assignment selection change
    const assignmentSelect = document.getElementById('assignment_id');
    if (assignmentSelect) {
        assignmentSelect.addEventListener('change', function() {
            updateMaxScore(this);
        });
    }

    // Practical selection change
    const practicalSelect = document.getElementById('practical_id');
    if (practicalSelect) {
        practicalSelect.addEventListener('change', function() {
            updateMaxScore(this);
        });
    }

    // Score validation
    const scoreInput = document.getElementById('score');
    if (scoreInput) {
        scoreInput.addEventListener('input', function() {
            const maxScore = parseFloat(this.getAttribute('max'));
            const currentScore = parseFloat(this.value);
            
            if (currentScore > maxScore) {
                this.value = maxScore;
                alert(`Nilai tidak boleh melebihi ${maxScore}`);
            }
        });
    }

    // Form validation and loading state
    const form = document.getElementById('assessmentForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            const type = document.getElementById('type')?.value;

            // Validate conditional fields
            if (type === 'assignment') {
                const assignmentId = document.getElementById('assignment_id')?.value;
                if (!assignmentId) {
                    e.preventDefault();
                    alert('Silakan pilih tugas yang akan dinilai');
                    document.getElementById('assignment_id')?.focus();
                    return;
                }
            } else if (type === 'practical') {
                const practicalId = document.getElementById('practical_id')?.value;
                if (!practicalId) {
                    e.preventDefault();
                    alert('Silakan pilih praktikum yang akan dinilai');
                    document.getElementById('practical_id')?.focus();
                    return;
                }
            }

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
        });
    }
});
</script>
@endpush
@endsection
