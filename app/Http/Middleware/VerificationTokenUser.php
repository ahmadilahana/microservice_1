<?php

namespace App\Http\Middleware;

use Closure;
use Helper\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class VerificationTokenUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        
        if ($token) {
            $key = 'passport:token:' . $token;
            $value = Redis::get($key);

            if ($value) {
                $request->merge(['TokenData' => $value]);
                return $next($request);
            }
            return ResponseHelper::UnauthorizedResponse();
        } else {
            return ResponseHelper::UnauthorizedResponse();
        }
    }
}
