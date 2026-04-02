@extends('layouts.siswa')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Pusat notifikasi sistem')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">Notifikasi</h5>
                    <p class="text-muted mb-0">Pusat notifikasi dan pengumuman sistem</p>
                </div>
                <div class="d-flex gap-2">
                    @if($notifications->where('is_read', false)->count() > 0)
                        <button type="button" class="btn btn-outline-primary" onclick="markAllAsRead()">
                            <i class="fas fa-check-double me-2"></i>Tandai Semua Dibaca
                        </button>
                    @endif
                    <button type="button" class="btn btn-outline-secondary" onclick="refreshNotifications()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#all-notifications" role="tab">
                        <i class="fas fa-bell me-2"></i>Semua
                        <span class="badge bg-primary ms-1">{{ $notifications->total() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#unread-notifications" role="tab">
                        <i class="fas fa-envelope me-2"></i>Belum Dibaca
                        <span class="badge bg-danger ms-1">{{ $notifications->where('is_read', false)->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#exam-notifications" role="tab">
                        <i class="fas fa-calendar-check me-2"></i>Ujian
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#assignment-notifications" role="tab">
                        <i class="fas fa-tasks me-2"></i>Tugas
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Notifications Content -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="tab-content">
                <!-- All Notifications -->
                <div class="tab-pane fade show active" id="all-notifications" role="tabpanel">
                    @include('partials.notification-list', ['notifications' => $notifications, 'showAll' => true])
                </div>

                <!-- Unread Notifications -->
                <div class="tab-pane fade" id="unread-notifications" role="tabpanel">
                    @include('partials.notification-list', ['notifications' => $notifications->where('is_read', false), 'showAll' => false])
                </div>

                <!-- Exam Notifications -->
                <div class="tab-pane fade" id="exam-notifications" role="tabpanel">
                    @include('partials.notification-list', ['notifications' => $notifications->where('type', 'exam'), 'showAll' => false])
                </div>

                <!-- Assignment Notifications -->
                <div class="tab-pane fade" id="assignment-notifications" role="tabpanel">
                    @include('partials.notification-list', ['notifications' => $notifications->where('type', 'assignment'), 'showAll' => false])
                </div>
            </div>
        </div>
        
        @if($notifications->hasPages())
        <div class="card-footer">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const element = document.getElementById(`notification-${notificationId}`);
            element.classList.remove('unread');
            element.querySelector('.unread-badge')?.remove();
            updateUnreadCount();
        }
    });
}

function deleteNotification(notificationId) {
    if (confirm('Hapus notifikasi ini?')) {
        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const element = document.getElementById(`notification-${notificationId}`);
                element.remove();
                updateUnreadCount();
            }
        });
    }
}

function refreshNotifications() {
    location.reload();
}

function updateUnreadCount() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            const countElements = document.querySelectorAll('.unread-count');
            countElements.forEach(element => {
                element.textContent = data.count;
                if (data.count === 0) {
                    element.style.display = 'none';
                }
            });
        });
}
</script>
@endsection
