@extends('layouts.app')

@section('title', 'Danh mục & tags')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('content')
    <div class="admin-page taxonomy-page">
        <section class="page-header admin-page-header">
            <h1>Quản lý danh mục & tags</h1>
        </section>

        <div class="taxonomy-grid">
            <section class="taxonomy-section taxonomy-section--categories">
                <header class="taxonomy-section-header">
                    <h2>Danh mục</h2>
                    <span class="taxonomy-count">{{ $categories->count() }}</span>
                </header>

                <form method="POST" action="{{ route('admin.categories.store') }}" class="taxonomy-form">
                    @csrf

                    <label for="category-name">Tên danh mục</label>
                    <input
                        id="category-name"
                        name="category_name"
                        type="text"
                        value="{{ old('category_name') }}"
                        maxlength="100"
                        placeholder="Ví dụ: Công nghệ"
                        required
                    >
                    @error('category_name')
                        <p class="field-error taxonomy-form-error">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="primary-btn">Tạo danh mục</button>
                </form>

                <div class="taxonomy-search">
                    <label for="category-search">Tìm danh mục</label>
                    <input
                        id="category-search"
                        type="search"
                        data-taxonomy-search
                        placeholder="Tên hoặc slug..."
                        autocomplete="off"
                    >
                    <span aria-live="polite" data-taxonomy-result-count>{{ $categories->count() }}</span>
                </div>

                <div class="taxonomy-list">
                    @forelse ($categories as $category)
                        <article
                            class="taxonomy-row"
                            data-taxonomy-item
                            data-search-text="{{ $category->name }} {{ $category->slug }}"
                        >
                            <div class="taxonomy-item-copy">
                                <h3>{{ $category->name }}</h3>
                                <p>
                                    <span>{{ $category->slug }}</span>
                                    <span>{{ $category->blogs_count }} bài viết</span>
                                </p>
                            </div>

                            <div class="taxonomy-actions">
                                <details class="taxonomy-edit">
                                    <summary>Sửa</summary>
                                    <form
                                        method="POST"
                                        action="{{ route('admin.categories.update', $category) }}"
                                        class="taxonomy-edit-form"
                                    >
                                        @csrf
                                        @method('PUT')

                                        <label for="category-edit-{{ $category->id }}">Tên danh mục</label>
                                        <input
                                            id="category-edit-{{ $category->id }}"
                                            name="category_name_update"
                                            type="text"
                                            value="{{ $category->name }}"
                                            maxlength="100"
                                            required
                                        >
                                        @error('category_name_update')
                                            <p class="field-error">{{ $message }}</p>
                                        @enderror

                                        <button type="submit" class="taxonomy-save">Lưu</button>
                                    </form>
                                </details>

                                <form
                                    method="POST"
                                    action="{{ route('admin.categories.destroy', $category) }}"
                                    onsubmit="return confirm('Xóa danh mục này? Bài viết đang dùng danh mục sẽ chuyển thành chưa phân loại.');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="taxonomy-delete">Xóa</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <p class="taxonomy-empty">Chưa có danh mục nào.</p>
                    @endforelse
                    <p class="taxonomy-empty" data-taxonomy-no-results hidden>Không tìm thấy danh mục phù hợp.</p>
                </div>
            </section>

            <section class="taxonomy-section taxonomy-section--tags">
                <header class="taxonomy-section-header">
                    <h2>Tags</h2>
                    <span class="taxonomy-count">{{ $tags->count() }}</span>
                </header>

                <form method="POST" action="{{ route('admin.tags.store') }}" class="taxonomy-form">
                    @csrf

                    <label for="tag-name">Tên tag</label>
                    <input
                        id="tag-name"
                        name="tag_name"
                        type="text"
                        value="{{ old('tag_name') }}"
                        maxlength="100"
                        placeholder="Ví dụ: Laravel"
                        required
                    >
                    @error('tag_name')
                        <p class="field-error taxonomy-form-error">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="primary-btn">Tạo tag</button>
                </form>

                <div class="taxonomy-search">
                    <label for="tag-search">Tìm tag</label>
                    <input
                        id="tag-search"
                        type="search"
                        data-taxonomy-search
                        placeholder="Tên hoặc slug..."
                        autocomplete="off"
                    >
                    <span aria-live="polite" data-taxonomy-result-count>{{ $tags->count() }}</span>
                </div>

                <div class="taxonomy-list">
                    @forelse ($tags as $tag)
                        <article
                            class="taxonomy-row"
                            data-taxonomy-item
                            data-search-text="{{ $tag->name }} {{ $tag->slug }}"
                        >
                            <div class="taxonomy-item-copy">
                                <h3>{{ $tag->name }}</h3>
                                <p>
                                    <span>{{ $tag->slug }}</span>
                                    <span>{{ $tag->blogs_count }} bài viết</span>
                                </p>
                            </div>

                            <div class="taxonomy-actions">
                                <details class="taxonomy-edit">
                                    <summary>Sửa</summary>
                                    <form
                                        method="POST"
                                        action="{{ route('admin.tags.update', $tag) }}"
                                        class="taxonomy-edit-form"
                                    >
                                        @csrf
                                        @method('PUT')

                                        <label for="tag-edit-{{ $tag->id }}">Tên tag</label>
                                        <input
                                            id="tag-edit-{{ $tag->id }}"
                                            name="tag_name_update"
                                            type="text"
                                            value="{{ $tag->name }}"
                                            maxlength="100"
                                            required
                                        >
                                        @error('tag_name_update')
                                            <p class="field-error">{{ $message }}</p>
                                        @enderror

                                        <button type="submit" class="taxonomy-save">Lưu</button>
                                    </form>
                                </details>

                                <form
                                    method="POST"
                                    action="{{ route('admin.tags.destroy', $tag) }}"
                                    onsubmit="return confirm('Xóa tag này khỏi hệ thống? Tag sẽ được gỡ khỏi các bài viết đang dùng.');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="taxonomy-delete">Xóa</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <p class="taxonomy-empty">Chưa có tag nào.</p>
                    @endforelse
                    <p class="taxonomy-empty" data-taxonomy-no-results hidden>Không tìm thấy tag phù hợp.</p>
                </div>
            </section>
        </div>
    </div>
@endsection
