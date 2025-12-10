<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem RT Digital - Warga')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ url('/css/custom.css') }}" rel="stylesheet">
    
    @yield('styles')
</head>
<body>
    <!-- Navigation Warga -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-rt">
        <div class="container">
            <a class="navbar-brand" href="/dashboard/warga">
                <i class="bi bi-house-heart"></i> 
                <strong>Sistem RT Digital</strong>
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/dashboard/warga">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link" href="/aspirasi">
                    <i class="bi bi-chat-dots"></i> Aspirasi
                </a>
                <a class="nav-link" href="/voting">
                    <i class="bi bi-check2-square"></i> Voting
                </a>
                <span class="navbar-text me-3">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </span>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container mt-4">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 footer-fixed">
        <div class="container text-center">
            <small>&copy; 2025 Sistem Aspirasi & Voting RT. Selamat berpartisipasi!</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>