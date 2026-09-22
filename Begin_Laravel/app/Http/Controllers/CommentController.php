<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Blog;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Blog $blog)
    {
        $comment = $blog->comments()->create([
            "user_id"=> $request->user()->id,
            "content"=> $request->validated()['content'],
        ]);

        return redirect()
            ->route('blog.show', $blog)
            ->with('success', 'Bình luận đã được đăng thành công!');
    }
}
