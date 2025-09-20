@extends('layouts.guru')

@section('title', 'Edit Praktikum - ' . $practical->title)

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Praktikum</h1>
    <p class="text-gray-600">Perbarui informasi kegiatan praktikum</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
        <h2 class="text-xl font-semibold text-blue-800">Edit: {{ $practical->title }}</h2>
        <p class="text-sm text-blue-600 mt-1">Terakhir diperbarui: {{ $practical->updated_at->translatedFormat('d F Y H:i') }}</p>
    </div>

    <form action="{{ route('guru.praktikum.update', $practical->id) }}" method="POST" enctype="multipart/form-data" id="practicalForm">
        @csrf
        @method('PUT')

        <div class="px-6 py-4 space-y-8">
            <!-- Informasi Dasar -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-blue-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Dasar Praktikum
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="title" class="form-label">Judul Praktikum *</label>
                        <input type="text" name="title" id="title" class="form-input"
                               value="{{ old('title', $practical->title) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subject_id" class="form-label">Mata Pelajaran *</label>
                        <select name="subject_id" id="subject_id" class="form-input" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $practical->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="class" class="form-label">Kelas *</label>
                        <select name="class" id="class" class="form-input" required>
                            <option value="">Pilih Kelas</option>
                            <option value="X" {{ old('class', $practical->class) == 'X' ? 'selected' : '' }}>Kelas X</option>
                            <option value="XI" {{ old('class', $practical->class) == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                            <option value="XII" {{ old('class', $practical->class) == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                        </select>
                        @error('class')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date" class="form-label">Tanggal Praktikum *</label>
                        <input type="date" name="date" id="date" class="form-input"
                               value="{{ old('date', $practical->date?->format('Y-m-d')) }}" required>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Waktu dan Durasi -->
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-green-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Waktu dan Durasi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="start_time" class="form-label">Waktu Mulai *</label>
                        <input type="time" name="start_time" id="start_time" class="form-input"
                               value="{{ old('start_time', $practical->start_time) }}" required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="duration" class="form-label">Durasi (menit) *</label>
                        <input type="number" name="duration" id="duration" class="form-input"
                               value="{{ old('duration', $practical->duration) }}" min="15" max="240" required>
                        @error('duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Deskripsi dan Tujuan -->
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-yellow-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Deskripsi dan Tujuan
                </h3>
                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi Singkat *</label>
                    <textarea name="description" id="description" class="form-input" rows="3" required>{{ old('description', $practical->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="objectives" class="form-label">Tujuan Pembelajaran *</label>
                    <textarea name="objectives" id="objectives" class="form-input" rows="4" required>{{ old('objectives', $practical->objectives) }}</textarea>
                    @error('objectives')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Bahan dan Alat -->
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-purple-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Bahan dan Alat Praktikum
                </h3>
                <div class="form-group">
                    <label for="materials" class="form-label">Bahan yang Diperlukan *</label>
                    <textarea name="materials" id="materials" class="form-input" rows="3" required>{{ old('materials', $practical->materials) }}</textarea>
                    @error('materials')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="equipment" class="form-label">Alat yang Diperlukan *</label>
                    <textarea name="equipment" id="equipment" class="form-input" rows="3" required>{{ old('equipment', $practical->equipment) }}</textarea>
                    @error('equipment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Prosedur Praktikum -->
            <div class="bg-red-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-red-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Prosedur Praktikum
                </h3>
                <div class="form-group">
                    <label for="procedures" class="form-label">Langkah Kerja *</label>
                    <textarea name="procedures" id="procedures" class="form-input" rows="6" required>{{ old('procedures', $practical->procedures) }}</textarea>
                    @error('procedures')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Live Preview -->
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Pratinjau Prosedur</h4>
                        <div id="preview" class="bg-white p-4 rounded border border-gray-200 min-h-32 prose max-w-none text-gray-800"></div>
                    </div>
                </div>
            </div>

            <!-- Keselamatan dan Penilaian -->
            <div class="bg-indigo-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-indigo-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Keselamatan dan Penilaian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="safety_notes" class="form-label">Catatan Keselamatan</label>
                        <textarea name="safety_notes" id="safety_notes" class="form-input" rows="3">{{ old('safety_notes', $practical->safety_notes) }}</textarea>
                        @error('safety_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="assessment_criteria" class="form-label">Kriteria Penilaian</label>
                        <textarea name="assessment_criteria" id="assessment_criteria" class="form-input" rows="3">{{ old('assessment_criteria', $practical->assessment_criteria) }}</textarea>
                        @error('assessment_criteria')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Lampiran -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    Lampiran Praktikum
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="worksheet" class="form-label">Lembar Kerja Siswa (Opsional)</label>
                        <div class="space-y-3">
                            @if($practical->worksheet_path)
                            <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ basename($practical->worksheet_path) }}</p>
                                    <p class="text-xs text-gray-500">File saat ini</p>
                                </div>
                                <a href="{{ Storage::url($practical->worksheet_path) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 ml-2" title="Lihat file">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                            @endif
                            <div>
                                <input type="file" name="worksheet" id="worksheet" class="form-input"
                                       accept=".pdf,.doc,.docx"
                                       @if(!old('worksheet')) max="10485760" @endif>
                                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah file</p>
                            </div>
                        </div>
                        @error('worksheet')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reference_material" class="form-label">Materi Referensi (Opsional)</label>
                        <div class="space-y-3">
                            @if($practical->reference_material_path)
                            <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ basename($practical->reference_material_path) }}</p>
                                    <p class="text-xs text-gray-500">File saat ini</p>
                                </div>
                                <a href="{{ Storage::url($practical->reference_material_path) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 ml-2" title="Lihat file">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                            @endif
                            <div>
                                <input type="file" name="reference_material" id="reference_material" class="form-input"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       @if(!old('reference_material')) max="10485760" @endif>
                                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah file</p>
                            </div>
                        </div>
                        @error('reference_material')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-blue-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Status Praktikum
                </h3>
                <div class="form-group">
                    <label for="status" class="form-label">Status *</label>
                    <select name="status" id="status" class="form-input" required>
                        <option value="upcoming" {{ old('status', $practical->status) == 'upcoming' ? 'selected' : '' }}>Mendatang</option>
                        <option value="ongoing" {{ old('status', $practical->status) == 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="completed" {{ old('status', $practical->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('guru.praktikum.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Batal
            </a>
            <button type="submit" class="btn-primary" id="submitBtn">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Perbarui Praktikum
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.getElementById('date');
    if (dateInput) {
        dateInput.min = today;
    }

    // Simple text editor for procedures
    const proceduresTextarea = document.getElementById('procedures');

    if (proceduresTextarea) {
        // Create editor tools
        const editorTools = document.createElement('div');
        editorTools.className = 'mb-2 flex flex-wrap items-center gap-2';
        editorTools.innerHTML = `
            <button type="button" onclick="formatText('bold')" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300" aria-label="Format tebal">B</button>
            <button type="button" onclick="formatText('italic')" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300" aria-label="Format miring">I</button>
            <button type="button" onclick="insertNumber()" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300" aria-label="Sisipkan nomor">1.</button>
            <button type="button" onclick="insertBullet()" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300" aria-label="Sisipkan poin">•</button>
            <p class="text-xs text-gray-500 ml-4">Gunakan format Markdown: **tebal**, _miring_</p>
        `;

        proceduresTextarea.parentNode.insertBefore(editorTools, proceduresTextarea);

        // Live preview
        const previewDiv = document.getElementById('preview');
        if (previewDiv) {
            proceduresTextarea.addEventListener('input', function() {
                previewDiv.innerHTML = marked.parse(this.value || '');
            });
            // Initial preview
            previewDiv.innerHTML = marked.parse(proceduresTextarea.value || '');
        }

        window.formatText = function(format) {
            const start = proceduresTextarea.selectionStart;
            const end = proceduresTextarea.selectionEnd;
            const selectedText = proceduresTextarea.value.substring(start, end);

            let formattedText = '';
            switch(format) {
                case 'bold':
                    formattedText = `**${selectedText}**`;
                    break;
                case 'italic':
                    formattedText = `_${selectedText}_`;
                    break;
            }

            proceduresTextarea.value = proceduresTextarea.value.substring(0, start) +
                                      formattedText +
                                      proceduresTextarea.value.substring(end);
            proceduresTextarea.focus();
            proceduresTextarea.setSelectionRange(start + 2, end + 2);
        };

        window.insertNumber = function() {
            const start = proceduresTextarea.selectionStart;
            proceduresTextarea.value = proceduresTextarea.value.substring(0, start) +
                                      '1. ' +
                                      proceduresTextarea.value.substring(start);
            proceduresTextarea.focus();
            proceduresTextarea.setSelectionRange(start + 3, start + 3);
        };

        window.insertBullet = function() {
            const start = proceduresTextarea.selectionStart;
            proceduresTextarea.value = proceduresTextarea.value.substring(0, start) +
                                      '• ' +
                                      proceduresTextarea.value.substring(start);
            proceduresTextarea.focus();
            proceduresTextarea.setSelectionRange(start + 2, start + 2);
        };
    }

    // Loading state on submit
    const form = document.getElementById('practicalForm');
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
