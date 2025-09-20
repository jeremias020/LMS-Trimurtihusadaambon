@extends('layouts.admin')

@section('title', 'Detail Pengguna - ' . $user->name)

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Detail Pengguna</h1>
    <p class="text-gray-600">Informasi lengkap tentang pengguna - SMK Kesehatan Trimurti Husada Ambon</p>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- User Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Profil Pengguna</h2>
            </div>

            <div class="px-6 py-4">
                <div class="text-center">
                    <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-full mx-auto object-cover border-4 border-gray-200">
                    <h3 class="mt-4 text-xl font-semibold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-gray-600">{{ $user->email }}</p>

                    <div class="mt-4">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($user->role === 'admin') bg-blue-100 text-blue-800
                            @elseif($user->role === 'guru') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($user->role) }}
                        </span>

                        <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($user->is_active) bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    @if($user->phone)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-gray-600">{{ $user->phone ?? '-' }}</span>
                    </div>
                    @endif

                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-600">Bergabung: {{ $user->created_at->format('d/m/Y') }}</span>
                    </div>

                    @if($user->last_login_at)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span class="text-gray-600">Login terakhir: {{ $user->last_login_at->diffForHumans() }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-6 flex space-x-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary flex-1 text-center">
                        Edit
                    </a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger w-full">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- User Details -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Informasi Detail</h2>
            </div>

            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            @if($user->phone)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                                <dd class="text-sm text-gray-900">{{ $user->phone }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Role</dt>
                                <dd class="text-sm text-gray-900 capitalize">{{ $user->role }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-sm text-gray-900">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Bergabung Pada</dt>
                                <dd class="text-sm text-gray-900">{{ $user->created_at->format('d F Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Role Specific Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Spesifik</h3>
                        <dl class="space-y-3">
                            @if($user->role === 'guru')
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">NIP</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->nip ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Mata Pelajaran</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->subject ?? '-' }}</dd>
                                </div>
                            @elseif($user->role === 'siswa')
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">NIS</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->nis ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kelas</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->class ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Lahir</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->address ?? '-' }}</dd>
                                </div>
                            @else
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Hak Akses</dt>
                                    <dd class="text-sm text-gray-900">Akses penuh sistem</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Additional Information -->
                @if($user->role === 'guru' || $user->role === 'siswa')
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if($user->role === 'guru')
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['materials_count'] ?? 0 }}</div>
                            <div class="text-sm text-blue-800">Materi</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['assignments_count'] ?? 0 }}</div>
                            <div class="text-sm text-green-800">Tugas</div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ $stats['practicals_count'] ?? 0 }}</div>
                            <div class="text-sm text-yellow-800">Praktikum</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $stats['students_count'] ?? 0 }}</div>
                            <div class="text-sm text-purple-800">Siswa</div>
                        </div>
                        @elseif($user->role === 'siswa')
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['completed_assignments'] ?? 0 }}</div>
                            <div class="text-sm text-blue-800">Tugas Selesai</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['average_score'] ?? 0 }}</div>
                            <div class="text-sm text-green-800">Nilai Rata-rata</div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ $stats['attendance_rate'] ?? 0 }}%</div>
                            <div class="text-sm text-yellow-800">Kehadiran</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $stats['pending_tasks'] ?? 0 }}</div>
                            <div class="text-sm text-purple-800">Tugas Tertunda</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Aktivitas Terbaru</h2>
            </div>

            <div class="px-6 py-4">
                @if(isset($activities) && $activities->count() > 0)
                <div class="space-y-4">
                    @foreach($activities as $activity)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $activity->description ?? 'Aktivitas' }}</p>
                            <p class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="ml-auto text-xs text-gray-400">
                            {{ $activity->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-4">Tidak ada aktivitas terbaru</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
