// JavaScript Khusus untuk Siswa LMS

const SiswaApp = {
    init: function() {
        this.initMaterialDownload();
        this.initAssignmentSubmission();
        this.initPracticalView();
        this.initScoreView();
        this.initAttendanceView();
        console.log('Siswa module initialized');
    },

    // Download materi
    initMaterialDownload: function() {
        $('.download-material-btn').on('click', function(e) {
            e.preventDefault();
            const materialId = $(this).data('material-id');
            const materialTitle = $(this).data('material-title');
            
            // Track download
            $.ajax({
                url: `/siswa/materials/${materialId}/track-download`,
                method: 'POST',
                success: function() {
                    console.log(`Download material ${materialTitle} tracked`);
                }
            });
            
            // Redirect to download
            window.location.href = $(this).attr('href');
        });
    },

    // Pengumpulan tugas
    initAssignmentSubmission: function() {
        // Preview file sebelum upload
        $('#submission_file').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileSize = LMSApp.formatFileSize(file.size);
                $('#submission-file-name').text(file.name);
                $('#submission-file-size').text(fileSize);
                $('#submission-file-preview').removeClass('d-none');
                
                // Validasi ukuran file (max 10MB)
                const maxSize = 10 * 1024 * 1024; // 10MB
                if (file.size > maxSize) {
                    LMSApp.showNotification('Ukuran file terlalu besar. Maksimal 10MB.', 'danger');
                    $(this).val('');
                    $('#submission-file-preview').addClass('d-none');
                }
            }
        });

        // Countdown timer untuk batas waktu tugas
        this.initAssignmentCountdown();
    },

    // Countdown timer untuk batas waktu tugas
    initAssignmentCountdown: function() {
        $('.assignment-countdown').each(function() {
            const dueDate = $(this).data('due-date');
            const countdownElement = $(this);
            
            function updateCountdown() {
                const now = moment();
                const due = moment(dueDate);
                const diff = due.diff(now);
                
                if (diff <= 0) {
                    countdownElement.html('<span class="text-danger">Waktu telah habis</span>');
                    return;
                }
                
                const duration = moment.duration(diff);
                const days = duration.days();
                const hours = duration.hours();
                const minutes = duration.minutes();
                
                let countdownText = '';
                if (days > 0) countdownText += `${days} hari `;
                if (hours > 0) countdownText += `${hours} jam `;
                countdownText += `${minutes} menit`;
                
                countdownElement.text(countdownText);
            }
            
            updateCountdown();
            setInterval(updateCountdown, 60000); // Update setiap menit
        });
    },

    // View praktikum
    initPracticalView: function() {
        // Filter praktikum
        $('#practical-filter').on('change', function() {
            const filter = $(this).val();
            window.location.href = `${window.location.pathname}?filter=${filter}`;
        });

        // Modal detail praktikum
        $('.practical-detail-btn').on('click', function() {
            const practicalId = $(this).data('practical-id');
            $.ajax({
                url: `/siswa/praktikum/${practicalId}/detail`,
                method: 'GET',
                success: function(response) {
                    $('#practicalDetailModal .modal-body').html(response);
                    $('#practicalDetailModal').modal('show');
                },
                error: function(xhr) {
                    LMSApp.handleAjaxError(xhr);
                }
            });
        });
    },

    // View nilai
    initScoreView: function() {
        // Filter nilai
        $('#score-filter').on('change', function() {
            const filter = $(this).val();
            window.location.href = `${window.location.pathname}?filter=${filter}`;
        });

        // Chart nilai
        this.initScoreCharts();
    },

    // Inisialisasi chart nilai
    initScoreCharts: function() {
        if (document.getElementById('scoreChart')) {
            const ctx = document.getElementById('scoreChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Kimia', 'Biologi', 'Fisika'],
                    datasets: [{
                        label: 'Nilai Rata-rata',
                        data: [85, 78, 82, 88, 90, 79],
                        backgroundColor: 'rgba(78, 115, 223, 0.5)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }

        if (document.getElementById('progressChart')) {
            const ctx = document.getElementById('progressChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5', 'Minggu 6'],
                    datasets: [{
                        label: 'Perkembangan Nilai',
                        data: [75, 78, 82, 85, 88, 90],
                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                        borderColor: 'rgba(28, 200, 138, 1)',
                        pointBackgroundColor: 'rgba(28, 200, 138, 1)',
                        pointBorderColor: '#fff',
                        tension: 0.3
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }
    },

    // View absensi
    initAttendanceView: function() {
        // Filter absensi
        $('#attendance-filter').on('change', function() {
            const month = $(this).val();
            window.location.href = `${window.location.pathname}?month=${month}`;
        });

        // Chart absensi
        this.initAttendanceCharts();
    },

    // Inisialisasi chart absensi
    initAttendanceCharts: function() {
        if (document.getElementById('attendanceChart')) {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
                    datasets: [{
                        data: [20, 2, 1, 1],
                        backgroundColor: [
                            'rgba(28, 200, 138, 0.8)',
                            'rgba(54, 185, 204, 0.8)',
                            'rgba(246, 194, 62, 0.8)',
                            'rgba(231, 74, 59, 0.8)'
                        ],
                        borderColor: [
                            'rgba(28, 200, 138, 1)',
                            'rgba(54, 185, 204, 1)',
                            'rgba(246, 194, 62, 1)',
                            'rgba(231, 74, 59, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
};

// Inisialisasi modul siswa
document.addEventListener('DOMContentLoaded', function() {
    if ($('body').hasClass('siswa-layout')) {
        SiswaApp.init();
    }
});