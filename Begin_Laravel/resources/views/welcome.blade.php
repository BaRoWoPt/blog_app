@extends('layouts.app')

@section('title', 'Bài viết')

@push('styles')
	@vite(['resources/css/home.css'])
@endpush

@section('content')
	<div class="home-page">
		<section class="home-page-header">
			<h1>Bài viết mới nhất</h1>
			@auth
				<a href="{{ route('my-blogs.index') }}" class="primary-btn">Bài viết của tôi</a>
			@else
				<a href="{{ route('login') }}" class="primary-btn">Đăng bài viết</a>
			@endauth
		</section>

		@forelse ($blogs as $blog)
			<article class="blog-card">
				<h2>
                    <a href="{{ route('blog.show', $blog) }}">{{ $blog->name }}</a>
                </h2>
                @if ($blog->category)
                    <span class="category-badge">{{ $blog->category->name }}</span>
                @endif
                @if ($blog->thumbnail)
                    <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->name }}" class="blog-image">
                @endif

                <p>{{ Str::limit($blog->description, 150) }}</p>
                <small>
                    {{ $blog->author ?: 'Ẩn danh' }}
                    · {{ $blog->created_at?->format('d/m/Y') }}
                </small>
            </article>
		@empty
			<p class="empty-state">Chưa có bài viết nào.</p>
		@endforelse

		{{ $blogs->links() }}
	</div>
@endsection
