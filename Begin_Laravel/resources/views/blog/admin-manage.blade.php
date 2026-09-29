@extends('layouts.app')

@section('title', 'Quản trị bài viết (Admin)')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('content')
    <div class="admin-page">
        <section class="page-header admin-page-header">
            <h1>Quản trị tất cả bài viết trong hệ thống</h1>
        </section>

        <h2>Tổng số bài viết: {{ $blogs->count() }}</h2>

        @forelse ($blogs as $blog)
            <article class="blog-card manage-card" style="margin-bottom: 24px; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fff;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">
                    <div style="flex: 1;">
                        <h3 style="margin-top: 0; margin-bottom: 10px;">
                            <a href="{{ route('blog.show', $blog) }}" target="_blank" style="color: #1f2937; text-decoration: none;">
                                {{ $blog->name }} ↗
                            </a>
                        </h3>

                        <div style="font-size: 0.9rem; color: #4b5563; line-height: 1.6; margin-bottom: 12px;">
                            <p style="margin: 4px 0;">
                                <strong>Người đăng (User):</strong>
                                {{ $blog->user?->name ?? 'Không xác định' }}
                                <span style="color: #6b7280;">({{ $blog->user?->email ?? 'Không có email' }})</span>
                            </p>
                            <p style="margin: 4px 0;">
                                <strong>Tên tác giả hiển thị:</strong> {{ $blog->author ?: 'Ẩn danh' }}
                            </p>
                            <p style="margin: 4px 0;">
                                <strong>Danh mục:</strong> {{ $blog->category?->name ?? 'Chưa phân loại' }}
                                ·
                                <strong>Ngày đăng:</strong> {{ $blog->created_at?->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <p style="color: #374151; font-size: 0.95rem; line-height: 1.5;">
                            {{ Str::limit($blog->description, 200) }}
                        </p>
                    </div>

                    @if ($blog->thumbnail)
                        <div>
                            <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->name }}" style="width: 120px; height: 90px; object-fit: cover; border-radius: 6px;">
                        </div>
                    @endif
                </div>

                {{-- Nút xóa bài viết cho admin --}}
                <div class="admin-delete-section">
                    <form
                        class="admin-delete-form"
    method="POST"
    action="{{ route('admin.blogs.destroy', $blog) }}"
    onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');"
>
    @csrf
    @method('DELETE')

                        <div class="admin-delete-field">
                            <label for="reason-{{ $blog->id }}">Lý do xóa bài viết</label>
                            <textarea
                                id="reason-{{ $blog->id }}"
                                name="reason"
                                rows="3"
                                required
                                minlength="10"
                                maxlength="255"
                                placeholder="Nhập lý do để gửi cho người đăng..."
                            >{{ old('reason') }}</textarea>
                        </div>

                        <button type="submit" class="danger-btn">Xóa bài viết vi phạm</button>

                        @error('reason')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
</form>
                </div>
            </article>
        @empty
            <p class="empty-state">Hệ thống chưa có bài viết nào.</p>
        @endforelse
        {{ $blogs->links() }}
    </div>
@endsection
