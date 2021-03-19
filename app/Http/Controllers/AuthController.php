<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\User;
use App\password;
use App\sql;
use Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;

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
            var_dump("EXISTE USUARIO");

            //Create Password Reset Token
            $update = "UPDATE user
                            SET 
                            token = '".Str::random(60)."',
                            token_start_date = '".Carbon::now()."',
                            token_end_date = '".Carbon::now()->addHours(3)."'
                            WHERE (email = '$email')
                            ";

            if ($con->query($update) === TRUE) {
                echo "New record created successfully";
            }else{
                echo "Error: " . $update . "<br>" . $con->error;
            }

            $selectToken = "SELECT token FROM user WHERE(email ='$email')";
            $resToken = $con->query($selectToken);

            $fromToken = array("token");

            $tokenData = $sql->fetch($resToken,$fromToken,$fromToken)[0]['token'];

            var_dump($tokenData);
            $this->sendResetEmail($email, $tokenData);
            /*
            if ($this->sendResetEmail($email, $tokenData)) {
                return redirect()->back()->with('status', trans('A reset link has been sent to your email address.'));
            }else{
                return redirect()->back()->withErrors(['error' => trans('A Network Error occurred. Please try again.')]);
            }*/


        }else{
            return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
        }

        /*
        $details = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp'
        ];
   
        Mail::to('lucior.jr@gmail.com')->send(new \App\Mail\testMailOne($details));
   
        dd("Email is Sent.");
        /*
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

    public function sendResetEmail($email, $token){
        var_dump(config('base_url'));

        $link = 'localhost/' . 'password/reset/' . $token . '?email=' . urlencode($email);
        var_dump($link);
        /*
        try {
        //Here send the link with CURL with an external email API 
            return true;
        } catch (\Exception $e) {
            return false;
        }
        */

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