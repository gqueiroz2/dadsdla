<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\User;
use App\password;
use App\sql;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;
use App\Mail\forgetPassword;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function loginGet(){
        return view('auth.login');
    }

    public function logout(){
    
        require_once('/var/simplesamlphp/lib/_autoload.php');
        $as = new \SimpleSAML\Auth\Simple('default-sp');
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
        return view('auth.logout2');
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
        $email = Request::get('email');

        $sql = new sql();

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $select = "SELECT * FROM user WHERE(email ='$email')";

        $from = array("region_id","user_type_id","sub_level_group","name","specialCases","performance_name","email","password","status","sub_level_bool","token","token_start_date","token_end_date");

        $res = $con->query($select);

        $list = $sql->fetch($res,$from,$from)[0];

        if($list){
            //Create Password Reset Token
            $update = "UPDATE user
                            SET 
                            token = '".Str::random(60)."',
                            token_start_date = '".Carbon::now()."',
                            token_end_date = '".Carbon::now()->addHours(3)."'
                            WHERE (email = '$email')
                            ";

            if ($con->query($update) === TRUE) {
                $selectToken = "SELECT token FROM user WHERE(email ='$email')";
                $resToken = $con->query($selectToken);

                $fromToken = array("token");

                $tokenData = $sql->fetch($resToken,$fromToken,$fromToken)[0]['token'];

                $sendMail = $this->sendResetEmail($email, $tokenData);

                if ($sendMail) {
                    return view('auth.reset',compact('email'));
                }else{
                    //return redirect()->back()->withErrors(['error' => trans('A Network Error occurred. Please try again.')]);
                }    
            }else{
                echo "Error: " . $update . "<br>" . $con->error;
            }

            
        }else{
            return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
        }        
    }

    public function sendResetEmail($email, $token){

        Mail::to($email)->send(new forgetPassword($token));

        if (Mail::failures()) {
            // return failed mails
            return new Error(Mail::failures()); 
        }else{
            
        }

        return true;        

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

        $email = Request::get("email");
        
        $pwd = new password();
        $bool = $pwd->choosePassword($con, $email);
        
        if ($bool['bool']) {
            return redirect('/logout2')->with("".$bool['type']."", $bool['msg']);
        }else{
            return view('auth.reset',compact('email'))->withErrors(["".$bool['type']."" => trans($bool['msg'])]);
         
        }
    }
}
