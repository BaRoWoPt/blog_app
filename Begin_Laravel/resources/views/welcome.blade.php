@extends('layouts.app')

@section('title', 'Bài viết')

@push('styles')
	@vite(['resources/css/home.css'])
@endpush

@section('content')
	<div class="home-page">
		<section class="home-page-header">
			<h1>Bài viết mới nhất</h1>
			<a href="{{ route('blogs.manage') }}" class="primary-btn">Quản lý blog</a>
		</section>

		@forelse ($blogs as $blog)
			<article class="blog-card">
				<h2>{{ $blog->title }}</h2>

				@if ($blog->image_path)
					<img src="{{ asset('storage/' . $blog->image_path) }}" alt="{{ $blog->title }}" class="blog-image">
				@endif

				<p>{{ $blog->content }}</p>
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
