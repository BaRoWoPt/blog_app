@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
    <div class="admin-page">
        <h1>Đăng ký tài khoản</h1>

        @if ($errors->any())
            <p class="empty-state">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('signup') }}" class="blog-form">
            @csrf

            <label for="name">Họ và tên</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}">

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}">

            <label for="password">Mật khẩu</label>
            <input id="password" name="password" type="password">

            <label for="password_confirmation">Xác nhận mật khẩu</label>
            <input id="password_confirmation" name="password_confirmation" type="password">

            <button type="submit" class="primary-btn">Đăng ký</button>
        </form>

        <p style="margin-top: 16px; text-align: center; color: #4b5563;">
            Đã có tài khoản? <a href="{{ route('login') }}" style="color: #2563eb; text-decoration: underline;">Đăng nhập ngay</a>
        </p>
    </div>
@endsection
