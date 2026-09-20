@extends('layouts.app')

@section('title', 'Sửa bài viết')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('content')
    <div class="admin-page">
        <section class="page-header admin-page-header">
            <h1>Sửa bài viết</h1>
        </section>

        <form method="POST" action="{{ route('blogs.update', $blog) }}" enctype="multipart/form-data" class="blog-form">
            @csrf
            @method('PUT')

            <label for="title">Tiêu đề</label>
            <input id="title" name="title" type="text" value="{{ old('title', $blog->title) }}">

            <label for="content">Nội dung</label>
            <textarea id="content" name="content">{{ old('content', $blog->content) }}</textarea>

            <label for="author">Tác giả</label>
            <input id="author" name="author" type="text" value="{{ old('author', $blog->author) }}">

            <label for="image">Thay ảnh</label>
            <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp">

            <button type="submit" class="secondary-btn">Cập nhật</button>
        </form>
    </div>
@endsection
