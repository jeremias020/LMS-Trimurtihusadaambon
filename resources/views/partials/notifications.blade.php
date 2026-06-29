<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle position-relative" href="#"
       id="notificationsDropdown" role="button"
       data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        @if(isset($unreadCount) && $unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                  style="font-size:0.6rem;">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-0"
        style="min-width:320px; max-height:420px; overflow-y:auto;"
        aria-labelledby="notificationsDropdown">

        <li class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
            <span class="fw-semibold">Notifikasi</span>
            @if(isset($unreadCount) && $unreadCount > 0)
                <span class="badge bg-primary">{{ $unreadCount }} baru</span>
            @endif
        </li>

        @if(isset($notifications) && $notifications->count() > 0)
            @foreach($notifications as $notification)
                <li>
                    <a class="dropdown-item d-flex align-items-start gap-3 py-3
                               {{ $notification->is_unread ? 'bg-light' : '' }}"
                       href="{{ $notification->display_action_url ?? '#' }}"
                       title="{{ $notification->display_title }}">
                        <div class="flex-shrink-0">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-{{ $notification->color }} bg-opacity-10"
                                  style="width:36px;height:36px;">
                                <i class="fas fa-{{ $notification->icon_name }} text-{{ $notification->color }}"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="small fw-semibold text-dark text-truncate">
                                {{ $notification->display_title }}
                            </div>
                            <div class="small text-muted text-truncate">
                                {{ $notification->display_message }}
                            </div>
                            <div class="small text-muted mt-1">
                                {{ $notification->time_ago }}
                            </div>
                        </div>
                        @if($notification->is_unread)
                            <span class="flex-shrink-0 rounded-circle bg-primary"
                                  style="width:8px;height:8px;margin-top:4px;"></span>
                        @endif
                    </a>
                </li>
                @if(!$loop->last)
                    <li><hr class="dropdown-divider my-0"></li>
                @endif
            @endforeach

            <li class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
                <a href="#" class="small text-muted text-decoration-none">Lihat semua</a>
                @if(isset($unreadCount) && $unreadCount > 0)
                    <a href="#" class="small text-primary text-decoration-none mark-all-read">
                        Tandai semua dibaca
                    </a>
                @endif
            </li>
        @else
            <li class="text-center py-4 text-muted">
                <i class="fas fa-bell-slash fa-lg mb-2 d-block opacity-50"></i>
                <small>Tidak ada notifikasi</small>
            </li>
        @endif
    </ul>
</li>
