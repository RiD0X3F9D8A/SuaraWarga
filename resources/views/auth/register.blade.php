<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Warga - Sistem RT Digital</title>
    
   <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        .login-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--bg-body);
            background-image: radial-gradient(var(--primary-light) 0.5px, transparent 0.5px), radial-gradient(var(--primary-light) 0.5px, var(--bg-body) 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.1;
            z-index: -1;
        }
    </style>
</head>
<body class="login-page position-relative">
    <div class="login-bg-pattern"></div>
    
    <div class="login-card-wrapper p-4" style="max-width: 550px;">
        <div class="text-center mb-4">
            <div class="brand-logo-login fs-2">
                <i class="bi bi-house-heart-fill"></i>
            </div>
            <h3 class="fw-bold text-primary-custom" style="font-family: var(--font-heading);">Pendaftaran Warga</h3>
            <p class="text-muted">Lengkapi data diri untuk bergabung</p>
        </div>
        
        <div class="card shadow-lg border-0 overflow-hidden">
            <div class="card-body p-4 p-md-5">
                @if($errors->any())
                    <div class="alert alert-danger bg-danger-subtle text-danger border-0 mb-4 rounded-3">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="/register" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="name" class="form-label text-muted small text-uppercase fw-bold ls-1">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control bg-light" placeholder="Sesuai KTP" required value="{{ old('name') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label text-muted small text-uppercase fw-bold ls-1">Alamat Email</label>
                        <input type="email" name="email" class="form-control bg-light" placeholder="nama@email.com" required value="{{ old('email') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label text-muted small text-uppercase fw-bold ls-1">Password</label>
                            <input type="password" name="password" class="form-control bg-light" placeholder="Min. 6 karakter" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label text-muted small text-uppercase fw-bold ls-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control bg-light" placeholder="Ulangi password" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nik" class="form-label text-muted small text-uppercase fw-bold ls-1">NIK (Opsional)</label>
                        <input type="text" name="nik" class="form-control bg-light" placeholder="Nomor Induk Kependudukan" value="{{ old('nik') }}">
                    </div>

                    <div class="mb-4">
                        <label for="alamat" class="form-label text-muted small text-uppercase fw-bold ls-1">Alamat Domisili (Opsional)</label>
                        <textarea name="alamat" class="form-control bg-light" rows="2" placeholder="Alamat lengkap...">{{ old('alamat') }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-3 fs-5 fw-bold shadow-sm">
                        Daftar Sekarang <i class="bi bi-person-plus ms-2"></i>
                    </button>
                    
                </form>
            </div>
            <div class="card-footer bg-light text-center py-3 border-top-0">
                <small class="text-muted">Sudah punya akun? <a href="/login" class="fw-bold text-primary-custom">Masuk disini</a></small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>