<?php

namespace App\Http\Controllers;
use App\Models\Blog;
class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(3)->withQueryString();
        return view('welcome', compact('blogs'));
    }
}
