<!-- Modern Admin Sidebar -->
<div class="admin-sidebar" id="adminSidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="brand-section">
            <div class="brand-logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="brand-info">
                <h3 class="brand-title">LMS Trimurti</h3>
                <p class="brand-subtitle">Admin Panel</p>
            </div>
        </div>
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- User Profile Section -->
    <div class="user-profile hover-lift">
        <div class="user-avatar">
            <img src="{{ asset('images/default-avatar.png') }}" alt="Admin Avatar" class="avatar-img">
            <div class="status-indicator online"></div>
        </div>
        <div class="user-info">
            <h4 class="user-name">{{ Auth::user()->name ?? 'Administrator' }}</h4>
            <p class="user-role">Super Administrator</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <!-- Dashboard Section -->
        <div class="nav-section">
            <div class="section-header">
                <span class="section-title">Main Menu</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Management Section -->
        <div class="nav-section">
            <div class="section-header">
                <span class="section-title">Management</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <span class="nav-text">Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.kelas.index') }}" class="nav-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-school"></i>
                        <span class="nav-text">Classes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subjects.index') }}" class="nav-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <span class="nav-text">Subjects</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Content Section -->
        <div class="nav-section">
            <div class="section-header">
                <span class="section-title">Content</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('admin.materials.index') }}" class="nav-link {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <span class="nav-text">Materials</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.assignments.index') }}" class="nav-link {{ request()->routeIs('admin.assignments.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <span class="nav-text">Assignments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.practicals.index') }}" class="nav-link {{ request()->routeIs('admin.practicals.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-flask"></i>
                        <span class="nav-text">Practicals</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Reports Section -->
        <div class="nav-section">
            <div class="section-header">
                <span class="section-title">Reports</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <span class="nav-text">Analytics</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.logs.index') }}" class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <span class="nav-text">Activity Logs</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Settings Section -->
        <div class="nav-section">
            <div class="section-header">
                <span class="section-title">Settings</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <span class="nav-text">System Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.profile.index') }}" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <span class="nav-text">Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="footer-info">
            <p class="version-text">Version 1.0.0</p>
            <p class="copyright-text">&copy; 2024 LMS Trimurti</p>
        </div>
    </div>
</div>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<style>
/* Modern Admin Sidebar Styles */
.admin-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 280px;
    height: 100vh;
    background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
    color: #f1f5f9;
    z-index: 1000;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.admin-sidebar.collapsed {
    width: 70px;
}

.admin-sidebar.collapsed .brand-info,
.admin-sidebar.collapsed .user-info,
.admin-sidebar.collapsed .nav-text,
.admin-sidebar.collapsed .section-title,
.admin-sidebar.collapsed .footer-info {
    opacity: 0;
    transform: translateX(-20px);
}

/* Sidebar Header */
.sidebar-header {
    padding: 1.5rem 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 80px;
}

.brand-section {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.brand-logo {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.brand-info {
    transition: all 0.3s ease;
}

.brand-title {
    font-size: 1.125rem;
    font-weight: 700;
    margin: 0;
    color: #f1f5f9;
    line-height: 1.2;
}

.brand-subtitle {
    font-size: 0.75rem;
    color: #94a3b8;
    margin: 0;
    font-weight: 500;
}

.sidebar-toggle {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #f1f5f9;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sidebar-toggle:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

/* User Profile */
.user-profile {
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.user-avatar {
    position: relative;
    flex-shrink: 0;
}

.avatar-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.status-indicator {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #1e293b;
}

.status-indicator.online {
    background: #10b981;
}

.user-info {
    flex: 1;
    transition: all 0.3s ease;
}

.user-name {
    font-size: 0.875rem;
    font-weight: 600;
    margin: 0;
    color: #f1f5f9;
    line-height: 1.2;
}

.user-role {
    font-size: 0.75rem;
    color: #94a3b8;
    margin: 0;
    font-weight: 500;
}

/* Navigation */
.sidebar-nav {
    flex: 1;
    padding: 1rem 0;
    overflow-y: auto;
}

.nav-section {
    margin-bottom: 1.5rem;
}

.section-header {
    padding: 0 1rem 0.5rem;
}

.section-title {
    font-size: 0.75rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: all 0.3s ease;
}

.nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin: 0.125rem 0;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: #cbd5e1;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    border-radius: 0 25px 25px 0;
    margin-right: 1rem;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #f1f5f9;
    transform: translateX(4px);
}

.nav-link.active {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.nav-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: white;
    border-radius: 0 2px 2px 0;
}

.nav-icon {
    width: 20px;
    text-align: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.nav-text {
    font-weight: 500;
    transition: all 0.3s ease;
}

/* Sidebar Footer */
.sidebar-footer {
    padding: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin-top: auto;
}

.footer-info {
    text-align: center;
    transition: all 0.3s ease;
}

.version-text {
    font-size: 0.75rem;
    color: #94a3b8;
    margin: 0 0 0.25rem 0;
    font-weight: 500;
}

.copyright-text {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0;
    font-weight: 400;
}

/* Sidebar Overlay */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.sidebar-overlay.active {
    opacity: 1;
    visibility: visible;
}

/* Responsive Design */
@media (max-width: 768px) {
    .admin-sidebar {
        transform: translateX(-100%);
        width: 280px;
    }
    
    .admin-sidebar.show {
        transform: translateX(0);
    }
    
    .admin-sidebar.collapsed {
        transform: translateX(-100%);
    }
}

/* Custom Scrollbar */
.sidebar-nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('adminSidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');
    
    // Toggle sidebar
    toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            
        // Update main content margin
        const mainContent = document.querySelector('.main-content, #main-content');
            if (mainContent) {
            if (sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = '70px';
            } else {
                mainContent.style.marginLeft = '280px';
            }
        }
        
        // Save state to localStorage
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    });
    
    // Mobile overlay
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        overlay.classList.remove('active');
        });
        
        // Restore sidebar state
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
            sidebar.classList.add('collapsed');
        const mainContent = document.querySelector('.main-content, #main-content');
            if (mainContent) {
                mainContent.style.marginLeft = '70px';
        }
    }
    
    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-toggle');
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.add('show');
            overlay.classList.add('active');
        });
    }
});
</script>
