@extends('admin.layouts.admin-layout')

@section('title', 'Tambah Kelas')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <div class="d-flex align-items-center">
            <i class="fas fa-plus me-2"></i>
            <h5 class="mb-0">Tambah Kelas</h5>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.kelas.store') }}" method="POST">
            @csrf

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
                               value="{{ old('name') }}" 
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
                               value="{{ old('code') }}" 
                               placeholder="Contoh: KEP12A"
                               maxlength="20"
                               required>
                        @error('code')
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
                                <option value="{{ $jurusan->id }}" data-nama="{{ $jurusan->nama }}" {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                    {{ $jurusan->nama }} ({{ $jurusan->kode }})
                                </option>
                            @endforeach
                        </select>
                        @error('jurusan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tingkat -->
                <div class="col-md-6 mb-3">
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
                            <option value="X" {{ old('grade') == 'X' ? 'selected' : '' }}>Kelas X</option>
                            <option value="XI" {{ old('grade') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                            <option value="XII" {{ old('grade') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                        </select>
                        @error('grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Hidden field for major auto-sync -->
            <input type="hidden" id="major" name="major" value="{{ old('major') }}">

            <div class="row">
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
                               value="{{ old('capacity', 40) }}" 
                               min="1" 
                               max="50" 
                               placeholder="40">
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Wali Kelas -->
                <div class="col-md-4 mb-3">
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
                                <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
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
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="academic_year" class="form-label">
                            <i class="fas fa-calendar-alt me-1 text-primary"></i>
                            Tahun Ajaran <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('academic_year') is-invalid @enderror" 
                               id="academic_year" 
                               name="academic_year" 
                               value="{{ old('academic_year') }}" 
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
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
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
                                  placeholder="Deskripsi kelas (opsional)">{{ old('description') }}</textarea>
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
                            <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali
                            </a>
                        </div>
                        <div class="btn-group">
                            <button type="reset" class="btn btn-outline-warning">
                                <i class="fas fa-undo me-1"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate class code based on grade and major
    const gradeSelect = document.getElementById('grade');
    const majorInput = document.getElementById('major');
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

            // Simple default suggestion (user can override)
            const code = majorCode + grade + 'A';
            if (!codeInput.value || codeInput.value.includes(majorCode)) {
                codeInput.value = code;
            }
        }
    }

    function syncMajorFromJurusan() {
        const selectedOption = jurusanSelect.options[jurusanSelect.selectedIndex];
        const majorName = selectedOption ? selectedOption.dataset.nama : '';
        majorInput.value = majorName;
        generateCode();
    }

    // Event listeners - NO FORM SUBMISSION INTERFERENCE
    gradeSelect.addEventListener('change', generateCode);
    jurusanSelect.addEventListener('change', syncMajorFromJurusan);
    
    // Initialize on load if old value exists
    syncMajorFromJurusan();
});
</script>
@endpush
@endsection
