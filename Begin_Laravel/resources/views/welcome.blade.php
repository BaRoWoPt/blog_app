<h1>Danh sách bài viết</h1>

@forelse ($blogs as $blog)
    <article>
        <h2>{{ $blog->title }}</h2>
        <p>{{ $blog->content }}</p>

        @if ($blog->author)
            <small>Tác giả: {{ $blog->author }}</small>
        @endif
    </article>
@empty
    <p>Chưa có bài viết nào.</p>
@endforelse