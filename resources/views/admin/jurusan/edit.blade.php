@extends('admin.layouts.admin-layout')

@section('title', 'Edit Jurusan - ' . $jurusan->nama)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Jurusan: {{ $jurusan->nama }}</h5>
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

        <form action="{{ route('admin.jurusan.update', $jurusan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $jurusan->nama) }}" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $jurusan->kode) }}" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Deskripsi jurusan">{{ old('deskripsi', $jurusan->deskripsi) }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                <div id="mapel-wrapper">
                    @php $mapels = old('mata_pelajaran', (array) $jurusan->mata_pelajaran ?: ['']); @endphp
                    @foreach($mapels as $i => $mp)
                    <div class="input-group mb-2">
                        <input type="text" name="mata_pelajaran[]" class="form-control" placeholder="Nama mata pelajaran" value="{{ $mp }}" required>
                        <button type="button" class="btn btn-outline-danger remove-mapel"><i class="fas fa-trash"></i></button>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="add-mapel"><i class="fas fa-plus me-1"></i>Tambah Mapel</button>
                @error('mata_pelajaran') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kapasitas Total</label>
                    <input type="number" name="kapasitas_total" class="form-control @error('kapasitas_total') is-invalid @enderror" value="{{ old('kapasitas_total', $jurusan->kapasitas_total) }}" min="1">
                    @error('kapasitas_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $jurusan->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Aktif</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i>Simpan Perubahan
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
  addBtn.addEventListener('click', function(){
    const group = document.createElement('div');
    group.className = 'input-group mb-2';
    group.innerHTML = `<input type=\"text\" name=\"mata_pelajaran[]\" class=\"form-control\" placeholder=\"Nama mata pelajaran\" required>
      <button type=\"button\" class=\"btn btn-outline-danger remove-mapel\"><i class=\"fas fa-trash\"></i></button>`;
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
