@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
    <div class="admin-page">
        <h1>Quên mật khẩu</h1>

        @if ($errors->any())
            <p class="empty-state">{{ $errors->first() }}</p>
        @endif

        @if (session('success'))
            <p class="success-message">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="blog-form">
            @csrf

            <label for="email">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
            >

            <button type="submit" class="primary-btn">
                Gửi link đặt lại mật khẩu
            </button>
        </form>
    </div>
@endsection
