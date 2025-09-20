// JavaScript Khusus untuk Admin LMS SMK Kesehatan Trimurti Husada Ambon

const AdminApp = {
    init: function() {
        this.initDataTables();
        this.initCharts();
        this.initUserManagement();
        this.initSettings();
        this.initReports();
        this.initHealthFeatures();
        console.log('Admin module initialized - SMK Kesehatan Trimurti Husada');
    },

    // Inisialisasi DataTables
    initDataTables: function() {
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                },
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']],
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                initComplete: function() {
                    // Tambahkan kelas styling untuk tema kesehatan
                    this.api().tables().header().to$().addClass('bg-health-primary text-white');
                }
            });
        } else {
            console.warn('DataTables tidak tersedia');
        }
    },

    // Inisialisasi chart untuk dashboard admin
    initCharts: function() {
        // Gunakan LMSChart jika tersedia, atau fallback ke Chart.js langsung
        if (typeof LMSChart !== 'undefined') {
            this.initHealthCharts();
        } else if (typeof Chart !== 'undefined') {
            this.initFallbackCharts();
        } else {
            console.warn('Chart.js tidak tersedia');
        }
    },

    // Chart dengan tema kesehatan
    initHealthCharts: function() {
        // Chart aktivitas pengguna
        if (document.getElementById('userActivityChart')) {
            try {
                new LMSChart('userActivityChart', {
                    type: 'line',
                    data: {
                        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                        datasets: [{
                            label: 'Login Pengguna',
                            data: [65, 59, 80, 81, 56, 55, 40],
                            backgroundColor: 'rgba(44, 125, 160, 0.1)',
                            borderColor: 'rgba(44, 125, 160, 1)',
                            pointBackgroundColor: 'rgba(44, 125, 160, 1)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgba(44, 125, 160, 1)',
                            tension: 0.3
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error initializing user activity chart:', error);
            }
        }

        // Chart distribusi pengguna
        if (document.getElementById('userDistributionChart')) {
            try {
                new LMSChart('userDistributionChart', {
                    type: 'doughnut',
                    data: {
                        labels: ['Admin', 'Guru', 'Siswa'],
                        datasets: [{
                            data: [15, 35, 250],
                            backgroundColor: [
                                'rgba(44, 125, 160, 0.8)',    // Biru medis
                                'rgba(169, 214, 229, 0.8)',   // Biru muda
                                'rgba(64, 145, 108, 0.8)'     // Hijau success
                            ],
                            hoverBackgroundColor: [
                                'rgba(44, 125, 160, 1)',
                                'rgba(169, 214, 229, 1)',
                                'rgba(64, 145, 108, 1)'
                            ],
                            hoverBorderColor: 'rgba(234, 236, 244, 1)',
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        cutout: '80%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error initializing user distribution chart:', error);
            }
        }
    },

    // Fallback charts jika LMSChart tidak tersedia
    initFallbackCharts: function() {
        // Chart aktivitas pengguna
        if (document.getElementById('userActivityChart')) {
            const ctx = document.getElementById('userActivityChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                    datasets: [{
                        label: 'Login Pengguna',
                        data: [65, 59, 80, 81, 56, 55, 40],
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        tension: 0.3
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Chart distribusi pengguna
        if (document.getElementById('userDistributionChart')) {
            const ctx = document.getElementById('userDistributionChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Admin', 'Guru', 'Siswa'],
                    datasets: [{
                        data: [15, 35, 250],
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                        hoverBorderColor: 'rgba(234, 236, 244, 1)',
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    },

    // Manajemen pengguna
    initUserManagement: function() {
        // Toggle status pengguna
        const statusToggles = document.querySelectorAll('.user-status-toggle');
        statusToggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const userId = this.getAttribute('data-user-id');
                const isActive = this.checked;

                this.setAttribute('disabled', 'disabled');

                // Gunakan fetch API sebagai fallback jika jQuery tidak tersedia
                if (typeof $ !== 'undefined' && $.ajax) {
                    $.ajax({
                        url: `/admin/users/${userId}/status`,
                        method: 'POST',
                        data: { is_active: isActive },
                        success: function(response) {
                            if (typeof LMSApp !== 'undefined') {
                                LMSApp.showNotification('Status pengguna berhasil diperbarui', 'success');
                            } else {
                                alert('Status pengguna berhasil diperbarui');
                            }
                        },
                        error: function(xhr) {
                            if (typeof LMSApp !== 'undefined') {
                                LMSApp.handleAjaxError(xhr);
                            } else {
                                console.error('Error:', xhr);
                            }
                            // Kembalikan toggle ke state sebelumnya
                            toggle.checked = !isActive;
                        },
                        complete: function() {
                            toggle.removeAttribute('disabled');
                        }
                    });
                } else {
                    // Fallback menggunakan fetch API
                    fetch(`/admin/users/${userId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({ is_active: isActive })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (typeof LMSApp !== 'undefined') {
                            LMSApp.showNotification('Status pengguna berhasil diperbarui', 'success');
                        } else {
                            alert('Status pengguna berhasil diperbarui');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toggle.checked = !isActive;
                    })
                    .finally(() => {
                        toggle.removeAttribute('disabled');
                    });
                }
            });
        });

        // Konfirmasi hapus pengguna
        const deleteButtons = document.querySelectorAll('.delete-user-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');

                const confirmation = confirm(`Apakah Anda yakin ingin menghapus pengguna "${userName}"? Tindakan ini tidak dapat dibatalkan.`);
                if (confirmation) {
                    const form = document.getElementById(`delete-user-form-${userId}`);
                    if (form) {
                        form.submit();
                    }
                }
            });
        });
    },

    // Pengaturan sistem
    initSettings: function() {
        // Toggle pengaturan
        const settingToggles = document.querySelectorAll('.setting-toggle');
        settingToggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const settingName = this.getAttribute('data-setting');
                const settingValue = this.checked ? 1 : 0;

                this.saveSetting(settingName, settingValue);
            }.bind(this));
        });

        // Save text settings dengan debounce
        const settingInputs = document.querySelectorAll('.setting-input');
        settingInputs.forEach(input => {
            input.addEventListener('input', this.debounce(function() {
                const settingName = this.getAttribute('data-setting');
                const settingValue = this.value;

                this.saveSetting(settingName, settingValue);
            }.bind(input), 1000));
        });
    },

    // Simpan pengaturan
    saveSetting: function(name, value) {
        if (typeof $ !== 'undefined' && $.ajax) {
            $.ajax({
                url: '/admin/settings',
                method: 'POST',
                data: { name: name, value: value },
                success: function(response) {
                    if (typeof LMSApp !== 'undefined') {
                        LMSApp.showNotification('Pengaturan berhasil disimpan', 'success');
                    }
                },
                error: function(xhr) {
                    if (typeof LMSApp !== 'undefined') {
                        LMSApp.handleAjaxError(xhr);
                    }
                }
            });
        } else {
            // Fallback menggunakan fetch API
            fetch('/admin/settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ name: name, value: value })
            })
            .then(response => response.json())
            .then(data => {
                if (typeof LMSApp !== 'undefined') {
                    LMSApp.showNotification('Pengaturan berhasil disimpan', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    },

    // Laporan
    initReports: function() {
        // Date range picker untuk laporan
        if (typeof $ !== 'undefined' && $.fn.daterangepicker) {
            $('.date-range-picker').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: ' to ',
                    applyLabel: 'Terapkan',
                    cancelLabel: 'Batal',
                    fromLabel: 'Dari',
                    toLabel: 'Sampai',
                    customRangeLabel: 'Kustom',
                    weekLabel: 'M',
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    firstDay: 1
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });
        }

        // Generate report
        const generateButtons = document.querySelectorAll('.generate-report-btn');
        generateButtons.forEach(button => {
            button.addEventListener('click', function() {
                const reportType = this.getAttribute('data-report-type');
                const dateRange = document.querySelector('.date-range-picker')?.value || '';

                if (typeof $ !== 'undefined' && $.ajax) {
                    $.ajax({
                        url: '/admin/reports/generate',
                        method: 'POST',
                        data: { type: reportType, date_range: dateRange },
                        success: function(response) {
                            const resultsContainer = document.getElementById('reportResults');
                            if (resultsContainer && response.html) {
                                resultsContainer.innerHTML = response.html;
                            }
                            if (typeof LMSApp !== 'undefined') {
                                LMSApp.showNotification('Laporan berhasil digenerate', 'success');
                            }
                        },
                        error: function(xhr) {
                            if (typeof LMSApp !== 'undefined') {
                                LMSApp.handleAjaxError(xhr);
                            }
                        }
                    });
                }
            });
        });
    },

    // Fitur khusus kesehatan
    initHealthFeatures: function() {
        this.initMedicalRecords();
        this.initHealthStatistics();
        this.initVaccinationTracking();
    },

    // Manajemen rekam medis
    initMedicalRecords: function() {
        // Inisialisasi fitur rekam medis siswa
        const medicalRecordForms = document.querySelectorAll('.medical-record-form');
        medicalRecordForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                this.submitMedicalRecord(this);
            }.bind(this));
        });
    },

    // Statistik kesehatan
    initHealthStatistics: function() {
        // Chart statistik kesehatan siswa
        if (document.getElementById('healthStatsChart') && typeof LMSChart !== 'undefined') {
            try {
                new LMSChart('healthStatsChart', {
                    type: 'bar',
                    data: {
                        labels: ['Sehat', 'Sakit Ringan', 'Sakit Sedang', 'Sakit Berat', 'Izin', 'Alpha'],
                        datasets: [{
                            label: 'Statistik Kesehatan Siswa',
                            data: [120, 15, 8, 2, 10, 5],
                            backgroundColor: [
                                'rgba(64, 145, 108, 0.8)',  // Hijau - Sehat
                                'rgba(255, 193, 7, 0.8)',   // Kuning - Sakit Ringan
                                'rgba(255, 158, 0, 0.8)',   // Orange - Sakit Sedang
                                'rgba(220, 53, 69, 0.8)',   // Merah - Sakit Berat
                                'rgba(108, 117, 125, 0.8)', // Abu-abu - Izin
                                'rgba(52, 58, 64, 0.8)'     // Dark gray - Alpha
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            title: {
                                display: true,
                                text: 'Statistik Kesehatan Siswa'
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error initializing health stats chart:', error);
            }
        }
    },

    // Pelacakan vaksinasi
    initVaccinationTracking: function() {
        // Inisialisasi tabel vaksinasi
        const vaccinationTable = document.querySelector('.vaccination-table');
        if (vaccinationTable && typeof $ !== 'undefined' && $.fn.DataTable) {
            $(vaccinationTable).DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                },
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });
        }
    },

    // Submit rekam medis
    submitMedicalRecord: function(form) {
        const formData = new FormData(form);

        if (typeof $ !== 'undefined' && $.ajax) {
            $.ajax({
                url: form.getAttribute('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (typeof LMSApp !== 'undefined') {
                        LMSApp.showNotification('Rekam medis berhasil disimpan', 'success');
                    }
                    form.reset();
                },
                error: function(xhr) {
                    if (typeof LMSApp !== 'undefined') {
                        LMSApp.handleAjaxError(xhr);
                    }
                }
            });
        } else {
            // Fallback menggunakan fetch API
            fetch(form.getAttribute('action'), {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (typeof LMSApp !== 'undefined') {
                    LMSApp.showNotification('Rekam medis berhasil disimpan', 'success');
                }
                form.reset();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
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
    }
};

// Inisialisasi modul admin
document.addEventListener('DOMContentLoaded', function() {
    if (document.body.classList.contains('admin-layout')) {
        AdminApp.init();
    }
});

// Export untuk penggunaan modul
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AdminApp;
}
