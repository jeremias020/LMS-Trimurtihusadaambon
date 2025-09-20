<!-- Modern Admin Header -->
<header class="admin-header" id="adminHeader">
    <!-- Left Section -->
    <div class="header-left">
        <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <i class="fas fa-bars"></i>
        </button>

        <div class="breadcrumb-section">
            <nav class="breadcrumb">
                <ol class="breadcrumb-list">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </a>
                    </li>
                    @if(isset($breadcrumbs))
                        @foreach($breadcrumbs as $breadcrumb)
                            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                                @if($loop->last)
                                    {{ $breadcrumb['title'] }}
                                @else
                                    <a href="{{ $breadcrumb['url'] }}" class="breadcrumb-link">
                                        {{ $breadcrumb['title'] }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ol>
            </nav>
            </div>
        </div>

    <!-- Center Section - Search -->
    <div class="header-center">
        <div class="search-container">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Search users, classes, materials..." id="globalSearch">
                <button class="search-clear" id="searchClear" style="display: none;">
                    <i class="fas fa-times"></i>
                    </button>
                </div>
            <div class="search-results" id="searchResults"></div>
        </div>
    </div>

    <!-- Right Section -->
    <div class="header-right">
        <!-- Quick Actions -->
        <div class="quick-actions">
            <button class="action-btn" title="Create New User" data-action="create-user">
                <i class="fas fa-user-plus"></i>
            </button>
            <button class="action-btn" title="Create New Class" data-action="create-class">
                <i class="fas fa-plus-circle"></i>
            </button>
            <button class="action-btn" title="Upload Material" data-action="upload-material">
                <i class="fas fa-upload"></i>
            </button>
        </div>

        <!-- Notifications -->
        <div class="notification-dropdown">
            <button class="notification-btn" id="notificationBtn">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notificationBadge">3</span>
            </button>
            <div class="notification-panel" id="notificationPanel">
                <div class="notification-header">
                    <h4>Notifications</h4>
                    <button class="mark-all-read" id="markAllRead">Mark all read</button>
                </div>
                <div class="notification-list">
                    <div class="notification-item unread">
                        <div class="notification-icon">
                            <i class="fas fa-user-plus text-success"></i>
                    </div>
                        <div class="notification-content">
                            <p class="notification-title">New user registered</p>
                            <p class="notification-text">John Doe has registered as a student</p>
                            <span class="notification-time">2 minutes ago</span>
                                </div>
                            </div>
                    <div class="notification-item unread">
                        <div class="notification-icon">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-title">System Alert</p>
                            <p class="notification-text">High server load detected</p>
                            <span class="notification-time">5 minutes ago</span>
                            </div>
                        </div>
                    <div class="notification-item">
                        <div class="notification-icon">
                            <i class="fas fa-check-circle text-info"></i>
                                </div>
                        <div class="notification-content">
                            <p class="notification-title">Backup Completed</p>
                            <p class="notification-text">Daily backup completed successfully</p>
                            <span class="notification-time">1 hour ago</span>
                            </div>
                        </div>
                                </div>
                <div class="notification-footer">
                    <a href="{{ route('admin.notifications.index') }}" class="view-all-link">View all notifications</a>
                            </div>
                        </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="user-dropdown">
            <button class="user-profile-btn" id="userProfileBtn">
                <div class="user-avatar">
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Admin Avatar" class="avatar-img">
                </div>
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </div>
            </button>
            <div class="user-menu" id="userMenu">
                <div class="user-menu-header">
                    <div class="user-avatar-large">
                        <img src="{{ asset('images/default-avatar.png') }}" alt="Admin Avatar" class="avatar-img-large">
                    </div>
                    <div class="user-details">
                        <h4 class="user-name-large">{{ Auth::user()->name ?? 'Administrator' }}</h4>
                        <p class="user-email">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                        <span class="user-role-badge">Super Administrator</span>
                    </div>
                </div>
                <div class="user-menu-items">
                    <a href="{{ route('admin.profile.index') }}" class="menu-item">
                        <i class="fas fa-user"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="menu-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="{{ route('admin.activity.index') }}" class="menu-item">
                        <i class="fas fa-history"></i>
                        <span>Activity Log</span>
                    </a>
                    <div class="menu-divider"></div>
                    <a href="{{ route('logout') }}" class="menu-item logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
/* Modern Admin Header Styles */
    .admin-header {
        position: fixed;
        top: 0;
    left: 280px;
        right: 0;
        height: 70px;
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    z-index: 999;
        display: flex;
        align-items: center;
        justify-content: space-between;
    padding: 0 1.5rem;
        transition: left 0.3s ease;
    }
    
    .admin-header.sidebar-collapsed {
        left: 70px;
    }
    
/* Header Left */
.header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    color: #64748b;
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.mobile-menu-toggle:hover {
    background: #f1f5f9;
    color: #334155;
}

.breadcrumb-section {
    flex: 1;
}

.breadcrumb-list {
    display: flex;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 0.5rem;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    font-size: 0.875rem;
    color: #64748b;
}

.breadcrumb-item:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: #cbd5e1;
}

