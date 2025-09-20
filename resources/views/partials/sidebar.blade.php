<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar" role="navigation">
    <div class="position-sticky pt-3">
        <div class="sidebar-header text-center py-4">
            <h6 class="text-muted">Menu Navigasi</h6>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}"
                   title="Dashboard"
                   aria-label="Dashboard">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>

            @if(Auth::user()->role === 'admin')
            <!-- Admin Menu -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                   href="{{ route('admin.users.index') }}"
                   title="Kelola Pengguna"
                   aria-label="Kelola Pengguna">
                    <i class="fas fa-users me-2"></i> Manage Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                   href="{{ route('admin.settings.index') }}"
                   title="Pengaturan Sistem"
                   aria-label="Pengaturan Sistem">
                    <i class="fas fa-cog me-2"></i> Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                   href="{{ route('admin.reports.index') }}"
                   title="Laporan Sistem"
                   aria-label="Laporan Sistem">
                    <i class="fas fa-chart-bar me-2"></i> Reports
                </a>
            </li>

            @elseif(Auth::user()->role === 'guru')
            <!-- Guru Menu -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('guru.materials.*') ? 'active' : '' }}"
                   href="{{ route('guru.materials.index') }}"
                   title="Materi Pembelajaran"
                   aria-label="Materi Pembelajaran">
                    <i class="fas fa-book me-2"></i> Materi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('guru.assignments.*') ? 'active' : '' }}"
                   href="{{ route('guru.assignments.index') }}"
                   title="Tugas & Quiz"
                   aria-label="Tugas & Quiz">
                    <i class="fas fa-tasks me-2"></i> Tugas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('guru.praktikum.*') ? 'active' : '' }}"
                   href="{{ route('guru.praktikum.index') }}"
                   title="Praktikum"
                   aria-label="Praktikum">
                    <i class="fas fa-flask me-2"></i> Praktikum
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('guru.absensi.*') ? 'active' : '' }}"
                   href="{{ route('guru.absensi.index') }}"
                   title="Absensi Siswa"
                   aria-label="Absensi Siswa">
                    <i class="fas fa-calendar-check me-2"></i> Absensi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('guru.penilaian.*') ? 'active' : '' }}"
                   href="{{ route('guru.penilaian.index') }}"
                   title="Penilaian Siswa"
                   aria-label="Penilaian Siswa">
                    <i class="fas fa-star me-2"></i> Penilaian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('guru.laporan.*') ? 'active' : '' }}"
                   href="{{ route('guru.laporan.index') }}"
                   title="Laporan Pembelajaran"
                   aria-label="Laporan Pembelajaran">
                    <i class="fas fa-file-alt me-2"></i> Laporan
                </a>
            </li>

            @elseif(Auth::user()->role === 'siswa')
            <!-- Siswa Menu -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.materials.*') ? 'active' : '' }}"
                   href="{{ route('siswa.materials.index') }}"
                   title="Materi Pembelajaran"
                   aria-label="Materi Pembelajaran">
                    <i class="fas fa-book me-2"></i> Materi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.assignments.*') ? 'active' : '' }}"
                   href="{{ route('siswa.assignments.index') }}"
                   title="Daftar Tugas"
                   aria-label="Daftar Tugas">
                    <i class="fas fa-tasks me-2"></i> Tugas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.praktikum.*') ? 'active' : '' }}"
                   href="{{ route('siswa.praktikum.index') }}"
                   title="Praktikum"
                   aria-label="Praktikum">
                    <i class="fas fa-flask me-2"></i> Praktikum
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}"
                   href="{{ route('siswa.nilai.index') }}"
                   title="Nilai Saya"
                   aria-label="Nilai Saya">
                    <i class="fas fa-chart-line me-2"></i> Nilai
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.absensi.*') ? 'active' : '' }}"
                   href="{{ route('siswa.absensi.index') }}"
                   title="Absensi Saya"
                   aria-label="Absensi Saya">
                    <i class="fas fa-calendar me-2"></i> Absensi
                </a>
            </li>

            @else
            <!-- Fallback Menu -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.edit') }}" title="Edit Profil" aria-label="Edit Profil">
                    <i class="fas fa-user me-2"></i> Profil
                </a>
            </li>
            @endif
        </ul>

        <!-- Quick Actions -->
        <div class="sidebar-footer mt-4 p-3 bg-light rounded">
            <h6 class="text-muted mb-3">Aksi Cepat</h6>
            <div class="d-grid gap-2">
                @if(Auth::user()->role === 'guru')
                <a href="{{ route('guru.materials.create') }}" class="btn btn-sm btn-outline-primary" title="Buat Materi Baru">
                    <i class="fas fa-plus me-1"></i> Materi Baru
                </a>
                <a href="{{ route('guru.assignments.create') }}" class="btn btn-sm btn-outline-success" title="Buat Tugas Baru">
                    <i class="fas fa-plus me-1"></i> Tugas Baru
                </a>
                @elseif(Auth::user()->role === 'siswa')
                <a href="{{ route('siswa.assignments.index') }}" class="btn btn-sm btn-outline-warning" title="Tugas dengan Deadline Mendekat">
                    <i class="fas fa-clock me-1"></i> Tugas Deadline
                </a>
                <a href="{{ route('siswa.nilai.index') }}" class="btn btn-sm btn-outline-info" title="Lihat Nilai Saya">
                    <i class="fas fa-chart-line me-1"></i> Lihat Nilai
                </a>
                @endif
            </div>
        </div>
    </div>
</nav>
