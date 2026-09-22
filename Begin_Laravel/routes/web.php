<?php

use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Middleware\ValidateBlogId;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

Route::get('/', [BlogController::class, 'index'])->name('home');
Route::get ('/blogs/{blog}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('blogs', AdminBlogController::class);
});

Route::get('/blog/{id}', function ($id) {
    return "Blog ID hợp lệ: {$id}";
})->middleware(ValidateBlogId::class);

Route::post('/blogs/{blog}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');