@extends('layouts.guru')

@section('title', 'Buat Penilaian - Modern Assessment System')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-6 relative overflow-hidden">
    <!-- Animated background elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-pink-400/20 to-indigo-400/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-br from-cyan-400/10 to-blue-400/10 rounded-full blur-3xl animate-pulse delay-500"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Ultra Modern Header -->
        <div class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-3xl shadow-2xl overflow-hidden mb-8">
            <!-- Animated gradient overlay -->
            <div class="absolute inset-0 bg-black/20"></div>
            <!-- Animated particles pattern -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 left-0 w-32 h-32 bg-white/10 rounded-full blur-2xl animate-pulse"></div>
                <div class="absolute top-1/4 right-0 w-24 h-24 bg-purple-500/20 rounded-full blur-xl animate-pulse delay-75"></div>
                <div class="absolute bottom-0 left-1/3 w-28 h-28 bg-pink-500/20 rounded-full blur-xl animate-pulse delay-150"></div>
            </div>
            <!-- Modern grid pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <pattern id="grid-modern" width="20" height="20" patternUnits="userSpaceOnUse">
                        <path d="M 20 0 L 0 0 0 20 M 20 5 L 0 5 M 20 10 L 0 10 M 20 15 L 0 15" fill="none" stroke="white" stroke-width="0.3"/>
                    </pattern>
                    <rect width="100" height="100" fill="url(#grid-modern)" />
                </svg>
            </div>
            
            <!-- Content with enhanced layout -->
            <div class="relative z-10 p-8">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-6">
                            <!-- Enhanced icon with multi-layer glow -->
                            <div class="relative mr-6">
                                <div class="absolute inset-0 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500 rounded-3xl blur-2xl opacity-60 animate-pulse"></div>
                                <div class="absolute inset-0 bg-gradient-to-r from-white/30 to-transparent rounded-3xl blur-xl opacity-80"></div>
                                <div class="relative bg-white/20 backdrop-blur-2xl border border-white/30 rounded-3xl p-4 shadow-2xl">
                                    <svg class="w-4 h-4 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-1.414a2 2 0 112.828 0L21 8M5 19h14"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-5xl font-black text-white mb-3 tracking-tight drop-shadow-2xl">Buat Penilaian Baru</h1>
                                <p class="text-white/90 text-xl font-semibold mb-4 drop-shadow-lg">Sistem penilaian modern dengan validasi real-time</p>
                                
                                <!-- Enhanced feature pills with animations -->
                                <div class="flex flex-wrap gap-3">
                                    <div class="group inline-flex items-center px-5 py-3 bg-white/15 backdrop-blur-lg border border-white/25 rounded-full hover:bg-white/25 transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <svg class="w-4 h-4 mr-2 text-cyan-400 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-white font-semibold">Real-time Validation</span>
                                    </div>
                                    <div class="group inline-flex items-center px-5 py-3 bg-white/15 backdrop-blur-lg border border-white/25 rounded-full hover:bg-white/25 transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <svg class="w-4 h-4 mr-2 text-emerald-400 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-white font-semibold">Smart Forms</span>
                                    </div>
                                    <div class="group inline-flex items-center px-5 py-3 bg-white/15 backdrop-blur-lg border border-white/25 rounded-full hover:bg-white/25 transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <svg class="w-4 h-4 mr-2 text-violet-400 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        <span class="text-white font-semibold">Auto-publish</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Enhanced back button -->
                    <a href="{{ route('guru.penilaian.index') }}" class="group relative bg-white/15 backdrop-blur-2xl border border-white/25 hover:bg-white/25 text-white px-8 py-4 rounded-2xl transition-all duration-300 flex items-center space-x-3 shadow-xl hover:shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <svg class="w-4 h-4 relative z-10 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class="relative z-10 font-bold text-lg">Kembali</span>
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('guru.penilaian.store') }}" method="POST" id="assessmentForm" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Panel - Basic Info -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Ultra Modern Assessment Type Card -->
                    <div class="group relative bg-white/90 backdrop-blur-2xl border border-white/30 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-1">
                        <!-- Animated gradient border -->
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/30 via-purple-600/30 to-pink-600/30 rounded-3xl animate-pulse"></div>
                        
                        <!-- Enhanced header with gradient -->
                        <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 px-6 py-5">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-700 via-purple-700 to-pink-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <!-- Animated shimmer effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 animate-pulse"></div>
                            
                            <h3 class="relative z-10 text-xl font-black text-white flex items-center">
                                <div class="bg-white/25 backdrop-blur-lg p-3 rounded-2xl mr-4 shadow-lg">
                                    <svg class="w-4 h-4 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-black">Jenis Penilaian</div>
                                    <div class="text-white/80 text-sm font-medium mt-1">Pilih tipe penilaian</div>
                                </div>
                            </h3>
                        </div>
                        
                        <div class="relative z-10 p-7 space-y-5">
                            <div class="space-y-5">
                                <div class="group">
                                    <label class="block text-sm font-black text-gray-800 mb-3 flex items-center">
                                        <div class="w-3 h-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mr-3 animate-pulse"></div>
                                        Tipe Penilaian
                                    </label>
                                    <select name="type" id="type" class="w-full px-5 py-4 bg-white/70 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300 font-bold text-gray-800 hover:bg-white/80 hover:border-blue-400/50" required onchange="toggleAssessmentType()">
                                        <option value="">Pilih Tipe Penilaian</option>
                                        <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>Tugas Teori</option>
                                        <option value="practical" {{ old('type') == 'practical' ? 'selected' : '' }}>Praktikum Keterampilan</option>
                                    </select>
                                </div>
                                
                                <div class="group">
                                    <label class="block text-sm font-black text-gray-800 mb-3 flex items-center">
                                        <div class="w-3 h-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mr-3 animate-pulse"></div>
                                        Pilih Siswa
                                    </label>
                                    @if($students->isEmpty())
                                        <div class="p-5 bg-gradient-to-r from-amber-50/90 to-orange-50/90 backdrop-blur-sm border-2 border-amber-200/50 rounded-2xl shadow-lg">
                                            <div class="flex items-center text-amber-800">
                                                <div class="bg-amber-500 p-2 rounded-xl mr-4">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="font-black text-lg">Tidak ada siswa</span>
                                                    <p class="text-sm font-medium mt-1">Silakan tambahkan siswa terlebih dahulu</p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <select name="siswa_id" id="siswa_id" class="w-full px-5 py-4 bg-white/70 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-purple-500/30 focus:border-purple-500 transition-all duration-300 font-bold text-gray-800 hover:bg-white/80 hover:border-purple-400/50" required>
                                            <option value="">-- Pilih Siswa --</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ old('siswa_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }} - {{ $student->kelas->name ?? 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Selection Card -->
                    <div class="group relative bg-white/90 backdrop-blur-2xl border border-white/30 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-1">
                        <!-- Animated gradient border -->
                        <div class="absolute inset-0 bg-gradient-to-r from-green-600/30 via-emerald-600/30 to-teal-600/30 rounded-3xl animate-pulse"></div>
                        
                        <!-- Enhanced header with gradient -->
                        <div class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 px-6 py-5">
                            <div class="absolute inset-0 bg-gradient-to-r from-green-700 via-emerald-700 to-teal-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <!-- Animated shimmer effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 animate-pulse"></div>
                            
                            <h3 class="relative z-10 text-xl font-black text-white flex items-center">
                                <div class="bg-white/25 backdrop-blur-lg p-3 rounded-2xl mr-4 shadow-lg">
                                    <svg class="w-4 h-4 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-black">Aktivitas</div>
                                    <div class="text-white/80 text-sm font-medium mt-1">Pilih tugas atau praktikum</div>
                                </div>
                            </h3>
                        </div>
                        
                        <div class="relative z-10 p-7 space-y-5">
                            <!-- Assignment Selection -->
                            <div class="form-group" id="assignmentField" style="display: none;">
                                <label class="block text-sm font-black text-gray-800 mb-3 flex items-center">
                                    <div class="w-3 h-3 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full mr-3 animate-pulse"></div>
                                    Pilih Tugas
                                </label>
                                @if($assignments->isEmpty())
                                    <div class="p-5 bg-gradient-to-r from-blue-50/90 to-indigo-50/90 backdrop-blur-sm border-2 border-blue-200/50 rounded-2xl shadow-lg">
                                        <div class="flex items-center text-blue-800">
                                            <div class="bg-blue-500 p-2 rounded-xl mr-4">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="font-black text-lg">Tidak ada tugas</span>
                                                <p class="text-sm font-medium mt-1">Silakan buat tugas terlebih dahulu</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <select name="assignment_id" id="assignment_id" class="w-full px-5 py-4 bg-white/70 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-green-500/30 focus:border-green-500 transition-all duration-300 font-bold text-gray-800 hover:bg-white/80 hover:border-green-400/50">
                                        <option value="">-- Pilih Tugas --</option>
                                        @foreach($assignments as $assignment)
                                            <option value="{{ $assignment->id }}" data-max-score="{{ $assignment->max_score }}" {{ old('assignment_id') == $assignment->id ? 'selected' : '' }}>
                                                {{ $assignment->title }} - {{ $assignment->subject->name ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <!-- Practical Selection -->
                            <div class="form-group" id="practicalField" style="display: none;">
                                <label class="block text-sm font-black text-gray-800 mb-3 flex items-center">
                                    <div class="w-3 h-3 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full mr-3 animate-pulse"></div>
                                    Pilih Praktikum
                                </label>
                                @if($practicals->isEmpty())
                                    <div class="p-5 bg-gradient-to-r from-emerald-50/90 to-teal-50/90 backdrop-blur-sm border-2 border-emerald-200/50 rounded-2xl shadow-lg">
                                        <div class="flex items-center text-emerald-800">
                                            <div class="bg-emerald-500 p-2 rounded-xl mr-4">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="font-black text-lg">Tidak ada praktikum</span>
                                                <p class="text-sm font-medium mt-1">Silakan buat praktikum terlebih dahulu</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <select name="practical_id" id="practical_id" class="w-full px-5 py-4 bg-white/70 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all duration-300 font-bold text-gray-800 hover:bg-white/80 hover:border-emerald-400/50">
                                        <option value="">-- Pilih Praktikum --</option>
                                        @foreach($practicals as $practical)
                                            <option value="{{ $practical->id }}" data-max-score="{{ $practical->max_score }}" {{ old('practical_id') == $practical->id ? 'selected' : '' }}>
                                                {{ $practical->judul }} - {{ $practical->subject->name ?? 'Keperawatan Dasar' }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel - Assessment Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Score & Feedback Card -->
                    <div class="group relative bg-white/90 backdrop-blur-2xl border border-white/30 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-1">
                        <!-- Animated gradient border -->
                        <div class="absolute inset-0 bg-gradient-to-br from-orange-600/30 via-red-600/30 to-pink-600/30 rounded-3xl animate-pulse"></div>
                        
                        <!-- Enhanced header with gradient -->
                        <div class="relative bg-gradient-to-r from-orange-600 via-red-600 to-pink-600 px-6 py-5">
                            <div class="absolute inset-0 bg-gradient-to-r from-orange-700 via-red-700 to-pink-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <!-- Animated shimmer effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 animate-pulse"></div>
                            
                            <h3 class="relative z-10 text-xl font-black text-white flex items-center">
                                <div class="bg-white/25 backdrop-blur-lg p-3 rounded-2xl mr-4 shadow-lg">
                                    <svg class="w-4 h-4 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2V10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2V14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-black">Nilai & Feedback</div>
                                    <div class="text-white/80 text-sm font-medium mt-1">Input nilai dan umpan balik</div>
                                </div>
                            </h3>
                        </div>
                        
                        <div class="relative z-10 p-7">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-5">
                                    <div class="group">
                                        <label class="block text-sm font-black text-gray-800 mb-3 flex items-center">
                                            <div class="w-3 h-3 bg-gradient-to-r from-orange-500 to-red-500 rounded-full mr-3 animate-pulse"></div>
                                            Nilai
                                        </label>
                                        <input type="number" name="score" id="score" class="w-full px-5 py-4 bg-white/70 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-orange-500/30 focus:border-orange-500 transition-all duration-300 font-bold text-gray-800 hover:bg-white/80 hover:border-orange-400/50" value="{{ old('score') }}" min="0" max="100" step="0.1" placeholder="0-100" required>
                                        <div id="percentageDisplay" class="text-sm text-gray-600 font-medium mt-2">0% dari nilai maksimal</div>
                                    </div>
                                    
                                    <div class="group">
                                        <label class="block text-sm font-black text-gray-800 mb-3 flex items-center">
                                            <div class="w-3 h-3 bg-gradient-to-r from-red-500 to-pink-500 rounded-full mr-3 animate-pulse"></div>
                                            Nilai Maksimal
                                        </label>
                                        <div class="w-full px-5 py-4 bg-gray-50/70 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl font-bold text-gray-600" id="max_score_display">Pilih aktivitas terlebih dahulu</div>
                                    </div>
                                </div>
                                
                                <div class="space-y-5">
                                    <div class="group">
                                        <label class="block text-sm font-black text-gray-800 mb-3 flex items-center">
                                            <div class="w-3 h-3 bg-gradient-to-r from-pink-500 to-rose-500 rounded-full mr-3 animate-pulse"></div>
                                            Feedback
                                        </label>
                                        <textarea name="feedback" id="feedback" class="w-full px-5 py-4 bg-white/70 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-pink-500/30 focus:border-pink-500 transition-all duration-300 font-bold text-gray-800 hover:bg-white/80 hover:border-pink-400/50 resize-none" rows="6" placeholder="Berikan umpan balik konstruktif untuk siswa...">{{ old('feedback') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date & Settings Card -->
                    <div class="group relative bg-white/90 backdrop-blur-2xl border border-white/30 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-1">
                        <!-- Animated gradient border -->
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-600/30 via-indigo-600/30 to-blue-600/30 rounded-3xl animate-pulse"></div>
                        
                        <!-- Enhanced header with gradient -->
                        <div class="relative bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 px-6 py-5">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <!-- Animated shimmer effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 animate-pulse"></div>
                            
                            <h3 class="relative z-10 text-xl font-black text-white flex items-center">
                                <div class="bg-white/25 backdrop-blur-lg p-3 rounded-2xl mr-4 shadow-lg">
                                    <svg class="w-4 h-4 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-black">Tanggal & Pengaturan</div>
                                    <div class="text-white/80 text-sm font-medium mt-1">Konfigurasi tanggal dan publikasi</div>
                                </div>
                            </h3>
                        </div>
                        
                        <div class="relative z-10 p-7">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-5">
                                    <div class="group">
                                        <label class="block text-sm font-black text-gray-800 mb-3 flex items-center">
                                            <div class="w-3 h-3 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full mr-3 animate-pulse"></div>
                                            Tanggal Penilaian
                                        </label>
                                        <input type="date" name="assessment_date" id="assessment_date" class="w-full px-5 py-4 bg-white/70 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-purple-500/30 focus:border-purple-500 transition-all duration-300 font-bold text-gray-800 hover:bg-white/80 hover:border-purple-400/50" value="{{ old('assessment_date', date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                                
                                <div class="space-y-5">
                                    <div class="p-5 bg-gradient-to-r from-green-50/90 to-emerald-50/90 backdrop-blur-sm border-2 border-green-200/50 rounded-2xl">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-bold text-gray-900">Publikasi Otomatis</p>
                                                <p class="text-xs text-gray-600 font-medium mt-1">Siswa dapat langsung melihat nilai setelah disimpan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="group relative bg-white/90 backdrop-blur-2xl border border-white/30 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-1">
                        <div class="relative z-10 p-6">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-600">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mr-2"></div>
                                            <span class="font-medium">Form akan divalidasi sebelum disimpan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-3">
                                    <button type="button" onclick="resetForm()" class="group relative bg-gradient-to-r from-gray-50 to-slate-50 hover:from-gray-100 hover:to-slate-100 text-gray-700 px-6 py-3.5 rounded-2xl transition-all duration-300 flex items-center space-x-2 border border-gray-200/50 hover:border-gray-300/50">
                                        <div class="absolute inset-0 bg-gradient-to-r from-gray-500/10 to-slate-500/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        <svg class="w-4 h-4 relative z-10 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        <span class="relative z-10 font-bold">Reset Form</span>
                                    </button>
                                    <button type="submit" class="group relative bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 text-white px-8 py-3.5 rounded-2xl transition-all duration-300 shadow-lg flex items-center space-x-2">
                                        <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        <svg class="w-4 h-4 relative z-10 group-hover:translate-y-0.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <span class="relative z-10 font-bold">Simpan Penilaian</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle assessment type
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
        maxScoreDisplay.textContent = 'Pilih tugas terlebih dahulu';
    } else if (type === 'practical') {
        assignmentField.style.display = 'none';
        practicalField.style.display = 'block';
        assignmentSelect.required = false;
        practicalSelect.required = true;
        maxScoreDisplay.textContent = 'Pilih praktikum terlebih dahulu';
    } else {
        assignmentField.style.display = 'none';
        practicalField.style.display = 'none';
        assignmentSelect.required = false;
        practicalSelect.required = false;
        maxScoreDisplay.textContent = 'Pilih aktivitas terlebih dahulu';
    }
    
    // Reset score
    if (scoreInput) {
        scoreInput.value = '';
        updatePercentageDisplay(0, 100);
    }
}

// Update max score display
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
        updatePercentageDisplay(parseFloat(scoreInput.value) || 0, parseFloat(maxScore));
    }
}

// Update percentage display
function updatePercentageDisplay(currentScore, maxScore) {
    const percentageDisplay = document.getElementById('percentageDisplay');
    if (percentageDisplay && maxScore > 0) {
        const percentage = (currentScore / maxScore * 100).toFixed(1);
        percentageDisplay.textContent = `${percentage}% dari nilai maksimal`;
    }
}

// Reset form
function resetForm() {
    document.getElementById('assessmentForm').reset();
    document.getElementById('max_score_display').textContent = 'Pilih aktivitas terlebih dahulu';
    document.getElementById('percentageDisplay').textContent = '0% dari nilai maksimal';
    toggleAssessmentType();
}

// Initialize on page load
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
    
    // Score input change
    const scoreInput = document.getElementById('score');
    if (scoreInput) {
        scoreInput.addEventListener('input', function() {
            const maxScore = parseFloat(this.getAttribute('max'));
            const currentScore = parseFloat(this.value) || 0;
            updatePercentageDisplay(currentScore, maxScore);
        });
    }
});
</script>
@endsection
