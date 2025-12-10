<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem RT Digital')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CUSTOM CSS UTAMA -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    
    @yield('styles')
</head>
<body>
    <!-- Navigation - PAKAI CLASS STANDARD -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-system">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-house-heart"></i> 
                <strong>SuaraWarga</strong>
            </a>
            
            <div class="navbar-nav ms-auto">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a class="nav-link {{ request()->is('dashboard/admin') ? 'active' : '' }}" href="/dashboard/admin">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->is('voting/manage*') ? 'active' : '' }}" href="/voting/manage">
                            <i class="bi bi-gear"></i> Kelola Voting
                        </a>
                        <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="/users">
                            <i class="bi bi-people"></i> Kelola User
                        </a>
                    @else
                        <a class="nav-link {{ request()->is('dashboard/warga') ? 'active' : '' }}" href="/dashboard/warga">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @endif
                    <a class="nav-link {{ request()->is('aspirasi*') ? 'active' : '' }}" href="/aspirasi">
                        <i class="bi bi-chat-dots"></i> Aspirasi
                    </a>
                    <a class="nav-link {{ request()->is('voting') ? 'active' : '' }}" href="/voting">
                        <i class="bi bi-check2-square"></i> Voting
                    </a>
                    <a href="{{ route('profile.edit') }}" class="nav-link text-light me-2">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <form action="/logout" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                @else
                    <a class="nav-link {{ request()->is('aspirasi') ? 'active' : '' }}" href="/aspirasi">
                        <i class="bi bi-chat-dots"></i> Aspirasi
                    </a>
                    <a class="nav-link {{ request()->is('voting') ? 'active' : '' }}" href="/voting">
                        <i class="bi bi-check2-square"></i> Voting
                    </a>
                    <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" href="/login">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                @endauth
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
    <footer class="footer-system">
        <div class="container text-center">
            <small>&copy; 2025 Sistem Aspirasi & Voting RT. All rights reserved.</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>