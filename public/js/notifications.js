// Notification Dropdown Component
class NotificationDropdown {
    constructor() {
        this.init();
    }

    init() {
        this.loadNotifications();
        this.setupEventListeners();
        this.startAutoRefresh();
    }

    setupEventListeners() {
        // Mark notification as read when clicked
        document.addEventListener('click', (e) => {
            if (e.target.closest('.notification-item')) {
                const item = e.target.closest('.notification-item');
                const notificationId = item.dataset.notificationId;
                
                if (!item.classList.contains('read')) {
                    this.markAsRead(notificationId);
                    item.classList.add('read');
                }
            }
        });

        // Mark all as read
        document.addEventListener('click', (e) => {
            if (e.target.closest('#mark-all-read')) {
                this.markAllAsRead();
            }
        });
    }

    async loadNotifications() {
        try {
            const response = await fetch('/notifications/recent');
            const data = await response.json();
            
            this.renderNotifications(data.notifications);
            this.updateUnreadCount();
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }

    renderNotifications(notifications) {
        const container = document.getElementById('notification-dropdown');
        if (!container) return;

        if (notifications.length === 0) {
            container.innerHTML = `
                <div class="dropdown-item text-center text-muted py-3">
                    <i class="fas fa-bell-slash fa-2x mb-2"></i>
                    <div>Tidak ada notifikasi</div>
                </div>
            `;
            return;
        }

        container.innerHTML = notifications.map(notification => `
            <a href="${notification.action_url || '#'}" 
               class="dropdown-item notification-item ${notification.is_read ? 'read' : ''}" 
               data-notification-id="${notification.id}">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <div class="bg-${notification.color} bg-opacity-10 text-${notification.color} rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="${notification.icon} fa-sm"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-medium small">${notification.title}</div>
                        <div class="text-muted small">${notification.message}</div>
                        <div class="text-muted smaller">${notification.time_ago}</div>
                    </div>
                    ${!notification.is_read ? '<span class="badge bg-primary rounded-pill ms-2">Baru</span>' : ''}
                </div>
            </a>
        `).join('');
    }

    async markAsRead(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                this.updateUnreadCount();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            const response = await fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                this.loadNotifications();
                this.updateUnreadCount();
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }

    async updateUnreadCount() {
        try {
            const response = await fetch('/notifications/unread-count');
            const data = await response.json();
            
            const badge = document.getElementById('notification-badge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
        } catch (error) {
            console.error('Error updating unread count:', error);
        }
    }

    startAutoRefresh() {
        // Refresh notifications every 30 seconds
        setInterval(() => {
            this.loadNotifications();
        }, 30000);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new NotificationDropdown();
});
