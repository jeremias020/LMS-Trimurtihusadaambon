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
                    <i class="fas fa-graduation-cap text-white fs-6"></i>
                </div>
                <span class="fw-bold text-dark">LMS Trimurti</span>
            </div>
        </div>

        <!-- Enhanced Search Bar -->
        <div class="position-relative d-none d-lg-block me-auto">
            <form action="{{ request()->routeIs('guru.assignments.*') ? route('guru.assignments.index') : '#' }}" method="GET" class="d-flex" id="globalSearchForm">
                @if(request()->has('tab'))
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                @endif
                <div class="input-group shadow-sm" style="min-width: 350px;">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                        <i class="fas fa-search text-primary"></i>
                    </span>
                    <input type="search" 
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control border-start-0 border-end-0 bg-white" 
                           placeholder="Cari tugas, materi, siswa..."
                           id="globalSearch"
                           autocomplete="off">
                    <button type="button" class="btn btn-outline-secondary border-start-0 rounded-end-pill" id="searchFilter" title="Filter Pencarian">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
            
            <!-- Search Suggestions -->
            <div class="position-absolute top-100 start-0 w-100 bg-white border rounded-3 shadow-lg mt-1 d-none" id="searchSuggestions" style="z-index: 1050;">
                <div class="p-3">
                    <div class="small text-muted mb-2 fw-medium">Pencarian Cepat</div>
                    <div class="d-flex flex-wrap gap-1">
                        <span class="badge bg-light text-dark border search-tag" data-search="tugas aktif">Tugas Aktif</span>
                        <span class="badge bg-light text-dark border search-tag" data-search="deadline hari ini">Deadline Hari Ini</span>
                        <span class="badge bg-light text-dark border search-tag" data-search="belum dinilai">Belum Dinilai</span>
                        <span class="badge bg-light text-dark border search-tag" data-search="kelas X">Kelas X</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <!-- Quick Actions -->
        <div class="dropdown me-2 d-none d-xl-block">
            <button class="btn btn-primary dropdown-toggle shadow-sm" type="button" id="quickActionsDropdown" data-bs-toggle="dropdown">
                <i class="fas fa-plus me-2"></i>Tambah Baru
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="quickActionsDropdown" style="min-width: 200px;">
                <li class="dropdown-header">
                    <small class="text-muted fw-medium">BUAT KONTEN BARU</small>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('guru.materials.create') }}">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="fas fa-book text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-medium">Materi Pembelajaran</div>
                                <small class="text-muted">Upload materi baru</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('guru.assignments.create') }}">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="fas fa-tasks text-success"></i>
                            </div>
                            <div>
                                <div class="fw-medium">Tugas & Quiz</div>
                                <small class="text-muted">Buat tugas baru</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('guru.praktikum.create') }}">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="fas fa-flask text-warning"></i>
                            </div>
                            <div>
                                <div class="fw-medium">Praktikum</div>
                                <small class="text-muted">Sesi praktikum</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('guru.absensi.create') }}">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="fas fa-clipboard-check text-danger"></i>
                            </div>
                            <div>
                                <div class="fw-medium">Absensi</div>
                                <small class="text-muted">Input kehadiran</small>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Mobile Quick Action -->
        <div class="d-xl-none">
            <a href="{{ route('guru.assignments.create') }}" class="btn btn-primary btn-sm" title="Buat Tugas">
                <i class="fas fa-plus"></i>
            </a>
        </div>

        <!-- Notifications -->
        <div class="dropdown me-2">
            <button class="btn btn-ghost position-relative p-2" type="button" id="notificationDropdown" data-bs-toggle="dropdown" title="Notifikasi">
                <i class="fas fa-bell text-primary fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger pulse-animation" style="font-size: 0.6rem;">
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
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-file-upload text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-medium">Tugas Baru Dikumpulkan</div>
                                <small class="text-muted">Ahmad Fadil mengumpulkan Tugas Anatomi</small>
                                <small class="text-muted d-block">5 menit yang lalu</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-3" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user-check text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-medium">Siswa Baru Terdaftar</div>
                                <small class="text-muted">Siti Nurhaliza bergabung di kelas 3A</small>
                                <small class="text-muted d-block">1 jam yang lalu</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-3" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-medium">Deadline Mendekati</div>
                                <small class="text-muted">Tugas Fisiologi berakhir besok</small>
                                <small class="text-muted d-block">2 jam yang lalu</small>
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
                        {{ Auth::user()->subject ? 'Guru ' . Str::limit(Auth::user()->subject, 15) : 'Guru' }}
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
                    <a class="dropdown-item" href="{{ route('guru.profile.edit') }}">
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
    
    console.log('🔍 Logout process started');
    console.log('📋 Current URL:', window.location.href);
    console.log('🍪 Cookies:', document.cookie);
    
    if (confirm('Apakah Anda yakin ingin keluar?')) {
        console.log('✅ User confirmed logout');
        
        // Update UI
        element.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Logging out...';
        element.classList.add('disabled');
        
        // Get form element
        const form = document.getElementById('logout-form');
        if (!form) {
            console.error('❌ Logout form not found!');
            alert('Error: Logout form tidak ditemukan!');
            return;
        }
        
        console.log('📝 Form details:');
        console.log('  - Action:', form.action);
        console.log('  - Method:', form.method);
        
        const csrfToken = form.querySelector('input[name="_token"]');
        if (!csrfToken) {
            console.error('❌ CSRF token not found!');
            alert('Error: CSRF token tidak ditemukan!');
            return;
        }
        
        console.log('🔐 CSRF token:', csrfToken.value.substring(0, 20) + '...');
        
        // Tambahan debugging - test dengan fetch juga
        console.log('🚀 Submitting logout form...');
        
        // Submit form dengan penanganan error
        try {
            form.submit();
            console.log('✅ Form submitted successfully');
            
            // Backup: redirect manual jika form submit tidak bekerja
            setTimeout(() => {
                console.log('⚠️ Backup redirect triggered');
                window.location.href = '{{ route("login") }}';
            }, 3000);
            
        } catch (error) {
            console.error('❌ Form submission failed:', error);
            
            // Fallback: gunakan fetch API
            console.log('🔄 Trying fallback logout with fetch...');
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken.value
                },
                body: '_token=' + encodeURIComponent(csrfToken.value)
            })
            .then(response => {
                console.log('📡 Fetch response:', response.status);
                if (response.ok || response.status === 302) {
                    console.log('✅ Logout successful, redirecting...');
                    window.location.href = '{{ route("login") }}';
                } else {
                    throw new Error('Logout request failed');
                }
            })
            .catch(fetchError => {
                console.error('❌ Fetch logout failed:', fetchError);
                alert('Error: Tidak dapat logout. Silakan refresh halaman dan coba lagi.');
                
                // Reset UI
                element.innerHTML = '<i class="fas fa-sign-out-alt me-2"></i>Keluar';
                element.classList.remove('disabled');
            });
        }
    } else {
        console.log('❌ User cancelled logout');
    }
}

// Function untuk test logout secara manual
window.testLogout = function() {
    console.log('🧪 Testing logout elements...');
    
    const form = document.getElementById('logout-form');
    const link = document.getElementById('logout-link');
    const token = form ? form.querySelector('input[name="_token"]') : null;
    
    console.log('Form exists:', !!form);
    console.log('Link exists:', !!link);
    console.log('Token exists:', !!token);
    
    if (form && link && token) {
        console.log('✅ All logout elements found!');
        return { form, link, token: token.value };
    } else {
        console.log('❌ Some logout elements missing!');
        return null;
    }
};

// Auto-test pada page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        console.log('🔍 Auto-testing logout elements...');
        testLogout();
    }, 1000);
});
</script>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif