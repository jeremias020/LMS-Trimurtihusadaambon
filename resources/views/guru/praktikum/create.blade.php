@extends('layouts.guru')

@section('title', 'Buat Praktikum - Guru')
@section('page-title', 'Buat Praktikum')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Form Praktikum Baru</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('guru.praktikum.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Judul Praktikum</label>
                    <input type="text" name="judul" class="form-control" placeholder="Contoh: Praktikum Anatomi Dasar">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" placeholder="Deskripsi singkat praktikum..."></textarea>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <a href="{{ route('guru.praktikum.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
