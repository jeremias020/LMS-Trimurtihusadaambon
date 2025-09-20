// Komponen File Upload untuk LMS SMK Kesehatan Trimurti Husada Ambon

class LMSFileUpload {
    constructor(inputElement, options = {}) {
        this.inputElement = inputElement;
        this.options = {
            maxSize: options.maxSize || 10 * 1024 * 1024, // 10MB default
            allowedTypes: options.allowedTypes || ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'xls', 'xlsx'],
            previewContainer: options.previewContainer || null,
            onError: options.onError || null,
            onSuccess: options.onSuccess || null,
            onProgress: options.onProgress || null,
            maxFiles: options.maxFiles || 5,
            browseButton: options.browseButton || null
        };
        this.uploadedFiles = [];
        this.objectUrls = []; // Untuk menyimpan object URLs untuk di-revoke nanti
        this.init();
    }

    init() {
        if (!this.inputElement) {
            console.error('Element input tidak ditemukan.');
            return;
        }

        // Set multiple attribute jika maxFiles > 1
        if (this.options.maxFiles > 1) {
            this.inputElement.setAttribute('multiple', 'multiple');
        }

        // Hubungkan browse button jika ada
        if (this.options.browseButton) {
            this.options.browseButton.addEventListener('click', () => {
                this.inputElement.click();
            });
        }

        this.inputElement.addEventListener('change', (e) => {
            this.handleFileSelect(e);
        });

        // Drag and drop support
        if (this.options.previewContainer) {
            this.setupDragAndDrop();
        }
    }

    handleFileSelect(event) {
        const files = event.target.files;
        if (!files || !files.length) return;

        // Cek jumlah file
        if (this.uploadedFiles.length + files.length > this.options.maxFiles) {
            this.showError(`Maksimal ${this.options.maxFiles} file yang diizinkan.`);
            this.inputElement.value = ''; // Reset input
            return;
        }

        Array.from(files).forEach(file => {
            if (!this.validateFile(file)) {
                return;
            }

            this.previewFile(file);
            this.uploadedFiles.push(file);

            if (typeof this.options.onSuccess === 'function') {
                this.options.onSuccess(file);
            }
        });

        // Update UI
        this.updateFileCount();
        this.updateUploadButton();
    }

    validateFile(file) {
        // Validate file type
        if (this.options.allowedTypes.length > 0) {
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!this.options.allowedTypes.includes(fileExtension)) {
                this.showError(`Tipe file "${fileExtension}" tidak diizinkan. Gunakan: ${this.options.allowedTypes.join(', ')}`);
                return false;
            }
        }

        // Validate file size
        if (file.size > this.options.maxSize) {
            const maxSizeMB = this.options.maxSize / (1024 * 1024);
            this.showError(`Ukuran file "${file.name}" terlalu besar (${this.formatFileSize(file.size)}). Maksimal: ${maxSizeMB}MB`);
            return false;
        }

        return true;
    }

    previewFile(file) {
        if (!this.options.previewContainer) return;

        const previewElement = this.createPreviewElement(file);

        // Hapus instruksi default jika ada
        const defaultContent = this.options.previewContainer.querySelector('.upload-instructions');
        if (defaultContent) {
            defaultContent.remove();
        }

        this.options.previewContainer.appendChild(previewElement);
    }

    createPreviewElement(file) {
        const fileSize = this.formatFileSize(file.size);
        const isImage = file.type.startsWith('image/');
        const objectUrl = URL.createObjectURL(file);
        this.objectUrls.push(objectUrl); // Simpan untuk di-revoke nanti

        const fileExtension = file.name.split('.').pop().toLowerCase();
        const iconClass = this.getFileIconClass(fileExtension);

        const preview = document.createElement('div');
        preview.className = 'file-preview-item';
        preview.dataset.fileName = file.name;
        preview.innerHTML = `
            <div class="card">
                <div class="card-body py-2">
                    <div class="d-flex align-items-center">
                        ${isImage ? `
                            <img src="${objectUrl}" class="file-preview-image me-3" alt="${file.name}">
                        ` : `
                            <i class="${iconClass} file-icon me-3"></i>
                        `}
                        <div class="flex-grow-1">
                            <h6 class="mb-1 text-truncate">${file.name}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="file-size">${fileSize}</small>
                                <span class="badge bg-success file-status">Siap</span>
                            </div>
                            <div class="progress d-none">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" aria-label="Hapus"></button>
                    </div>
                </div>
            </div>
        `;

        // Remove button handler
        const removeBtn = preview.querySelector('.btn-close');
        removeBtn.addEventListener('click', () => {
            // Hapus object URL untuk mencegah kebocoran memori
            if (isImage) {
                URL.revokeObjectURL(objectUrl);
                this.objectUrls = this.objectUrls.filter(url => url !== objectUrl);
            }

            preview.remove();
            this.removeFileFromInput(file);
            this.updateFileCount();
            this.updateUploadButton();

            // Jika tidak ada file lagi, tampilkan pesan default
            if (this.options.previewContainer.querySelectorAll('.file-preview-item').length === 0) {
                this.showDefaultContent();
            }
        });

        return preview;
    }

    getFileIconClass(extension) {
        const iconMap = {
            'pdf': 'fas fa-file-pdf text-danger',
            'doc': 'fas fa-file-word text-primary',
            'docx': 'fas fa-file-word text-primary',
            'ppt': 'fas fa-file-powerpoint text-warning',
            'pptx': 'fas fa-file-powerpoint text-warning',
            'xls': 'fas fa-file-excel text-success',
            'xlsx': 'fas fa-file-excel text-success',
            'jpg': 'fas fa-file-image text-info',
            'jpeg': 'fas fa-file-image text-info',
            'png': 'fas fa-file-image text-info',
            'zip': 'fas fa-file-archive text-secondary',
            'rar': 'fas fa-file-archive text-secondary'
        };

        return iconMap[extension] || 'fas fa-file text-secondary';
    }

    removeFileFromInput(fileToRemove) {
        // Hapus file dari uploadedFiles
        this.uploadedFiles = this.uploadedFiles.filter(file => file !== fileToRemove);

        // Buat DataTransfer dengan file yang tersisa
        const dataTransfer = new DataTransfer();
        this.uploadedFiles.forEach(file => {
            try {
                dataTransfer.items.add(file);
            } catch (error) {
                console.error('Error adding file to DataTransfer:', error);
            }
        });

        this.inputElement.files = dataTransfer.files;
    }

    setupDragAndDrop() {
        const container = this.options.previewContainer;

        container.addEventListener('dragover', (e) => {
            e.preventDefault();
            container.classList.add('drag-over');
        });

        container.addEventListener('dragleave', (e) => {
            e.preventDefault();
            // Only remove class if not dragging over child elements
            if (e.currentTarget === container) {
                container.classList.remove('drag-over');
            }
        });

        container.addEventListener('drop', (e) => {
            e.preventDefault();
            container.classList.remove('drag-over');

            const files = e.dataTransfer.files;
            if (files && files.length) {
                // Buat DataTransfer untuk menangani multiple files
                const dataTransfer = new DataTransfer();

                // Tambahkan file yang sudah ada
                this.uploadedFiles.forEach(file => {
                    try {
                        dataTransfer.items.add(file);
                    } catch (error) {
                        console.error('Error adding file to DataTransfer:', error);
                    }
                });

                // Tambahkan file baru (maksimal sesuai maxFiles)
                const filesToAdd = Array.from(files).slice(0, this.options.maxFiles - this.uploadedFiles.length);
                filesToAdd.forEach(file => {
                    try {
                        dataTransfer.items.add(file);
                    } catch (error) {
                        console.error('Error adding file to DataTransfer:', error);
                    }
                });

                this.inputElement.files = dataTransfer.files;
                this.inputElement.dispatchEvent(new Event('change'));
            }
        });
    }

    showDefaultContent() {
        if (this.options.previewContainer) {
            this.options.previewContainer.innerHTML = `
                <div class="upload-instructions text-center py-4">
                    <i class="fas fa-cloud-upload-alt upload-icon mb-3 text-muted" style="font-size: 3rem;"></i>
                    <p class="mb-1 text-muted">Tarik dan lepas file di sini</p>
                    <p class="text-muted small">atau</p>
                    <button type="button" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-folder-open me-2"></i>Jelajahi File
                    </button>
                </div>
            `;

            // Re-attach event listener to the new button
            const newBrowseButton = this.options.previewContainer.querySelector('button');
            if (newBrowseButton && this.options.browseButton) {
                newBrowseButton.addEventListener('click', () => {
                    this.inputElement.click();
                });
            }
        }
    }

    updateFileCount() {
        const fileCountElement = document.getElementById('fileCount');
        if (fileCountElement) {
            fileCountElement.textContent = this.uploadedFiles.length;
        }
    }

    updateUploadButton() {
        const uploadButton = document.getElementById('uploadButton');
        if (uploadButton) {
            uploadButton.disabled = this.uploadedFiles.length === 0;
        }
    }

    showError(message) {
        if (typeof this.options.onError === 'function') {
            this.options.onError(message);
        } else {
            // Buat notifikasi Bootstrap fallback
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show mt-3';
            alert.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            // Sisipkan setelah preview container atau di body
            if (this.options.previewContainer && this.options.previewContainer.parentNode) {
                this.options.previewContainer.parentNode.insertBefore(alert, this.options.previewContainer.nextSibling);
            } else {
                document.body.appendChild(alert);
            }

            // Hapus otomatis setelah 5 detik
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Static method untuk upload file dengan AJAX
    static upload(url, formData, options = {}) {
        return new Promise((resolve, reject) => {
            // Gunakan fetch API sebagai fallback jika jQuery tidak tersedia
            if (typeof $ !== 'undefined' && $.ajax) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        const xhr = new window.XMLHttpRequest();

                        // Upload progress
                        if (options.onProgress) {
                            xhr.upload.addEventListener('progress', function(e) {
                                if (e.lengthComputable) {
                                    const percent = Math.round((e.loaded / e.total) * 100);
                                    options.onProgress(percent);
                                }
                            }, false);
                        }

                        return xhr;
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            } else {
                // Fallback menggunakan fetch API
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => resolve(data))
                .catch(error => reject(error));
            }
        });
    }

    // Bersihkan object URLs untuk mencegah kebocoran memori
    cleanup() {
        this.objectUrls.forEach(url => {
            try {
                URL.revokeObjectURL(url);
            } catch (error) {
                console.error('Error revoking object URL:', error);
            }
        });
        this.objectUrls = [];
    }
}

// Export untuk penggunaan global
if (typeof window !== 'undefined') {
    window.LMSFileUpload = LMSFileUpload;
}

// Export untuk modul ES6
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LMSFileUpload;
}
