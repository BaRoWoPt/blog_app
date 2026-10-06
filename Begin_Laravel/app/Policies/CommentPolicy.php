<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Xác định xem người dùng có quyền xóa bình luận hay không.
     * Cho phép nếu: là admin, hoặc là người tạo bình luận, hoặc là chủ sở hữu bài viết có bình luận đó.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->isAdmin()
            || $comment->user_id === $user->id
            || $comment->blog->user_id === $user->id;
    }
}

