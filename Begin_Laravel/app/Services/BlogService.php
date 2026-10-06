<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Throwable;

class BlogService
{
    /**
     * Lấy danh sách bài viết đã published cho trang chủ,
     * hỗ trợ lọc theo category slug và từ khoá tìm kiếm.
     */
    public function listForHome(
        ?string $categorySlug = null,
        ?string $searchQuery = null,
        int $perPage = 3,
    ): LengthAwarePaginator {
        return Blog::query()
            ->where('status', 'published')
            ->with('category', 'tags')
            ->when($categorySlug, function ($query) use ($categorySlug) {
                $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
            })
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('name', 'like', "%{$searchQuery}%")
                        ->orWhere('description', 'like', "%{$searchQuery}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Tạo bài viết mới cho user, xử lý upload ảnh và sync tag trong transaction.
     * Nếu transaction thất bại, ảnh đã upload sẽ được xóa để tránh file orphan.
     *
     * @param  array<string, mixed>  $data      Dữ liệu đã validated (không bao gồm tag_ids).
     * @param  int[]                 $tagIds    Danh sách ID của tag cần gán.
     * @param  UploadedFile|null     $thumbnail File ảnh thumbnail (nếu có).
     */
    public function create(
        User $user,
        array $data,
        array $tagIds = [],
        ?UploadedFile $thumbnail = null,
    ): Blog {
        $thumbnailPath = null;

        if ($thumbnail !== null) {
            $thumbnailPath = $thumbnail->store('blog-images', 'public');
            $data['thumbnail'] = $thumbnailPath;
        }

        try {
            return DB::transaction(function () use ($user, $data, $tagIds): Blog {
                $blog = $user->blogs()->create($data);
                $blog->tags()->sync($tagIds);

                return $blog;
            });
        } catch (Throwable $e) {
            if ($thumbnailPath !== null) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            throw $e;
        }
    }

    /**
     * Cập nhật bài viết, xử lý thay thế ảnh và sync tag trong transaction.
     * Ảnh cũ chỉ bị xóa sau khi transaction thành công.
     *
     * @param  array<string, mixed>  $data
     * @param  int[]                 $tagIds
     */
    public function update(
        Blog $blog,
        array $data,
        array $tagIds = [],
        ?UploadedFile $thumbnail = null,
    ): Blog {
        $oldThumbnailPath = $blog->thumbnail;
        $newThumbnailPath = null;

        if ($thumbnail !== null) {
            $newThumbnailPath = $thumbnail->store('blog-images', 'public');
            $data['thumbnail'] = $newThumbnailPath;
        } else {
            // Không upload ảnh mới → giữ nguyên thumbnail cũ
            unset($data['thumbnail']);
        }

        try {
            DB::transaction(function () use ($blog, $data, $tagIds): void {
                $blog->update($data);
                $blog->tags()->sync($tagIds);
            });
        } catch (Throwable $e) {
            if ($newThumbnailPath !== null) {
                Storage::disk('public')->delete($newThumbnailPath);
            }

            throw $e;
        }

        // Xóa ảnh cũ chỉ sau khi transaction thành công và đã có ảnh mới
        if ($newThumbnailPath !== null && $oldThumbnailPath !== null) {
            Storage::disk('public')->delete($oldThumbnailPath);
        }

        return $blog->fresh();
    }

    /**
     * Xóa bài viết và ảnh thumbnail liên quan (nếu có).
     */
    public function delete(Blog $blog): void
    {
        DB::transaction(function () use ($blog): void {
            $blog->delete();
        });

        if ($blog->thumbnail) {
            Storage::disk('public')->delete($blog->thumbnail);
        }
    }
}
