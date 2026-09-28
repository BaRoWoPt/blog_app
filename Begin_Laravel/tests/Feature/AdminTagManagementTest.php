<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTagManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_tag(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.tags.store'), [
            'tag_name' => 'Laravel nâng cao',
        ]);

        $response->assertRedirect(route('admin.tags.index'));
        $this->assertDatabaseHas('tags', [
            'name' => 'Laravel nâng cao',
            'slug' => 'laravel-nang-cao',
        ]);
    }

    public function test_regular_user_cannot_create_a_tag(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->post(route('admin.tags.store'), ['tag_name' => 'Không được phép'])
            ->assertForbidden();

        $this->assertDatabaseMissing('tags', ['name' => 'Không được phép']);
    }

    public function test_admin_can_delete_a_tag(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tag = Tag::query()->create([
            'name' => 'Tag cần xóa',
            'slug' => 'tag-can-xoa',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.tags.destroy', $tag))
            ->assertRedirect(route('admin.tags.index'));

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    public function test_admin_can_update_a_tag(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tag = Tag::query()->create([
            'name' => 'Laravel',
            'slug' => 'laravel',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.tags.update', $tag), ['tag_name_update' => 'Laravel nâng cao'])
            ->assertRedirect(route('admin.tags.index'));

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => 'Laravel nâng cao',
            'slug' => 'laravel-nang-cao',
        ]);
    }

    public function test_admin_can_create_a_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'category_name' => 'Chuyện đời thường',
        ]);

        $response->assertRedirect(route('admin.tags.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Chuyện đời thường',
            'slug' => 'chuyen-doi-thuong',
        ]);
    }

    public function test_regular_user_cannot_create_a_category(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->post(route('admin.categories.store'), ['category_name' => 'Không được phép'])
            ->assertForbidden();

        $this->assertDatabaseMissing('categories', ['name' => 'Không được phép']);
    }

    public function test_admin_can_delete_a_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::query()->create([
            'name' => 'Danh mục cần xóa',
            'slug' => 'danh-muc-can-xoa',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.tags.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_can_update_a_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::query()->create([
            'name' => 'Công nghệ',
            'slug' => 'cong-nghe',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'category_name_update' => 'Công nghệ mới',
            ])
            ->assertRedirect(route('admin.tags.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Công nghệ mới',
            'slug' => 'cong-nghe-moi',
        ]);
    }

    public function test_regular_user_cannot_update_taxonomy(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $tag = Tag::query()->create([
            'name' => 'Laravel',
            'slug' => 'laravel',
        ]);

        $this->actingAs($user)
            ->put(route('admin.tags.update', $tag), ['tag_name_update' => 'Không được phép'])
            ->assertForbidden();

        $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => 'Laravel']);
    }
}
