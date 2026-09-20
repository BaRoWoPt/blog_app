<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdminAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Ví dụ demo middleware: chỉ cho phép truy cập nếu có query param ?admin=1
        // Bạn có thể thay bằng auth check thực tế sau này.
        if (! $request->boolean('admin')) {
            abort(403, 'Bạn không có quyền truy cập khu vực admin.');
        }

        return $next($request);
    }
}