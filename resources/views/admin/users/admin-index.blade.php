@extends('layouts.admin')

@section('title', 'Manajemen Administrator')

@section('page-title', 'Manajemen administrator')
@section('page-subtitle', 'Akun dengan peran administrator pada sistem.')

@section('page-actions')
    <a href="{{ route('admin.users.guru') }}" class="btn btn-outline-success btn-sm">
        <i class="fas fa-chalkboard-teacher me-1"></i> Guru
    </a>
    <a href="{{ route('admin.users.siswa') }}" class="btn btn-outline-warning btn-sm text-dark">
        <i class="fas fa-user-graduate me-1"></i> Siswa
    </a>
    <a href="{{ route('admin.users.create.admin') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Tambah admin
    </a>
@endsection

@section('content')
@php
    $total = $admins->count();
    $aktif = $admins->where('is_active', true)->count();
    $baru = $admins->filter(fn ($u) => $u->created_at && $u->created_at->greaterThanOrEqualTo(now()->subMonth()))->count();
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-indigo-100 rounded-lg">
                <i class="fas fa-user-shield text-indigo-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total admin</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($total) }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
                <i class="fas fa-user-check text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Aktif</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($aktif) }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-sky-100 rounded-lg">
                <i class="fas fa-user-plus text-sky-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Baru (30 hari)</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($baru) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <h2 class="text-lg font-semibold text-gray-800 mb-0">Daftar administrator</h2>
        <div class="d-flex align-items-center gap-2">
            <input type="search" class="form-control form-control-sm" placeholder="Cari nama, email…" id="adminSearch" style="width: 14rem;" autocomplete="off">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="adminSearchReset" title="Reset">
                <i class="fas fa-undo"></i>
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:3rem;">No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th style="width:7rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $i => $admin)
                <tr class="admin-row">
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $admin->photo_url }}" class="rounded-circle me-2 flex-shrink-0" width="36" height="36" alt="">
                            <div class="min-w-0">
                                <div class="fw-semibold text-truncate">{{ $admin->name }}</div>
                                <small class="text-muted">{{ $admin->username ?? '—' }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="small">{{ $admin->email }}</td>
                    <td class="small">{{ $admin->username ?? '—' }}</td>
                    <td class="small">{{ $admin->phone ?? '—' }}</td>
                    <td>
                        @if($admin->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.users.show', $admin->id) }}" class="btn btn-outline-info" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit.modern', $admin->id) }}" class="btn btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy.modern', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus administrator ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-user-shield fa-2x mb-2 d-block opacity-50"></i>
                        Belum ada administrator. <a href="{{ route('admin.users.create.admin') }}">Tambah admin</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('adminSearch');
    var reset = document.getElementById('adminSearchReset');
    if (!input) return;
    input.addEventListener('input', function () {
        var q = this.value.toLowerCase();
        document.querySelectorAll('.admin-row').forEach(function (row) {
            row.style.display = !q || row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
    if (reset) {
        reset.addEventListener('click', function () {
            input.value = '';
            document.querySelectorAll('.admin-row').forEach(function (row) { row.style.display = ''; });
        });
    }
});
</script>
@endpush
