<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LMS Trimurti Husada</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1a56db;
            --primary-dark: #1e429f;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #64748b;
            --error: #dc2626;
            --success: #059669;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(120deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
            color: var(--dark);
        }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .card-header {
            border-radius: 16px 16px 0 0 !important;
            background: linear-gradient(120deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
            border: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(26, 86, 219, 0.25);
        }

        .btn-primary {
            background: linear-gradient(120deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(120deg, var(--primary-dark) 0%, var(--primary) 100%);
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            border-radius: 10px;
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .logo-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 2px solid var(--border);
        }

        .logo-wrapper img {
            max-width: 50px;
            height: auto;
        }

        .toggle-password {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border: 1px solid #ced4da;
            background-color: #f8f9fa;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .progress {
            height: 5px;
            background-color: #e9ecef;
            border-radius: 3px;
        }

        .progress-bar {
            transition: width 0.3s ease;
            border-radius: 3px;
        }

        .password-strength-text {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(26, 86, 219, 0.25);
        }

        .text-muted {
            color: var(--gray) !important;
        }

        @media (max-width: 768px) {
            .card {
                margin: 1rem 0;
            }

            .row {
                margin: 0;
            }

            .col-md-6 {
                padding: 0 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-header text-white text-center py-4">
                        <h3 class="mb-0"><i class="fas fa-hospital me-2"></i>LMS Trimurti Husada</h3>
                        <p class="mb-0">SMK Kesehatan - Daftar Akun Baru</p>
                    </div>

                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="logo-wrapper">
                                @if(file_exists(public_path('images/logo-trimurti-husada.png')))
                                    <img src="{{ asset('images/logo-trimurti-husada.png') }}" alt="Logo">
                                @elseif(file_exists(public_path('images/logo.png')))
                                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                                @else
                                    <div style="font-size: 1.5rem; font-weight: bold; color: #1a56db;">TH</div>
                                @endif
                            </div>
                            <h4 class="text-dark">Buat Akun Baru</h4>
                            <p class="text-muted">Isi data diri dengan benar sesuai status Anda</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                            @csrf

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name') }}"
                                               placeholder="Nama lengkap sesuai ijazah" required autocomplete="name" autofocus>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Alamat Email *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email') }}"
                                               placeholder="Email aktif" required autocomplete="email">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nis_nip" class="form-label">NIS/NIP *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control @error('nis_nip') is-invalid @enderror"
                                               id="nis_nip" name="nis_nip" value="{{ old('nis_nip') }}"
                                               placeholder="Nomor induk" required pattern="[0-9]+" title="Hanya angka yang diperbolehkan" autocomplete="off">
                                    </div>
                                    @error('nis_nip')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Masukkan NIS (Siswa) atau NIP (Guru)</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password" placeholder="Min. 8 karakter" required autocomplete="new-password">
                                        <button type="button" class="btn btn-outline-secondary toggle-password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="password-strength">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="password-strength-text"></small>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password_confirmation"
                                               name="password_confirmation" placeholder="Ulangi password" required autocomplete="new-password">
                                        <button type="button" class="btn btn-outline-secondary toggle-password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="password-match-feedback" class="form-text"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">Peran Pengguna *</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="" disabled selected>Pilih peran</option>
                                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                        <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3" id="kelas-field" style="{{ old('role') == 'siswa' ? 'display: block;' : 'display: none;' }}">
                                    <label for="kelas" class="form-label">Kelas (Siswa)</label>
                                    <select class="form-select @error('kelas') is-invalid @enderror" id="kelas" name="kelas" {{ old('role') == 'siswa' ? 'required' : '' }}>
                                        <option value="" selected>Pilih kelas</option>
                                        <option value="X" {{ old('kelas') == 'X' ? 'selected' : '' }}>Kelas X</option>
                                        <option value="XI" {{ old('kelas') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                        <option value="XII" {{ old('kelas') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                                    </select>
                                    @error('kelas')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Hanya untuk siswa</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                           id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" autocomplete="bday">
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" autocomplete="sex">
                                        <option value="" selected>Pilih jenis kelamin</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                          name="alamat" rows="2" placeholder="Alamat lengkap" autocomplete="street-address">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror"
                                       id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="terms">
                                    Saya menyetujui <a href="#" class="text-decoration-none">Syarat & Ketentuan</a> dan <a href="#" class="text-decoration-none">Kebijakan Privasi</a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Daftar Akun
                                </button>
                            </div>

                            <div class="text-center">
                                <p class="mb-0">Sudah punya akun?
                                    <a href="{{ route('login') }}" class="text-decoration-none">Masuk di sini</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <p class="text-muted">&copy; {{ date('Y') }} SMK Kesehatan Trimurti Husada Ambon. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const toggleButtons = document.querySelectorAll('.toggle-password');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

            // Password strength meter
            const passwordInput = document.getElementById('password');
            const strengthBar = document.querySelector('.progress-bar');
            const strengthText = document.querySelector('.password-strength-text');

            if (passwordInput && strengthBar && strengthText) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;
                    let message = '';

                    // Check password strength
                    if (password.length >= 8) strength += 1;
                    if (password.match(/[a-z]+/)) strength += 1;
                    if (password.match(/[A-Z]+/)) strength += 1;
                    if (password.match(/[0-9]+/)) strength += 1;
                    if (password.match(/[!@#$%^&*(),.?":{}|<>]+/)) strength += 1;

                    // Update progress bar
                    const width = (strength / 5) * 100;
                    strengthBar.style.width = width + '%';

                    // Update text and color
                    switch (strength) {
                        case 0:
                        case 1:
                            strengthBar.className = 'progress-bar bg-danger';
                            message = 'Sangat lemah';
                            break;
                        case 2:
                            strengthBar.className = 'progress-bar bg-warning';
                            message = 'Lemah';
                            break;
                        case 3:
                            strengthBar.className = 'progress-bar bg-info';
                            message = 'Sedang';
                            break;
                        case 4:
                            strengthBar.className = 'progress-bar bg-primary';
                            message = 'Kuat';
                            break;
                        case 5:
                            strengthBar.className = 'progress-bar bg-success';
                            message = 'Sangat kuat';
                            break;
                    }

                    strengthText.textContent = message;
                });
            }

            // Password confirmation check
            const passwordConfirm = document.getElementById('password_confirmation');
            const passwordMatchFeedback = document.getElementById('password-match-feedback');

            if (passwordConfirm && passwordMatchFeedback) {
                passwordConfirm.addEventListener('input', function() {
                    const password = passwordInput.value;
                    const confirmPassword = this.value;

                    if (confirmPassword === '') {
                        passwordMatchFeedback.textContent = '';
                        passwordMatchFeedback.className = 'form-text';
                    } else if (password === confirmPassword) {
                        passwordMatchFeedback.textContent = '✓ Password cocok';
                        passwordMatchFeedback.className = 'form-text text-success';
                    } else {
                        passwordMatchFeedback.textContent = '✗ Password tidak cocok';
                        passwordMatchFeedback.className = 'form-text text-danger';
                    }
                });
            }

            // Show/hide kelas field based on role
            const roleSelect = document.getElementById('role');
            const kelasField = document.getElementById('kelas-field');
            const kelasSelect = document.getElementById('kelas');

            if (roleSelect && kelasField && kelasSelect) {
                roleSelect.addEventListener('change', function() {
                    if (this.value === 'siswa') {
                        kelasField.style.display = 'block';
                        kelasSelect.setAttribute('required', 'required');
                    } else {
                        kelasField.style.display = 'none';
                        kelasSelect.removeAttribute('required');
                        kelasSelect.value = '';
                    }
                });

                // Trigger change on load to set initial state
                roleSelect.dispatchEvent(new Event('change'));
            }

            // Form validation
            const form = document.getElementById('registerForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    let isValid = true;

                    // Basic validation
                    const requiredFields = form.querySelectorAll('[required]');
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('is-invalid');
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });

                    // Password match validation
                    if (passwordInput.value !== passwordConfirm.value) {
                        isValid = false;
                        passwordConfirm.classList.add('is-invalid');
                    } else {
                        passwordConfirm.classList.remove('is-invalid');
                    }

                    if (!isValid) {
                        e.preventDefault();
                        // Scroll to first error
                        const firstError = form.querySelector('.is-invalid');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
