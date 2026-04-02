<!DOCTYPE html>
<html>
<head>
    <title>Login - LMS Trimurti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Login LMS Trimurti</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <strong>Test Credentials:</strong><br>
                        Admin: admin@lms-trimurti.sch.id / password<br>
                        Guru: siti@lms-trimurti.sch.id / password<br>
                        Siswa: agus.setiawan@lms-trimurti.sch.id / password
                    </small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>