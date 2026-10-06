@extends('layouts.app')

@section('title', 'Bài viết của tôi')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('content')
    <div class="admin-page">
        <section class="page-header admin-page-header">
            <h1>Bài viết của tôi</h1>
        </section>

        <div class="blog-list-toolbar">
            <h2>Danh sách bài viết bạn đã đăng ({{ $blogs->count() }})</h2>

            <details class="blog-create-disclosure" @if ($errors->any()) open @endif>
                <summary class="primary-btn blog-create-toggle">
                    <span class="blog-create-label-closed">+ Thêm bài viết mới</span>
                    <span class="blog-create-label-open">Đóng biểu mẫu</span>
                </summary>

                <form method="POST" action="{{ route('my-blogs.store') }}" enctype="multipart/form-data" class="blog-form">
            @csrf

            <label for="name">Tiêu đề</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}">
            @error('name')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <div class="blog-select-row">
                <div class="blog-select-field">
                    <label for="status">Trạng thái</label>
                    <select id="status" name="status" required>
                        <option value="draft" @selected(old('status', 'draft') === 'draft')>Bản nháp</option>
                        <option value="published" @selected(old('status') === 'published')>Xuất bản</option>
                    </select>
                    @error('status')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="blog-select-field">
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
                </div>
            </div>

            <fieldset class="tag-options">
                <legend>Tags</legend>

                <div class="tag-options-list">
                    @foreach ($tags as $tag)
                        <label class="tag-option" for="new-tag-{{ $tag->id }}">
                        <input
                                id="new-tag-{{ $tag->id }}"
                                class="tag-option-input"
                                type="checkbox"
                                name="tag_ids[]"
                                value="{{ $tag->id }}"
                                @checked(in_array($tag->id, old('tag_ids', [])))
                            >
                            <span>{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            @error('tag_ids')
                <p class="field-error">{{ $message }}</p>
            @enderror

            @error('tag_ids.*')
                 <p class="field-error">{{ $message }}</p>
            @enderror
            <label for="description">Nội dung</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
            @error('description')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <label for="author">Tác giả</label>
            <input id="author" name="author" type="text" value="{{ old('author', Auth::user()->name) }}">
            @error('author')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <label for="thumbnail">Ảnh bài viết</label>
            <input id="thumbnail" name="thumbnail" type="file" accept=".jpg,.jpeg,.png,.webp">
            @error('thumbnail')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <button type="submit" class="primary-btn">Đăng bài viết</button>
                </form>
            </details>
        </div>

        @forelse ($blogs as $blog)
            <article class="blog-card manage-card">
                <h3>{{ $blog->name }}</h3>
                <p>Trạng thái: <strong>{{ $blog->status === 'published' ? 'Đã xuất bản' : 'Bản nháp' }}</strong></p>

                @if ($blog->thumbnail)
                    <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->name }}" class="blog-image">
                @endif

                {{-- Form cập nhật bài viết --}}
                <form method="POST" action="{{ route('my-blogs.update', $blog) }}" enctype="multipart/form-data" class="inline-form">
                    @csrf
                    @method('PUT')

                    <label for="name-{{ $blog->id }}">Tiêu đề</label>
                    <input
                        id="name-{{ $blog->id }}"
                        name="name"
                        type="text"
                        value="{{ $blog->name }}"
                    >

                    <div class="blog-select-row">
                        <div class="blog-select-field">
                            <label for="status-{{ $blog->id }}">Trạng thái</label>
                            <select id="status-{{ $blog->id }}" name="status" required>
                                <option value="draft" @selected(old('status', $blog->status) === 'draft')>Bản nháp</option>
                                <option value="published" @selected(old('status', $blog->status) === 'published')>Xuất bản</option>
                            </select>
                        </div>

                        <div class="blog-select-field">
                            <label for="category-{{ $blog->id }}">Danh mục</label>
                            <select id="category-{{ $blog->id }}" name="category_id">
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $blog->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <fieldset class="tag-options">
                        <legend>Tags</legend>

                        <div class="tag-options-list">
                            @foreach ($tags as $tag)
                                <label class="tag-option" for="edit-blog-{{ $blog->id }}-tag-{{ $tag->id }}">
                                    <input
                                        id="edit-blog-{{ $blog->id }}-tag-{{ $tag->id }}"
                                        class="tag-option-input"
                                        type="checkbox"
                                        name="tag_ids[]"
                                        value="{{ $tag->id }}"
                                        @checked($blog->tags->contains('id', $tag->id))
                                    >
                                    <span>{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <label for="description-{{ $blog->id }}">Nội dung</label>
                    <textarea
                        id="description-{{ $blog->id }}"
                        name="description"
                    >{{ $blog->description }}</textarea>

                    <label for="author-{{ $blog->id }}">Tác giả</label>
                    <input
                        id="author-{{ $blog->id }}"
                        name="author"
                        type="text"
                        value="{{ $blog->author }}"
                    >

                    <label for="thumbnail-{{ $blog->id }}">Thay ảnh</label>
                    <input id="thumbnail-{{ $blog->id }}" name="thumbnail" type="file" accept=".jpg,.jpeg,.png,.webp">

                    <button type="submit" class="secondary-btn">Cập nhật</button>
                </form>

                {{-- Nút xóa bài viết --}}
                <form method="POST" action="{{ route('my-blogs.destroy', $blog) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');" class="delete-form">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="danger-btn">Xóa bài viết</button>
                </form>
            </article>
        @empty
            <p class="empty-state">Bạn chưa đăng bài viết nào.</p>
        @endforelse
    </div>
@endsection
