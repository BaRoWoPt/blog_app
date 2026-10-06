<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterBlogRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Services\BlogService;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService) {}

    public function index(FilterBlogRequest $request)
    {
        $validated = $request->validated();

        $categorySlug = $validated['category'] ?? null;
        $searchQuery  = $validated['q'] ?? null;

        $blogs = $this->blogService->listForHome($categorySlug, $searchQuery);

        $categories = Category::query()->orderBy('name')->get();

        return view('welcome', compact('blogs', 'categories', 'categorySlug', 'searchQuery'));
    }

    public function show(Blog $blog)
    {
        abort_unless($blog->status === 'published', 404);
        $blog->load('category', 'tags');
        $comments = $blog->comments()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('blog.show', compact('blog', 'comments'));
    }
}