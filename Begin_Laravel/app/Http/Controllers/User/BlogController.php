<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use App\Services\BlogService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService) {}

    /**
     * Hiển thị danh sách bài viết của chính user đang đăng nhập.
     */
    public function index(Request $request)
    {
        $blogs      = $request->user()->blogs()->with('category', 'tags')->latest()->paginate(3);
        $categories = Category::query()->orderBy('name')->get();
        $tags       = Tag::query()->orderBy('name')->get();

        return view('blog.my-blogs', compact('blogs', 'categories', 'tags'));
    }

    /**
     * Lưu bài viết mới (tự động gán user_id từ user đang đăng nhập).
     */
    public function store(StoreBlogRequest $request)
    {
        $validated = $request->validated();
        $tagIds    = $validated['tag_ids'] ?? [];
        unset($validated['tag_ids']);

        $this->blogService->create(
            user: $request->user(),
            data: $validated,
            tagIds: $tagIds,
            thumbnail: $request->file('thumbnail'),
        );

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
        $tagIds    = $validated['tag_ids'] ?? [];
        unset($validated['tag_ids']);

        $this->blogService->update(
            blog: $blog,
            data: $validated,
            tagIds: $tagIds,
            thumbnail: $request->file('thumbnail'),
        );

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

        $this->blogService->delete($blog);

        return redirect()->route('my-blogs.index')->with('success', 'Đã xóa bài viết thành công!');
    }
}
