// Aplikasi JavaScript Utama untuk LMS SMK Kesehatan Trimurti Husada Ambon
/**
 * Objek utama aplikasi - Kompatibel dengan Laravel 12 dan plugin v0.7.8
 */
const LMSApp = {
    // Konfigurasi tema sekolah kesehatan
    theme: {
        primary: '#2c7da0',
        secondary: '#a9d6e5',
        accent: '#e63946',
        success: '#40916c',
        warning: '#ff9e00',
        danger: '#dc3545',
        light: '#f8f9fa'
    },
    // Inisialisasi aplikasi
    init: function() {
        this.setupCSRF();
        this.setupAjax();
        this.setupTooltips();
        this.setupPopovers();
        this.setupNotifications();
        this.setupForms();
        this.setupModals();
        this.setupSidebar();
        this.setupTheme();
        this.setupCharts();

        // Event listener untuk plugin v0.7.8
        this.setupPluginCompatibility();

        console.log('LMS SMK Kesehatan Trimurti initialized - Laravel 12 Compatible');
    },

    // Setup kompatibilitas dengan plugin v0.7.8
    setupPluginCompatibility: function() {
        // Deteksi plugin dan tambahkan event listeners jika diperlukan
        if (typeof Plugin !== 'undefined' && Plugin.version === '0.7.8') {
            // Tambahkan event listeners khusus untuk plugin v0.7.8
            document.addEventListener('plugin:ready', this.handlePluginReady.bind(this));
            document.addEventListener('plugin:dataLoaded', this.handlePluginDataLoaded.bind(this));
        }
    },

    // Handle plugin ready event
    handlePluginReady: function(event) {
        console.log('Plugin v0.7.8 siap digunakan');
        // Inisialisasi fitur yang bergantung pada plugin
        this.initializePluginFeatures();
    },

    // Handle plugin data loaded event
    handlePluginDataLoaded: function(event) {
        console.log('Data plugin telah dimuat');
        // Update UI dengan data dari plugin
        this.updateUIWithPluginData(event.detail);
    },

    // Inisialisasi fitur yang bergantung pada plugin
    initializePluginFeatures: function() {
        // Implementasi fitur khusus plugin
        if (typeof Plugin !== 'undefined') {
            // Contoh: Plugin Charts
            if (Plugin.charts) {
                this.setupPluginCharts();
            }

            // Contoh: Plugin Tables
            if (Plugin.tables) {
                this.setupPluginTables();
            }
        }
    },

    // Update UI dengan data dari plugin
    updateUIWithPluginData: function(data) {
        // Implementasi update UI dengan data plugin
        if (data && data.charts) {
            this.renderPluginCharts(data.charts);
        }
    },

    // Setup plugin charts
    setupPluginCharts: function() {
        // Implementasi setup charts menggunakan plugin
        console.log('Mengatur grafik menggunakan plugin');
    },

    // Setup plugin tables
    setupPluginTables: function() {
        // Implementasi setup tables menggunakan plugin
        console.log('Mengatur tabel menggunakan plugin');
    },

    // Render plugin charts
    renderPluginCharts: function(chartsData) {
        // Implementasi render charts menggunakan plugin
        chartsData.forEach(chart => {
            if (chart.type === 'attendance') {
                this.renderAttendanceChart(chart);
            }
        });
    },

    // Render attendance chart
    renderAttendanceChart: function(chartData) {
        // Implementasi render attendance chart
        console.log('Merender grafik kehadiran', chartData);
    },

    // Setup tema warna sekolah kesehatan
    setupTheme: function() {
        // Terapkan variabel CSS custom
        document.documentElement.style.setProperty('--primary-color', this.theme.primary);
        document.documentElement.style.setProperty('--secondary-color', this.theme.secondary);
        document.documentElement.style.setProperty('--accent-color', this.theme.accent);
        document.documentElement.style.setProperty('--success-color', this.theme.success);
    },

    // Setup CSRF token untuk AJAX
    setupCSRF: function() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            // Untuk jQuery
            if (typeof $ !== 'undefined') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token.getAttribute('content')
                    }
                });
            }
            // Untuk Axios/Fetch
            if (typeof axios !== 'undefined') {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
            }
        }
    },

    // Setup konfigurasi AJAX global
    setupAjax: function() {
        if (typeof $ !== 'undefined') {
            $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
                LMSApp.handleAjaxError(jqXHR, thrownError);
            });
        }
    },

    // Setup tooltips Bootstrap
    setupTooltips: function() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    },

    // Setup popovers Bootstrap
    setupPopovers: function() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Popover) {
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        }
    },

    // Setup sistem notifikasi
    setupNotifications: function() {
        // Auto-hide alert setelah 5 detik
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(alert => {
                alert.style.transition = 'all 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            });
        }, 5000);
    },

    // Setup form validation
    setupForms: function() {
        // Validasi form Bootstrap
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Konfirmasi sebelum submit form penting
        const confirmForms = document.querySelectorAll('form[data-confirm]');
        confirmForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const message = this.getAttribute('data-confirm') || 'Anda yakin ingin melanjutkan?';
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });
    },

    // Setup modal handlers
    setupModals: function() {
        if (typeof $ !== 'undefined') {
            // Auto-focus pada input pertama di modal
            $('.modal').on('shown.bs.modal', function() {
                const firstInput = this.querySelector('input:not([type="hidden"]):not([disabled]), textarea:not([disabled]), select:not([disabled])');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            });

            // Clear modal content ketika ditutup
            $('.modal').on('hidden.bs.modal', function() {
                const form = this.querySelector('form');
                if (form) {
                    form.reset();
                    form.classList.remove('was-validated');
                }
                const invalidFeedbacks = this.querySelectorAll('.invalid-feedback');
                invalidFeedbacks.forEach(el => {
                    if (!el.hasAttribute('data-bs-original')) {
                        el.remove();
                    }
                });
                const invalidInputs = this.querySelectorAll('.is-invalid');
                invalidInputs.forEach(el => el.classList.remove('is-invalid'));
            });
        }
    },

    // Setup sidebar toggle
    setupSidebar: function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                document.body.classList.toggle('sidebar-toggled');
                sidebar.classList.toggle('toggled');
                if (sidebar.classList.contains('toggled')) {
                    const collapses = sidebar.querySelectorAll('.collapse');
                    collapses.forEach(collapse => {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                            const bsCollapse = bootstrap.Collapse.getInstance(collapse);
                            if (bsCollapse) {
                                bsCollapse.hide();
                            }
                        }
                    });
                }
                // Simpan preferensi pengguna
                localStorage.setItem('sidebarToggled', document.body.classList.contains('sidebar-toggled'));
            });

            // Restore sidebar state
            if (localStorage.getItem('sidebarToggled') === 'true') {
                document.body.classList.add('sidebar-toggled');
                sidebar.classList.add('toggled');
            }
        }
    },

    // Setup charts untuk halaman yang membutuhkan
    setupCharts: function() {
        // Pastikan Chart.js tersedia
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js tidak tersedia. Beberapa fitur grafik mungkin tidak berfungsi.');
            return;
        }

        // Set default options untuk semua chart
        Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
        Chart.defaults.color = '#6c757d';

        // Override untuk chart tertentu jika diperlukan
        const charts = document.querySelectorAll('canvas[id$="Chart"]');
        if (charts.length > 0) {
            console.log('Menginisialisasi grafik...');
        }
    },

    // Handle AJAX errors
    handleAjaxError: function(jqXHR, thrownError) {
        let message = 'Terjadi kesalahan. Silakan coba lagi.';
        if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
            message = jqXHR.responseJSON.message;
        } else if (jqXHR.status === 422) {
            message = 'Data yang dimasukkan tidak valid. Silakan periksa kembali.';
            // Tampilkan error validation
            if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                this.showValidationErrors(jqXHR.responseJSON.errors);
            }
        } else if (jqXHR.status === 404) {
            message = 'Data tidak ditemukan.';
        } else if (jqXHR.status === 403) {
            message = 'Anda tidak memiliki izin untuk melakukan aksi ini.';
        } else if (jqXHR.status === 401) {
            message = 'Sesi Anda telah berakhir. Silakan login kembali.';
            setTimeout(() => window.location.reload(), 2000);
        } else if (jqXHR.statusText) {
            message = jqXHR.statusText;
        }
        this.showNotification(message, 'danger');
    },

    // Tampilkan error validasi
    showValidationErrors: function(errors) {
        // Hapus error sebelumnya
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            if (!el.hasAttribute('data-bs-original')) {
                el.remove();
            }
        });

        // Tampilkan error baru
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = errors[field][0];
                input.parentNode.appendChild(errorDiv);
            }
        });
    },

    // Tampilkan notifikasi
    showNotification: function(message, type = 'info') {
        const alertClass = `alert-${type}`;
        const icon = this.getNotificationIcon(type);
        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show`;
        notification.innerHTML = `
            <i class="${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        const container = document.getElementById('notifications-container') || document.body;
        container.appendChild(notification);

        // Auto-hide notifikasi setelah 5 detik
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    },

    // Dapatkan icon untuk notifikasi
    getNotificationIcon: function(type) {
        const icons = {
            'success': 'fas fa-check-circle',
            'danger': 'fas fa-exclamation-circle',
            'warning': 'fas fa-exclamation-triangle',
            'info': 'fas fa-info-circle'
        };
        return icons[type] || 'fas fa-info-circle';
    },

    // Format tanggal
    formatDate: function(date, format = 'DD MMMM YYYY') {
        if (typeof moment !== 'undefined') {
            return moment(date).format(format);
        }
        return new Date(date).toLocaleDateString('id-ID');
    },

    // Format file size
    formatFileSize: function(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    },

    // Debounce function
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Check if element is in viewport
    isInViewport: function(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    },

    // Smooth scroll to element
    scrollToElement: function(elementId, offset = 0) {
        const element = document.getElementById(elementId);
        if (element) {
            const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
            window.scrollTo({
                top: elementPosition - offset,
                behavior: 'smooth'
            });
        }
    },

    // Fungsi untuk membuat grafik distribusi kehadiran
    createAttendanceDistributionChart: function(canvasId, data) {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js tidak tersedia');
            return;
        }

        const ctx = document.getElementById(canvasId);
        if (!ctx) {
            console.error(`Canvas dengan ID ${canvasId} tidak ditemukan`);
            return;
        }

        return new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Tidak Hadir', 'Terlambat', 'Izin'],
                datasets: [{
                    data: [
                        data.present || 0,
                        data.absent || 0,
                        data.late || 0,
                        data.excused || 0
                    ],
                    backgroundColor: ['#10b981', '#ef4444', '#f59e0b', '#3b82f6'],
                    hoverBackgroundColor: ['#059669', '#dc2626', '#d97706', '#2563eb']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    },

    // Fungsi untuk membuat grafik tren kehadiran harian
    createDailyTrendChart: function(canvasId, labels, data) {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js tidak tersedia');
            return;
        }

        const ctx = document.getElementById(canvasId);
        if (!ctx) {
            console.error(`Canvas dengan ID ${canvasId} tidak ditemukan`);
            return;
        }

        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Tingkat Kehadiran (%)',
                    data: data,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 0,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Persentase Kehadiran'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Tingkat Kehadiran: ${context.raw}%`;
                            }
                        }
                    }
                }
            }
        });
    }
};

// Inisialisasi aplikasi ketika dokumen siap
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        LMSApp.init();
    });
} else {
    LMSApp.init();
}

// Export untuk penggunaan modul
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LMSApp;
}
