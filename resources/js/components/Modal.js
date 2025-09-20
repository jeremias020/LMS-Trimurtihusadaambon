// Komponen Modal untuk LMS SMK Kesehatan Trimurti Husada Ambon

class LMSModal {
    constructor(modalId, options = {}) {
        this.modalId = modalId;
        this.options = options;
        this.modalElement = document.getElementById(modalId);
        this.modalInstance = null;
        this.eventHandlers = {};
        this.init();
    }

    init() {
        if (!this.modalElement) {
            console.error(`Modal dengan ID ${this.modalId} tidak ditemukan.`);
            return;
        }

        // Periksa apakah Bootstrap tersedia
        if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
            console.error('Bootstrap Modal tidak ditemukan. Pastikan Bootstrap 5 telah dimuat.');
            return;
        }

        try {
            this.modalInstance = new bootstrap.Modal(this.modalElement, this.options);

            // Event handlers
            this.setupEventListeners();
        } catch (error) {
            console.error('Error initializing modal:', error);
        }
    }

    setupEventListeners() {
        // Simpan reference untuk dapat dihapus nanti
        this.eventHandlers.show = (e) => {
            if (typeof this.options.onShow === 'function') {
                this.options.onShow(e);
            }
        };

        this.eventHandlers.shown = (e) => {
            this.focusFirstInput();
            if (typeof this.options.onShown === 'function') {
                this.options.onShown(e);
            }
        };

        this.eventHandlers.hide = (e) => {
            if (typeof this.options.onHide === 'function') {
                this.options.onHide(e);
            }
        };

        this.eventHandlers.hidden = (e) => {
            this.clearForm();
            if (typeof this.options.onHidden === 'function') {
                this.options.onHidden(e);
            }
        };

        // Attach event listeners
        this.modalElement.addEventListener('show.bs.modal', this.eventHandlers.show);
        this.modalElement.addEventListener('shown.bs.modal', this.eventHandlers.shown);
        this.modalElement.addEventListener('hide.bs.modal', this.eventHandlers.hide);
        this.modalElement.addEventListener('hidden.bs.modal', this.eventHandlers.hidden);
    }

    show() {
        if (this.modalInstance) {
            this.modalInstance.show();
        }
    }

    hide() {
        if (this.modalInstance) {
            this.modalInstance.hide();
        }
    }

    toggle() {
        if (this.modalInstance) {
            this.modalInstance.toggle();
        }
    }

    dispose() {
        if (this.modalInstance) {
            // Hapus event listeners
            this.removeEventListeners();

            // Dispose Bootstrap modal instance
            this.modalInstance.dispose();
            this.modalInstance = null;
        }
    }

    removeEventListeners() {
        if (this.modalElement) {
            this.modalElement.removeEventListener('show.bs.modal', this.eventHandlers.show);
            this.modalElement.removeEventListener('shown.bs.modal', this.eventHandlers.shown);
            this.modalElement.removeEventListener('hide.bs.modal', this.eventHandlers.hide);
            this.modalElement.removeEventListener('hidden.bs.modal', this.eventHandlers.hidden);
        }
    }

    focusFirstInput() {
        try {
            const focusableElements = this.modalElement.querySelectorAll(
                'input:not([type="hidden"]):not([disabled]), textarea:not([disabled]), select:not([disabled]), button:not([disabled]), [tabindex]:not([tabindex="-1"]):not([disabled])'
            );

            if (focusableElements.length > 0) {
                setTimeout(() => {
                    focusableElements[0].focus();
                }, 100);
            }
        } catch (error) {
            console.error('Error focusing first input:', error);
        }
    }

    clearForm() {
        try {
            const form = this.modalElement.querySelector('form');
            if (form) {
                form.reset();

                // Clear validation states
                form.classList.remove('was-validated');
                form.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                // Hapus pesan error tambahan
                form.querySelectorAll('.invalid-feedback').forEach(el => {
                    if (!el.hasAttribute('data-bs-original')) {
                        el.remove();
                    }
                });
            }
        } catch (error) {
            console.error('Error clearing form:', error);
        }
    }

    setContent(content) {
        try {
            const modalBody = this.modalElement.querySelector('.modal-body');
            if (modalBody) {
                modalBody.innerHTML = content;
            }
        } catch (error) {
            console.error('Error setting modal content:', error);
        }
    }

    setTitle(title) {
        try {
            const modalTitle = this.modalElement.querySelector('.modal-title');
            if (modalTitle) {
                modalTitle.textContent = title;
            }
        } catch (error) {
            console.error('Error setting modal title:', error);
        }
    }

    setSize(size) {
        try {
            const validSizes = ['sm', 'lg', 'xl'];
            const modalDialog = this.modalElement.querySelector('.modal-dialog');

            if (modalDialog && validSizes.includes(size)) {
                // Hapus semua kelas size sebelumnya
                modalDialog.classList.remove('modal-sm', 'modal-lg', 'modal-xl');
                // Tambahkan kelas size baru
                modalDialog.classList.add(`modal-${size}`);
            }
        } catch (error) {
            console.error('Error setting modal size:', error);
        }
    }

    setTheme(theme) {
        try {
            if (theme === 'health') {
                this.modalElement.classList.add('modal-health');
            } else {
                this.modalElement.classList.remove('modal-health');
            }
        } catch (error) {
            console.error('Error setting modal theme:', error);
        }
    }

    // Static method untuk membuat modal konfirmasi
    static confirm(options = {}) {
        return new Promise((resolve) => {
            const modalId = 'confirmModal_' + Date.now();
            let modalElement = document.getElementById(modalId);

            // Hapus modal yang sudah ada jika ada
            if (modalElement) {
                modalElement.remove();
            }

            modalElement = document.createElement('div');
            modalElement.innerHTML = `
                <div class="modal fade" id="${modalId}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${options.title || 'Konfirmasi'}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>${options.message || 'Apakah Anda yakin?'}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${options.cancelText || 'Batal'}</button>
                                <button type="button" class="btn btn-primary" id="confirmButton">${options.confirmText || 'Ya'}</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modalElement);

            const modal = new bootstrap.Modal(modalElement);
            const confirmButton = modalElement.querySelector('#confirmButton');

            // Event handlers
            const handleConfirm = () => {
                modal.hide();
                resolve(true);
            };

            const handleHide = () => {
                confirmButton.removeEventListener('click', handleConfirm);
                modalElement.removeEventListener('hidden.bs.modal', handleHide);

                // Hapus modal dari DOM setelah ditutup
                setTimeout(() => {
                    if (modalElement && modalElement.parentNode) {
                        modalElement.remove();
                    }
                }, 500);

                resolve(false);
            };

            confirmButton.addEventListener('click', handleConfirm);
            modalElement.addEventListener('hidden.bs.modal', handleHide);

            modal.show();
        });
    }

    // Static method untuk membuat modal alert
    static alert(options = {}) {
        return new Promise((resolve) => {
            const modalId = 'alertModal_' + Date.now();
            let modalElement = document.createElement('div');
            modalElement.innerHTML = `
                <div class="modal fade" id="${modalId}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${options.title || 'Informasi'}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>${options.message || ''}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modalElement);

            const modal = new bootstrap.Modal(modalElement);

            modalElement.addEventListener('hidden.bs.modal', () => {
                setTimeout(() => {
                    if (modalElement && modalElement.parentNode) {
                        modalElement.remove();
                    }
                }, 500);
                resolve(true);
            });

            modal.show();
        });
    }
}

// Export untuk penggunaan global
if (typeof window !== 'undefined') {
    window.LMSModal = LMSModal;
}

// Export untuk modul ES6
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LMSModal;
}
