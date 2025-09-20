@extends('layouts.app')

@section('title', 'Login - LMS Trimurti Husada')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card auth-card">
                <div class="auth-header">
                    <h3 class="fw-bold mb-2">Selamat Datang Kembali</h3>
                    <p class="mb-0">Masuk ke akun Anda untuk melanjutkan</p>
                </div>
                <div class="card-body p-4 p-md-5"

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }

        .login-card.loaded {
            opacity: 1;
            transform: translateY(0);
        }

        .login-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-5px);
        }

        .card-header {
            background: linear-gradient(120deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 2.5rem 2rem;
            text-align: center;
            color: white;
            position: relative;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.1) 50%, rgba(255, 255, 255, 0.1) 75%, transparent 75%, transparent);
            background-size: 10px 10px;
            opacity: 0.1;
        }

        .logo-container {
            position: relative;
            z-index: 2;
        }

        .logo-wrapper {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            backdrop-filter: blur(4px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .logo-wrapper img {
            width: 60px;
            height: auto;
            object-fit: contain;
        }

        .logo-placeholder {
            font-size: 2rem;
            color: white;
            font-weight: bold;
        }

        .card-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .card-header p {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .card-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            z-index: 1;
        }

        .select-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            z-index: 1;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            z-index: 2;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 1rem;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.2s;
            background: var(--light);
            font-weight: 500;
        }

        .form-select {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.2s;
            background: var(--light);
            font-weight: 500;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.15);
        }

        .form-select:focus + .select-icon {
            color: var(--primary);
        }

        .select-arrow {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            pointer-events: none;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.15);
        }

        .form-input:focus + .input-icon {
            color: var(--primary);
        }

        .form-input.error {
            border-color: var(--error);
        }

        .form-input.success {
            border-color: var(--success);
        }

        .form-select.error {
            border-color: var(--error);
        }

        .error-message {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--error);
            background: rgba(220, 38, 38, 0.05);
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            border-left: 3px solid var(--error);
            animation: fadeIn 0.3s ease;
        }

        .success-message {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--success);
            background: rgba(5, 150, 105, 0.05);
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            border-left: 3px solid var(--success);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.5rem 0;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
        }

        .custom-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid var(--border);
            border-radius: 4px;
            background: white;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }

        .custom-checkbox:checked {
            background: var(--primary);
            border-color: var(--primary);
        }

        .custom-checkbox:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 12px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .checkbox-label {
            margin-left: 0.5rem;
            font-size: 0.9rem;
            color: var(--dark);
            font-weight: 500;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s;
            cursor: pointer;
            background: none;
            border: none;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(120deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px rgba(26, 86, 219, 0.2);
            position: relative;
        }

        .login-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(26, 86, 219, 0.3);
        }

        .login-button:active:not(:disabled) {
            transform: translateY(0);
        }

        .login-button:disabled {
            opacity: 0.8;
            cursor: not-allowed;
        }

        .button-loading .button-text {
            visibility: hidden;
            opacity: 0;
        }

        .button-loading::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            border: 3px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: button-loading-spinner 1s ease infinite;
        }

        @keyframes button-loading-spinner {
            from {
                transform: rotate(0turn);
            }
            to {
                transform: rotate(1turn);
            }
        }

        .footer {
            text-align: center;
            margin-top: 2rem;
            color: var(--gray);
            font-size: 0.875rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Status messages */
        .status-message {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: fadeIn 0.5s ease;
        }

        .status-error {
            background-color: rgba(220, 38, 38, 0.1);
            color: var(--error);
            border-left: 4px solid var(--error);
        }

        .status-success {
            background-color: rgba(5, 150, 105, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        /* Register link */
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }

        .register-link p {
            margin-bottom: 0.5rem;
            color: var(--gray);
        }

        /* Responsiveness */
        @media (max-width: 480px) {
            .login-card {
                border-radius: 12px;
            }

            .card-header {
                padding: 2rem 1.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .forgot-link {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="card-header">
                <div class="logo-container">
                    <div class="logo-wrapper">
                        @if(file_exists(public_path('images/logo-trimurti-husada.png')))
                            <img src="{{ asset('images/logo-trimurti-husada.png') }}" alt="Logo SMK Trimurti Husada">
                        @elseif(file_exists(public_path('images/logo.png')))
                            <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Trimurti Husada">
                        @else
                            <div class="logo-placeholder">TH</div>
                        @endif
                    </div>
                    <h1>Sistem Manajemen Pembelajaran</h1>
                    <p>SMK Kesehatan Trimurti Husada Ambon</p>
                </div>
            </div>

            <div class="card-body">
                <!-- Status Messages -->
                @if (session('status'))
                    <div class="status-message status-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="status-message status-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="status-message status-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Terjadi kesalahan dalam pengisian form</span>
                    </div>
                @endif

                <form class="login-form" action="{{ route('login') }}" method="POST" novalidate>
                    @csrf

                    <!-- Role Selection Dropdown -->
                    <div class="form-group">
                        <div class="input-with-icon">
                            <i class="select-icon fas fa-user-tag"></i>
                            <select id="role" name="role" class="form-select @error('role') error @enderror" required aria-describedby="role-error">
                                <option value="" disabled selected>Pilih peran pengguna</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            </select>
                            <i class="select-arrow fas fa-chevron-down"></i>
                        </div>
                        @error('role')
                            <div class="error-message" id="role-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-with-icon">
                            <i class="input-icon fas fa-envelope"></i>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   class="form-input @error('email') error @enderror @if(old('email') && !$errors->has('email')) success @endif"
                                   placeholder="Masukkan alamat email"
                                   value="{{ old('email') }}"
                                   aria-describedby="email-error">
                        </div>
                        @error('email')
                            <div class="error-message" id="email-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-with-icon">
                            <i class="input-icon fas fa-lock"></i>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   class="form-input @error('password') error @enderror"
                                   placeholder="Masukkan kata sandi"
                                   aria-describedby="password-error">
                            <button type="button" class="password-toggle" id="password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-message" id="password-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="remember-forgot">
                        <div class="checkbox-container">
                            <input id="remember-me" name="remember" type="checkbox" class="custom-checkbox" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember-me" class="checkbox-label">Ingat saya</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa kata sandi?</a>
                    </div>

                    <button type="submit" class="login-button" id="login-button">
                        <span class="button-text">
                            <i class="fas fa-sign-in-alt"></i>
                            Masuk
                        </span>
                    </button>
                </form>

                <div class="register-link">
                    <p>Belum punya akun?</p>
                    <a href="{{ route('register') }}" class="forgot-link">Daftar di sini</a>
                </div>

                <div class="footer">
                    <p>&copy; {{ date('Y') }} LMS Trimurti Husada. All rights reserved.</p>
                    <p class="mt-1">Versi 1.0</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi saat halaman dimuat
            const loginCard = document.querySelector('.login-card');
            if (loginCard) {
                setTimeout(function() {
                    loginCard.classList.add('loaded');
                }, 100);
            }

            // Toggle password visibility
            const passwordToggle = document.getElementById('password-toggle');
            const passwordInput = document.getElementById('password');

            if (passwordToggle && passwordInput) {
                passwordToggle.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    // Toggle icon
                    const icon = this.querySelector('i');
                    if (type === 'password') {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    } else {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    }
                });
            }

            // Form validation and submission handling
            const loginForm = document.querySelector('.login-form');
            const loginButton = document.getElementById('login-button');

            if (loginForm && loginButton) {
                loginForm.addEventListener('submit', function(e) {
                    // Basic validation
                    const role = document.getElementById('role');
                    const email = document.getElementById('email');
                    const password = document.getElementById('password');
                    let isValid = true;

                    // Reset error classes
                    role.classList.remove('error');
                    email.classList.remove('error');
                    password.classList.remove('error');

                    // Remove existing error messages
                    document.querySelectorAll('.error-message').forEach(function(el) {
                        if (!el.id || (el.id !== 'role-error' && el.id !== 'email-error' && el.id !== 'password-error')) {
                            el.remove();
                        }
                    });

                    if (!role.value) {
                        role.classList.add('error');
                        showError(role, 'Pilih peran pengguna terlebih dahulu');
                        isValid = false;
                    }

                    if (!email.value) {
                        email.classList.add('error');
                        showError(email, 'Email harus diisi');
                        isValid = false;
                    } else if (!isValidEmail(email.value)) {
                        email.classList.add('error');
                        showError(email, 'Format email tidak valid');
                        isValid = false;
                    }

                    if (!password.value) {
                        password.classList.add('error');
                        showError(password, 'Kata sandi harus diisi');
                        isValid = false;
                    } else if (password.value.length < 6) {
                        password.classList.add('error');
                        showError(password, 'Kata sandi minimal 6 karakter');
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                        // Focus on first error field
                        const firstError = document.querySelector('.error');
                        if (firstError) firstError.focus();
                        return;
                    }

                    // Show loading state
                    loginButton.disabled = true;
                    loginButton.classList.add('button-loading');
                });

                // Real-time validation
                const inputs = loginForm.querySelectorAll('input[required], select[required]');
                inputs.forEach(function(input) {
                    input.addEventListener('input', function() {
                        validateField(this);
                    });

                    // For select element, trigger change event
                    if (input.tagName === 'SELECT') {
                        input.addEventListener('change', function() {
                            validateField(this);
                        });
                    }
                });
            }

            // Auto-hide status messages after 5 seconds
            const statusMessages = document.querySelectorAll('.status-message');
            if (statusMessages.length > 0) {
                statusMessages.forEach(function(message) {
                    setTimeout(function() {
                        message.style.opacity = '0';
                        message.style.transition = 'opacity 0.5s ease';
                        setTimeout(function() {
                            message.remove();
                        }, 500);
                    }, 5000);
                });
            }

            // Helper functions
            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            function showError(input, message) {
                // Remove existing error message for this input
                const formGroup = input.closest('.form-group');
                const existingError = formGroup.querySelector('.error-message:not([id])');
                if (existingError) {
                    existingError.remove();
                }

                // Create error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>' + message + '</span>';

                // Insert after input container
                input.closest('.input-with-icon').parentNode.appendChild(errorDiv);
            }

            function validateField(field) {
                const formGroup = field.closest('.form-group');
                const existingError = formGroup.querySelector('.error-message:not([id])');

                if (existingError) {
                    existingError.remove();
                }

                if (field.value) {
                    if (field.type === 'email' && !isValidEmail(field.value)) {
                        field.classList.remove('success');
                        field.classList.add('error');
                    } else {
                        field.classList.remove('error');
                        field.classList.add('success');
                    }
                } else {
                    field.classList.remove('success');
                    field.classList.remove('error');
                }
            }
        });
    </script>
</body>
</html>
