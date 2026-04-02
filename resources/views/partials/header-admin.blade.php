<header class="top-header">
    <div class="d-flex align-items-center gap-2">
        <!-- Desktop Toggle -->
        <button id="sidebarToggle" class="btn btn-ghost d-none d-md-inline-block" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Mobile Toggle -->
        <button id="mobileSidebarToggle" class="btn btn-ghost d-md-none" title="Toggle Mobile Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        
        <h6 class="mb-0">Admin Panel</h6>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Notifications -->
        <div class="dropdown" id="notificationDropdown">
            <button class="btn btn-ghost position-relative" type="button" id="notificationBtn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="font-size: 0.6rem; display: none;">0</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationBtn" style="min-width: 320px;">
                <li class="dropdown-header d-flex justify-content-between align-items-center py-3">
                    <div>
                        <span class="fw-bold text-dark">Notifikasi</span>
                        <div><small class="text-muted" id="notificationCount">0 notifikasi baru</small></div>
                    </div>
                </li>
                <li><hr class="dropdown-divider m-0"></li>
                <li>
                    <div class="px-3 py-4 text-center text-muted">
                        <i class="fas fa-bell-slash fa-lg mb-2"></i>
                        <div>Tidak ada notifikasi</div>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-center py-2" href="#"><small class="fw-medium">Lihat Semua Notifikasi</small></a></li>
            </ul>
        </div>

        <!-- User Menu -->
        <div class="dropdown">
            <button class="btn btn-ghost d-flex align-items-center gap-2" type="button" id="userMenuBtn" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" 
                     alt="User Avatar" 
                     class="rounded-circle" 
                     style="width: 32px; height: 32px; object-fit: cover;"
                     onerror="this.src='{{ asset('images/default-avatar.png') }}';">
                <span class="d-none d-md-block">{{ Str::limit(Auth::user()->name, 15) }}</span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuBtn">
                <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                    <i class="fas fa-user me-2"></i> Profile
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<style>
.top-header {
    background: white;
    height: var(--header-height, 70px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    position: sticky;
    top: 0;
    z-index: 999;
    border-bottom: 1px solid var(--border-color, #e2e8f0);
}

.btn-ghost {
    background: transparent;
    border: none;
    color: inherit;
    transition: all 0.3s ease;
}

.btn-ghost:hover {
    background: rgba(13,110,253,0.1);
    color: var(--primary-color, #3b82f6);
    transform: translateY(-1px);
}

.dropdown-menu {
    border: 1px solid rgba(0,0,0,0.1);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.dropdown-item {
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: #f8f9fa;
    transform: translateX(2px);
}

@media (max-width: 768px) {
    .top-header {
        padding: 0 1rem;
    }
    
    .btn-ghost span {
        display: none !important;
    }
}
</style>
