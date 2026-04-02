<header class="landing-nav navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="34" class="me-2" onerror="this.style.display='none'">
            <span class="fw-bold text-primary">LMS Trimurti Husada</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#landingNavbar" aria-controls="landingNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="landingNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}#fitur">Fitur</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}#faq">FAQ</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Kontak</a></li>
            </ul>

            <div class="d-flex gap-2 ms-lg-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                @endauth
            </div>
        </div>
    </div>
</header>

@push('css')
<style>
.landing-nav {
    backdrop-filter: blur(12px);
}

.landing-nav .nav-link {
    font-weight: 600;
    color: #334155;
}

.landing-nav .nav-link:hover {
    color: #0d6efd;
}
</style>
@endpush
