<h1>Quản lý blog</h1>

<form method="POST" action="{{ route('blogs.store') }}">
    @csrf

    <label for="title">Tiêu đề</label>
    <input id="title" name="title" type="text" value="{{ old('title') }}">

    <label for="content">Nội dung</label>
    <textarea id="content" name="content">{{ old('content') }}</textarea>

    <label for="author">Tác giả</label>
    <input id="author" name="author" type="text" value="{{ old('author') }}">

    <button type="submit">Thêm bài viết</button>
</form>

<h2>Danh sách bài viết</h2>

@forelse ($blogs as $blog)
    <article>
        <h3>{{ $blog->title }}</h3>

        <form method="POST" action="{{ route('blogs.update', $blog) }}">
            @csrf
            @method('PUT')

            <label for="title-{{ $blog->id }}">Tiêu đề</label>
            <input
                id="title-{{ $blog->id }}"
                name="title"
                type="text"
                value="{{ $blog->title }}"
            >

            <label for="content-{{ $blog->id }}">Nội dung</label>
            <textarea
                id="content-{{ $blog->id }}"
                name="content"
            >{{ $blog->content }}</textarea>

            <label for="author-{{ $blog->id }}">Tác giả</label>
            <input
                id="author-{{ $blog->id }}"
                name="author"
                type="text"
                value="{{ $blog->author }}"
            >

            <button type="submit">Cập nhật</button>
        </form>

        <!-- Delete-Optional -->
        <form method="POST" action="{{ route('blogs.destroy', $blog) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');" style="margin-top: 10px;">
            @csrf
            @method('DELETE')

            <button type="submit" style="background-color: red; color: white;">Xóa bài viết</button>
        </form>
    </article>
@empty
    <p>Chưa có bài viết nào.</p>
@endforelse

