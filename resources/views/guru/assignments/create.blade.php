<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tugas Baru - LMS Trimurti Husada</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a56db;
            --primary-dark: #1e429f;
            --success: #059669;
            --warning: #d97706;
            --danger: #dc2626;
        }

        .form-input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            width: 100%;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(120deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background-color: #f3f4f6;
            color: #374151;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #e5e7eb;
        }

        .editor-toolbar {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            flex-wrap: wrap;
        }

        .editor-btn {
            padding: 0.5rem;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .editor-btn:hover {
            background-color: #e5e7eb;
        }

        @media (max-width: 768px) {
            .grid-cols-1 {
                grid-template-columns: 1fr;
            }

            .md-grid-cols-2 {
                grid-template-columns: 1fr;
            }

            .editor-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Buat Tugas Baru</h1>
                    <p class="text-gray-600">Buat tugas baru untuk siswa</p>
                </div>
                <div>
                    <a href="{{ route('guru.assignments.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-tasks mr-2 text-blue-600"></i>
                    Form Buat Tugas
                </h2>
            </div>

            <!-- Display Errors -->
            @if ($errors->any())
                <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan dalam form:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('guru.assignments.store') }}" method="POST" enctype="multipart/form-data" id="assignmentForm">
                @csrf

                <div class="px-6 py-4 space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="title" class="form-label">Judul Tugas *</label>
                                <input type="text" name="title" id="title" class="form-input"
                                       value="{{ old('title') }}" required placeholder="Masukkan judul tugas" autocomplete="off">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="subject_id" class="form-label">Mata Pelajaran *</label>
                                <select name="subject_id" id="subject_id" class="form-input" required autocomplete="off">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->nama ?? $subject->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="class" class="form-label">Kelas *</label>
                                <select name="class" id="class" class="form-input" required autocomplete="off">
                                    <option value="">Pilih Kelas</option>
                                    <option value="X" {{ old('class') == 'X' ? 'selected' : '' }}>Kelas X</option>
                                    <option value="XI" {{ old('class') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                    <option value="XII" {{ old('class') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                                </select>
                                @error('class')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="deadline" class="form-label">Batas Waktu *</label>
                                <input type="datetime-local" name="deadline" id="deadline" class="form-input"
                                       value="{{ old('deadline') }}" required autocomplete="off">
                                @error('deadline')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Instructions and Content -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-clipboard-list mr-2 text-green-500"></i>
                            Instruksi Tugas
                        </h3>
                        <div class="form-group">
                            <label for="description" class="form-label">Deskripsi Tugas *</label>
                            <textarea name="description" id="description" class="form-input" rows="3"
                                      required placeholder="Deskripsi singkat tentang tugas" autocomplete="off">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="instructions" class="form-label">Instruksi Detail</label>
                            <div class="editor-toolbar" id="instructionsToolbar">
                                <button type="button" onclick="formatText('bold')" class="editor-btn" title="Bold">
                                    <i class="fas fa-bold"></i>
                                </button>
                                <button type="button" onclick="formatText('italic')" class="editor-btn" title="Italic">
                                    <i class="fas fa-italic"></i>
                                </button>
                                <button type="button" onclick="formatText('underline')" class="editor-btn" title="Underline">
                                    <i class="fas fa-underline"></i>
                                </button>
                                <button type="button" onclick="insertBullet()" class="editor-btn" title="Bullet List">
                                    <i class="fas fa-list-ul"></i>
                                </button>
                                <button type="button" onclick="insertNumber()" class="editor-btn" title="Numbered List">
                                    <i class="fas fa-list-ol"></i>
                                </button>
                                <button type="button" onclick="insertCode()" class="editor-btn" title="Code">
                                    <i class="fas fa-code"></i>
                                </button>
                            </div>
                            <textarea name="instructions" id="instructions" class="form-input" rows="6"
                                      placeholder="Instruksi detail untuk siswa (opsional)" autocomplete="off">{{ old('instructions') }}</textarea>
                            @error('instructions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- File Attachment -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-paperclip mr-2 text-yellow-500"></i>
                            Lampiran Tugas
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="file" class="form-label">File Tugas (Opsional)</label>
                                <input type="file" name="file" id="file" class="form-input"
                                       accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.zip,.rar"
                                       title="Pilih file PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP, RAR (maks 20MB)">
                                <p class="text-xs text-gray-500 mt-1">
                                    Format: PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP, RAR. Maks: 20MB
                                </p>
                                @error('file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="max_score" class="form-label">Nilai Maksimal *</label>
                                <input type="number" name="max_score" id="max_score" class="form-input"
                                       value="{{ old('max_score', 100) }}" min="1" max="1000" required autocomplete="off">
                                @error('max_score')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Settings -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-cog mr-2 text-gray-500"></i>
                            Pengaturan Tambahan
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="flex items-center">
                                    <input type="checkbox" name="allow_late" value="1"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2"
                                           {{ old('allow_late') ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">Izinkan pengumpulan terlambat</span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_published" value="1"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2"
                                           {{ old('is_published', 1) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">Publikasikan tugas</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('guru.assignments.index') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Buat Tugas
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-sm mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} SMK Kesehatan Trimurti Husada Ambon. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum datetime to current time
        const now = new Date();
        const localDateTime = now.toISOString().slice(0, 16);
        document.getElementById('deadline').min = localDateTime;

        // File size validation
        const fileInput = document.getElementById('file');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file && file.size > 20 * 1024 * 1024) { // 20MB
                    alert('Ukuran file terlalu besar. Maksimal 20MB.');
                    this.value = '';
                }
            });
        }

        // Form validation
        const form = document.getElementById('assignmentForm');
        form.addEventListener('submit', function(e) {
            console.log('Form submitted');
            
            // Validate required fields
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();
            const instructions = document.getElementById('instructions').value.trim();
            const subjectId = document.getElementById('subject_id').value;
            const deadline = document.getElementById('deadline').value;
            const maxScore = document.getElementById('max_score').value;

            if (!title) {
                e.preventDefault();
                alert('Judul tugas wajib diisi!');
                document.getElementById('title').focus();
                return false;
            }

            if (!description) {
                e.preventDefault();
                alert('Deskripsi tugas wajib diisi!');
                document.getElementById('description').focus();
                return false;
            }

            // Instructions is optional, no validation needed

            if (!subjectId) {
                e.preventDefault();
                alert('Mata pelajaran wajib dipilih!');
                document.getElementById('subject_id').focus();
                return false;
            }

            if (!deadline) {
                e.preventDefault();
                alert('Batas waktu wajib diisi!');
                document.getElementById('deadline').focus();
                return false;
            }

            const dueDate = new Date(deadline);
            const now = new Date();

            if (dueDate <= now) {
                e.preventDefault();
                alert('Batas waktu harus lebih besar dari waktu sekarang.');
                document.getElementById('deadline').focus();
                return false;
            }

            if (!maxScore || maxScore < 1) {
                e.preventDefault();
                alert('Nilai maksimal harus diisi dan minimal 1!');
                document.getElementById('max_score').focus();
                return false;
            }

            console.log('Form validation passed, submitting...');
            
            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                Menyimpan...
            `;
        });
    });

    function formatText(format) {
        const textarea = document.getElementById('instructions');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = textarea.value.substring(start, end);

        let formattedText = '';
        switch(format) {
            case 'bold':
                formattedText = `**${selectedText}**`;
                break;
            case 'italic':
                formattedText = `_${selectedText}_`;
                break;
            case 'underline':
                formattedText = `__${selectedText}__`;
                break;
        }

        textarea.value = textarea.value.substring(0, start) +
                       formattedText +
                       textarea.value.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + formattedText.length, start + formattedText.length);
    }

    function insertBullet() {
        const textarea = document.getElementById('instructions');
        const start = textarea.selectionStart;
        textarea.value = textarea.value.substring(0, start) +
                       '• ' +
                       textarea.value.substring(start);
        textarea.focus();
        textarea.setSelectionRange(start + 2, start + 2);
    }

    function insertNumber() {
        const textarea = document.getElementById('instructions');
        const start = textarea.selectionStart;
        textarea.value = textarea.value.substring(0, start) +
                       '1. ' +
                       textarea.value.substring(start);
        textarea.focus();
        textarea.setSelectionRange(start + 3, start + 3);
    }

    function insertCode() {
        const textarea = document.getElementById('instructions');
        const start = textarea.selectionStart;
        textarea.value = textarea.value.substring(0, start) +
                       '`' +
                       textarea.value.substring(start);
        textarea.focus();
        textarea.setSelectionRange(start + 1, start + 1);
    }
    </script>
</body>
</html>
