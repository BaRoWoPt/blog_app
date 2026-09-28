@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
    <div class="admin-page">
        <h1>Đăng nhập</h1>

        @if ($errors->any())
            <p class="empty-state">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}" class="blog-form">
            @csrf

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}">

            <label for="password">Mật khẩu</label>
            <input id="password" name="password" type="password">

            <button type="submit" class="primary-btn">Đăng nhập</button>
        </form>
        <a href="{{ route('password.request') }}">
    Quên mật khẩu?
</a>
        <p style="margin-top: 16px; text-align: center; color: #4b5563;">
            Chưa có tài khoản? <a href="{{ route('signup') }}" style="color: #2563eb; text-decoration: underline;">Đăng ký ngay</a>
        </p>
    </div>
@endsection
