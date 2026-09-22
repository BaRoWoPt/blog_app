<?php

namespace App\Http\Controllers;
use App\Models\Blog;
use App\Models\Category;
class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('category')->latest()->paginate(3)->withQueryString();
        $categories = Category::all();
        return view('welcome', compact('blogs', 'categories'));
    }
    public function show(Blog $blog)
    {
        $blog->load('category', 'comments.user');
        return view('blog.show', compact('blog'));
    }
}
