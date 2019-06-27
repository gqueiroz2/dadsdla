<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Request;
use Closure;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if (is_null( Request::session()->get('userName')) ) {
            return redirect(route('logoutGet'));
        }else{
            return $next($request);
        }
    }
}
