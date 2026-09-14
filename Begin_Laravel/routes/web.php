<?php
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogController::class, 'index']);

// Gom nhóm các route quản lý admin
Route::prefix('admin')->name('blogs.')->group(function () {
    
    Route::get('/blogs', [BlogController::class, 'manage'])->name('manage');
    
    Route::post('/blogs', [BlogController::class, 'store'])->name('store');
    
    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('update');
    
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('destroy');

});