<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Policies\BlogPolicy;
use App\Models\User;
use App\Models\Blog;
use App\Enums\UserRole;

class BlogPolicyTest extends TestCase
{
    public function test_admin_can_update_any_blog(): void
    {
        $policy = new BlogPolicy();

        $admin = new User();
        $admin->id = 1;
        $admin->role = UserRole::ADMIN;

        $blog = new Blog();
        $blog->user_id = 999; // Blog của người khác

        $this->assertTrue($policy->update($admin, $blog));
    }

    public function test_author_can_update_their_own_blog(): void
    {
        $policy = new BlogPolicy();

        $author = new User();
        $author->id = 5;
        $author->role = UserRole::USER;

        $blog = new Blog();
        $blog->user_id = 5; // Trùng ID tác giả

        $this->assertTrue($policy->update($author, $blog));
    }

    public function test_regular_user_cannot_update_others_blog(): void
    {
        $policy = new BlogPolicy();

        $user = new User();
        $user->id = 5;
        $user->role = UserRole::USER;

        $blog = new Blog();
        $blog->user_id = 10; // Của người khác

        $this->assertFalse($policy->update($user, $blog));
    }
}
