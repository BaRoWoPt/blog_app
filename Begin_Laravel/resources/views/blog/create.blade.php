@extends('layouts.app')

@section('title', 'Thêm bài viết')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('content')
    <div class="admin-page">
        <section class="page-header admin-page-header">
            <h1>Thêm bài viết mới</h1>
        </section>

        <form method="POST" action="{{ route('blogs.store') }}" enctype="multipart/form-data" class="blog-form">
            @csrf

            <label for="title">Tiêu đề</label>
            <input id="title" name="title" type="text" value="{{ old('title') }}">

            <label for="content">Nội dung</label>
            <textarea id="content" name="content">{{ old('content') }}</textarea>

            <label for="author">Tác giả</label>
            <input id="author" name="author" type="text" value="{{ old('author') }}">

            <label for="image">Ảnh bài viết</label>
            <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp">

            <button type="submit" class="primary-btn">Thêm bài viết</button>
        </form>
    </div>
@endsection
