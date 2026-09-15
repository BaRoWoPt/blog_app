@extends('layouts.app')

@section('title', 'Quản lý blog')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('content')
    <div class="admin-page">
        <section class="page-header admin-page-header">
            <h1>Quản lý blog</h1>
        </section>

        <form method="POST" action="{{ route('blogs.store') }}" class="blog-form">
            @csrf

            <label for="title">Tiêu đề</label>
            <input id="title" name="title" type="text" value="{{ old('title') }}">

            <label for="content">Nội dung</label>
            <textarea id="content" name="content">{{ old('content') }}</textarea>

            <label for="author">Tác giả</label>
            <input id="author" name="author" type="text" value="{{ old('author') }}">

            <button type="submit" class="primary-btn">Thêm bài viết</button>
        </form>

        <h2>Danh sách bài viết</h2>

        @forelse ($blogs as $blog)
            <article class="blog-card manage-card">
                <h3>{{ $blog->title }}</h3>

                <form method="POST" action="{{ route('blogs.update', $blog) }}" class="inline-form">
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

                    <button type="submit" class="secondary-btn">Cập nhật</button>
                </form>

                <form method="POST" action="{{ route('blogs.destroy', $blog) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');" class="delete-form">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="danger-btn">Xóa bài viết</button>
                </form>
            </article>
        @empty
            <p class="empty-state">Chưa có bài viết nào.</p>
        @endforelse
    </div>
@endsection
