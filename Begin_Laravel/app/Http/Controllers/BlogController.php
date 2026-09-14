<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->get();
        return view ('welcome', compact("blogs"));
    }

public function manage()
{
    $blogs = Blog::latest()->get();
    return view('blog.manage', compact('blogs'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'content' => ['required', 'string'],
        'author' => ['nullable', 'string', 'max:255'],
    ]);

    Blog::create($validated);

    return redirect()->route('blogs.manage');
}

public function update (Request $request, Blog $blog)
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'content' => ['required', 'string'],
        'author' => ['nullable', 'string', 'max:255'],
    ]);

    $blog->update($validated);

    return redirect()->route('blogs.manage');
}

public function destroy(Blog $blog)
{
    $blog->delete();

    return redirect()->route('blogs.manage');
}
}
