<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateBlogId
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');
        if (! is_numeric($id)) {
            return response()->json(['error' => 'Invalid blog ID'], 400);
        }

        return $next($request);
    }
}
