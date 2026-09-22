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

            <label for="name">Tiêu đề</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}">
            @error('name')
                <p class="field-error">{{ $message }}</p>
            @enderror
            <label for="category_id">Danh mục</label>
            <select id="category_id" name="category_id">
                <option value="">Chọn danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="field-error">{{ $message }}</p>
            @enderror
            <label for="description">Nội dung</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
            @error('description')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <label for="author">Tác giả</label>
            <input id="author" name="author" type="text" value="{{ old('author') }}">

            <label for="thumbnail">Ảnh bài viết</label>
            <input id="thumbnail" name="thumbnail" type="file" accept=".jpg,.jpeg,.png,.webp">
            @error('thumbnail')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <button type="submit" class="primary-btn">Thêm bài viết</button>
        </form>
    </div>
@endsection
