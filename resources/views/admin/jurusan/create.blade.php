@extends('admin.layouts.admin-layout')

@section('title', 'Tambah Jurusan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tambah Jurusan</h5>
        <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.jurusan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="return_to" value="{{ request('return_to') }}">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Deskripsi jurusan">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                <div id="mapel-wrapper">
                    @php $selectedMapels = old('mata_pelajaran', []); @endphp
                    @if(count($selectedMapels) > 0)
                        @foreach($selectedMapels as $i => $selectedMapel)
                        <div class="input-group mb-2">
                            <select name="mata_pelajaran[]" class="form-select" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ $selectedMapel == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-danger remove-mapel"><i class="fas fa-trash"></i></button>
                        </div>
                        @endforeach
                    @else
                        <div class="input-group mb-2">
                            <select name="mata_pelajaran[]" class="form-select" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-danger remove-mapel"><i class="fas fa-trash"></i></button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="add-mapel"><i class="fas fa-plus me-1"></i>Tambah Mapel</button>
                @error('mata_pelajaran') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kapasitas Total</label>
                    <input type="number" name="kapasitas_total" class="form-control @error('kapasitas_total') is-invalid @enderror" value="{{ old('kapasitas_total') }}" min="1">
                    @error('kapasitas_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Aktif</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const addBtn = document.getElementById('add-mapel');
  const wrapper = document.getElementById('mapel-wrapper');
  
  // Options for dropdown (dari server-side rendering)
  const subjectOptions = {!! json_encode($subjects->map(function($subject) {
      return [
          'id' => $subject->id,
          'name' => $subject->name . ' (' . $subject->code . ')'
      ];
  })) !!};
  
  addBtn.addEventListener('click', function(){
    const group = document.createElement('div');
    group.className = 'input-group mb-2';
    
    let optionsHtml = '<option value="">-- Pilih Mata Pelajaran --</option>';
    subjectOptions.forEach(function(subject) {
        optionsHtml += '<option value="' + subject.id + '">' + subject.name + '</option>';
    });
    
    group.innerHTML = '<select name="mata_pelajaran[]" class="form-select" required>' + optionsHtml + '</select>' +
      '<button type="button" class="btn btn-outline-danger remove-mapel"><i class="fas fa-trash"></i></button>';
    wrapper.appendChild(group);
  });
  
  wrapper.addEventListener('click', function(e){
    if(e.target.closest('.remove-mapel')){
      const row = e.target.closest('.input-group');
      row.parentNode.removeChild(row);
    }
  });
});
</script>
@endpush
