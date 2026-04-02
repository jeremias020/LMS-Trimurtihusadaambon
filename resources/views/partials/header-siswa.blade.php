<header class="top-header">
    <div class="d-flex align-items-center gap-2">
        <button id="sidebarToggle" class="btn btn-ghost" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <form id="globalSearchForm" class="d-none d-md-block" method="GET" action="#">
        <div class="input-group" style="min-width: 360px;">
            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
            <input id="globalSearch" type="text" class="form-control border-start-0" placeholder="Cari materi, tugas, nilai...">
        </div>
    </form>

    <div class="d-flex align-items-center gap-3">
        <div class="dropdown">
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
        <div class="dropdown">
            <button class="btn btn-ghost dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                @php
                    $student = \App\Models\Student::where('user_id', auth()->id())->first();
                @endphp
                @if($student && $student->foto)
                    <img src="{{ asset('storage/' . $student->foto) }}" class="rounded-circle me-2" alt="Avatar" style="width:32px;height:32px;object-fit:cover;"
                         onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';">
                @else
                    <img src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" class="rounded-circle me-2" alt="Avatar" style="width:32px;height:32px;object-fit:cover;"
                         onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';">
                @endif
                <span class="d-none d-md-inline">{{ Str::limit(Auth::user()->name, 18) }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('siswa.profile.edit') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
