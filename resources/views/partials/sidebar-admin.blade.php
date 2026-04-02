<!-- Modern Admin Sidebar (Standardized with Guru/Siswa) -->
<nav class="sidebar admin-sidebar" id="sidebar" style="width: 280px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
    <!-- Brand -->
    <div class="p-3 border-bottom border-secondary">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none brand-link">
            <div class="bg-light rounded p-2 me-2">
                <i class="fas fa-user-shield text-primary"></i>
            </div>
            <div class="sidebar-brand-text">
                <div class="fw-bold fs-6">LMS Trimurti</div>
                <small class="text-light opacity-75">Admin Panel</small>
            </div>
        </a>
    </div>

    <!-- User Profile -->
    <div class="p-3 border-bottom border-secondary">
        <div class="d-flex align-items-center">
            <img src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}"
                 alt="Profile"
                 class="rounded-circle me-2 d-block"
                 style="width: 40px; height: 40px; object-fit: cover;"
                 onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';">
            <div class="sidebar-user-info flex-grow-1">
                <div class="fw-medium text-white small">{{ Str::limit(Auth::user()->name ?? 'Administrator', 15) }}</div>
                <small class="text-light opacity-75">Administrator</small>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-menu flex-grow-1">
        <div class="p-2">
            <!-- Dashboard Section -->
            <div class="mb-3">
                <div class="nav-section-title px-2 py-1 mb-2">
                    <small class="text-light opacity-75 fw-medium">DASHBOARD</small>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('admin.dashboard') ? 'active bg-primary' : 'hover-bg' }}" style="transition: all 0.2s ease; border-radius: 0.375rem;">
                    <i class="fas fa-tachometer-alt me-2 nav-icon" style="width: 16px; text-align: center; font-size: 0.875rem;"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </div>

            <!-- Management Section -->
            <div class="mb-3">
                <div class="nav-section-title px-2 py-1 mb-2">
                    <small class="text-light opacity-75 fw-medium">MANAGEMENT</small>
                </div>
                
                <!-- Users Dropdown Menu -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex align-items-center p-2 rounded text-white dropdown-toggle {{ request()->routeIs('admin.users.*') ? 'active bg-primary' : 'hover-bg' }}" 
                       id="usersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="return false;">
                        <i class="fas fa-users me-2 nav-icon"></i>
                        <span class="nav-text">Users</span>
                        <i class="fas fa-chevron-down ms-auto nav-icon" style="font-size: 0.75rem; transition: transform 0.3s;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="usersDropdown" 
                        style="background: #334155; border: 1px solid rgba(255,255,255,0.1); min-width: 200px; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15); position: absolute; z-index: 1050;">
                        <li>
                            <a href="{{ route('admin.users.separated') }}" class="dropdown-item d-flex align-items-center text-white {{ request()->routeIs('admin.users.separated') ? 'active' : '' }}" 
                               style="padding: 0.5rem 1rem; transition: all 0.2s; color: rgba(255,255,255,0.8);">
                                <i class="fas fa-table-columns me-2" style="width: 16px; font-size: 0.875rem;"></i>
                                <span>Semua Users</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.guru') }}" class="dropdown-item d-flex align-items-center text-white {{ request()->routeIs('admin.users.guru') ? 'active' : '' }}" 
                               style="padding: 0.5rem 1rem; transition: all 0.2s; color: rgba(255,255,255,0.8);">
                                <i class="fas fa-chalkboard-teacher me-2" style="width: 16px; font-size: 0.875rem;"></i>
                                <span>Guru</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.siswa') }}" class="dropdown-item d-flex align-items-center text-white {{ request()->routeIs('admin.users.siswa') ? 'active' : '' }}" 
                               style="padding: 0.5rem 1rem; transition: all 0.2s; color: rgba(255,255,255,0.8);">
                                <i class="fas fa-user-graduate me-2" style="width: 16px; font-size: 0.875rem;"></i>
                                <span>Siswa</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="dropdown-item d-flex align-items-center text-white {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" 
                               style="padding: 0.5rem 1rem; transition: all 0.2s; color: rgba(255,255,255,0.8);">
                                <i class="fas fa-users-cog me-2" style="width: 16px; font-size: 0.875rem;"></i>
                                <span>Users (Lama)</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('admin.kelas.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('admin.kelas.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-door-open me-2 nav-icon"></i>
                    <span class="nav-text">Kelas</span>
                </a>
                <a href="{{ route('admin.jurusan.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('admin.jurusan.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-school me-2 nav-icon"></i>
                    <span class="nav-text">Jurusan</span>
                </a>
                <a href="{{ route('admin.mata-pelajaran.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('admin.mata-pelajaran.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-book me-2 nav-icon"></i>
                    <span class="nav-text">Mata Pelajaran</span>
                </a>
                <a href="{{ route('admin.kriteria-penilaian.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('admin.kriteria-penilaian.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-clipboard-check me-2 nav-icon"></i>
                    <span class="nav-text">Kriteria Penilaian</span>
                </a>
                <a href="{{ route('admin.exam-schedules.index') }}" class="nav-link d-flex align-items-center p-2 rounded text-white {{ request()->routeIs('admin.exam-schedules.*') ? 'active bg-primary' : 'hover-bg' }}">
                    <i class="fas fa-calendar-alt me-2 nav-icon"></i>
                    <span class="nav-text">Jadwal Ujian</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Sidebar Footer (Collapse Control like Guru) -->
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

<style>
/* Standardized Sidebar Styles (Match Guru/Siswa) */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 280px !important;
    min-width: 280px !important;
    max-width: 280px !important;
    height: 100vh;
    background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
    color: #f1f5f9;
    z-index: 1060 !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    display: flex;
    flex-direction: column;
    box-shadow: none;
    overflow-y: auto;
    overflow-x: hidden;
}

