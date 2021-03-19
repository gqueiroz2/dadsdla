<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Request;
use Closure;
use App\dataBase;
use App\User;

class Auth
{
    public function handle($request, Closure $next){
        if($request->is('auth/login')){
        	return redirect(route('logoutGet'));
        }else if (is_null( Request::session()->get('userName')) ) {            
            return redirect(route('logoutGet'));
        }else{
            return $next($request);
        }
    }
}
