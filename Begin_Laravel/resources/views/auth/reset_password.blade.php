@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu')

@section('content')
    <div class="admin-page">
        <h1>Đặt lại mật khẩu</h1>

        @if ($errors->any())
            <p class="empty-state">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="blog-form">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <label for="email">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ $email }}"
                required
            >

            <label for="password">Mật khẩu mới</label>
            <input id="password" name="password" type="password" required>

            <label for="password_confirmation">Xác nhận mật khẩu</label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
            >

            <button type="submit" class="primary-btn">
                Đặt lại mật khẩu
            </button>
        </form>
    </div>
@endsection
