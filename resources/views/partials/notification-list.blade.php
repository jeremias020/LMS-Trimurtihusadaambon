@if($notifications->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th width="40">Status</th>
                    <th>Notifikasi</th>
                    <th>Waktu</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifications as $notification)
                <tr id="notification-{{ $notification->id }}" class="{{ $notification->is_read ? '' : 'unread' }}">
                    <td>
                        @if($notification->is_read)
                            <i class="fas fa-envelope-open text-muted"></i>
                        @else
                            <i class="fas fa-envelope text-primary"></i>
                            <span class="badge bg-danger rounded-pill unread-badge">Baru</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="bg-{{ $notification->color }} bg-opacity-10 text-{{ $notification->color }} rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="{{ $notification->icon }}"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-medium">{{ $notification->title }}</div>
                                <div class="text-muted small">{{ $notification->message }}</div>
                                @if($notification->action_url)
                                    <a href="{{ $notification->action_url }}" class="btn btn-sm btn-outline-primary mt-1">
                                        <i class="fas fa-external-link-alt me-1"></i>Lihat Detail
                                    </a>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="small text-muted">{{ $notification->time_ago }}</div>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            @if(!$notification->is_read)
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="markAsRead({{ $notification->id }})" title="Tandai dibaca">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteNotification({{ $notification->id }})" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
        <h6 class="text-muted">Tidak ada notifikasi</h6>
        <p class="text-muted">Belum ada notifikasi untuk ditampilkan</p>
    </div>
@endif
