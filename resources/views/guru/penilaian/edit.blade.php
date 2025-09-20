@extends('layouts.guru')

@section('title', 'Edit Penilaian - SMK Kesehatan Trimurti Husada')

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
    
    .form-input:disabled {
        background-color: #f3f4f6;
        color: #6b7280;
        cursor: not-allowed;
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
    
    .space-x-3 > * + * {
        margin-left: 0.75rem;
    }
    
    .space-y-8 > * + * {
        margin-top: 2rem;
    }
    
    .space-y-4 > * + * {
        margin-top: 1rem;
    }
    
    .grid {
        display: grid;
    }
    
    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    .gap-6 {
        gap: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .md\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    
    .bg-blue-50 { background-color: #eff6ff; }
    .bg-green-50 { background-color: #f0fdf4; }
    .bg-yellow-50 { background-color: #fefce8; }
    .bg-gray-50 { background-color: #f9fafb; }
    .text-blue-800 { color: #1e40af; }
    .text-green-800 { color: #166534; }
    .text-yellow-800 { color: #92400e; }
    .text-gray-800 { color: #1f2937; }
    .text-gray-600 { color: #4b5563; }
    .text-gray-500 { color: #6b7280; }
    .text-red-600 { color: #dc2626; }
    .text-green-600 { color: #16a34a; }
    .text-yellow-600 { color: #ca8a04; }
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
    .mb-2 { margin-bottom: 0.5rem; }
    .mt-1 { margin-top: 0.25rem; }
    .mr-2 { margin-right: 0.5rem; }
    .mr-3 { margin-right: 0.75rem; }
    .ml-2 { margin-left: 0.5rem; }
    .ml-3 { margin-left: 0.75rem; }
    .border-b { border-bottom-width: 1px; }
    .border-t { border-top-width: 1px; }
    .border-gray-200 { border-color: #e5e7eb; }
    .w-4 { width: 1rem; }
    .h-4 { height: 1rem; }
    .w-5 { width: 1.25rem; }
    .h-5 { height: 1.25rem; }
    .w-6 { width: 1.5rem; }
    .h-6 { height: 1.5rem; }
    .flex-1 { flex: 1 1 0%; }
    .font-bold { font-weight: 700; }
    .font-semibold { font-weight: 600; }
    .font-medium { font-weight: 500; }
    .text-2xl { font-size: 1.5rem; line-height: 2rem; }
    .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
    .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
    .text-sm { font-size: 0.875rem; }
    .text-xs { font-size: 0.75rem; }
    .italic { font-style: italic; }
    .whitespace-pre-wrap { white-space: pre-wrap; }
    .animate-spin { animation: spin 1s linear infinite; }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Penilaian</h1>
    <p class="text-gray-600">Perbarui penilaian untuk siswa</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
        <h2 class="text-xl font-semibold text-blue-800">Edit Penilaian</h2>
        <p class="text-sm text-blue-600 mt-1">Terakhir diperbarui: {{ $submission->updated_at->format('d F Y H:i') }}</p>
    </div>

    <form action="{{ route('guru.penilaian.update', $submission->id) }}" method="POST" id="assessmentForm">
        @csrf
        @method('PUT')

        <div class="px-6 py-4 space-y-8">
            <!-- Informasi Penilaian -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-blue-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Penilaian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="form-label">Jenis Penilaian</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">
                            @if(isset($submission->assignment))
                                Tugas Teori
                            @else
                                Praktikum Keterampilan
                            @endif
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mata Pelajaran</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">
                            @if(isset($submission->assignment))
                                {{ $submission->assignment->subject->name ?? 'Tidak tersedia' }}
                            @elseif(isset($submission->practical))
                                {{ $submission->practical->subject->name ?? 'Tidak tersedia' }}
                            @else
                                Tidak tersedia
                            @endif
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Siswa</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">
                            {{ $submission->siswa->name ?? 'Tidak tersedia' }}
                            @if($submission->siswa)
                                (NIS: {{ $submission->siswa->nis ?? '-' }})
                            @endif
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kelas</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">
                            {{ $submission->siswa->kelas->nama_kelas ?? 'Tidak diketahui' }}
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Aktivitas</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">
                            @if(isset($submission->assignment))
                                {{ $submission->assignment->title ?? 'Tidak tersedia' }}
                            @elseif(isset($submission->practical))
                                {{ $submission->practical->judul ?? 'Tidak tersedia' }}
                            @else
                                Tidak tersedia
                            @endif
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Pengumpulan</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($submission->submitted_at)
                                {{ $submission->submitted_at->format('d F Y H:i') }}
                            @else
                                <span class="text-gray-500 italic">Belum dikumpulkan</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail Pengumpulan -->
@if(isset($submission->assignment) && $submission->file_path)
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-green-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Detail Pengumpulan Tugas
                </h3>
                <div class="space-y-4">
                    @if($submission->file_path)
                    <div>
                        <label class="form-label">File Tugas</label>
                        <div class="flex items-center mt-1 p-3 bg-white rounded-lg border border-gray-200">
                            <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ basename($submission->file_path) }}</p>
                                <p class="text-xs text-gray-500">
                                    @php
                                        $size = $submission->file_size ?? 0;
                                    @endphp
                                    {{ $size > 0 ? number_format($size / 1024, 1) . ' KB' : 'Ukuran tidak tersedia' }}
                                </p>
                            </div>
                            <a href="{{ Storage::url($submission->file_path) }}" download
                               class="text-blue-600 hover:text-blue-800 ml-2 p-2" title="Unduh file" aria-label="Unduh file">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($submission->content)
                    <div>
                        <label class="form-label">Teks Jawaban</label>
                        <div class="mt-1 p-4 bg-white rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $submission->content }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Detail Penilaian -->
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-yellow-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    Detail Penilaian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="score" class="form-label">Nilai *</label>
                        <input type="number" name="score" id="score" class="form-input"
                               value="{{ old('score', $submission->score) }}" min="0" max="100" step="0.1" required>
                        <div id="percentageDisplay" class="text-sm text-gray-500 mt-1">0% dari nilai maksimal</div>
                        @error('score')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="max_score" class="form-label">Nilai Maksimal *</label>
                        <input type="number" name="max_score" id="max_score" class="form-input" disabled readonly
                               value="@if(isset($submission->assignment)){{ $submission->assignment->max_score ?? 100 }}@else{{ $submission->practical->max_score ?? 100 }}@endif">
                        <p class="text-xs text-gray-500 mt-1">Nilai maksimal ditentukan dari aktivitas</p>
                        @error('max_score')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="feedback" class="form-label">Umpan Balik dan Catatan</label>
                    <textarea name="feedback" id="feedback" class="form-input" rows="4"
                              placeholder="Berikan umpan balik konstruktif untuk siswa...">{{ old('feedback', $submission->feedback) }}</textarea>
                    @error('feedback')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                Perbarui Penilaian
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set maximum date to today
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.getElementById('assessment_date');
    if (dateInput) {
        dateInput.max = today;
    }

    // Percentage calculator
    const scoreInput = document.getElementById('score');
    const maxScoreInput = document.getElementById('max_score');
    const percentageDisplay = document.getElementById('percentageDisplay');

    function updatePercentage() {
        if (!scoreInput || !maxScoreInput || !percentageDisplay) return;

        const score = parseFloat(scoreInput.value) || 0;
        const maxScore = parseFloat(maxScoreInput.value) || 100;
        const percentage = maxScore > 0 ? (score / maxScore * 100).toFixed(1) : 0;

        percentageDisplay.textContent = `${percentage}% dari nilai maksimal`;

        if (percentage >= 75) {
            percentageDisplay.className = 'text-sm text-green-600 mt-1';
        } else if (percentage >= 60) {
            percentageDisplay.className = 'text-sm text-yellow-600 mt-1';
        } else {
            percentageDisplay.className = 'text-sm text-red-600 mt-1';
        }
    }

    if (scoreInput) scoreInput.addEventListener('input', updatePercentage);
    if (maxScoreInput) maxScoreInput.addEventListener('input', updatePercentage);
    updatePercentage(); // Initial calculation

    // Form submit with loading state
    const form = document.getElementById('assessmentForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memperbarui...
            `;
        });
    }
});
</script>
@endpush
@endsection
