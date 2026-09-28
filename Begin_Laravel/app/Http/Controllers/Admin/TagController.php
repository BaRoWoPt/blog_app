<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::query()
            ->withCount('blogs')
            ->orderBy('name')
            ->get();
        $categories = Category::query()
            ->withCount('blogs')
            ->orderBy('name')
            ->get();

        return view('blog.admin-tags', compact('categories', 'tags'));
    }

    public function store(StoreTagRequest $request): RedirectResponse
    {
        $name = $request->validated('tag_name');
        Tag::create([
            'name' => $name,
            'slug' => $this->uniqueSlug($name),
        ]);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Đã tạo tag thành công.');
    }

    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $name = $request->validated('tag_name_update');

        $tag->update([
            'name' => $name,
            'slug' => $this->uniqueSlug($name, $tag->id),
        ]);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Đã cập nhật tag thành công.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Đã xóa tag thành công.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'tag';
        $slug = $baseSlug;
        $suffix = 2;

        while (Tag::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
