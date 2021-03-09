<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\User;
use App\password;
use Session;
class AuthController extends Controller
{
    public function loginGet(){
        return view('auth.login');
    }

    public function logout(){
    
        require_once('/var/simplesamlphp/lib/_autoload.php');
        $as = new \SimpleSAML\Auth\Simple('default-sp');
        var_dump($as);
        //$as->logout(route('logoutGet'));
    }
   
    public function permission(){
        return view('auth.permission');
    }

    public function autenticate(){
        $user = new User();
        
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        if(file_exists('/var/simplesamlphp/lib/_autoload.php')){
            require_once('/var/simplesamlphp/lib/_autoload.php');
            $as = new \SimpleSAML\Auth\Simple('default-sp');
            $as->requireAuth();
            $bool=$user->autenticate($con,$as);

            if($bool){
                return redirect('home');
            }else{
                return redirect('permission');
            }

        }else{
            return view('auth.login');
        }
    }

    public function autenticate2(){
        $user = new User();
        
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        return view('auth.login');

    }

    public function logoutGet(){
        Request::session()->flush();       
        $cookie_name = 'SimpleSAML';
        unset($_COOKIE[$cookie_name]);
        $res = setcookie($cookie_name, '', time() - 72000);
        $cookie_name = 'SimpleSAMLAuthToken';
        unset($_COOKIE[$cookie_name]);
        $res = setcookie($cookie_name, '', time() - 72000);
        return view('auth.logout');
    }

    public function logoutGet2(){
        Request::session()->flush();       
        $cookie_name = 'SimpleSAML';
        unset($_COOKIE[$cookie_name]);
        $res = setcookie($cookie_name, '', time() - 72000);
        $cookie_name = 'SimpleSAMLAuthToken';
        unset($_COOKIE[$cookie_name]);
        $res = setcookie($cookie_name, '', time() - 72000);
        return view('auth.logout');
    }

    public function loginPost(){
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
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
            Request::session()->put('performanceName',$resp['performance_name']);
            Request::session()->put('special',$resp['special']);
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
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $email = Request::get('email');
        $pwd = new password();
        $bool = $pwd->requestToEmail($con, $email);
        /*if ($bool) {
            return back()->with('response',"E-mail send with success");
        }else{
            return back()->with('error', "E-mail doesn't send");
        }*/
    }
    public function requestToChangePassword(){
        
        date_default_timezone_set('America/Sao_Paulo');
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $email = Request::get('email');
        $token = Request::get('_token');
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
        
        return view('auth.passwords.password', compact('permission', 'email'));
    }
    public function resetPassword(){
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        $permission = Request::get('permission');
        $email = Request::get("email");
        
        $pwd = new password();
        $resp = $pwd->choosePassword($con, $email);
        
        if ($resp['bool']) {
            return redirect('/');
        }else{
            \Session::flash('error', $resp['msg']);
            return view('auth.passwords.password', compact('permission', 'email'));//->with('error',$resp['msg']);
        }
    }
}