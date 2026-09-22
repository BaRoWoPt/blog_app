<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Blog')</title>
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ url('/') }}" class="brand" aria-label="Trang chủ blog">
                <img class="logo" src="https://zigexn.vn/images/logo.svg" alt="ZIGExN">
            </a>

            <nav class="main-nav" aria-label="Điều hướng chính">
                <a href="{{ url('/') }}">Trang chủ</a>

                @auth
                    <span class="user-greeting">
                        Xin chào, <strong>{{ Auth::user()->name }}</strong>
                        (Vai trò: <em>{{ Auth::user()->role }}</em>)
                    </span>

                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('blogs.index') }}" class="admin-link">Trang quản trị</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Đăng nhập</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container page-content">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            © ZIGExN VeNtura@2025
        </div>
    </footer>
</body>
</html>
