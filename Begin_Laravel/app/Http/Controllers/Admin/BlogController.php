<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Admin xem danh sách tất cả bài viết của mọi người trong hệ thống.
     */
    public function index()
    {
        $blogs = Blog::with(['user', 'category'])->latest()->get();

        return view('blog.admin-manage', compact('blogs'));
    }

    /**
     * Admin có quyền xóa bài viết của bất kỳ ai.
     */
    public function destroy(Blog $blog)
    {
        if ($blog->thumbnail) {
            Storage::disk('public')->delete($blog->thumbnail);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Admin đã xóa bài viết!');
    }
}
