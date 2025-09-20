<header class="top-header border-bottom bg-white shadow-sm">
    <div class="d-flex align-items-center">
        <!-- Mobile Sidebar Toggle -->
        <button class="btn btn-ghost d-lg-none me-3 p-2" id="mobileSidebarToggle" type="button" title="Toggle Navigation">
            <i class="fas fa-bars text-primary fs-5"></i>
        </button>

        <!-- Desktop Sidebar Toggle -->
        <button class="btn btn-ghost d-none d-lg-block me-3 p-2" id="sidebarToggle" type="button" title="Collapse Sidebar">
            <i class="fas fa-bars text-primary fs-5"></i>
        </button>

        <!-- Brand Logo for Mobile -->
        <div class="d-lg-none me-auto">
            <div class="d-flex align-items-center">
                <div class="bg-primary rounded p-1 me-2">
                    <i class="fas fa-user-graduate text-white fs-6"></i>
                </div>
                <span class="fw-bold text-dark">LMS Siswa</span>
            </div>
        </div>

        <!-- Search Bar (Student specific) -->
        <div class="position-relative d-none d-lg-block me-auto">
            <form action="#" method="GET" class="d-flex" id="globalSearchForm">
                <div class="input-group shadow-sm" style="min-width: 350px;">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                        <i class="fas fa-search text-primary"></i>
                    </span>
                    <input type="search" 
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control border-start-0 border-end-0 bg-white" 
                           placeholder="Cari materi, tugas, nilai..."
                           id="globalSearch"
                           autocomplete="off">
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <!-- Quick Access -->
        <div class="dropdown me-2 d-none d-xl-block">
            <button class="btn btn-outline-primary dropdown-toggle shadow-sm" type="button" id="quickAccessDropdown" data-bs-toggle="dropdown">
                <i class="fas fa-bolt me-2"></i>Akses Cepat
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="quickAccessDropdown" style="min-width: 200px;">
                <li class="dropdown-header">
                    <small class="text-muted fw-medium">MENU POPULER</small>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('siswa.assignments.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="fas fa-tasks text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-medium">Tugas Saya</div>
                                <small class="text-muted">Lihat semua tugas</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('siswa.nilai.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="fas fa-chart-line text-success"></i>
                            </div>
                            <div>
                                <div class="fw-medium">Nilai</div>
                                <small class="text-muted">Lihat progres nilai</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('siswa.materials.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="fas fa-book text-info"></i>
                            </div>
                            <div>
                                <div class="fw-medium">Materi</div>
                                <small class="text-muted">Bahan pembelajaran</small>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Mobile Quick Action -->
        <div class="d-xl-none">
            <a href="{{ route('siswa.assignments.index') }}" class="btn btn-primary btn-sm" title="Tugas">
                <i class="fas fa-tasks"></i>
            </a>
        </div>

        <!-- Study Progress -->
        <div class="dropdown me-2">
            <button class="btn btn-ghost position-relative p-2" type="button" id="progressDropdown" data-bs-toggle="dropdown" title="Progress Belajar">
                <i class="fas fa-chart-pie text-success fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="progressDropdown" style="min-width: 280px;">
                <li class="dropdown-header">
                    <div class="fw-bold text-dark">Progress Belajar</div>
                    <small class="text-muted">Semester ini</small>
                </li>
                <li><hr class="dropdown-divider m-0"></li>
                <li class="px-3 py-2">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">Tugas Selesai</small>
                            <small class="fw-medium">85%</small>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">Nilai Rata-rata</small>
                            <small class="fw-medium text-primary">8.2</small>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('siswa.nilai.index') }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Notifications -->
        <div class="dropdown me-2">
            <button class="btn btn-ghost position-relative p-2" type="button" id="notificationDropdown" data-bs-toggle="dropdown" title="Notifikasi">
                <i class="fas fa-bell text-primary fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                    3
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="notificationDropdown" style="min-width: 320px; max-height: 400px; overflow-y: auto;">
                <li class="dropdown-header d-flex justify-content-between align-items-center py-3">
                    <div>
                        <span class="fw-bold text-dark">Notifikasi</span>
                        <div><small class="text-muted">3 notifikasi baru</small></div>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" id="markAllRead" title="Tandai Semua Sudah Dibaca">
                        <i class="fas fa-check-double"></i>
                    </button>
                </li>
                <li><hr class="dropdown-divider m-0"></li>
                <li>
                    <a class="dropdown-item py-3" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-medium">Deadline Tugas</div>
                                <small class="text-muted">Tugas Anatomi berakhir besok</small>
                                <small class="text-muted d-block">2 jam yang lalu</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-3" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-medium">Nilai Tersedia</div>
                                <small class="text-muted">Nilai Quiz Fisiologi sudah keluar</small>
                                <small class="text-muted d-block">1 hari yang lalu</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-3" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-book text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-medium">Materi Baru</div>
                                <small class="text-muted">Materi Keperawatan Dasar telah diupload</small>
                                <small class="text-muted d-block">2 hari yang lalu</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-center py-2" href="#">
                        <small class="fw-medium">Lihat Semua Notifikasi</small>
                    </a>
                </li>
            </ul>
        </div>

        <!-- User Profile -->
        <div class="dropdown">
            <button class="btn btn-ghost d-flex align-items-center text-decoration-none p-2 rounded-pill" type="button" id="userDropdown" data-bs-toggle="dropdown">
                <div class="position-relative">
                    <img src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" 
                         alt="Profile" 
                         class="rounded-circle border border-2 border-primary" 
                         style="width: 42px; height: 42px; object-fit: cover;">
                    <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white" 
                          style="width: 12px; height: 12px;" title="Online"></span>
                </div>
                <div class="text-start d-none d-xl-block ms-2 me-1">
                    <div class="fw-medium text-dark small">{{ Str::limit(Auth::user()->name, 20) }}</div>
                    <small class="text-muted">
                        {{ Auth::user()->class ? 'Siswa - ' . Str::limit(Auth::user()->class, 10) : 'Siswa' }}
                    </small>
                </div>
                <i class="fas fa-chevron-down text-muted fs-6 d-none d-xl-block"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userDropdown">
                <li class="dropdown-header">
                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                    <small class="text-muted">{{ Auth::user()->email }}</small>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('siswa.profile.edit') }}">
                        <i class="fas fa-user me-2"></i>
                        Profil Saya
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cog me-2"></i>
                        Pengaturan
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-question-circle me-2"></i>
                        Bantuan
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="#" id="logout-link"
                       onclick="handleLogout(event, this)">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Keluar
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<script>
function handleLogout(event, element) {
    event.preventDefault();
    
    if (confirm('Apakah Anda yakin ingin keluar?')) {
        // Update UI
        element.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Logging out...';
        element.classList.add('disabled');
        
        // Submit form
        const form = document.getElementById('logout-form');
        if (form) {
            form.submit();
        }
        
        // Backup redirect
        setTimeout(() => {
            window.location.href = '{{ route("login") }}';
        }, 3000);
    }
}

// Progress circle animation
$(document).ready(function() {
    // Auto-update progress when dropdown is shown
    $('#progressDropdown').on('shown.bs.dropdown', function() {
        // Simulate progress bar animation
        $('.progress-bar').each(function() {
            const width = $(this).css('width');
            $(this).css('width', '0').animate({width: width}, 1000);
        });
    });
});
</script>