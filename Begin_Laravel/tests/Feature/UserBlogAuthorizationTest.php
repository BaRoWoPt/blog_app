<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBlogAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_update_another_users_blog_by_changing_the_id(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $blog = Blog::query()->create([
            'user_id' => $owner->id,
            'name' => 'Bài viết riêng',
            'description' => 'Nội dung ban đầu',
            'author' => $owner->name,
            'status' => 'draft',
        ]);

        $this->actingAs($otherUser)
            ->put(route('my-blogs.update', $blog), [
                'name' => 'Nội dung bị chiếm quyền',
                'description' => 'Nội dung đã bị sửa',
                'author' => $owner->name,
                'status' => 'draft',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'name' => 'Bài viết riêng',
            'description' => 'Nội dung ban đầu',
            'user_id' => $owner->id,
        ]);
    }

    public function test_user_cannot_delete_another_users_blog_by_changing_the_id(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $blog = Blog::query()->create([
            'user_id' => $owner->id,
            'name' => 'Bài viết riêng',
            'description' => 'Nội dung ban đầu',
            'author' => $owner->name,
            'status' => 'draft',
        ]);

        $this->actingAs($otherUser)
            ->delete(route('my-blogs.destroy', $blog))
            ->assertForbidden();

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'user_id' => $owner->id,
        ]);
    }
}