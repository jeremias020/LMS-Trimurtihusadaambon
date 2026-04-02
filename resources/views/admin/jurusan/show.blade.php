@extends('admin.layouts.admin-layout')

@section('title', 'Detail Jurusan - ' . $jurusan->nama)

@section('content')
<div class="row">
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Informasi Jurusan</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <div class="text-muted small">Nama</div>
          <div class="fw-semibold">{{ $jurusan->nama }}</div>
        </div>
        <div class="mb-3">
          <div class="text-muted small">Kode</div>
          <div><span class="badge bg-secondary">{{ $jurusan->kode }}</span></div>
        </div>
        <div class="mb-3">
          <div class="text-muted small">Deskripsi</div>
          <div>{{ $jurusan->deskripsi ?: '-' }}</div>
        </div>
        <div class="mb-3">
          <div class="text-muted small">Status</div>
          <div>
            @if($jurusan->status)
              <span class="badge bg-success">Aktif</span>
            @else
              <span class="badge bg-secondary">Nonaktif</span>
            @endif
          </div>
        </div>
        <div class="mb-3">
          <div class="text-muted small">Kapasitas Total</div>
          <div>{{ $jurusan->kapasitas_total ?? '-' }}</div>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('admin.jurusan.edit', $jurusan->id) }}" class="btn btn-warning flex-fill"><i class="fas fa-edit me-1"></i>Edit</a>
          <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary flex-fill"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list-ul me-2"></i>Mata Pelajaran</h5>
      </div>
      <div class="card-body">
        @php $mapels = (array) $jurusan->mata_pelajaran; @endphp
        @if(!empty($mapels))
          <ul class="list-group list-group-flush">
            @foreach($mapels as $mp)
              <li class="list-group-item d-flex align-items-center">
                <i class="fas fa-book-open text-primary me-2"></i>
                <span>{{ $mp }}</span>
              </li>
            @endforeach
          </ul>
        @else
          <div class="text-muted">Belum ada daftar mata pelajaran.</div>
        @endif
      </div>
    </div>

    <div class="card mt-4">
      <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-school me-2"></i>Kelas Terkait</h5>
      </div>
      <div class="card-body">
        @if($jurusan->kelas()->count())
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-light">
                <tr>
                  <th>Nama Kelas</th>
                  <th>Kode</th>
                  <th>Tingkat</th>
                  <th>Tahun Ajaran</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($jurusan->kelas as $kls)
                <tr>
                  <td><a href="{{ route('admin.kelas.show', $kls->id) }}">{{ $kls->name }}</a></td>
                  <td>{{ $kls->code }}</td>
                  <td>{{ $kls->grade }}</td>
                  <td>{{ $kls->academic_year }}</td>
                  <td>
                    @if($kls->status === 'active')
                      <span class="badge bg-success">Aktif</span>
                    @else
                      <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-muted">Belum ada kelas terkait jurusan ini.</div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
