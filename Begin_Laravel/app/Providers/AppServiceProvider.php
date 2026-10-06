<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Giới hạn tạo bài viết: 5 bài / 1 phút (theo user ID hoặc IP)
        RateLimiter::for('posts', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });

        // Giới hạn gửi bình luận: 10 bình luận / 1 phút (theo user ID hoặc IP)
        RateLimiter::for('comments', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        // Giới hạn các tác vụ nhạy cảm (đăng ký, quên mật khẩu): 5 lần / 1 phút (theo IP)
        RateLimiter::for('auth-sensitive', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        Model::preventLazyLoading(!app()->isProduction());
    }
}
