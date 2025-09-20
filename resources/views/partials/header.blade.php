<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" role="navigation">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('uploads/logo/logo.png') }}" alt="Logo LMS Trimurti Husada" title="LMS Trimurti Husada" height="40" class="d-inline-block align-text-top me-2">
            LMS Trimurti Husada
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                       href="{{ url('/') }}"
                       {{ request()->is('/') ? 'aria-current="page"' : '' }}>
                        Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                       href="{{ route('about') }}"
                       {{ request()->routeIs('about') ? 'aria-current="page"' : '' }}>
                        Tentang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                       href="{{ route('contact') }}"
                       {{ request()->routeIs('contact') ? 'aria-current="page"' : '' }}>
                        Kontak
                    </a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                               href="{{ route('login') }}"
                               {{ request()->routeIs('login') ? 'aria-current="page"' : '' }}>
                                {{ __('Login') }}
                            </a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"
                               href="{{ route('register') }}"
                               {{ request()->routeIs('register') ? 'aria-current="page"' : '' }}>
                                {{ __('Register') }}
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            @php
                                $dashboardRoute = match(Auth::user()->role ?? '') {
                                    'admin' => 'admin.dashboard',
                                    'teacher' => 'guru.dashboard',
                                    'student' => 'siswa.dashboard',
                                    default => 'home',
                                };
                            @endphp

                            <a class="dropdown-item" href="{{ route($dashboardRoute) }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2"></i> Profil
                            </a>
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
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
