<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->get();
        return view('blog.manage', compact('blogs'));
    }

    public function create()
    {
        return view('blog.create');
    }

    public function show(Blog $blog)
    {
        return view('blog.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        return view('blog.edit', compact('blog'));
    }

    public function store(StoreBlogRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('blog-images', 'public');
        }
        unset($validated['image']);

        $request->user()->blogs()->create($validated); // tự gán user_id

        return redirect()->route('blogs.index');
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($blog->image_path) {
                Storage::disk('public')->delete($blog->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('blog-images', 'public');
        }
        unset($validated['image']);

        $blog->update($validated);

        return redirect()->route('blogs.index');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image_path) {
            Storage::disk('public')->delete($blog->image_path);
        }

        $blog->delete();

        return redirect()->route('blogs.index');
    }
}
