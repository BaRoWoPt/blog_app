<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(5)->withQueryString();
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
        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    if ($request->hasFile('image')) {
        $validated['image_path'] = $request->file('image')->store('blog-images', 'public');
    }
    unset($validated['image']);

    Blog::create($validated);

    return redirect()->route('blogs.manage');
}

public function update (Request $request, Blog $blog)
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'content' => ['required', 'string'],
        'author' => ['nullable', 'string', 'max:255'],
        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    if ($request->hasFile('image')) {
        if ($blog->image_path) {
            Storage::disk('public')->delete($blog->image_path);
        }

        $validated['image_path'] = $request->file('image')->store('blog-images', 'public');
    }
    unset($validated['image']);

    $blog->update($validated);

    return redirect()->route('blogs.manage');
}

public function destroy(Blog $blog)
{
    if ($blog->image_path) {
        Storage::disk('public')->delete($blog->image_path);
    }

    $blog->delete();

    return redirect()->route('blogs.manage');
}
}