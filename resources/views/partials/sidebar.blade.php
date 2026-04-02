<nav class="sidebar" id="sidebar">
    <!-- Sidebar Brand -->
    <div class="p-3 border-bottom border-secondary">
        @php
            $role = Auth::check() ? Auth::user()->role : null;
        @endphp
        @if($role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none">
                <div class="bg-light rounded p-2 me-2">
                    <i class="fas fa-graduation-cap text-primary"></i>
                </div>
                <div class="sidebar-brand-text">
                    <div class="fw-bold fs-6">LMS Trimurti</div>
                    <small class="text-light opacity-75">Admin Panel</small>
                </div>
            </a>
        @elseif($role === 'guru')
            <a href="{{ route('guru.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none">
                <div class="bg-light rounded p-2 me-2">
                    <i class="fas fa-graduation-cap text-primary"></i>
                </div>
                <div class="sidebar-brand-text">
                    <div class="fw-bold fs-6">LMS Trimurti</div>
                    <small class="text-light opacity-75">Guru Portal</small>
                </div>
            </a>
        @elseif($role === 'student')
            <a href="{{ route('siswa.dashboard') }}" class="text-white text-decoration-none">
                <i class="fas fa-user-graduate me-2"></i>
                <span class="fw-bold">LMS Siswa</span>
            </a>
        @else
            <span class="fw-bold">LMS Trimurti</span>
        @endif
    </div>

    <!-- User Profile Section (Guru/Admin/Siswa) -->
    @if($role === 'admin' || $role === 'guru' || $role === 'student')
    <div class="p-3 border-bottom border-secondary">
        <div class="d-flex align-items-center">
            <img src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}"
                 alt="Profile"
                 class="rounded-circle me-2 d-block"
                 style="width: 40px; height: 40px; object-fit: cover;">
            <div class="sidebar-user-info flex-grow-1">
                <div class="fw-medium text-white small">{{ Str::limit(Auth::user()->name, 15) }}</div>
                <small class="text-light opacity-75">
                    @if($role === 'admin') Super Administrator
                    @elseif($role === 'guru') {{ Auth::user()->subject ? 'Guru ' . Str::limit(Auth::user()->subject, 10) : 'Guru' }}
                    @elseif($role === 'student') {{ Auth::user()->class ? 'Siswa - ' . Auth::user()->class : 'Siswa' }}
                    @endif
                </small>
            </div>
        </div>
    </div>
    @endif

    <!-- Navigation Menu -->
    <div class="sidebar-menu flex-grow-1">
        <div class="p-2">
            @if($role === 'admin')
                <!-- Admin Menu -->
                <a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('admin.dashboard') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-tachometer-alt me-2 nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('admin.users.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-users me-2 nav-icon"></i>
                    <span class="nav-text">Users</span>
                </a>
                <a href="{{ route('admin.kriteria-penilaian.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('admin.kriteria-penilaian.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-clipboard-list me-2 nav-icon"></i>
                    <span class="nav-text">Kriteria Penilaian</span>
                </a>
                <a href="{{ route('admin.exam-schedules.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('admin.exam-schedules.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-calendar-check me-2 nav-icon"></i>
                    <span class="nav-text">Jadwal</span>
                </a>
                <a href="{{ route('admin.profile.edit') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('admin.profile.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-user-cog me-2 nav-icon"></i>
                    <span class="nav-text">Profile</span>
                </a>
            @elseif($role === 'guru')
                <!-- Guru Menu -->
                <a href="{{ route('guru.dashboard') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.dashboard') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-tachometer-alt me-2 nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="{{ route('guru.materials.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.materials.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-book me-2 nav-icon"></i>
                    <span class="nav-text">Materi</span>
                </a>
                <a href="{{ route('guru.assignments.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.assignments.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-tasks me-2 nav-icon"></i>
                    <span class="nav-text">Tugas & Quiz</span>
                </a>
                <a href="{{ route('guru.penilaian.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.penilaian.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-star me-2 nav-icon"></i>
                    <span class="nav-text">Penilaian</span>
                </a>
                <a href="{{ route('guru.laporan.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.laporan.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-file-alt me-2 nav-icon"></i>
                    <span class="nav-text">Laporan</span>
                </a>
                <a href="{{ route('guru.profile.edit') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.profile.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-user-cog me-2 nav-icon"></i>
                    <span class="nav-text">Profile</span>
                </a>
            @elseif($role === 'student')
                <!-- Siswa Menu -->
                <a href="{{ route('siswa.dashboard') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('siswa.dashboard') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-tachometer-alt me-2 nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="{{ route('siswa.materials.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('siswa.materials.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-book me-2 nav-icon"></i>
                    <span class="nav-text">Materi Pembelajaran</span>
                </a>
                <a href="{{ route('siswa.assignments.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('siswa.assignments.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-tasks me-2 nav-icon"></i>
                    <span class="nav-text">Tugas</span>
                </a>
                <a href="{{ route('siswa.reports.practical') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('siswa.reports.practical') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-flask me-2 nav-icon"></i>
                    <span class="nav-text">Praktikum</span>
                </a>
                <a href="{{ route('siswa.reports.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('siswa.reports.index') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-chart-line me-2 nav-icon"></i>
                    <span class="nav-text">Nilai</span>
                </a>
                <a href="{{ route('siswa.reports.attendance') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('siswa.reports.attendance') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-calendar-check me-2 nav-icon"></i>
                    <span class="nav-text">Absensi</span>
                </a>
                <a href="{{ route('siswa.profile.edit') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('siswa.profile.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-user-cog me-2 nav-icon"></i>
                    <span class="nav-text">Profil</span>
                </a>
            @else
                <span class="text-muted">Silakan login untuk melihat menu.</span>
            @endif
        </div>
    </div>
</nav>
