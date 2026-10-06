<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminDeleteBlogRequest;
use App\Http\Requests\UpdateBlogStatusRequest;
use App\Models\Blog;
use App\Notifications\BlogDeletedByAdminNotification;
use App\Services\BlogService;
use Illuminate\Http\RedirectResponse;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService) {}

    /**
     * Admin xem danh sách tất cả bài viết của mọi người trong hệ thống.
     */
    public function index()
    {
        $blogs = Blog::with(['user', 'category'])
            ->latest()
            ->paginate(3)
            ->withQueryString();

        return view('blog.admin-manage', compact('blogs'));
    }

    /**
     * Admin có quyền xóa bài viết của bất kỳ ai.
     */
    public function destroy(AdminDeleteBlogRequest $request, Blog $blog): RedirectResponse
    {
        $author = $blog->user;

        if ($author === null) {
            return redirect()
                ->route('admin.blogs.index')
                ->with('error', 'Không thể gửi thông báo vì bài viết chưa liên kết với người dùng.');
        }

        $this->blogService->delete($blog);

        $author->notify(new BlogDeletedByAdminNotification(
            $blog->name,
            $request->validated()['reason'],
        ));

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Đã gửi email thông báo và xóa bài viết.');
    }

    /**
     * Admin cập nhật trạng thái bài viết.
     */
    public function updateStatus(UpdateBlogStatusRequest $request, Blog $blog): RedirectResponse
    {
        $blog->status = $request->validated()['status'];
        $blog->save();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Đã cập nhật trạng thái bài viết.');
    }
}