<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Blog $blog)
    {
        $validated = $request->validated();
        $blog->comments()->create($validated);

        return redirect()
            ->route('blog.show', $blog)
            ->with('success', 'Bình luận đã được đăng thành công!');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $blog = $comment->blog;
        $comment->delete();

        return redirect()
            ->route('blog.show', $blog)
            ->with('success', 'Đã xóa bình luận thành công!');
    }
}
