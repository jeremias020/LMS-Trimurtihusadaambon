@extends('layouts.guru')

@section('title', 'Praktikum - Guru')
@section('page-title', 'Praktikum')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Praktikum</h5>
        <a href="{{ route('guru.praktikum.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Buat Praktikum Baru
        </a>
    </div>
    <div class="card-body">
        <p class="text-muted mb-0">Belum ada data. Ini adalah halaman placeholder untuk modul Praktikum Guru.</p>
    </div>
</div>
@endsection
