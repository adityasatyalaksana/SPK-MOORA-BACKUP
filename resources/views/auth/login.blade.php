<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SPK MOORA</title>
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #22c55e, #15803d);
            --bg-gradient: radial-gradient(circle at 50% 50%, #0f172a 0%, #064e3b 100%);
            --glass-bg: rgba(15, 23, 42, 0.6);
            --glass-border: rgba(255, 255, 255, 0.08);
            --text-muted-rgba: rgba(255, 255, 255, 0.6);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            margin: 0;
            color: #f8fafc;
        }

        /* Background Mountain Image Layer */
        .bg-mountain-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            opacity: 0.12;
            z-index: 1;
            pointer-events: none;
        }

        /* Glowing Orbs */
        .orb {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            pointer-events: none;
            opacity: 0.35;
        }
        .orb-green {
            background: #22c55e;
            top: -50px;
            right: -50px;
        }
        .orb-teal {
            background: #0d9488;
            bottom: -50px;
            left: -50px;
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .brand-logo {
            width: 64px;
            height: 64px;
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            color: #22c55e;
            font-size: 2rem;
            text-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted-rgba);
            font-size: 1.1rem;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .form-control-custom {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 13px 20px 13px 45px;
            color: #ffffff !important;
            font-size: 0.95rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .form-control-custom::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-control-custom:focus {
            background: rgba(15, 23, 42, 0.6);
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.25);
            outline: none;
        }

        .form-control-custom:focus + i {
            color: #22c55e;
        }

        .btn-login {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-weight: 600;
            padding: 13px;
            width: 100%;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.5);
            filter: brightness(1.1);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .link-back {
            color: var(--text-muted-rgba);
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }

        .link-back:hover {
            color: #ffffff;
        }

        .alert-custom {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 12px;
            font-size: 0.9rem;
            padding: 12px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Background Layers -->
<div class="bg-mountain-overlay"></div>
<div class="orb orb-green"></div>
<div class="orb orb-teal"></div>

<div class="login-container">
    <div class="glass-card text-center">
        <!-- Logo / Brand Icon -->
        <div class="brand-logo">
            <i class="bi bi-compass"></i>
        </div>
        
        <div class="mb-4">
            <h3 class="fw-bold m-0" style="letter-spacing: 1px;">Admin SPK MOORA</h3>
            <p class="small mt-1 mb-0" style="color: var(--text-muted-rgba);">Pemilihan Jalur Pendakian Gunung</p>
        </div>

        @if(session()->has('loginError'))
        <div class="alert alert-custom d-flex align-items-center text-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.1rem;"></i>
            <div>{{ session('loginError') }}</div>
        </div>
        @endif

        <form action="/login" method="POST" class="text-start">
            @csrf
            
            <!-- Username Input -->
            <div class="input-group-custom">
                <i class="bi bi-person"></i>
                <input type="text" name="username" class="form-control-custom" placeholder="Username" required autofocus>
            </div>

            <!-- Password Input -->
            <div class="input-group-custom">
                <i class="bi bi-lock"></i>
                <input type="password" name="password" class="form-control-custom" placeholder="Password" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-login mt-2">Masuk ke Dashboard</button>
        </form>
        
        <!-- Back Link -->
        <div class="mt-4">
            <a href="/" class="link-back text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

</body>
</html>