<nav class="sidebar admin-sidebar" id="sidebar">
    <!-- Brand -->
    <div class="p-3 border-bottom border-secondary">
        <a href="{{ route('siswa.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none brand-link">
            <div class="bg-light rounded p-2 me-2">
                <i class="fas fa-user-graduate text-primary"></i>
            </div>
            <div class="sidebar-brand-text">
                <div class="fw-bold fs-6">LMS Trimurti</div>
                <small class="text-light opacity-75">Siswa Panel</small>
            </div>
        </a>
    </div>

    <!-- User Profile -->
    <div class="p-3 border-bottom border-secondary">
        <div class="d-flex align-items-center">
            @php
                $student = \App\Models\Student::where('user_id', auth()->id())->first();
            @endphp
            @if($student && $student->foto)
                <img src="{{ asset('storage/' . $student->foto) }}"
                     alt="Profile"
                     class="rounded-circle me-2 d-block"
                     style="width: 40px; height: 40px; object-fit: cover;"
                     onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';">
            @else
                <img src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}"
                     alt="Profile"
                     class="rounded-circle me-2 d-block"
                     style="width: 40px; height: 40px; object-fit: cover;"
                     onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';">
            @endif
            <div class="sidebar-user-info flex-grow-1">
                <div class="fw-medium text-white small">{{ Str::limit(Auth::user()->name ?? 'Siswa', 15) }}</div>
                <small class="text-light opacity-75">Siswa</small>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-menu flex-grow-1">
        <div class="p-2">
            <!-- Learning Section -->
            <div class="mb-3">
                <div class="nav-section-title px-2 py-1 mb-2">
                    <small class="text-light opacity-75 fw-medium">LEARNING</small>
                </div>
                <a href="{{ route('siswa.dashboard') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('siswa.dashboard') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-tachometer-alt me-2 nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="{{ route('siswa.materials.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('siswa.materials.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-book me-2 nav-icon"></i>
                    <span class="nav-text">Materi</span>
                </a>
                <a href="{{ route('siswa.assignments.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('siswa.assignments.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-tasks me-2 nav-icon"></i>
                    <span class="nav-text">Tugas</span>
                </a>
                <a href="{{ route('siswa.reports.practical') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('siswa.reports.practical') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-flask me-2 nav-icon"></i>
                    <span class="nav-text">Praktikum</span>
                </a>
                <a href="{{ route('siswa.attendance.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('siswa.attendance.*') || request()->routeIs('siswa.reports.attendance') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-calendar-check me-2 nav-icon"></i>
                    <span class="nav-text">Absensi</span>
                </a>
            </div>


        </div>
    </div>

    <!-- Sidebar Footer (Collapse Control) -->
    <div class="sidebar-footer p-3 border-top border-opacity-25">
        <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-link text-light p-0 sidebar-toggle" title="Toggle Sidebar">
                <i class="fas fa-angle-left"></i>
            </button>
            <div class="sidebar-collapse-text">
                <small class="text-light opacity-75">Collapse</small>
            </div>
        </div>
    </div>
</nav>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>


<script>
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.querySelector('.sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const mainContent = document.getElementById('main-content') || document.querySelector('.main-content');

  function applyCollapsed(collapsed) {
    if (!sidebar) return;
    
    sidebar.classList.toggle('collapsed', collapsed);
    if (mainContent) {
      mainContent.classList.toggle('expanded', collapsed);
    }
    localStorage.setItem('sidebarCollapsed', collapsed);
    
    // Update toggle icon
    const toggleIcon = document.querySelector('.sidebar-toggle i');
    if (toggleIcon) {
      toggleIcon.className = collapsed ? 'fas fa-angle-right' : 'fas fa-angle-left';
    }
  }

  // Bind all toggle triggers
  const headerToggle = document.getElementById('sidebarToggle');
  const footerToggles = document.querySelectorAll('.sidebar-toggle');
  
  if (headerToggle) {
    headerToggle.addEventListener('click', () => applyCollapsed(!sidebar.classList.contains('collapsed')));
  }
  
  footerToggles.forEach(btn => {
    btn.addEventListener('click', () => applyCollapsed(!sidebar.classList.contains('collapsed')));
  });

  // Restore state
  const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
  applyCollapsed(isCollapsed);

  // Mobile sidebar toggle
  const mobileToggle = document.getElementById('mobileSidebarToggle');
  if (mobileToggle) {
    mobileToggle.addEventListener('click', function(e) {
      e.preventDefault();
      sidebar.classList.toggle('show');
      if (overlay) {
        overlay.classList.toggle('active');
      }
    });
  }

  // Mobile overlay
  if (overlay) {
    overlay.addEventListener('click', function() {
      sidebar.classList.remove('show');
      overlay.classList.remove('active');
    });
  }

  // Close mobile sidebar when clicking outside
  document.addEventListener('click', function(e) {
    if (window.innerWidth <= 768) {
      if (!e.target.closest('.sidebar, #mobileSidebarToggle, .sidebar-toggle')) {
        sidebar.classList.remove('show');
        if (overlay) {
          overlay.classList.remove('active');
        }
      }
    }
  });
});
</script>
