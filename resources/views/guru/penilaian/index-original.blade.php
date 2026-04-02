@extends('layouts.guru')

@section('title', 'Daftar Penilaian - Modern Assessment System')

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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-5xl font-black text-white mb-3 tracking-tight drop-shadow-2xl">Daftar Penilaian</h1>
                                <p class="text-white/90 text-xl font-semibold mb-4 drop-shadow-lg">Kelola penilaian tugas dan praktikum siswa dengan sistem modern</p>
                                
                                <!-- Enhanced feature pills with animations -->
                                <div class="flex flex-wrap gap-3">
                                    <div class="group inline-flex items-center px-5 py-3 bg-white/15 backdrop-blur-lg border border-white/25 rounded-full hover:bg-white/25 transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <svg class="w-4 h-4 mr-2 text-cyan-400 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        <span class="text-white font-semibold">{{ $allAssessments->count() }} Total</span>
                                    </div>
                                    <div class="group inline-flex items-center px-5 py-3 bg-white/15 backdrop-blur-lg border border-white/25 rounded-full hover:bg-white/25 transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <svg class="w-4 h-4 mr-2 text-emerald-400 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-white font-semibold">{{ $allAssessments->where(function($assessment) { return getAssessmentScore($assessment) !== null; })->count() }} Dinilai</span>
                                    </div>
                                    <div class="group inline-flex items-center px-5 py-3 bg-white/15 backdrop-blur-lg border border-white/25 rounded-full hover:bg-white/25 transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <svg class="w-4 h-4 mr-2 text-amber-400 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-white font-semibold">{{ number_format($allAssessments->where(function($assessment) { return getAssessmentScore($assessment) !== null; })->avg(function($assessment) { return getAssessmentScore($assessment); }), 1) }} Rata-rata</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Enhanced action buttons -->
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('guru.penilaian.create') }}" class="group relative bg-white/15 backdrop-blur-2xl border border-white/25 hover:bg-white/25 text-white px-6 py-3 rounded-2xl transition-all duration-300 flex items-center space-x-3 shadow-xl hover:shadow-2xl">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <svg class="w-4 h-4 relative z-10 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="relative z-10 font-bold">Manual</span>
                        </a>
                        <a href="{{ route('guru.penilaian.auto') }}" class="group relative bg-white/15 backdrop-blur-2xl border border-white/25 hover:bg-white/25 text-white px-6 py-3 rounded-2xl transition-all duration-300 flex items-center space-x-3 shadow-xl hover:shadow-2xl">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <svg class="w-4 h-4 relative z-10 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="relative z-10 font-bold">Otomatis</span>
                        </a>
                        <a href="{{ route('guru.penilaian.auto.criteria') }}" class="group relative bg-white/15 backdrop-blur-2xl border border-white/25 hover:bg-white/25 text-white px-6 py-3 rounded-2xl transition-all duration-300 flex items-center space-x-3 shadow-xl hover:shadow-2xl">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <svg class="w-4 h-4 relative z-10 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="relative z-10 font-bold">SOP</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Penilaian Card -->
            <div class="group relative bg-white/90 backdrop-blur-2xl border border-white/30 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-1">
                <!-- Animated gradient border -->
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/30 via-purple-600/30 to-pink-600/30 rounded-3xl animate-pulse"></div>
                
                <div class="relative z-10 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 p-3 rounded-2xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">{{ $allAssessments->count() }}</div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Total Penilaian</h3>
                    <p class="text-sm text-gray-600 font-medium">Semua jenis penilaian</p>
                </div>
            </div>

            <!-- Belum Dinilai Card -->
            <div class="group relative bg-white/90 backdrop-blur-2xl border border-white/30 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-1">
                <!-- Animated gradient border -->
                <div class="absolute inset-0 bg-gradient-to-r from-amber-600/30 via-orange-600/30 to-yellow-600/30 rounded-3xl animate-pulse"></div>
                
                <div class="relative z-10 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 p-3 rounded-2xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-orange-600">{{ $allAssessments->where(function($assessment) { return getAssessmentScore($assessment) === null; })->count() }}</div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Belum Dinilai</h3>
                    <p class="text-sm text-gray-600 font-medium">Menunggu penilaian</p>
                </div>
            </div>

            <!-- Sudah Dinilai Card -->
            <div class="group relative bg-white/90 backdrop-blur-2xl border border-white/30 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-1">
                <!-- Animated gradient border -->
                <div class="absolute inset-0 bg-gradient-to-r from-green-600/30 via-emerald-600/30 to-teal-600/30 rounded-3xl animate-pulse"></div>
                
                <div class="relative z-10 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-3 rounded-2xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">{{ $allAssessments->where(function($assessment) { return getAssessmentScore($assessment) !== null; })->count() }}</div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Sudah Dinilai</h3>
                    <p class="text-sm text-gray-600 font-medium">Selesai dinilai</p>
                </div>
            </div>

            <!-- Rata-rata Nilai Card -->
            <div class="group relative bg-white/90 backdrop-blur-2xl border border-white/30 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-1">
                <!-- Animated gradient border -->
                <div class="absolute inset-0 bg-gradient-to-r from-purple-600/30 via-indigo-600/30 to-blue-600/30 rounded-3xl animate-pulse"></div>
                
                <div class="relative z-10 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-500 p-3 rounded-2xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">{{ number_format($allAssessments->where(function($assessment) { return getAssessmentScore($assessment) !== null; })->avg(function($assessment) { return getAssessmentScore($assessment); }), 1) }}</div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Rata-rata Nilai</h3>
                    <p class="text-sm text-gray-600 font-medium">Skor rata-rata</p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="group relative bg-white/90 backdrop-blur-2xl border border-white/30 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-500 mb-8">
            <!-- Animated gradient border -->
            <div class="absolute inset-0 bg-gradient-to-r from-gray-600/30 via-slate-600/30 to-zinc-600/30 rounded-3xl animate-pulse"></div>
            
            <div class="relative z-10 p-6">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-gray-500 to-slate-500 p-3 rounded-2xl mr-4 shadow-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-800">Filter Penilaian</h3>
                        <p class="text-sm text-gray-600 font-medium">Saring data penilaian</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="group">
                        <label class="block text-sm font-black text-gray-800 mb-2 flex items-center">
                            <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mr-2 animate-pulse"></div>
                            Mata Pelajaran
                        </label>
                        <select id="subject_filter" class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300 font-bold text-gray-800 hover:bg-white/80 hover:border-blue-400/50">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="group">
                        <label class="block text-sm font-black text-gray-800 mb-2 flex items-center">
                            <div class="w-2 h-2 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full mr-2 animate-pulse"></div>
                            Kelas
                        </label>
                        <select id="class_filter" class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-green-500/30 focus:border-green-500 transition-all duration-300 font-bold text-gray-800 hover:bg-white/80 hover:border-green-400/50">
                            <option value="">Semua Kelas</option>
                            @php
                                $classes = [];
                                foreach($allAssessments as $assessment) {
                                    $student = getAssessmentStudent($assessment);
                                    $className = $student ? ($student->kelas->name ?? 'N/A') : 'N/A';
                                    if($className !== 'N/A' && !in_array($className, $classes)) {
                                        $classes[] = $className;
                                    }
                                }
                                sort($classes);
                            @endphp
                            @foreach($classes as $class)
                            <option value="{{ $class }}">{{ $class }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="group">
                        <label class="block text-sm font-black text-gray-800 mb-2 flex items-center">
                            <div class="w-2 h-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full mr-2 animate-pulse"></div>
                            Status Penilaian
                        </label>
                        <select id="status_filter" class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-amber-500/30 focus:border-amber-500 transition-all duration-300 font-bold text-gray-800 hover:bg-white/80 hover:border-amber-400/50">
                            <option value="">Semua Status</option>
                            <option value="graded">Sudah Dinilai</option>
                            <option value="ungraded">Belum Dinilai</option>
                        </select>
                    </div>

                    <div class="group flex items-end">
                        <button id="reset_filters" class="group relative w-full bg-gradient-to-r from-gray-50 to-slate-50 hover:from-gray-100 hover:to-slate-100 text-gray-700 px-6 py-3 rounded-2xl transition-all duration-300 flex items-center justify-center space-x-2 border border-gray-200/50 hover:border-gray-300/50">
                            <div class="absolute inset-0 bg-gradient-to-r from-gray-500/10 to-slate-500/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <svg class="w-4 h-4 relative z-10 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span class="relative z-10 font-bold">Reset Filter</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Table -->
        <div class="group relative bg-white/90 backdrop-blur-2xl border border-white/30 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-500">
            <!-- Animated gradient border -->
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/30 via-purple-600/30 to-pink-600/30 rounded-3xl animate-pulse"></div>
            
            <!-- Tabs -->
            <div class="relative z-10 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 px-6 py-4">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-purple-700 to-pink-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <!-- Animated shimmer effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 animate-pulse"></div>
                
                <div class="relative z-10 flex space-x-1">
                    <button class="tab-button group relative bg-white/20 backdrop-blur-lg border border-white/30 text-white px-6 py-3 rounded-2xl transition-all duration-300 flex items-center space-x-2 hover:bg-white/30" data-tab="all">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <svg class="w-4 h-4 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="relative z-10 font-bold">Semua</span>
                    </button>
                    <button class="tab-button group relative bg-white/10 backdrop-blur-lg border border-white/20 text-white/70 px-6 py-3 rounded-2xl transition-all duration-300 flex items-center space-x-2 hover:bg-white/20 hover:text-white" data-tab="assignments">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="font-bold">Tugas</span>
                    </button>
                    <button class="tab-button group relative bg-white/10 backdrop-blur-lg border border-white/20 text-white/70 px-6 py-3 rounded-2xl transition-all duration-300 flex items-center space-x-2 hover:bg-white/20 hover:text-white" data-tab="practicals">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <span class="font-bold">Praktikum</span>
                    </button>
                </div>
            </div>
            
            <!-- Table Content -->
            <div class="relative z-10 p-6">
                <div class="overflow-x-auto">
                    <table class="w-full" id="assessmentTable">
                        <thead>
                            <tr class="border-b border-gray-200/50">
                                <th class="text-left py-3 px-4 font-black text-gray-800 text-sm">#</th>
                                <th class="text-left py-3 px-4 font-black text-gray-800 text-sm">Siswa</th>
                                <th class="text-left py-3 px-4 font-black text-gray-800 text-sm">Kelas</th>
                                <th class="text-left py-3 px-4 font-black text-gray-800 text-sm">Mata Pelajaran</th>
                                <th class="text-left py-3 px-4 font-black text-gray-800 text-sm">Jenis</th>
                                <th class="text-left py-3 px-4 font-black text-gray-800 text-sm">Judul Aktivitas</th>
                                <th class="text-left py-3 px-4 font-black text-gray-800 text-sm">Nilai</th>
                                <th class="text-left py-3 px-4 font-black text-gray-800 text-sm">Status</th>
                                <th class="text-left py-3 px-4 font-black text-gray-800 text-sm">Tanggal</th>
                                <th class="text-left py-3 px-4 font-black text-gray-800 text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="assessmentTableBody">
                            @forelse($allAssessments as $index => $assessment)
                            <tr class="assessment-row border-b border-gray-100/50 hover:bg-gray-50/50 transition-colors duration-200"
                                data-type="{{ getAssessmentType($assessment) }}"
                                data-subject="{{ getAssessmentSubject($assessment)->id ?? '' }}"
                                data-class="{{ getAssessmentClass($assessment) }}"
                                data-status="{{ getAssessmentScore($assessment) !== null ? 'graded' : 'ungraded' }}">
                                <td class="py-4 px-4 font-bold text-gray-600">{{ $index + 1 }}</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 w-8 h-8 rounded-full flex items-center justify-center mr-3 shadow-lg">
                                            <span class="text-white font-bold text-sm">{{ substr(getAssessmentStudent($assessment)->name ?? 'N/A', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ getAssessmentStudent($assessment)->name ?? 'Tidak tersedia' }}</div>
                                            <div class="text-xs text-gray-500 font-medium">NIS: {{ getAssessmentStudent($assessment)->nis ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 rounded-full text-xs font-bold">{{ getAssessmentClass($assessment) }}</span>
                                </td>
                                <td class="py-4 px-4 font-bold text-gray-800">{{ getAssessmentSubject($assessment)->name ?? 'Tidak tersedia' }}</td>
                                <td class="py-4 px-4">
                                    @if(getAssessmentType($assessment) === 'assignment')
                                        <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-full text-xs font-bold">Tugas</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full text-xs font-bold">Praktikum</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 font-bold text-gray-800">{{ getAssessmentTitle($assessment) }}</td>
                                <td class="py-4 px-4">
                                    <div class="font-black text-lg {{ getAssessmentScore($assessment) !== null ? 'text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600' : 'text-gray-400' }}">
                                        {{ getAssessmentScore($assessment) ?? '-' }}
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if(getAssessmentScore($assessment) !== null)
                                        <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full text-xs font-bold">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Dinilai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-full text-xs font-bold">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-sm text-gray-600 font-medium">{{ $assessment->assessment_date ? $assessment->assessment_date->format('d M Y') : '-' }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('guru.penilaian.edit', $assessment->id) }}" 
                                           class="group relative bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 text-blue-700 p-2 rounded-xl transition-all duration-300 border border-blue-200/50 hover:border-blue-300/50">
                                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-indigo-500/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            <svg class="w-4 h-4 relative z-10 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-1.414a2 2 0 112.828 0L21 8M5 19h14"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('guru.penilaian.destroy', $assessment->id) }}" 
                                              method="POST" 
                                              class="inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus penilaian ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="group relative bg-gradient-to-r from-red-50 to-rose-50 hover:from-red-100 hover:to-rose-100 text-red-700 p-2 rounded-xl transition-all duration-300 border border-red-200/50 hover:border-red-300/50">
                                                <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 to-rose-500/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                <svg class="w-4 h-4 relative z-10 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-16">
                                    <div class="relative bg-gradient-to-br from-gray-50/80 to-white/80 backdrop-blur-sm rounded-3xl p-8 border border-gray-200/50 overflow-hidden">
                                        <!-- Background pattern -->
                                        <div class="absolute inset-0 opacity-5">
                                            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                                <pattern id="grid-empty" width="20" height="20" patternUnits="userSpaceOnUse">
                                                    <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="0.5"/>
                                                </pattern>
                                                <rect width="100" height="100" fill="url(#grid-empty)" />
                                            </svg>
                                        </div>
                                        
                                        <div class="relative z-10">
                                            <div class="bg-gradient-to-r from-gray-100 to-gray-200 w-20 h-20 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                                                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-2xl font-black text-gray-800 mb-3">Belum Ada Penilaian</h4>
                                            <p class="text-gray-600 font-medium mb-6">Belum ada data penilaian yang tersedia. Mulai buat penilaian untuk siswa Anda.</p>
                                            <a href="{{ route('guru.penilaian.create') }}" class="group relative bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 text-white px-8 py-4 rounded-2xl transition-all duration-300 shadow-lg flex items-center justify-center space-x-2 mx-auto inline-flex">
                                                <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                <svg class="w-4 h-4 relative z-10 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                <span class="relative z-10 font-bold">Buat Penilaian Baru</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    // Helper functions to handle different assessment types
    function getAssessmentScore($assessment) {
        if (isset($assessment->score)) {
            return $assessment->score;
        } elseif (isset($assessment->total_nilai)) {
            return $assessment->total_nilai;
        }
        return null;
    }
    
    function getAssessmentStudent($assessment) {
        if (isset($assessment->siswa)) {
            return $assessment->siswa;
        } elseif (isset($assessment->student)) {
            return $assessment->student;
        }
        return null;
    }
    
    function getAssessmentDate($assessment) {
        if (isset($assessment->graded_at)) {
            return $assessment->graded_at;
        } elseif (isset($assessment->tanggal_praktik)) {
            return $assessment->tanggal_praktik;
        }
        return $assessment->created_at ?? null;
    }
    
    function getAssessmentSubject($assessment) {
        if (isset($assessment->assignment)) {
            return $assessment->assignment->subject ?? null;
        } elseif (isset($assessment->practical)) {
            return $assessment->practical->subject ?? null;
        }
        // For NilaiPraktik, create a dummy subject object
        return (object) [
            'id' => $assessment->id,
            'name' => $assessment->mata_praktik ?? 'Praktikum'
        ];
    }
    
    function getAssessmentTitle($assessment) {
        if (isset($assessment->assignment)) {
            return $assessment->assignment->judul ?? 'Tugas';
        } elseif (isset($assessment->practical)) {
            return $assessment->practical->judul ?? 'Praktikum';
        }
        // For NilaiPraktik, use mata_praktik as title
        return $assessment->mata_praktik ?? 'Aktivitas';
    }
    
    function getAssessmentType($assessment) {
        if (isset($assessment->assignment)) {
            return 'assignment';
        } elseif (isset($assessment->practical)) {
            return 'practical';
        }
        // For NilaiPraktik, return 'practical'
        return 'practical';
    }
    
    function getAssessmentClass($assessment) {
        $student = getAssessmentStudent($assessment);
        return $student ? ($student->kelas->name ?? 'N/A') : 'N/A';
    }
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const tabButtons = document.querySelectorAll('.tab-button');
    const rows = document.querySelectorAll('.assessment-row');
    const subjectFilter = document.getElementById('subject_filter');
    const classFilter = document.getElementById('class_filter');
    const statusFilter = document.getElementById('status_filter');
    const resetButton = document.getElementById('reset_filters');

    function filterTable() {
        const activeTab = document.querySelector('.tab-button.bg-white\\/20')?.dataset.tab || 'all';
        const subjectValue = subjectFilter.value;
        const classValue = classFilter.value;
        const statusValue = statusFilter.value;
        
        let visibleCount = 0;

        rows.forEach(row => {
            const type = row.dataset.type;
            const subject = row.dataset.subject;
            const klass = row.dataset.class;
            const status = row.dataset.status;

            let show = true;

            // Filter by tab
            if (activeTab !== 'all') {
                if (activeTab === 'assignments' && type !== 'assignment') show = false;
                if (activeTab === 'practicals' && type !== 'practical') show = false;
            }

            // Filter by subject
            if (subjectValue && subjectValue !== subject) show = false;

            // Filter by class
            if (classValue && classValue !== klass) show = false;

            // Filter by status
            if (statusValue && statusValue !== status) show = false;

            if (show) {
                row.style.display = '';
                visibleCount++;
                // Update row number
                row.querySelector('td:first-child').textContent = visibleCount;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide empty state
        const emptyRow = document.querySelector('tbody tr td[colspan]')?.closest('tr');
        if (emptyRow) {
            emptyRow.style.display = visibleCount === 0 && rows.length === 1 ? '' : 'none';
        }
    }

    // Tab click event
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active from all tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('bg-white/20', 'border-white/30', 'text-white');
                btn.classList.add('bg-white/10', 'border-white/20', 'text-white/70');
            });
            // Add active to clicked tab
            this.classList.remove('bg-white/10', 'border-white/20', 'text-white/70');
            this.classList.add('bg-white/20', 'border-white/30', 'text-white');
            
            // Filter table
            setTimeout(filterTable, 100);
        });
    });

    // Filter change events
    if (subjectFilter) subjectFilter.addEventListener('change', filterTable);
    if (classFilter) classFilter.addEventListener('change', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);

    // Reset filters
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (subjectFilter) subjectFilter.value = '';
            if (classFilter) classFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            
            // Reset active tab
            tabButtons.forEach(btn => {
                btn.classList.remove('bg-white/20', 'border-white/30', 'text-white');
                btn.classList.add('bg-white/10', 'border-white/20', 'text-white/70');
            });
            const allTab = document.querySelector('[data-tab="all"]');
            if (allTab) {
                allTab.classList.remove('bg-white/10', 'border-white/20', 'text-white/70');
                allTab.classList.add('bg-white/20', 'border-white/30', 'text-white');
            }
            
            filterTable();
        });
    }
    
    // Initial filter
    filterTable();
});
</script>
@endsection
