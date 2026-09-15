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
                <span class="logo">Z</span>
                <span class="brand-text">ZIGExN</span>
            </a>

            <nav class="main-nav" aria-label="Điều hướng chính">
                <a href="{{ url('/') }}">Trang chủ</a>
                <a href="{{ route('blogs.manage') }}">Quản lý blog</a>
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
