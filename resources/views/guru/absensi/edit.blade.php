<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Absensi - LMS Trimurti Husada</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a56db;
            --primary-dark: #1e429f;
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
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Absensi</h1>
                    <p class="text-gray-600">Perbarui data kehadiran siswa</p>
                </div>
                <div>
                    <a href="{{ route('guru.absensi.index') }}" class="btn-secondary" title="Kembali ke daftar absensi">
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
                    <i class="fas fa-edit mr-2 text-blue-600"></i>
                    Edit Absensi
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $attendance->subject->name }} - Kelas {{ $attendance->class }} -
                    {{ $attendance->date->format('d/m/Y') }}
                </p>
            </div>

            <form action="{{ route('guru.absensi.update', $attendance->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="px-6 py-4 space-y-6">
                    <!-- Basic Information (Readonly) -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Informasi Absensi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="form-label">Mata Pelajaran</label>
                                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $attendance->subject->name }}</p>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Kelas</label>
                                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $attendance->class }}</p>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tanggal</label>
                                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $attendance->date->format('d/m/Y') }}</p>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Sesi</label>
                                <p class="mt-1 text-sm text-gray-900 font-medium">
                                    Sesi {{ $attendance->session }} ({{ $attendance->session_time ?? 'Waktu tidak tersedia' }})
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Student Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-user-graduate mr-2 text-green-500"></i>
                            Data Siswa
                        </h3>
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0 h-12 w-12">
                                <img class="h-12 w-12 rounded-full object-cover"
                                     src="{{ $attendance->student->avatar_url ?? asset('images/default-avatar.png') }}"
                                     alt="{{ $attendance->student->name }}"
                                     onerror="this.src='/images/default-avatar.png'">
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">{{ $attendance->student->name }}</h4>
                                <p class="text-sm text-gray-500">
                                    NIS: {{ $attendance->student->nis }} | Kelas: {{ $attendance->student->class }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Details -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-clipboard-check mr-2 text-yellow-500"></i>
                            Detail Kehadiran
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="status" class="form-label">Status Kehadiran *</label>
                                <select name="status" id="status" class="form-input" required onchange="toggleTimeField()" autocomplete="off">
                                    <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Hadir</option>
                                    <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>Terlambat</option>
                                    <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                                    <option value="excused" {{ old('status', $attendance->status) == 'excused' ? 'selected' : '' }}>Izin</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="time" class="form-label">Waktu Kehadiran</label>
                                <input type="time" name="time" id="time" class="form-input"
                                       value="{{ old('time', $attendance->time) }}"
                                       {{ !in_array(old('status', $attendance->status), ['present', 'late']) ? 'disabled' : '' }}
                                       autocomplete="off">
                                @error('time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Diisi hanya untuk status Hadir atau Terlambat</p>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label for="notes" class="form-label">Keterangan (Opsional)</label>
                            <textarea name="notes" id="notes" class="form-input" rows="3"
                                      placeholder="Masukkan keterangan jika diperlukan">{{ old('notes', $attendance->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-history mr-2 text-gray-500"></i>
                            Informasi Tambahan
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="form-label">Dibuat Pada</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $attendance->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Diperbarui Pada</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $attendance->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('guru.absensi.index') }}" class="btn-secondary" title="Kembali ke daftar absensi">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Perbarui Absensi
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
    function toggleTimeField() {
        const status = document.getElementById('status').value;
        const timeField = document.getElementById('time');

        if (status === 'present' || status === 'late') {
            timeField.disabled = false;
            timeField.required = true;
        } else {
            timeField.disabled = true;
            timeField.required = false;
            timeField.value = '';
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleTimeField();
    });
    </script>
</body>
</html>
