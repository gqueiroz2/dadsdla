<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\dataBase;
use App\User;
use App\password;

class AuthController extends Controller
{
    public function loginGet(){
    	return view('auth.login');
    }

    public function loginPost(){

    	$db = new dataBase();
		$con = $db->openConnection('DLA');

		$usr = new User();
		$resp = $usr->login($con);

		if (!$resp['bool']) {
            return back()->with('error',$resp['msg']);
        }else{
        	return redirect('home');
        }
    }

    public function forgotPasswordGet(){
    	return view('auth.passwords.email');
    }

    public function forgotPasswordPost(){
    	
    	$db = new dataBase();
        $con = $db->openConnection('DLA');

		$pwd = new password();
		$bool = $pwd->requestToEmail($con);

        var_dump($bool);

		if ($bool) {
			return back()->with('response',"E-mail envied with success");
		}else{
			return back()->with('error', "E-mail doesn't envied");
		}

    }

    public function requestToChangePassword(){
    	
    	date_default_timezone_set('America/Sao_Paulo');

    	$db = new dataBase();
        $con = $db->openConnection('DLA');

        $pwd = new password();
        $email = $pwd->getValuesRequest()['email'];
        $token = $pwd->getValuesRequest()['token'];

        $usr = new User();
        $user = $usr->getUserByEmail($con, $email);

        $permission = false;

        $time = mktime(0, 0, 0, 1, 1, 1970);
        $time = date("Y-m-d h:i:s", $time);

        if ($user[0]['token'] == $token) {
            if ($user[0]['token_start_date'] != $time) {
                if (date("Y-m-d h:i:s") < $user[0]['token_end_date']) {
                    $permission = true;     
                }
            }
        }

        return view('auth.passwords.password', compact('permission'));
    }

    public function resetPassword(){

    	$db = new dataBase();
        $con = $db->openConnection('DLA');

        $pwd = new password();
        $resp = $pwd->choosePassword($con);

        if ($resp['bool']) {
            return back()->with('response',$resp['msg']);
        }else{
            return back()->with('error',$resp['msg']);
        }
    }
}
