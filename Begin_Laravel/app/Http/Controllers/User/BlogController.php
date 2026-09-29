<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BlogController extends Controller
{
    /**
     * Hiển thị danh sách bài viết của chính user đang đăng nhập.
     */
    public function index(Request $request)
    {
        $blogs = $request->user()->blogs()->with('category', 'tags')->latest()->paginate(3);
        $categories = Category::query()->orderBy('name')->get();
        $tags = Tag::query()->orderBy('name')->get();

        return view('blog.my-blogs', compact('blogs', 'categories', 'tags'));
    }

    /**
     * Lưu bài viết mới (tự động gán user_id từ user đang đăng nhập).
     */
    public function store(StoreBlogRequest $request)
    {
        $validated = $request->validated();
        $tagIds = $validated['tag_ids'] ?? [];
        unset($validated['tag_ids']);

        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('blog-images', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        try {
            DB::transaction(function () use ($request, $validated, $tagIds): void {
                $blog = $request->user()->blogs()->create($validated);
                $blog->tags()->sync($tagIds);
            });
        } catch (Throwable $exception) {
            if ($thumbnailPath !== null) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            throw $exception;
        }

        return redirect()->route('my-blogs.index')->with('success', 'Đã thêm bài viết mới thành công!');
    }

    /**
     * Cập nhật bài viết của chính mình.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        // Chặn nếu không phải chủ sở hữu bài viết
        if ($blog->user_id !== $request->user()->id) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết của người khác.');
        }

        $validated = $request->validated();
        $oldThumbnailPath = $blog->thumbnail;
        $newThumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $newThumbnailPath = $request->file('thumbnail')->store('blog-images', 'public');
            $validated['thumbnail'] = $newThumbnailPath;
        } else {
            unset($validated['thumbnail']);
        }

        $tagIds = $validated['tag_ids'] ?? [];
        unset($validated['tag_ids']);

        try {
            DB::transaction(function () use ($blog, $validated, $tagIds): void {
                $blog->update($validated);
                $blog->tags()->sync($tagIds);
            });
        } catch (Throwable $exception) {
            if ($newThumbnailPath !== null) {
                Storage::disk('public')->delete($newThumbnailPath);
            }

            throw $exception;
        }

        if ($newThumbnailPath !== null && $oldThumbnailPath !== null) {
            Storage::disk('public')->delete($oldThumbnailPath);
        }

        return redirect()->route('my-blogs.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    /**
     * Xóa bài viết của chính mình.
     */
    public function destroy(Request $request, Blog $blog)
    {
        // Chặn nếu không phải chủ sở hữu bài viết
        if ($blog->user_id !== $request->user()->id) {
            abort(403, 'Bạn không có quyền xóa bài viết của người khác.');
        }

        if ($blog->thumbnail) {
            Storage::disk('public')->delete($blog->thumbnail);
        }

        $blog->delete();

        return redirect()->route('my-blogs.index')->with('success', 'Đã xóa bài viết thành công!');
    }
}
