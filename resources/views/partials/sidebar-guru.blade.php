<nav class="sidebar" id="sidebar">
    <!-- Sidebar Brand -->
    <div class="p-3 border-bottom border-secondary">
        <a href="{{ route('guru.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none">
            <div class="bg-light rounded p-2 me-2">
                <i class="fas fa-graduation-cap text-primary"></i>
            </div>
            <div class="sidebar-brand-text">
                <div class="fw-bold fs-6">LMS Trimurti</div>
                <small class="text-light opacity-75">Guru Portal</small>
            </div>
        </a>
    </div>

    <!-- User Profile Section -->
    <div class="p-3 border-bottom border-secondary">
        <div class="d-flex align-items-center">
            <img src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" 
                 alt="Profile" 
                 class="rounded-circle me-2" 
                 style="width: 40px; height: 40px; object-fit: cover;">
            <div class="sidebar-user-info flex-grow-1">
                <div class="fw-medium text-white small">{{ Str::limit(Auth::user()->name, 15) }}</div>
                <small class="text-light opacity-75">
                    {{ Auth::user()->subject ? 'Guru ' . Str::limit(Auth::user()->subject, 10) : 'Guru' }}
                </small>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-menu flex-grow-1">
        <div class="p-2">
            <!-- Dashboard -->
            <div class="mb-3">
                <a href="{{ route('guru.dashboard') }}" 
                   class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.dashboard') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-tachometer-alt me-2 nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </div>

            <!-- Pembelajaran Section -->
            <div class="mb-3">
                <div class="nav-section-title px-2 py-1 mb-2">
                    <small class="text-light opacity-75 fw-medium">PEMBELAJARAN</small>
                </div>
                <a href="{{ route('guru.materials.index') }}" 
                   class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.materials.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-book me-2 nav-icon"></i>
                    <span class="nav-text">Materi</span>
                    <span class="badge bg-light text-dark ms-auto sidebar-badge">{{ $stats['total_materials'] ?? 0 }}</span>
                </a>
                <a href="{{ route('guru.assignments.index') }}" 
                   class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.assignments.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-tasks me-2 nav-icon"></i>
                    <span class="nav-text">Tugas & Quiz</span>
                    <span class="badge bg-light text-dark ms-auto sidebar-badge">{{ $stats['total_assignments'] ?? 0 }}</span>
                </a>
                <a href="{{ route('guru.praktikum.index') }}" 
                   class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.praktikum.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-flask me-2 nav-icon"></i>
                    <span class="nav-text">Praktikum</span>
                    <span class="badge bg-light text-dark ms-auto sidebar-badge">{{ $stats['total_practicals'] ?? 0 }}</span>
                </a>
            </div>

            <!-- Penilaian Section -->
            <div class="mb-3">
                <div class="nav-section-title px-2 py-1 mb-2">
                    <small class="text-light opacity-75 fw-medium">PENILAIAN</small>
                </div>
                <a href="{{ route('guru.submissions') }}" 
                   class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.submissions*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-file-upload me-2 nav-icon"></i>
                    <span class="nav-text">Pengumpulan</span>
                    @if(isset($stats['pending_submissions']) && $stats['pending_submissions'] > 0)
                        <span class="badge bg-danger ms-auto sidebar-badge">{{ $stats['pending_submissions'] }}</span>
                    @endif
                </a>
                <a href="{{ route('guru.penilaian.index') }}" 
                   class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.penilaian.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-star me-2 nav-icon"></i>
                    <span class="nav-text">Beri Nilai</span>
                    @if(isset($stats['pending_grading']) && $stats['pending_grading'] > 0)
                        <span class="badge bg-warning text-dark ms-auto sidebar-badge">{{ $stats['pending_grading'] }}</span>
                    @endif
                </a>
                <a href="{{ route('guru.scoring.index') }}" 
                   class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.scoring.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-award me-2 nav-icon"></i>
                    <span class="nav-text">Skor Praktikum</span>
                </a>
            </div>

            <!-- Monitoring Section -->
            <div class="mb-3">
                <div class="nav-section-title px-2 py-1 mb-2">
                    <small class="text-light opacity-75 fw-medium">MONITORING</small>
                </div>
                <a href="{{ route('guru.absensi.index') }}" 
                   class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.absensi.*') || request()->routeIs('guru.attendance.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-calendar-check me-2 nav-icon"></i>
                    <span class="nav-text">Absensi</span>
                </a>
                <a href="{{ route('guru.reports.index') }}" 
                   class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.reports.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-chart-line me-2 nav-icon"></i>
                    <span class="nav-text">Laporan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer p-3 border-top border-secondary mt-auto">
        <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-link text-light p-0 sidebar-toggle" title="Toggle Sidebar">
                <i class="fas fa-angle-left"></i>
            </button>
            <div class="sidebar-collapse-text">
                <small class="text-light opacity-75">Collapse</small>
            </div>
        </div>
    </div>

    <style>
    .sidebar {
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.3) transparent;
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.3);
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255,255,255,0.5);
    }

    .nav-link {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-link.hover-bg:hover {
        background: rgba(255,255,255,0.1) !important;
        transform: translateX(4px);
    }

    .nav-link.active {
        box-shadow: 0 2px 8px rgba(59,130,246,0.3);
    }

    .nav-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #fff;
    }

    .nav-icon {
        width: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .sidebar.collapsed .nav-text,
    .sidebar.collapsed .sidebar-brand-text,
    .sidebar.collapsed .sidebar-user-info,
    .sidebar.collapsed .nav-section-title,
    .sidebar.collapsed .sidebar-badge,
    .sidebar.collapsed .sidebar-collapse-text {
        display: none;
    }

    .sidebar.collapsed .nav-link {
        justify-content: center;
        padding: 0.75rem 0.5rem !important;
    }

    .sidebar.collapsed .nav-icon {
        margin-right: 0 !important;
        font-size: 1.1rem;
    }

    .sidebar.collapsed .sidebar-footer {
        text-align: center;
    }

    @media (max-width: 768px) {
        .sidebar {
            box-shadow: 2px 0 20px rgba(0,0,0,0.3);
        }
    }
    </style>
</nav>
