@extends('layouts.admin')

@section('title', 'Edit Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.assignments.index') }}">Tugas & Quiz</a></li>
    <li class="breadcrumb-item active">Edit Tugas</li>
@endsection

@section('page-title', 'Edit Tugas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Tugas</h3>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.assignments.update', $assignment) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $assignment->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi Tugas <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="5" required>{{ old('description', $assignment->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="guru_id" class="form-label">Guru Penanggung Jawab <span class="text-danger">*</span></label>
                                <select class="form-select @error('guru_id') is-invalid @enderror" 
                                        id="guru_id" name="guru_id" required>
                                    <option value="">Pilih Guru</option>
                                    @foreach($gurus as $guru)
                                        <option value="{{ $guru->id }}" 
                                                {{ old('guru_id', $assignment->guru_id) == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->name }} ({{ $guru->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('guru_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Tanggal Deadline <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date" name="due_date" 
                                       value="{{ old('due_date', $assignment->deadline->format('Y-m-d\TH:i')) }}" required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="max_score" class="form-label">Nilai Maksimal <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('max_score') is-invalid @enderror" 
                                       id="max_score" name="max_score" 
                                       value="{{ old('max_score', $assignment->max_score) }}" 
                                       min="0" max="100" required>
                                @error('max_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="attachment" class="form-label">Lampiran Baru</label>
                                <input type="file" class="form-control @error('attachment') is-invalid @enderror" 
                                       id="attachment" name="attachment" 
                                       accept=".pdf,.doc,.docx,.ppt,.pptx">
                                <div class="form-text">Format yang didukung: PDF, DOC, DOCX, PPT, PPTX (Max: 10MB)</div>
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if($assignment->file)
                                    <div class="mt-2">
                                        <label class="form-label">Lampiran Saat Ini:</label>
                                        <div class="border rounded p-2 bg-light">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-paperclip me-2 text-muted"></i>
                                                <span class="flex-grow-1">{{ $assignment->file }}</span>
                                                <a href="{{ asset('storage/assignments/' . $assignment->file) }}" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-download me-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                        <small class="text-muted">Upload file baru untuk mengganti lampiran saat ini.</small>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_published" name="is_published" 
                                           {{ old('is_published', $assignment->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        Publikasikan Tugas
                                    </label>
                                </div>
                                <div class="form-text">Centang untuk mempublikasikan tugas kepada siswa</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.assignments.show', $assignment) }}" class="btn btn-secondary">
                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                </a>
                                <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update Tugas
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Set minimum date to today
    const today = new Date().toISOString().slice(0, 16);
    $('#due_date').attr('min', today);
});
</script>
@endpush
