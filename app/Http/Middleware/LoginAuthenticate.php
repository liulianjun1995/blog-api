<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LoginAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {

            $request->headers->set('Accept', 'application/json');

            return response()->json([
                'code' => 50008,
                'message' => '身份认证未通过'
            ]);
        }

        return $next($request);
    }
}
