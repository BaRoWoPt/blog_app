@extends('layouts.app')

@section('title', $blog->name)

@section('content')
        <article class="blog-card blog-detail">
        {{-- Nút quay lại trang chủ --}}
        <a href="{{ route('home') }}" class="back-link">← Quay lại danh sách</a>

        <h1>{{ $blog->name }}</h1>

        <div class="blog-meta">
            @if ($blog->category)
                <span class="category-badge">{{ $blog->category->name }}</span>
            @endif
            @if ($blog->tags->isNotEmpty())
                <div class="blog-tags">
                    @foreach ($blog->tags as $tag)
                        <span class="tag-badge">{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif
            <span class="blog-author">Tác giả: {{ $blog->author ?: 'Ẩn danh' }}</span>
            ·
            <time>{{ $blog->created_at?->format('d/m/Y H:i') }}</time>
        </div>

        @if ($blog->thumbnail)
            <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->name }}" class="blog-image detail-image">
        @endif

        <div class="blog-content">
            {{-- Dùng nl2br và e() để giữ nguyên các dòng xuống hàng của bài viết --}}
            {!! nl2br(e($blog->description)) !!}
        </div>

    </article>
    {{-- Khối danh sách và form bình luận --}}
<section class="comments-section" style="margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 20px;">
    <h2>Bình luận ({{ $blog->comments->count() }})</h2>

    {{-- Form gửi comment --}}
    @auth
        <form action="{{ route('comments.store', $blog) }}" method="POST" style="margin-bottom: 25px;">
            @csrf
            <div style="margin-bottom: 10px;">
                <textarea name="content" rows="3" placeholder="Viết bình luận của bạn..." required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">{{ old('content') }}</textarea>
                @error('content')
                    <p style="color: red; font-size: 0.875rem;">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="primary-btn">Gửi bình luận</button>
        </form>
    @else
        <p style="background: #f3f4f6; padding: 12px; border-radius: 6px;">
            Vui lòng <a href="{{ route('login') }}" style="color: #2563eb; text-decoration: underline;">đăng nhập</a> để tham gia bình luận.
        </p>
    @endauth

    {{-- Danh sách comment --}}
    <div class="comments-list">
        @forelse ($blog->comments as $comment)
            <div class="comment-item" style="border-bottom: 1px solid #f1f5f9; padding: 12px 0;">
                <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 4px;">
                    <strong>{{ $comment->user->name }}</strong>
                    · <time>{{ $comment->created_at->diffForHumans() }}</time>
                </div>
                <div style="color: #1f2937;">
                    {{ $comment->content }}
                </div>
            </div>
        @empty
            <p style="color: #6b7280; font-style: italic;">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
        @endforelse
    </div>
</section>
@endsection
