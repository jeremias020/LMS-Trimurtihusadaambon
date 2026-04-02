<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assignment->title }} - LMS Trimurti Husada</title>
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

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            table {
                font-size: 0.875rem;
            }

            th, td {
                padding: 0.5rem;
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
                    <h1 class="text-2xl font-bold text-gray-800">{{ $assignment->title }}</h1>
                    <p class="text-gray-600">{{ $assignment->subject->name }} - Kelas {{ $assignment->class }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('guru.assignments.edit', $assignment->id) }}" class="btn-primary" title="Edit tugas">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('guru.assignments.index') }}" class="btn-secondary" title="Kembali ke daftar tugas">
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
                    Detail Tugas
                </h2>
            </div>

            <div class="px-6 py-4">
                <!-- Assignment Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-2 sm:space-y-0">
                    <div class="flex items-center space-x-4">
                        @php
                            $statusClasses = [
                                'active' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-blue-100 text-blue-800',
                                'upcoming' => 'bg-yellow-100 text-yellow-800'
                            ];
                        @endphp
                        <span class="status-badge {{ $statusClasses[$assignment->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($assignment->status) }}
                        </span>
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-calendar-plus mr-1"></i>Dibuat: {{ $assignment->created_at->format('d/m/Y H:i') }}
                        </span>
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-clock mr-1"></i>Batas: {{ $assignment->due_date?->format('d/m/Y H:i') ?? $assignment->deadline?->format('d/m/Y H:i') ?? '-' }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span><i class="fas fa-file-upload mr-1"></i>{{ $assignment->submissions_count }} dikumpulkan</span>
                        <span><i class="fas fa-users mr-1"></i>{{ $assignment->total_students }} siswa</span>
                    </div>
                </div>

                <!-- Assignment Content -->
                <div class="prose max-w-none mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-align-left mr-2 text-blue-500"></i>
                        Deskripsi
                    </h3>
                    <p class="text-gray-700 mb-6">{{ $assignment->description }}</p>

                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-list-ol mr-2 text-green-500"></i>
                        Instruksi Tugas
                    </h3>
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        @if($assignment->formatted_instructions)
                            {!! $assignment->formatted_instructions !!}
                        @else
                            <p class="text-gray-500 italic">Tidak ada instruksi tambahan.</p>
                        @endif
                    </div>
                </div>

                <!-- Attachments and Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    @if($assignment->attachment_path)
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <h4 class="text-sm font-medium text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-paperclip mr-2 text-blue-500"></i>
                            File Lampiran
                        </h4>
                        <div class="flex items-center">
                            <a href="{{ Storage::url($assignment->attachment_path) }}" download target="_blank" rel="noopener"
                               class="text-blue-600 hover:text-blue-800 text-sm flex items-center" title="Download file tugas">
                                <i class="fas fa-download mr-2"></i>
                                {{ basename($assignment->attachment_path) }}
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-medium text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-gray-500"></i>
                            Detail Tugas
                        </h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p class="flex justify-between">
                                <span>Nilai Maksimal:</span>
                                <span class="font-medium">{{ $assignment->max_score }}</span>
                            </p>
                            <p class="flex justify-between">
                                <span>Pengumpulan Terlambat:</span>
                                <span class="font-medium {{ $assignment->allow_late ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $assignment->allow_late ? 'Diizinkan' : 'Tidak Diizinkan' }}
                                </span>
                            </p>
                            <p class="flex justify-between">
                                <span>Status:</span>
                                <span class="font-medium {{ $assignment->is_published ? 'text-green-600' : 'text-gray-600' }}">
                                    {{ $assignment->is_published ? 'Dipublikasikan' : 'Draft' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submission Statistics -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="text-2xl font-bold text-blue-600">{{ $assignment->submissions_count }}</div>
                        <div class="text-sm text-blue-800">Dikumpulkan</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="text-2xl font-bold text-green-600">{{ $assignment->graded_count }}</div>
                        <div class="text-sm text-green-800">Dinilai</div>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="text-2xl font-bold text-yellow-600">{{ $assignment->pending_count }}</div>
                        <div class="text-sm text-yellow-800">Belum Dinilai</div>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                        <div class="text-2xl font-bold text-red-600">{{ $assignment->missing_count }}</div>
                        <div class="text-sm text-red-800">Belum Dikumpulkan</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 mb-6">
                    <a href="{{ route('guru.penilaian.index', ['assignment_id' => $assignment->id]) }}" class="btn-primary" title="Lihat daftar pengumpulan">
                        <i class="fas fa-list mr-2"></i>Lihat Pengumpulan
                    </a>
                    <a href="{{ route('guru.laporan.tugas') }}" class="btn-secondary" title="Lihat laporan tugas">
                        <i class="fas fa-chart-bar mr-2"></i>Lihat Laporan
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Submissions -->
        <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-file-upload mr-2 text-blue-600"></i>
                    Pengumpulan Terbaru
                </h2>
            </div>
            <div class="px-6 py-4">
                @if($recentSubmissions->count() > 0)
                <div class="table-responsive overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Siswa</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Dikumpulkan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nilai</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentSubmissions as $submission)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <img class="avatar"
                                                 src="{{ $submission->student->avatar_url ?? asset('images/default-avatar.png') }}"
                                                 alt="{{ $submission->student->name }}"
                                                 onerror="this.src='/images/default-avatar.png'">
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $submission->student->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $submission->student->class }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500">
                                    {{ $submission->submitted_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-4">
                                    @php
                                        $statusClasses = [
                                            'graded' => 'bg-green-100 text-green-800',
                                            'submitted' => 'bg-blue-100 text-blue-800',
                                            'late' => 'bg-yellow-100 text-yellow-800'
                                        ];
                                    @endphp
                                    <span class="status-badge {{ $statusClasses[$submission->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    @if($submission->score)
                                    <span class="font-medium {{ $submission->score >= $assignment->max_score * 0.75 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $submission->score }}/{{ $assignment->max_score }}
                                    </span>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm font-medium">
                                    <a href="{{ route('guru.penilaian.edit', $submission->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Nilai pengumpulan ini">
                                        <i class="fas fa-edit mr-1"></i>Nilai
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-4"></i>
                    <p>Belum ada pengumpulan</p>
                </div>
                @endif
            </div>
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
</body>
</html>
