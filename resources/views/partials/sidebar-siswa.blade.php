<nav id="sidebar" class="sidebar-wrapper bg-dark" role="navigation">
    <div class="sidebar-content">
        <div class="sidebar-brand p-3">
            <a href="{{ route('siswa.dashboard') }}" class="text-white text-decoration-none" title="Dashboard Siswa">
                <i class="fas fa-user-graduate me-2"></i>
                <span class="fw-bold">LMS Siswa</span>
            </a>
        </div>

        <div class="sidebar-header">
            <div class="user-pic">
                <img class="img-responsive img-rounded"
                     src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}"
                     alt="Foto profil {{ Auth::user()->name }}">
            </div>
            <div class="user-info">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <span class="user-role">
                    {{ Auth::user()->class ? 'Siswa - ' . Auth::user()->class : 'Siswa' }}
                </span>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul>
                <li class="header-menu">
                    <span>Menu Utama</span>
                </li>
                <li class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('siswa.dashboard') }}" title="Dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="header-menu">
                    <span>Pembelajaran</span>
                </li>
                <li class="{{ request()->routeIs('siswa.materials.*') ? 'active' : '' }}">
                    <a href="{{ route('siswa.materials.index') }}" title="Materi Pembelajaran">
                        <i class="fas fa-book"></i>
                        <span>Materi Pembelajaran</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('siswa.assignments.*') ? 'active' : '' }}">
                    <a href="{{ route('siswa.assignments.index') }}" title="Tugas Saya">
                        <i class="fas fa-tasks"></i>
                        <span>Tugas</span>
                        @if(isset($upcomingDeadlinesCount) && $upcomingDeadlinesCount > 0)
                            <span class="badge bg-warning ms-2">{{ $upcomingDeadlinesCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="{{ request()->routeIs('siswa.praktikum.*') ? 'active' : '' }}">
                    <a href="{{ route('siswa.praktikum.index') }}" title="Praktikum">
                        <i class="fas fa-flask"></i>
                        <span>Praktikum</span>
                    </a>
                </li>

                <li class="header-menu">
                    <span>Nilai & Kehadiran</span>
                </li>
                <li class="{{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}">
                    <a href="{{ route('siswa.nilai.index') }}" title="Nilai Saya">
                        <i class="fas fa-chart-line"></i>
                        <span>Nilai</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('siswa.absensi.*') ? 'active' : '' }}">
                    <a href="{{ route('siswa.absensi.index') }}" title="Absensi Saya">
                        <i class="fas fa-calendar-check"></i>
                        <span>Absensi</span>
                    </a>
                </li>

                <li class="header-menu">
                    <span>Akun</span>
                </li>
                <li>
                    <a href="{{ route('siswa.profile.edit') }}" title="Edit Profil">
                        <i class="fas fa-user"></i>
                        <span>Profil</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-footer">
        <a href="#" class="sidebar-toggle" data-toggle="tooltip" title="Toggle Sidebar" aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </a>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();
                    this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>';
                    this.classList.add('disabled');"
           data-toggle="tooltip" title="Logout" aria-label="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</nav>
