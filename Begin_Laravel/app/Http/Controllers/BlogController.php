<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
class BlogController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'category' => ['nullable', 'string', 'exists:categories,slug'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $categorySlug = $validated['category'] ?? null;

        $searchQuery = $validated['q'] ?? null;

        $blogs = Blog::query()
            -> where ('status', 'published')
            ->with('category', 'tags')
            ->when($categorySlug, function ($query) use ($categorySlug) {
                $query->whereHas('category', function ($categoryQuery) use ($categorySlug) {
                    $categoryQuery->where('slug', $categorySlug);
                });
            })
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where(function (Builder $searchBuilder) use ($searchQuery) {
                    $searchBuilder->where('name', 'like', "%{$searchQuery}%")
                        ->orWhere('description', 'like', "%{$searchQuery}%");
                });
            })
            ->latest()
            ->paginate(3)
            ->withQueryString();

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('welcome', compact('blogs', 'categories', 'categorySlug', 'searchQuery'));
    }

    public function show(Blog $blog)
    {
        abort_unless($blog->status === 'published', 404);
        $blog->load('category', 'tags', 'comments.user');
        $comments = $blog->comments()
            ->with('user')
            ->latest()
            ->paginate(10);
        return view('blog.show', compact('blog', 'comments'));
    }
}