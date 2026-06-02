<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SPK MOORA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{url('/')}}/storage/assets/gunung/bootsrap.css">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-4">
            <div class="card card-custom p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-success">Admin Login</h3>
                    <p class="text-muted">Khusus Pengelola Data Gunung</p>
                </div>

                @if(session()->has('loginError'))
                <div class="alert alert-danger">{{ session('loginError') }}</div>
                @endif

                <form action="/login" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100 py-2 mt-2">Login Sekarang</button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="/" class="text-decoration-none text-muted small">← Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>