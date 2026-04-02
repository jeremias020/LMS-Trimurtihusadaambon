<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateAuthConfigCommand extends Command
{
    protected $signature = 'update:auth-config';
    protected $description = 'Update authentication configuration for modern user system';

    public function handle()
    {
        $this->info('=== UPDATING AUTHENTICATION CONFIGURATION ===');
        
        try {
            // Update config/auth.php
            $this->updateAuthConfig();
            
            // Create login controller examples
            $this->createLoginExamples();
            
            $this->info('✅ Authentication configuration updated!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
    
    private function updateAuthConfig()
    {
        $authConfig = config_path('auth.php');
        
        if (!File::exists($authConfig)) {
            $this->error('❌ auth.php config file not found');
            return;
        }
        
        $configContent = File::get($authConfig);
        
        // Update providers section
        $newProviders = <<<'PHP'
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        
        'users_central' => [
            'driver' => 'eloquent',
            'model' => App\Models\UserCentral::class,
        ],
    ],
PHP;
        
        // Replace providers section
        $pattern = "/    'providers' => \[.*?\],/s";
        $configContent = preg_replace($pattern, $newProviders, $configContent);
        
        File::put($authConfig, $configContent);
        $this->line('✅ Updated auth.php configuration');
    }
    
    private function createLoginExamples()
    {
        $this->line('📝 Creating login examples...');
        
        $webLoginExample = <<<'PHP'
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserCentral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect based on role
            return match($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'guru' => redirect()->route('guru.dashboard'),
                'siswa' => redirect()->route('siswa.dashboard'),
                default => redirect()->route('home')
            };
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
PHP;
        
        File::put(app_path('Http/Controllers/Auth/LoginController.php'), $webLoginExample);
        $this->line('✅ Created LoginController example');
        
        // Create login blade example
        $loginView = <<<'BLADE'
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
BLADE;
        
        if (!File::exists(resource_path('views/auth'))) {
            File::makeDirectory(resource_path('views/auth'));
        }
        
        File::put(resource_path('views/auth/login.blade.php'), $loginView);
        $this->line('✅ Created login view example');
    }
}
