<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | LMS Trimurti Husada</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a56db;
            --primary-dark: #1e429f;
            --secondary: #7e22ce;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #64748b;
            --error: #dc2626;
            --border: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
        }

        .error-container {
            text-align: center;
            background: white;
            padding: 3rem 2rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 900;
            color: var(--error);
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .error-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.1rem;
            color: var(--gray);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 86, 219, 0.3);
        }

        .btn-secondary {
            background: var(--light);
            color: var(--dark);
            border: 2px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--border);
            transform: translateY(-2px);
        }

        .error-icon {
            font-size: 4rem;
            color: var(--error);
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .contact-info {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
            font-size: 0.9rem;
            color: var(--gray);
        }

        @media (max-width: 480px) {
            .error-container {
                padding: 2rem 1rem;
            }
            
            .error-code {
                font-size: 6rem;
            }
            
            .error-title {
                font-size: 1.5rem;
            }
            
            .error-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        
        <div class="error-code">500</div>
        
        <h1 class="error-title">Server Error</h1>
        
        <p class="error-message">
            Maaf, terjadi kesalahan pada server. Tim kami telah diberitahu tentang masalah ini 
            dan sedang berusaha memperbaikinya. Silakan coba lagi dalam beberapa saat.
        </p>
        
        <div class="error-actions">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
            
            <a href="{{ route('welcome') }}" class="btn btn-primary">
                <i class="fas fa-home"></i>
                Beranda
            </a>
        </div>
        
        <div class="contact-info">
            <p>Jika masalah berlanjut, silakan hubungi administrator sistem.</p>
        </div>
    </div>
</body>
</html>