.sidebar.collapsed {
    width: 70px !important;
    min-width: 70px !important;
    max-width: 70px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

/* More specific overrides to force sidebar collapse */
nav.sidebar.collapsed,
nav.admin-sidebar.collapsed,
body.admin-layout nav.sidebar.collapsed,
body.admin-layout nav.admin-sidebar.collapsed {
    width: 70px !important;
    min-width: 70px !important;
    max-width: 70px !important;
}

/* Force override universal.css */
body.admin-layout .sidebar.collapsed,
body.admin-layout nav.sidebar.collapsed,
body.admin-layout .admin-sidebar.collapsed {
    width: 70px !important;
    min-width: 70px !important;
    max-width: 70px !important;
}

/* Ultimate override with inline-style equivalent */
body.admin-layout nav.sidebar.admin-sidebar.collapsed {
    width: 70px !important;
    min-width: 70px !important;
    max-width: 70px !important;
}

/* Override any potential CSS framework conflicts */
.sidebar.collapsed,
nav.sidebar.collapsed,
.admin-sidebar.collapsed,
body.admin-layout .sidebar.collapsed,
body.admin-layout nav.sidebar.collapsed,
body.admin-layout .admin-sidebar.collapsed {
    width: 70px !important;
    min-width: 70px !important;
    max-width: 70px !important;
}

.sidebar.collapsed .sidebar-brand-text,
.sidebar.collapsed .sidebar-user-info,
.sidebar.collapsed .nav-text,
.sidebar.collapsed .nav-section-title,
.sidebar.collapsed .sidebar-badge,
.sidebar.collapsed .sidebar-collapse-text {
    display: none !important;
}

.sidebar.collapsed .nav-link {
    justify-content: center !important;
    padding: 0.75rem 0.5rem !important;
}

.sidebar.collapsed .nav-icon {
    margin: 0 !important;
}

.sidebar.collapsed .sidebar-footer .d-flex {
    justify-content: center !important;
}

/* Toggle Button Styling */
.sidebar-toggle {
    background: rgba(255, 255, 255, 0.1) !important;
    border: none !important;
    color: #f1f5f9 !important;
    border-radius: 0.375rem !important;
    padding: 0.5rem !important;
    transition: all 0.3s ease !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 40px !important;
    min-height: 40px !important;
}

.sidebar-toggle:hover {
    background: rgba(255, 255, 255, 0.2) !important;
    transform: scale(1.05) !important;
}

.sidebar-toggle i {
    font-size: 1rem !important;
    width: 1rem !important;
    height: 1rem !important;
    display: block !important;
}

.sidebar.collapsed .sidebar-toggle {
    margin: 0 auto !important;
}

/* Ensure sidebar remains interactive */
.sidebar {
    pointer-events: auto !important;
    z-index: 1030 !important;
}

.sidebar * {
    pointer-events: auto !important;
}

/* Fix any potential overlay issues */
.sidebar-overlay {
    pointer-events: none !important;
}

.sidebar-overlay.active {
    pointer-events: auto !important;
}

/* Brand link styling */
.sidebar .brand-link,
.sidebar .brand-link:visited,
.sidebar .brand-link:hover,
.sidebar .brand-link:active {
    color: #f1f5f9 !important;
    text-decoration: none !important;
}

.sidebar.collapsed .brand-link {
    justify-content: center !important;
}

/* Navigation styling */
.sidebar-menu {
    flex-grow: 1;
    overflow-y: auto;
    overflow-x: hidden;
}

.nav-section-title {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: rgba(255, 255, 255, 0.6);
}

.nav-link {
    color: rgba(255, 255, 255, 0.8) !important;
    text-decoration: none !important;
    margin-bottom: 0.25rem;
    transition: all 0.2s ease;
    border-radius: 0.375rem;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
    transform: translateX(2px);
}

.nav-link.active {
    background: var(--primary-color, #3b82f6) !important;
    color: #ffffff !important;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.nav-icon {
    width: 16px;
    text-align: center;
    font-size: 0.875rem;
}

/* Sidebar footer */
.sidebar-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin-top: auto;
}

.sidebar-toggle {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #f1f5f9;
    border-radius: 0.375rem;
    padding: 0.5rem;
    transition: all 0.3s ease;
    font-size: 1rem;
    width: auto;
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sidebar-toggle:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

.sidebar-toggle i {
    font-size: 1rem;
    width: 1rem;
    height: 1rem;
    display: block;
}

/* Dropdown Menu Fixes */
.sidebar .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    margin-top: 0.25rem !important;
    background: #334155 !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    border-radius: 0.375rem !important;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    min-width: 200px !important;
    z-index: 1050 !important;
    padding: 0.5rem 0 !important;
}

.sidebar .dropdown-item {
    color: rgba(255,255,255,0.8) !important;
    padding: 0.5rem 1rem !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
    text-decoration: none !important;
}

.sidebar .dropdown-item:hover {
    background: rgba(255,255,255,0.1) !important;
    color: #ffffff !important;
    transform: translateX(4px);
}

.sidebar .dropdown-item.active {
    background: var(--primary-color, #3b82f6) !important;
    color: #ffffff !important;
}

.sidebar .dropdown-item i {
    width: 16px !important;
    font-size: 0.875rem !important;
    margin-right: 0.5rem !important;
}

.sidebar .dropdown-divider {
    border-color: rgba(255,255,255,0.1) !important;
    margin: 0.5rem 0 !important;
}

/* Chevron animation */
.sidebar .dropdown-toggle[aria-expanded="true"] .fa-chevron-down {
    transform: rotate(180deg);
}

/* Ensure dropdown is visible */
.sidebar .dropdown.show .dropdown-menu {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Fix dropdown positioning in collapsed sidebar */
.sidebar.collapsed .dropdown-menu {
    left: 100% !important;
    top: 0 !important;
    margin-left: 0.25rem !important;
}

/* Dropdown toggle styling */
.sidebar .dropdown-toggle {
    cursor: pointer !important;
}

.sidebar .dropdown-toggle:hover {
    background: rgba(255,255,255,0.1) !important;
}

/* Active state for dropdown parent */
.sidebar .nav-item.dropdown.show .nav-link {
    background: var(--primary-color, #3b82f6) !important;
    color: #ffffff !important;
}

/* Responsive Design */
@media (min-width: 769px) {
    .main-content {
        margin-left: var(--sidebar-width, 280px);
        padding-bottom: 4rem;
    }
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        width: 280px;
        transition: transform 0.3s ease;
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
        padding-bottom: 4rem;
    }
    
    .sidebar-overlay {
        display: block;
    }
    
    .top-header {
        padding: 0.75rem 1rem;
    }
    
    .content-wrapper {
        padding: 1rem;
    }
}

/* Sidebar Overlay */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1055 !important;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.sidebar-overlay.active {
    opacity: 1;
    visibility: visible;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  console.log('🚀 Sidebar script initializing...');
  
  const sidebar = document.querySelector('.sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const mainContent = document.getElementById('main-content') || document.querySelector('.main-content');

  console.log('📦 Elements found:', {
    sidebar: !!sidebar,
    overlay: !!overlay,
    mainContent: !!mainContent
  });

  // Initialize Bootstrap dropdowns
  const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
  const dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
    return new bootstrap.Dropdown(dropdownToggleEl);
  });

  // Custom dropdown handling for sidebar
  const usersDropdown = document.getElementById('usersDropdown');
  if (usersDropdown) {
    usersDropdown.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const dropdown = this.closest('.dropdown');
      const menu = dropdown.querySelector('.dropdown-menu');
      const isShowing = dropdown.classList.contains('show');
      
      // Close all other dropdowns
      document.querySelectorAll('.dropdown').forEach(d => {
        d.classList.remove('show');
        const m = d.querySelector('.dropdown-menu');
        if (m) m.style.display = 'none';
      });
      
      if (!isShowing) {
        dropdown.classList.add('show');
        menu.style.display = 'block';
        this.setAttribute('aria-expanded', 'true');
        
        // Position dropdown correctly
        const rect = this.getBoundingClientRect();
        const sidebarRect = sidebar.getBoundingClientRect();
        
        menu.style.top = (rect.bottom - sidebarRect.top) + 'px';
        menu.style.left = '0';
        menu.style.right = 'auto';
        
        // Adjust if sidebar is collapsed
        if (sidebar.classList.contains('collapsed')) {
          menu.style.left = '100%';
          menu.style.top = '0';
          menu.style.marginLeft = '0.25rem';
        }
      } else {
        dropdown.classList.remove('show');
        menu.style.display = 'none';
        this.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // Close dropdowns when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
      document.querySelectorAll('.dropdown').forEach(d => {
        d.classList.remove('show');
        const m = d.querySelector('.dropdown-menu');
        if (m) m.style.display = 'none';
      });
      document.querySelectorAll('.dropdown-toggle').forEach(t => {
        t.setAttribute('aria-expanded', 'false');
      });
    }
  });

  function toggleSidebar() {
    console.log('🔄 Toggle function called');
    if (!sidebar) {
      console.error('❌ Sidebar element not found!');
      return;
    }
    
    const isCollapsed = sidebar.classList.contains('collapsed');
    console.log('🔄 Toggle clicked, current state:', isCollapsed);
    
    if (isCollapsed) {
      // EXPAND SIDEBAR
      sidebar.classList.remove('collapsed');
      
      // Update icon
      const toggleIcon = document.querySelector('.sidebar-toggle i');
      if (toggleIcon) {
        toggleIcon.className = 'fas fa-angle-left';
        console.log('🎨 Icon updated to fa-angle-left');
      }
      
      // Update main content
      if (mainContent) {
        mainContent.classList.remove('sidebar-collapsed');
        console.log('📱 Main content class removed');
      }
      
      console.log('✅ Sidebar expanded');
      
    } else {
      // COLLAPSE SIDEBAR
      sidebar.classList.add('collapsed');
      
      // Update icon
      const toggleIcon = document.querySelector('.sidebar-toggle i');
      if (toggleIcon) {
        toggleIcon.className = 'fas fa-angle-right';
        console.log('🎨 Icon updated to fa-angle-right');
      }
      
      // Update main content
      if (mainContent) {
        mainContent.classList.add('sidebar-collapsed');
        console.log('📱 Main content class added');
      }
      
      console.log('✅ Sidebar collapsed');
    }
    
    // Save state
    localStorage.setItem('sidebarCollapsed', !isCollapsed);
    console.log('💾 State saved:', !isCollapsed);
  }

  // Bind all toggle buttons
  const headerToggle = document.getElementById('sidebarToggle');
  const footerToggles = document.querySelectorAll('.sidebar-toggle');
  
  console.log('🎯 Toggle buttons found:', {
    headerToggle: !!headerToggle,
    footerToggles: footerToggles.length
  });
  
  if (headerToggle) {
    headerToggle.addEventListener('click', function(e) {
      console.log('🖱️ Header toggle clicked');
      e.preventDefault();
      toggleSidebar();
    });
    console.log('✅ Header toggle event bound');
  }
  
  footerToggles.forEach((btn, index) => {
    btn.addEventListener('click', function(e) {
      console.log('🖱️ Footer toggle', index, 'clicked');
      e.preventDefault();
      toggleSidebar();
    });
    console.log('✅ Footer toggle', index, 'event bound');
  });

  // Restore state on load
  const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
  console.log('💾 Restored state:', isCollapsed);
  
  if (isCollapsed) {
    sidebar.classList.add('collapsed');
    
    // Update icon
    const toggleIcon = document.querySelector('.sidebar-toggle i');
    if (toggleIcon) {
      toggleIcon.className = 'fas fa-angle-right';
      console.log('🎨 Initial icon set to fa-angle-right');
    }
    
    // Update main content
    if (mainContent) {
      mainContent.classList.add('sidebar-collapsed');
      console.log('📱 Initial main content class added');
    }
  }

  console.log('🎉 Sidebar toggle initialized successfully');
});
</script>
