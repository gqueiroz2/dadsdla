<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Request;
use Closure;
use App\dataBase;
use App\User;

class Auth
{
    public function handle($request, Closure $next){

	$userName = Request::session()->get('userName');
	
        if(is_null($userName)){
		return redirect(route('logoutGet'));
	}

        return $next($request);
	    
    }	
}
