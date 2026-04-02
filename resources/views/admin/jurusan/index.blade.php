@extends('admin.layouts.admin-layout')

@section('title', 'Manajemen Jurusan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manajemen Jurusan</h5>
        <a href="{{ route('admin.jurusan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Jurusan
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Kode</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurusan as $jsn)
                    <tr>
                        <td class="fw-semibold">{{ $jsn->name }}</td>
                        <td><span class="badge bg-secondary">{{ $jsn->code }}</span></td>
                        <td>{{ $jsn->description ?? '-' }}</td>
                        <td>{{ $jsn->kelas_count ?? $jsn->kelas()->count() }}</td>
                        <td>{{ $jsn->siswa_count ?? $jsn->siswa()->count() }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.jurusan.show', $jsn->id) }}" class="btn btn-sm btn-outline-info" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.jurusan.edit', $jsn->id) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.jurusan.destroy', $jsn->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jurusan ini? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">Belum ada data jurusan</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
