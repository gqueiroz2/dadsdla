<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Request;
use Closure;
use App\dataBase;
use App\User;

class Auth
{
    public function handle($request, Closure $next){
	require_once('/var/simplesamlphp/lib/_autoload.php');
        $as = new \SimpleSAML\Auth\Simple('default-sp');

        $as->requireAuth();

        $userName = Request::session()->get('userName');
	
        if(is_null($userName)){
             $db = new dataBase();

             $con = $db->openConnection('DLA');

             $user = new User();

             $user->autenticate($con,$as);


        }

        return $next($request);
	    
    }	
}
