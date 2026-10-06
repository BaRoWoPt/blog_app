<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;

class BlogPolicy
{
    /**
     * Xác định xem người dùng có quyền cập nhật bài viết hay không.
     */
    public function update(User $user, Blog $blog): bool
    {
        return $user->isAdmin() || $blog->user_id === $user->id;
    }

    /**
     * Xác định xem người dùng có quyền xóa bài viết hay không.
     */
    public function delete(User $user, Blog $blog): bool
    {
        return $user->isAdmin() || $blog->user_id === $user->id;
    }
}

