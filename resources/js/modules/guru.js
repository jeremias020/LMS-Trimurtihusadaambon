// JavaScript Khusus untuk Guru LMS

const GuruApp = {
    init: function() {
        this.initMaterialManagement();
        this.initAssignmentManagement();
        this.initPracticalManagement();
        this.initScoringSystem();
        this.initAttendanceSystem();
        console.log('Guru module initialized');
    },

    // Manajemen materi pembelajaran
    initMaterialManagement: function() {
        // Preview file sebelum upload
        $('#material_file').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileSize = LMSApp.formatFileSize(file.size);
                $('#file-name').text(file.name);
                $('#file-size').text(fileSize);
                $('#file-preview').removeClass('d-none');
            }
        });

        // Editor teks untuk deskripsi materi
        if ($('#material_description').length) {
            ClassicEditor
                .create(document.querySelector('#material_description'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                        ]
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        }
    },

    // Manajemen tugas
    initAssignmentManagement: function() {
        // Datepicker untuk batas waktu tugas
        $('#due_date').daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm',
                applyLabel: 'Pilih',
                cancelLabel: 'Batal',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
            }
        });

        // Multiple file upload untuk lampiran tugas
        $('#assignment_attachments').on('change', function(e) {
            const files = e.target.files;
            if (files.length > 0) {
                $('#attachments-preview').empty();
                Array.from(files).forEach(file => {
                    const fileSize = LMSApp.formatFileSize(file.size);
                    $('#attachments-preview').append(`
                        <div class="alert alert-light d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file me-2"></i>
                                ${file.name} <small class="text-muted">(${fileSize})</small>
                            </div>
                            <button type="button" class="btn-close" onclick="$(this).parent().remove()"></button>
                        </div>
                    `);
                });
            }
        });
    },

    // Manajemen praktikum
    initPracticalManagement: function() {
        // Datepicker untuk jadwal praktikum
        $('#practical_date').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD',
                applyLabel: 'Pilih',
                cancelLabel: 'Batal',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
            }
        });

        // Timepicker untuk jam praktikum
        $('#start_time, #end_time').timepicker({
            timeFormat: 'HH:mm',
            interval: 30,
            minTime: '06:00',
            maxTime: '20:00',
            defaultTime: '08:00',
            startTime: '06:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
    },

    // Sistem penilaian
    initScoringSystem: function() {
        // Auto-calculate total score
        $('.score-input').on('input', function() {
            this.calculateTotalScore();
        });

        // Bulk scoring
        $('.bulk-score-btn').on('click', function() {
            const score = $('#bulk_score').val();
            if (!score) {
                LMSApp.showNotification('Masukkan nilai terlebih dahulu', 'warning');
                return;
            }

            $('.score-input').val(score).trigger('input');
            LMSApp.showNotification('Nilai berhasil diterapkan ke semua siswa', 'success');
        });
    },

    // Calculate total score
    calculateTotalScore: function() {
        let total = 0;
        let count = 0;
        
        $('.score-input').each(function() {
            const score = parseFloat($(this).val()) || 0;
            total += score;
            count++;
        });
        
        const average = count > 0 ? (total / count).toFixed(2) : 0;
        $('#average-score').text(average);
        $('#total-students').text(count);
    },

    // Sistem absensi
    initAttendanceSystem: function() {
        // Bulk attendance
        $('.attendance-bulk-select').on('change', function() {
            const status = $(this).val();
            $(`.attendance-select`).val(status);
        });

        // Datepicker untuk absensi
        $('#attendance_date').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD',
                applyLabel: 'Pilih',
                cancelLabel: 'Batal',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
            }
        });

        // Attendance statistics
        this.calculateAttendanceStats();
    },

    // Calculate attendance statistics
    calculateAttendanceStats: function() {
        let present = 0;
        let absent = 0;
        let permission = 0;
        let sick = 0;
        let total = 0;
        
        $('.attendance-select').each(function() {
            const status = $(this).val();
            switch(status) {
                case 'hadir': present++; break;
                case 'alpha': absent++; break;
                case 'izin': permission++; break;
                case 'sakit': sick++; break;
            }
            total++;
        });
        
        $('#present-count').text(present);
        $('#absent-count').text(absent);
        $('#permission-count').text(permission);
        $('#sick-count').text(sick);
        $('#total-count').text(total);
        
        // Update progress bars
        this.updateProgressBar('#present-progress', present, total);
        this.updateProgressBar('#absent-progress', absent, total);
        this.updateProgressBar('#permission-progress', permission, total);
        this.updateProgressBar('#sick-progress', sick, total);
    },

    // Update progress bar
    updateProgressBar: function(selector, value, total) {
        const percentage = total > 0 ? (value / total) * 100 : 0;
        $(selector).css('width', `${percentage}%`).attr('aria-valuenow', percentage);
        $(`${selector} span`).text(`${value} (${percentage.toFixed(1)}%)`);
    }
};

// Inisialisasi modul guru
document.addEventListener('DOMContentLoaded', function() {
    if ($('body').hasClass('guru-layout')) {
        GuruApp.init();
    }
});