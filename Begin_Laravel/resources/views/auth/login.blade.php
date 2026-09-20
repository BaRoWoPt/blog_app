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
    </div>
@endsection
