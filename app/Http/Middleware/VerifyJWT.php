<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class VerifyJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$user = Auth::user())  return response()->json(['status' => false, 'message' => "Vui lòng đăng nhập"], 401);
        return $next($request);
    }
}
