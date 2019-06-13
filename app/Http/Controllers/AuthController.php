<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\User;
use App\password;

class AuthController extends Controller
{
    public function loginGet(){
    	return view('auth.login');
    }

    public function logout(){
        Request::session()->flush();
        
        return redirect('');
    }

    public function loginPost(){

    	$db = new dataBase();
		$con = $db->openConnection('DLA');

		$usr = new User();
		$resp = $usr->login($con);

		if (!$resp['bool']) {
            return back()->with('error',$resp['msg']);
        }else{
            Request::session()->put('userName',$resp['name']);
            Request::session()->put('userRegion',$resp['region']);
            Request::session()->put('userRegionID',$resp['regionID']);
            Request::session()->put('userEmail',$resp['email']);
            Request::session()->put('userLevel',$resp['level']);

            if($resp['subLevelBool'] == 1){
                Request::session()->put('userSalesRepGroup',$resp['salesRepGroup']);
                Request::session()->put('userSalesRepGroupID',$resp['salesRepGroupID']);
            }else{
                Request::session()->put('userSalesRepGroup',false);
                Request::session()->put('userSalesRepGroupID',false);
            }

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

        $email = Request::get('x_email');
        $token = Request::get('x_token');        

        $usr = new User();
        $user = $usr->getUserByEmail($con, $email);

        $permission = false;

        $time = mktime(0, 0, 0, 1, 1, 1970);
        $time = date("Y-m-d h:i:s", $time);

        if ($user['token'] == $token) {
            if ($user['token_start_date'] != $time) {
                if (date("Y-m-d h:i:s") < $user['token_end_date']) {
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
        $permission = Request::get('permission');

        if ($resp['bool']) {
            return redirect('/');
            //return back()->with('response',$resp['msg']);
        }else{
            return view('auth.passwords.password', compact('permission'))->with('error',$resp['msg']);
        }
    }
}
