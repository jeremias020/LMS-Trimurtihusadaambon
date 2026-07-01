@extends('layouts.guru')

@section('title', 'Edit Praktikum')
@section('page-title', 'Edit Praktikum')
@section('page-subtitle', $praktikum->title)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('guru.praktikum.index') }}">Praktikum</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 fw-semibold">Edit: {{ $praktikum->title }}</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('guru.praktikum.update', $praktikum->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-medium">Judul Praktikum <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $praktikum->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-medium">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                              required>{{ old('description', $praktikum->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-medium">Instruksi / Prosedur</label>
                    <textarea name="instructions" rows="4" class="form-control @error('instructions') is-invalid @enderror">{{ old('instructions', $praktikum->instructions) }}</textarea>
                    @error('instructions')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Mata Pelajaran <span class="text-danger">*</span></label>
                    <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" {{ old('subject_id', $praktikum->subject_id) == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Kelas <span class="text-danger">*</span></label>
                    <select name="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id', $praktikum->kelas_id) == $k->id ? 'selected' : '' }}>
                                {{ $k->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Tanggal Pelaksanaan</label>
                    <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
                           value="{{ old('due_date', $praktikum->due_date?->format('Y-m-d')) }}">
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Durasi (menit)</label>
                    <input type="number" name="durasi" class="form-control @error('durasi') is-invalid @enderror"
                           value="{{ old('durasi', $praktikum->durasi) }}" min="1">
                    @error('durasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Nilai Maksimal</label>
                    <input type="number" name="max_score" class="form-control @error('max_score') is-invalid @enderror"
                           value="{{ old('max_score', $praktikum->max_score ?? 100) }}" min="1" max="1000">
                    @error('max_score')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                           value="{{ old('lokasi', $praktikum->lokasi) }}">
                    @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Tingkat Kesulitan</label>
                    <select name="skill_level" class="form-select @error('skill_level') is-invalid @enderror">
                        <option value="">Pilih Tingkat</option>
                        @foreach(['Pemula','Menengah','Mahir'] as $lvl)
                            <option value="{{ $lvl }}" {{ old('skill_level', $praktikum->skill_level) == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                        @endforeach
                    </select>
                    @error('skill_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="is_published" value="1" class="form-check-input"
                               id="is_published" {{ old('is_published', $praktikum->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">Publikasikan</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('guru.praktikum.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
