@extends('layouts.app')

@section('title', $blog->title)

@section('content')
    <article class="blog-card">
        <h1>{{ $blog->title }}</h1>

        @if ($blog->image_path)
            <img src="{{ asset('storage/' . $blog->image_path) }}" alt="{{ $blog->title }}" class="blog-image">
        @endif

        <p class="blog-author">Tác giả: {{ $blog->author }}</p>

        <div class="blog-content">
            {{ $blog->content }}
        </div>
    </article>
@endsection
