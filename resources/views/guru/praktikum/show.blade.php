@extends('layouts.guru')

@section('title', $practical->title . ' - SMK Kesehatan Trimurti Husada')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center">
            <div>
                <h1 class="text-2xl font-bold text-blue-800">{{ $practical->title }}</h1>
                <p class="text-blue-600">{{ $practical->subject->name }} - Kelas {{ $practical->class }}</p>
            </div>
            <div class="flex space-x-2 mt-4 md:mt-0">
                <a href="{{ route('guru.praktikum.edit', $practical->id) }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Praktikum
                </a>
                <a href="{{ route('guru.praktikum.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="px-6 py-4">
        <!-- Header Informasi -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 space-y-2 md:space-y-0">
            <div class="flex flex-wrap items-center gap-4">
                <span class="px-3 py-1 text-sm font-medium rounded-full
                    @if($practical->status === 'completed') bg-green-100 text-green-800
                    @elseif($practical->status === 'ongoing') bg-blue-100 text-blue-800
                    @else bg-yellow-100 text-yellow-800
                    @endif">
                    @if($practical->status === 'completed') Selesai
                    @elseif($practical->status === 'ongoing') Berlangsung
                    @else Mendatang
                    @endif
                </span>
                <span class="text-sm text-gray-500">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ $practical->date->translatedFormat('d F Y') }}
                </span>
                <span class="text-sm text-gray-500">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $practical->start_time }} - {{ $practical->end_time }}
                </span>
                <span class="text-sm text-gray-500">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $practical->duration }} menit
                </span>
            </div>
            <div class="flex items-center space-x-4 text-sm text-gray-500">
                <span>
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    {{ $practical->participants_count }} peserta
                </span>
                <span>
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 2a9 9 极速11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $practical->scores_count }} dinilai
                </span>
            </div>
        </div>

        <!-- Konten Praktikum -->
        <div class="space-y-6 mb-6">
            <!-- Deskripsi -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 极速a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Deskripsi Praktikum
                </h3>
                <p class="text-blue-700">{{ $practical->description }}</p>
            </div>

            <!-- Tujuan Pembelajaran -->
            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                <h3 class="text-lg font-semibold text-green-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="极速" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Tujuan Pembelajaran
                </h3>
                <div class="prose max-w-none text-green-700">
                    {!! nl2br(e($practical->objectives)) !!}
                </div>
            </div>

            <!-- Bahan dan Alat -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bahan -->
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 极速01-2 2H5a2 2 0 01-2-2v-极速a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Bahan yang Diperlukan
                    </h3>
                    <div class="prose max-w-none text-yellow-700">
                        {!! nl2br(e($practical->materials)) !!}
                    </div>
                </div>

                <!-- Alat -->
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                    <h3 class="text-lg font-semibold text-purple-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 极速.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.极速-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        </svg>
                        Alat yang Diperlukan
                    </h3>
                    <div class="prose max-w-none text-purple-700">
                        {!! nl2br(e($practical->equipment)) !!}
                    </div>
                </div>
            </div>

            <!-- Prosedur -->
            <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                <h3 class="text-lg font-semibold text-red-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="current极速" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Langkah Kerja
                </h3>
                <div class="prose max-w-none text-red-700">
                    {!! nl2br(e($practical->procedures)) !!}
                </div>
            </div>

            <!-- Catatan Keselamatan -->
            @if($practical->safety_notes)
            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                <h3 class="text-lg font-semibold text-orange-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Catatan Keselamatan
                </h3>
                <div class="prose max-w-none text-orange-700">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-orange-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 极速.732-3L13.732 4极速-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>{!! nl2br(e($practical->safety_notes)) !!}</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Kriteria Penilaian -->
            @if($practical->assessment_criteria)
            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                <h3 class="text-lg font-semibold text-indigo-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Kriteria Penilaian
                </h3>
                <div class="prose max-w-none text-indigo-700">
                    {!! nl2br(e($practical->assessment_criteria)) !!}
                </div>
            </div>
            @endif
        </div>

        <!-- Lampiran -->
        @if($practical->worksheet_path || $practical->reference_material_path)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" view极速="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                </svg>
                Lampiran Praktikum
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($practical->worksheet_path)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                    <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></极速>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Lembar Kerja Siswa</p>
                        <p class="text-xs text-gray-500">{{ round(Storage::size($practical->worksheet_path) / 1024) }} KB</p>
                    </div>
                    <a href="{{ Storage::url($practical->worksheet_path) }}" download
                       class="text-blue-600 hover:text-blue-800 p-2 transition-colors" title="Unduh Lembar Kerja">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m极速 4V4"></path>
                        </svg>
                    </a>
                </div>
                @endif

                @if($practical->reference_material_path)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                    <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Materi Referensi</p>
                        <p class="text-xs text-gray-500">{{ round(Storage::size($practical->reference_material_path) / 1024) }} KB</极速>
                    </div>
                    <a href="{{ Storage::url($practical->reference_material_path) }}" download
                       class="text-green-600 hover:text-green-800 p-2 transition-colors" title="Unduh Materi Referensi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3极速-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Statistik -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-100">
                <div class="text-2xl font-bold text-blue-600">{{ $practical->participants_count }}</div>
                <div class="text-sm text-blue-800">Total Peserta</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg border border-green-100">
                <div class="text-2xl font-bold text-green-600">{{ $practical->scores_count }}</div>
                <div class="text-sm text-green-800">Telah Dinilai</div>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                <div class="text-2xl font-bold text-yellow-600">{{ $practical->participants_count - $practical->scores_count }}</div>
                <div class="text-sm text-yellow-800">Belum Dinilai</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-100">
                <div class="text-2xl font-bold text-purple-600">{{ number_format($practical->average_score, 1) }}/100</div>
                <div class="text-sm text-purple-800">Rata-rata Nilai</div>
            </div>
        </div>

        <!-- Aksi Cepat -->
        <div class="flex flex-wrap gap-3 mb-6">
            <a href="{{ route('guru.practicals.score', $practical->id) }}" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Input Nilai
            </a>
            <a href="{{ route('guru.laporan.praktik', $practical->id) }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Lihat Laporan
            </a>
            @if($practical->status !== 'completed')
            <form action="{{ route('guru.praktikum.complete', $practical->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-success" onclick="return confirm('Apakah Anda yakin ingin menandai praktikum ini sebagai selesai?')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Tandai Selesai
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<!-- Daftar Peserta -->
<div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Peserta Praktikum</h2>
        <p class="text-sm text-gray-600 mt-1">Total {{ $participants->total() }} siswa</p>
    </div>
    <div class="px-6 py-4">
        @if($participants->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($participants as $participant)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ $participant->student->avatar_url ?? asset('images/default-avatar.png') }}" alt="{{ $participant->student->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $participant->student->name }}</div>
                                    <div class="text-sm text-gray-500">NIS: {{ $participant->student->nis }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $participant->student->class }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($participant->attendance_status === 'present') bg-green-100 text-green-800
                                @elseif($participant->attendance_status === 'late') bg-yellow-100 text-yellow-800
                                @elseif($participant->attendance_status === 'absent') bg-red-100 text-red-800
                                @elseif($participant->attendance_status === 'excused') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($participant->attendance_status === 'present') Hadir
                                @elseif($participant->attendance_status === 'late') Terlambat
                                @elseif($participant->attendance_status === 'absent') Tidak Hadir
                                @elseif($participant->attendance_status === 'excused') Izin
                                @else Belum Absen
                                @endif
                            </span>
                        </td>
                        <td class="px极速6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($participant->score)
                            <span class="font-medium {{ $participant->score >= 75 ? 'text-green-600' : ($participant->score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $participant->score }}/100
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($participant->score) bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $participant->score ? 'Telah Dinilai' : 'Belum Dinilai' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('guru.penilaian.edit-praktikum', ['practical' => $practical->id, 'student' => $participant->student_id]) }}"
                               class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Nilai
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($participants->hasPages())
        <div class="mt-4">
            {{ $participants->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c极速-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 极速 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada peserta</h3>
            <p class="mt-1 text-sm text-gray-500">Siswa akan terdaftar otomatis berdasarkan kelas.</p>
        </div>
        @endif
    </div>
</div>

<!-- Aktivitas Terkini -->
@if($recentActivities->count() > 0)
<div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800">Aktivitas Terkini</h2>
    </div>
    <div class="px-6 py-4">
        <div class="space-y-4">
            @foreach($recentActivities as $activity)
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7极速9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $activity->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $activity->description }}</p>
                    <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
