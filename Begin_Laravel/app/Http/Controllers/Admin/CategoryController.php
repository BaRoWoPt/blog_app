<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $name = $request->validated('category_name');
        Category::create([
            'name' => $name,
            'slug' => $this->uniqueSlug($name),
        ]);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Đã tạo danh mục thành công.');
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $name = $request->validated('category_name_update');

        $category->update([
            'name' => $name,
            'slug' => $this->uniqueSlug($name, $category->id),
        ]);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Đã cập nhật danh mục thành công.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Đã xóa danh mục thành công.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'category';
        $slug = $baseSlug;
        $suffix = 2;

        while (Category::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
