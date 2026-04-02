<header class="top-header border-bottom bg-white shadow-sm">
    <div class="d-flex align-items-center">
        <!-- Sidebar Toggle -->
        <button class="btn btn-ghost d-lg-none me-3 p-2" id="mobileSidebarToggle" type="button" title="Toggle Navigation">
            <i class="fas fa-bars text-primary fs-5"></i>
        </button>
        <button class="btn btn-ghost d-none d-lg-block me-3 p-2" id="sidebarToggle" type="button" title="Collapse Sidebar">
            <i class="fas fa-bars text-primary fs-5"></i>
        </button>

        <!-- Brand Logo -->
        <div class="d-lg-none me-auto">
            <div class="d-flex align-items-center">
                <div class="bg-primary rounded p-1 me-2">
                    @if(Auth::check() && Auth::user()->role === 'student')
                        <i class="fas fa-user-graduate text-white fs-6"></i>
                    @else
                        <i class="fas fa-graduation-cap text-white fs-6"></i>
                    @endif
                </div>
                <span class="fw-bold text-dark">
                    @if(Auth::check() && Auth::user()->role === 'student')
                        LMS Siswa
                    @else
                        LMS Trimurti
                    @endif
                </span>
            </div>
        </div>

        <!-- Search Bar -->
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
                           placeholder="@if(Auth::check() && Auth::user()->role === 'student')Cari materi, tugas, nilai...@else Cari tugas, materi, pengguna...@endif"
                           id="globalSearch"
                           autocomplete="off">
                </div>
            </form>
        </div>

        {{-- Quick Access/Actions (optional, bisa pakai @if role) --}}
        {{-- Tambahkan dropdown/aksi cepat sesuai kebutuhan --}}

        <!-- Notification Dropdown -->
        @auth
        <div class="dropdown me-2">
            <button class="btn btn-ghost position-relative" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                <i class="fas fa-bell"></i>
                <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                    0
                </span>
            </button>
            <ul id="notification-dropdown" class="dropdown-menu dropdown-menu-end" style="min-width: 320px;">
                <li class="dropdown-header d-flex justify-content-between align-items-center">
                    <span>Notifikasi</span>
                    <button id="mark-all-read" class="btn btn-sm btn-outline-primary">Tandai semua dibaca</button>
                </li>
                <li><hr class="dropdown-divider"></li>
                <!-- Notifications will be loaded here -->
            </ul>
        </div>
        @endauth

        <!-- User Dropdown -->
        <div class="d-flex align-items-center gap-2 flex-nowrap order-2">
            @guest
                @if (Route::has('login'))
                    <a class="btn btn-outline-primary me-2" href="{{ route('login') }}">Login</a>
                @endif
            @else
                <div class="dropdown">
                    <a id="navbarDropdown" class="btn btn-outline-secondary dropdown-toggle" href="#" role="button"
                       data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        @php
                            $role = Auth::user()->role ?? '';
                            if ($role === 'admin') {
                                $dashboardRoute = 'admin.dashboard';
                                $profileRoute = 'admin.profile.edit';
                            } elseif ($role === 'guru') {
                                $dashboardRoute = 'guru.dashboard';
                                $profileRoute = 'guru.profile.edit';
                            } elseif ($role === 'student') {
                                $dashboardRoute = 'siswa.dashboard';
                                $profileRoute = 'siswa.profile.edit';
                            } else {
                                $dashboardRoute = 'dashboard';
                                $profileRoute = null;
                            }
                        @endphp
                        <a class="dropdown-item" href="{{ route($dashboardRoute) }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        @if (!empty($profileRoute) && Route::has($profileRoute))
                            <a class="dropdown-item" href="{{ route($profileRoute) }}">
                                <i class="fas fa-user me-2"></i> Profil
                            </a>
                        @endif
                        <hr class="dropdown-divider">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();
                                    this.innerHTML = '<i class=\'fas fa-spinner fa-spin me-2\'></i> Logging out...';
                                    this.classList.add('disabled');">
                            <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</header>
