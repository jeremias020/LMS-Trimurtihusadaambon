@extends('admin.layouts.admin-layout')

@section('title', 'Edit Kelas - ' . $kelas->name)

@push('css')
<style>
/* Custom styling for edit kelas form */
.form-card {
    background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
    border: 1px solid #e3e6f0;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.form-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.form-group label {
    font-weight: 600;
    color: #5a5c69;
    margin-bottom: 8px;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #d1d3e2;
    padding: 12px 15px;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-group .btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 12px 24px;
    transition: all 0.3s ease;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.kelas.index') }}">Manajemen Kelas</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.kelas.show', $kelas->id) }}">{{ $kelas->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card form-card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit me-2"></i>
                        <h5 class="mb-0">Edit Kelas: {{ $kelas->name }}</h5>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Nama Kelas -->
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-tag me-1 text-primary"></i>
                                        Nama Kelas <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $kelas->name) }}" 
                                           placeholder="Contoh: XII Keperawatan A"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kode Kelas -->
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="code" class="form-label">
                                        <i class="fas fa-barcode me-1 text-primary"></i>
                                        Kode Kelas <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('code') is-invalid @enderror" 
                                           id="code" 
                                           name="code" 
                                           value="{{ old('code', $kelas->code) }}" 
                                           placeholder="Contoh: KEP12A"
                                           required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Tingkat -->
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="grade" class="form-label">
                                        <i class="fas fa-layer-group me-1 text-primary"></i>
                                        Tingkat <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('grade') is-invalid @enderror" 
                                            id="grade" 
                                            name="grade" 
                                            required>
                                        <option value="">Pilih Tingkat</option>
                                        <option value="X" {{ old('grade', $kelas->grade) == 'X' ? 'selected' : '' }}>Kelas X</option>
                                        <option value="XI" {{ old('grade', $kelas->grade) == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                        <option value="XII" {{ old('grade', $kelas->grade) == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                                    </select>
                                    @error('grade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Jurusan (tersinkron dari pilihan) -->
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-graduation-cap me-1 text-primary"></i>
                                        Jurusan (tersinkron)
                                    </label>
                                    <input type="text" id="major_display" class="form-control" value="{{ old('major', $kelas->major) }}" placeholder="Pilih jurusan terlebih dahulu" readonly>
                                    <input type="hidden" id="major" name="major" value="{{ old('major', $kelas->major) }}">
                                    @error('major')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kapasitas -->
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="capacity" class="form-label">
                                        <i class="fas fa-users me-1 text-primary"></i>
                                        Kapasitas
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('capacity') is-invalid @enderror" 
                                           id="capacity" 
                                           name="capacity" 
                                           value="{{ old('capacity', $kelas->capacity) }}" 
                                           min="1" 
                                           max="50" 
                                           placeholder="40">
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Jurusan (Relasi) -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="jurusan_id" class="form-label">
                                        <i class="fas fa-graduation-cap me-1 text-primary"></i>
                                        Pilih Jurusan <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('jurusan_id') is-invalid @enderror" 
                                            id="jurusan_id" 
                                            name="jurusan_id" required>
                                        <option value="">-- Pilih Jurusan --</option>
                                        @foreach($jurusans as $jurusan)
                                            <option value="{{ $jurusan->id }}" data-nama="{{ $jurusan->nama }}" {{ (string) old('jurusan_id', $kelas->jurusan_id) === (string) $jurusan->id ? 'selected' : '' }}>
                                                {{ $jurusan->nama }} ({{ $jurusan->kode }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jurusan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Wali Kelas -->
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="guru_id" class="form-label">
                                        <i class="fas fa-chalkboard-teacher me-1 text-primary"></i>
                                        Wali Kelas
                                    </label>
                                    <select class="form-control @error('guru_id') is-invalid @enderror" 
                                            id="guru_id" 
                                            name="guru_id">
                                        <option value="">Pilih Wali Kelas</option>
                                        @foreach($availableGuru as $guru)
                                            <option value="{{ $guru->id }}" {{ old('guru_id', $kelas->guru_id) == $guru->id ? 'selected' : '' }}>
                                                {{ $guru->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('guru_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tahun Ajaran -->
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="academic_year" class="form-label">
                                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                        Tahun Ajaran <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('academic_year') is-invalid @enderror" 
                                           id="academic_year" 
                                           name="academic_year" 
                                           value="{{ old('academic_year', $kelas->academic_year) }}" 
                                           placeholder="2024/2025"
                                           required>
                                    @error('academic_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-toggle-on me-1 text-primary"></i>
                                        Status
                                    </label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status">
                                        <option value="active" {{ old('status', $kelas->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status', $kelas->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left me-1 text-primary"></i>
                                        Deskripsi
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3" 
                                              placeholder="Deskripsi kelas (opsional)">{{ old('description', $kelas->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('admin.kelas.show', $kelas->id) }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>
                                            Kembali
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-1"></i>
                                            Simpan Perubahan
                                        </button>
                                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i>
                                            Batal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate class code based on grade and major
    const gradeSelect = document.getElementById('grade');
    const majorInput = document.getElementById('major');
    const majorDisplay = document.getElementById('major_display');
    const jurusanSelect = document.getElementById('jurusan_id');
    const codeInput = document.getElementById('code');

    function generateCode() {
        const grade = gradeSelect.value;
        const major = majorInput.value;

        if (grade && major) {
            let majorCode = '';
            switch (major) {
                case 'Keperawatan':
                    majorCode = 'KEP';
                    break;
                case 'Farmasi':
                    majorCode = 'FAR';
                    break;
                case 'Analis Kesehatan':
                    majorCode = 'AKS';
                    break;
                default:
                    majorCode = (major.substring(0,3) || 'GEN').toUpperCase();
            }

            // Simple default suggestion
            const code = majorCode + grade + 'A';
            if (!codeInput.value || codeInput.value.includes(majorCode)) {
                codeInput.value = code;
            }
        }
    }

    function syncMajorFromJurusan() {
        const selected = jurusanSelect.options[jurusanSelect.selectedIndex];
        const nama = selected ? selected.getAttribute('data-nama') : '';
        if (nama) {
            majorInput.value = nama;
            if (majorDisplay) majorDisplay.value = nama;
            generateCode();
        }
    }

    if (jurusanSelect) {
        jurusanSelect.addEventListener('change', syncMajorFromJurusan);
        syncMajorFromJurusan();
    }

    gradeSelect.addEventListener('change', generateCode);

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = ['name', 'code', 'grade', 'major', 'academic_year'];
        let isValid = true;
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi.');
        }
    });
});
</script>
@endpush