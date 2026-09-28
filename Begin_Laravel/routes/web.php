<?php

use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\User\BlogController as UserBlogController;
use App\Http\Middleware\ValidateBlogId;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogController::class, 'index'])->name('home');
Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('blog.show');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showSignupForm'])->name('signup');
    Route::post('/register', [AuthController::class, 'signup'])->name('signup.post');

});

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [
        PasswordResetController::class,
        'showForgotForm',
    ])->name('password.request');

    Route::post('/forgot-password', [
        PasswordResetController::class,
        'sendResetLink',
    ])->name('password.email');

    Route::get('/reset-password/{token}', [
        PasswordResetController::class,
        'showResetForm',
    ])->name('password.reset');

    Route::post('/reset-password', [
        PasswordResetController::class,
        'reset',
    ])->name('password.update');
});
// Bình luận bài viết (yêu cầu đăng nhập)
Route::post('/blogs/{blog}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');

// Người dùng quản lý bài viết của chính mình
Route::middleware('auth')->prefix('my-blogs')->name('my-blogs.')->group(function () {
    Route::get('/', [UserBlogController::class, 'index'])->name('index');
    Route::post('/', [UserBlogController::class, 'store'])->name('store');
    Route::put('/{blog}', [UserBlogController::class, 'update'])->name('update');
    Route::delete('/{blog}', [UserBlogController::class, 'destroy'])->name('destroy');
});

// Admin quản trị toàn bộ bài viết trong hệ thống
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/blogs', [AdminBlogController::class, 'index'])->name('blogs.index');
    Route::delete('/blogs/{blog}', [AdminBlogController::class, 'destroy'])->name('blogs.destroy');
});

Route::get('/blog/{id}', function ($id) {
    return "Blog ID hợp lệ: {$id}";
})->middleware(ValidateBlogId::class);
