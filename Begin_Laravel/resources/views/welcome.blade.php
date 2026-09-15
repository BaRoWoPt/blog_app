@extends('layouts.app')

@section('title', 'Trang chủ blog')

@push('styles')
    @vite(['resources/css/home.css'])
@endpush

@section('content')
    <div class="home-page">
        <section class="page-header home-page-header">
            <h1>Danh sách bài viết</h1>
            <a href="{{ route('blogs.manage') }}" class="primary-btn">Thêm bài viết mới</a>
        </section>

        @forelse ($blogs as $blog)
            <article class="blog-card">
                <h2>{{ $blog->title }}</h2>
                <p>{{ $blog->content }}</p>

                @if ($blog->author)
                    <small>Tác giả: {{ $blog->author }}</small>
                @endif
            </article>
        @empty
            <p class="empty-state">Chưa có bài viết nào.</p>
        @endforelse
    </div>
@endsection