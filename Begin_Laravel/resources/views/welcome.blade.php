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
                <a href="{{ route('my-blogs.index') }}" class="primary-btn">
                    Bài viết của tôi
                </a>
            @else
                <a href="{{ route('login') }}" class="primary-btn">
                    Đăng bài viết
                </a>
            @endauth
        </section>

        <form method="GET" action="{{ route('home') }}" class="category-filter">
            <div class="category-filter-control">
                <label for="category">Danh mục bài viết</label>
                <select id="category" name="category">
                    <option value="">Tất cả danh mục</option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->slug }}"
                            @selected($categorySlug === $category->slug)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="category-filter-actions">
                <button type="submit" class="primary-btn">Lọc bài viết</button>

                @if ($categorySlug)
                    <a href="{{ route('home') }}" class="category-filter-reset">Xóa lọc</a>
                @endif
            </div>

            @error('category')
                <p class="field-error category-filter-error">{{ $message }}</p>
            @enderror
        </form>
        @forelse ($blogs as $blog)
            <article class="blog-card">
                <h2>
                    <a href="{{ route('blog.show', $blog) }}">
                        {{ $blog->name }}
                    </a>
                </h2>

                @if ($blog->category)
                    <span class="category-badge">
                        {{ $blog->category->name }}
                    </span>
                @endif

                @if ($blog->tags->isNotEmpty())
                    <div class="blog-tags">
                        @foreach ($blog->tags as $tag)
                            <span class="tag-badge">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                @if ($blog->thumbnail)
                    <img
                        src="{{ asset('storage/' . $blog->thumbnail) }}"
                        alt="{{ $blog->name }}"
                        class="blog-image"
                    >
                @endif

                <p>{{ Str::limit($blog->description, 150) }}</p>

                <small>
                    {{ $blog->author ?: 'Ẩn danh' }}
                    · {{ $blog->created_at?->format('d/m/Y') }}
                </small>
            </article>
        @empty
            <p class="empty-state">Không tìm thấy bài viết trong danh mục này.</p>
        @endforelse

        {{ $blogs->links() }}
    </div>
@endsection
