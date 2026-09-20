<?php

use App\Http\Controllers\BlogController;
use App\Http\Middleware\ValidateBlogId;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogController::class, 'index'])->name('home');

Route::middleware('auth')->prefix('admin')->name('blogs.')->group(function () {
    Route::get('/blogs', [BlogController::class, 'manage'])->name('manage');
    Route::post('/blogs', [BlogController::class, 'store'])->name('store');
    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('update');
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('destroy');
});

Route::get('/blog/{id}', function ($id) {
    return "Blog ID hợp lệ: {$id}";
})->middleware(ValidateBlogId::class);

