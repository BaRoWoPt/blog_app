<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'category' => ['nullable', 'string', 'exists:categories,slug'],
        ]);

        $categorySlug = $validated['category'] ?? null;

        $blogs = Blog::query()
            ->with('category', 'tags')
            ->when($categorySlug, function ($query) use ($categorySlug) {
                $query->whereHas('category', function ($categoryQuery) use ($categorySlug) {
                    $categoryQuery->where('slug', $categorySlug);
                });
            })
            ->latest()
            ->paginate(3)
            ->withQueryString();

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('welcome', compact('blogs', 'categories', 'categorySlug'));
    }

    public function show(Blog $blog)
    {
        $blog->load('category', 'tags', 'comments.user');

        return view('blog.show', compact('blog'));
    }
}
