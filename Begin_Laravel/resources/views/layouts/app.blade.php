<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Blog')</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('logo.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

                    <a href="{{ route('my-blogs.index') }}">Bài viết của tôi</a>

                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.blogs.index') }}" class="admin-link">Quản trị bài viết (Admin)</a>
                        <a href="{{ route('admin.tags.index') }}" class="admin-link">Danh mục & tags</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Đăng nhập</a>
                    <a href="{{ route('signup') }}">Đăng ký</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container page-content">
        @if (session('success'))
            <div style="background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #a7f3d0;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            © ZIGExN VeNtura@2026
        </div>
    </footer>
</body>
</html>
