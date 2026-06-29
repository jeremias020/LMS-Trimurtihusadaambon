@extends('layouts.admin')

@section('title', 'Manajemen Notifikasi')
@section('page-title', 'Manajemen Notifikasi')
@section('page-subtitle', 'Kirim dan kelola notifikasi untuk guru dan siswa')

@section('page-actions')
    <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-paper-plane me-1"></i>Kirim Notifikasi
    </a>
@endsection

@section('content')

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold"><i class="fas fa-bell me-2 text-primary"></i>Riwayat Notifikasi</h6>
        <span class="badge bg-secondary">{{ $notifications->total() }} total</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Judul</th>
                        <th>Pesan</th>
                        <th class="text-center">Tipe</th>
                        <th>Penerima</th>
                        <th>Dikirim oleh</th>
                        <th>Waktu</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notif)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $notif->display_title }}</td>
                            <td class="text-muted">{{ Str::limit($notif->display_message, 60) }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $notif->color }}">{{ $notif->display_type }}</span>
                            </td>
                            <td class="text-muted">
                                @if($notif->tipe_penerima === 'semua')
                                    <span class="badge bg-info text-white">Semua</span>
                                @elseif($notif->penerima_id)
                                    {{ $notif->receiver?->name ?? 'User #' . $notif->penerima_id }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $notif->createdBy?->name ?? 'Sistem' }}</td>
                            <td class="text-muted">{{ $notif->created_at->format('d M Y H:i') }}</td>
                            <td class="text-center pe-4">
                                <form action="{{ route('admin.notifications.destroy', $notif->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus notifikasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-bell-slash fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                <h6 class="text-muted">Belum ada notifikasi</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($notifications->hasPages())
        <div class="card-footer bg-white border-top">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

@endsection
