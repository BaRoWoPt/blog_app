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

        <form method="POST" action="{{ route('my-blogs.update', $blog) }}" enctype="multipart/form-data" class="blog-form">
            @csrf
            @method('PUT')

            <label for="name">Tiêu đề</label>
            <input id="name" name="name" type="text" value="{{ old('name', $blog->name) }}">
            @error('name')
                <p class="field-error">{{ $message }}</p>
            @enderror
            <fieldset class="tag-options">
    <legend>Tags</legend>

    @foreach ($tags as $tag)
        <label for="blog-{{ $blog->id }}-tag-{{ $tag->id }}">
            <input
                id="blog-{{ $blog->id }}-tag-{{ $tag->id }}"
                type="checkbox"
                name="tag_ids[]"
                value="{{ $tag->id }}"
                @checked(in_array(
                    $tag->id,
                    old('tag_ids', $blog->tags->modelKeys())
                ))
            >
            {{ $tag->name }}
        </label>
    @endforeach
</fieldset>
            <label for="description">Nội dung</label>
            <textarea id="description" name="description">{{ old('description', $blog->description) }}</textarea>
            @error('description')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <label for="author">Tác giả</label>
            <input id="author" name="author" type="text" value="{{ old('author', $blog->author) }}">

            <label for="thumbnail">Thay ảnh</label>
            <input id="thumbnail" name="thumbnail" type="file" accept=".jpg,.jpeg,.png,.webp">
            @error('thumbnail')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <button type="submit" class="secondary-btn">Cập nhật</button>
        </form>
    </div>
@endsection
