@extends('layouts.siswa')

@section('title', 'Detail Nilai Praktikum - LMS Trimurti Husada')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2 class="mb-0">Detail Nilai Praktikum</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Praktikum:</strong> {{ optional($score->practical)->judul ?? '-' }}</p>
                    <p><strong>Kriteria:</strong> {{ optional($score->criteria)->name }}</p>
                    <p><strong>Nilai:</strong> {{ $score?->score ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Rata-rata Kelas:</strong> {{ number_format($averageScore, 2) }}</p>
                    <p><strong>Tanggal Dinilai:</strong> {{ optional($score->created_at)->format('d M Y') }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <a href="{{ route('siswa.reports.practical') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
