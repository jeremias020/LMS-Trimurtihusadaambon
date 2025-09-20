@extends('layouts.guru')

@section('title', 'Penilaian Praktikum - ' . $praktikum->title)

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Penilaian Praktikum</h1>
    <p class="text-gray-600">Kelas {{ $praktikum->kelas }} • {{ optional($praktikum->subject)->name }}</p>
    <p class="text-gray-500">Pilih siswa, lakukan checklist SOP, lalu Generate Nilai Otomatis</p>
    <a href="{{ route('guru.practicals.index') }}" class="btn-secondary mt-3">Kembali</a>
    <a href="{{ route('guru.practicals.show', $praktikum->id) }}" class="btn-secondary mt-3">Detail Praktikum</a>
    <a href="{{ route('guru.practicals.scores', $praktikum->id) }}" class="btn-secondary mt-3">Rekap Nilai</a>
    @include('partials.notifications')
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Siswa</h3>
                <p class="text-sm text-gray-500">Kelas {{ $praktikum->kelas }}</p>
            </div>
            <div class="px-6 py-4 space-y-2 max-h-[65vh] overflow-y-auto">
                @foreach($siswas as $siswa)
                    <a href="{{ request()->fullUrlWithQuery(['siswa' => $siswa->id]) }}"
                       class="flex items-center justify-between p-3 rounded-lg border {{ (int)request('siswa') === $siswa->id ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200 hover:bg-gray-100' }}">
                        <span class="text-sm font-medium text-gray-800">{{ $siswa->name }}</span>
                        <span class="text-xs text-gray-500">NIS: {{ $siswa->nis }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        @php $selectedSiswa = $siswas->firstWhere('id', (int)request('siswa')); @endphp
        @if($selectedSiswa)
            @include('guru.penilaian._sop_checklist', [
                'practical' => $praktikum,
                'siswa' => $selectedSiswa,
                'criterias' => $criterias,
            ])
        @else
            <div class="bg-white rounded-lg shadow p-10 text-center text-gray-500 border border-dashed border-gray-300">
                Silakan pilih siswa di sisi kiri untuk melakukan penilaian SOP.
            </div>
        @endif
    </div>
</div>
@endsection


