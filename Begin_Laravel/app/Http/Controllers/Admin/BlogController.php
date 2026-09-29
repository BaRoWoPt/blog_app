<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AdminDeleteBlogRequest;
use App\Notifications\BlogDeletedByAdminNotification;
use Illuminate\Http\RedirectResponse;

class BlogController extends Controller
{
    /**
     * Admin xem danh sách tất cả bài viết của mọi người trong hệ thống.
     */
    public function index()
    {
        $blogs = Blog::with(['user', 'category'])->latest() ->paginate(3)
            ->withQueryString();;

        return view('blog.admin-manage', compact('blogs'));
    }

    /**
     * Admin có quyền xóa bài viết của bất kỳ ai.
     */
   public function destroy(
    AdminDeleteBlogRequest $request,
    Blog $blog
): RedirectResponse {
    $author = $blog->user;

    if ($author === null) {
        return redirect()
            ->route('admin.blogs.index')
            ->with('error', 'Không thể gửi thông báo vì bài viết chưa liên kết với người dùng.');
    }

    $author->notify(new BlogDeletedByAdminNotification(
        $blog->name,
        $request->validated()['reason'],
    ));

    if ($blog->thumbnail) {
        Storage::disk('public')->delete($blog->thumbnail);
    }

    $blog->delete();

    return redirect()
        ->route('admin.blogs.index')
        ->with('success', 'Đã gửi email thông báo và xóa bài viết.');
}
}