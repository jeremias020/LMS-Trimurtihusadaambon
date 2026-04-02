<nav class="sidebar admin-sidebar" id="sidebar">
    <!-- Brand -->
    <div class="p-3 border-bottom border-secondary">
        <a href="{{ route('guru.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none brand-link">
            <div class="bg-light rounded p-2 me-2">
                <i class="fas fa-chalkboard-teacher text-primary"></i>
            </div>
            <div class="sidebar-brand-text">
                <div class="fw-bold fs-6">LMS Trimurti</div>
                <small class="text-light opacity-75">Guru Panel</small>
            </div>
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-menu flex-grow-1">
        <div class="p-2">
            <!-- Teaching Section -->
            <div class="mb-3">
                <div class="nav-section-title px-2 py-1 mb-2">
                    <small class="text-light opacity-75 fw-medium">TEACHING</small>
                </div>
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
                    <span class="nav-text">Tugas</span>
                </a>
                <a href="{{ route('guru.praktikum.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.praktikum.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-flask me-2 nav-icon"></i>
                    <span class="nav-text">Praktikum</span>
                </a>
                <a href="{{ route('guru.absensi.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.absensi.*') || request()->routeIs('guru.attendance.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-user-check me-2 nav-icon"></i>
                    <span class="nav-text">Absensi</span>
                </a>
                @if (Route::has('guru.submissions'))
                <a href="{{ route('guru.submissions') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.submissions') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-inbox me-2 nav-icon"></i>
                    <span class="nav-text">Submissions</span>
                </a>
                @endif
                @if (Route::has('guru.penilaian.index'))
                <a href="{{ route('guru.penilaian.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.penilaian.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-star me-2 nav-icon"></i>
                    <span class="nav-text">Penilaian</span>
                </a>
                @endif
            </div>

            <!-- Reports Section -->
            <div class="mb-3">
                <div class="nav-section-title px-2 py-1 mb-2">
                    <small class="text-light opacity-75 fw-medium">REPORTS</small>
                </div>
                <a href="{{ route('guru.reports.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('guru.reports.*') || request()->routeIs('guru.laporan.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-chart-bar me-2 nav-icon"></i>
                    <span class="nav-text">Laporan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Sidebar Footer (Collapse Control like Admin) -->
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