.breadcrumb-item.active {
    color: #334155;
    font-weight: 600;
}

.breadcrumb-link {
    color: #64748b;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: color 0.3s ease;
}

.breadcrumb-link:hover {
    color: #3b82f6;
}

/* Header Center */
.header-center {
    flex: 1;
    max-width: 500px;
    margin: 0 2rem;
}

.search-container {
    position: relative;
    width: 100%;
}

.search-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #f8fafc;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-icon {
    position: absolute;
    left: 0.875rem;
    color: #94a3b8;
    font-size: 0.875rem;
}

.search-clear {
    position: absolute;
    right: 0.875rem;
    background: none;
        border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.search-clear:hover {
    color: #64748b;
    background: #f1f5f9;
}

.search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
    max-height: 400px;
    overflow-y: auto;
}

/* Header Right */
.header-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.quick-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.action-btn:hover {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
        transform: translateY(-1px);
    }
    
/* Notifications */
.notification-dropdown {
    position: relative;
}

.notification-btn {
    position: relative;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.notification-btn:hover {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.notification-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ef4444;
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.125rem 0.375rem;
    border-radius: 10px;
    min-width: 18px;
    text-align: center;
}

.notification-panel {
    position: absolute;
    top: 100%;
    right: 0;
    width: 350px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
    max-height: 400px;
        overflow: hidden;
    }
    
.notification-header {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.notification-header h4 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #334155;
}

.mark-all-read {
    background: none;
    border: none;
    color: #3b82f6;
    font-size: 0.875rem;
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.mark-all-read:hover {
    background: #f0f9ff;
}

.notification-list {
    max-height: 300px;
    overflow-y: auto;
}

.notification-item {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.notification-item:hover {
    background: #f8fafc;
}

.notification-item.unread {
    background: #f0f9ff;
}

.notification-icon {
    flex-shrink: 0;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #334155;
    margin: 0 0 0.25rem 0;
}

.notification-text {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0 0 0.25rem 0;
    line-height: 1.4;
}

.notification-time {
    font-size: 0.75rem;
    color: #94a3b8;
}

.notification-footer {
    padding: 1rem;
    border-top: 1px solid #e2e8f0;
    text-align: center;
}

.view-all-link {
    color: #3b82f6;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
}

.view-all-link:hover {
    text-decoration: underline;
}

/* User Profile Dropdown */
.user-dropdown {
    position: relative;
}

.user-profile-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: none;
        border: none;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.user-profile-btn:hover {
    background: #f8fafc;
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    overflow: hidden;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.user-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: #334155;
}

.dropdown-icon {
    font-size: 0.75rem;
    color: #94a3b8;
    transition: transform 0.3s ease;
}

.user-menu {
    position: absolute;
    top: 100%;
    right: 0;
    width: 280px;
    background: white;
    border: 1px solid #e2e8f0;
        border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
    overflow: hidden;
}

.user-menu-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    gap: 1rem;
    align-items: center;
}

.user-avatar-large {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    overflow: hidden;
}

.avatar-img-large {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-details {
    flex: 1;
}

.user-name-large {
    font-size: 1rem;
    font-weight: 600;
    color: #334155;
    margin: 0 0 0.25rem 0;
}

.user-email {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0 0 0.5rem 0;
}

.user-role-badge {
    display: inline-block;
    background: #dbeafe;
    color: #1d4ed8;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
}

.user-menu-items {
        padding: 0.5rem 0;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    color: #64748b;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.menu-item:hover {
    background: #f8fafc;
    color: #334155;
}

.menu-item.logout {
    color: #ef4444;
}

.menu-item.logout:hover {
    background: #fef2f2;
    color: #dc2626;
}

.menu-divider {
    height: 1px;
    background: #e2e8f0;
    margin: 0.5rem 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .admin-header {
        left: 0;
        padding: 0 1rem;
    }
    
    .mobile-menu-toggle {
        display: block;
    }
    
    .header-center {
        display: none;
    }
    
    .quick-actions {
        display: none;
    }
    
    .user-info .user-name {
        display: none;
    }
    
    .notification-panel {
        width: 300px;
    }
    
    .user-menu {
        width: 250px;
    }
}

@media (max-width: 480px) {
    .notification-panel {
        width: 280px;
        right: -50px;
    }
    
    .user-menu {
        width: 220px;
        right: -30px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            sidebar.classList.add('show');
            overlay.classList.add('active');
        });
    }
    
    // Notification dropdown
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationPanel = document.getElementById('notificationPanel');
    
    if (notificationBtn && notificationPanel) {
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationPanel.style.display = notificationPanel.style.display === 'block' ? 'none' : 'block';
        });
    }
    
    // User profile dropdown
    const userProfileBtn = document.getElementById('userProfileBtn');
    const userMenu = document.getElementById('userMenu');
    
    if (userProfileBtn && userMenu) {
        userProfileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.style.display = userMenu.style.display === 'block' ? 'none' : 'block';
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        if (notificationPanel) notificationPanel.style.display = 'none';
        if (userMenu) userMenu.style.display = 'none';
    });
    
    // Global search functionality
    const searchInput = document.getElementById('globalSearch');
    const searchClear = document.getElementById('searchClear');
    const searchResults = document.getElementById('searchResults');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length > 2) {
                // Show search results (implement actual search logic here)
                searchResults.innerHTML = `
                    <div class="search-result-item">
                        <i class="fas fa-user"></i>
                        <span>Searching for: "${query}"</span>
                    </div>
                `;
                searchResults.style.display = 'block';
                searchClear.style.display = 'block';
            } else {
                searchResults.style.display = 'none';
                searchClear.style.display = 'none';
            }
        });
        
        searchClear.addEventListener('click', function() {
            searchInput.value = '';
            searchResults.style.display = 'none';
            this.style.display = 'none';
        });
    }
    
    // Quick actions
    const actionBtns = document.querySelectorAll('.action-btn');
    actionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            // Implement quick action logic here
            console.log('Quick action:', action);
        });
    });
    
    // Mark all notifications as read
    const markAllRead = document.getElementById('markAllRead');
    if (markAllRead) {
        markAllRead.addEventListener('click', function() {
            const unreadItems = document.querySelectorAll('.notification-item.unread');
            unreadItems.forEach(item => {
                item.classList.remove('unread');
            });
            
            const badge = document.getElementById('notificationBadge');
            if (badge) {
                badge.textContent = '0';
                badge.style.display = 'none';
            }
        });
    }
    
    // Update header position when sidebar changes
    const header = document.getElementById('adminHeader');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    if (sidebarToggle && header) {
        sidebarToggle.addEventListener('click', function() {
            setTimeout(() => {
                if (sidebar.classList.contains('collapsed')) {
                    header.classList.add('sidebar-collapsed');
                } else {
                    header.classList.remove('sidebar-collapsed');
                }
            }, 300);
        });
    }
});
</script>
