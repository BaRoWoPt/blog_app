<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class guest
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // Nếu người dùng đã đăng nhập ở bất kỳ guard nào
            if (Auth::guard($guard)->check()) {
                // Chuyển hướng về trang quản trị / dashboard
                return redirect('');
            }
        }

        return $next($request);
    }
}