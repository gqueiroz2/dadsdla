<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\User;
use App\sql;

class password extends Model{
    public function checkPassword($password){
        
        $pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';

        if (preg_match($pattern, $password)) {
            $resp['msg'] = "Password accepted";
            $resp['bool'] = true;
        }else{
            $resp['msg'] = "Password must be at least 8 characters, 
                            must contain at least one lower case letter, one upper case letter, one digit and one special character.";
            $resp['bool'] = false;
        }

        return $resp;
    }

    public function createEmail($url, $email, $token){
		
		$message = "
					<html>
					<head>
					  <title>Change password</title>
					</head>
					<body>
						<span>Click at the bottom button to be redirected to the page that will be use to change your password.</span>
						<form method='POST' action='$url'>
							<input type='hidden' name='token' value='$token'>
							<input type='hidden' name='email' value='$email'>
							<input type='submit' name='changePassword' value='redirect'>
						</form>
					</body>
					</html>
					";

		return $message;			

	}

    public function sendEmail($email, $token){
		
		$url = route('requestToChangePassword');
		
		$message = $this->createEmail($url, $email, $token);

		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = 'From: TesteChangePassword <no-reply@dads.com>';
		
		mail($email, "Request to change password", $message, implode("\r\n", $headers));

		return true;
	}

    public function requestToEmail($con){
    	
    	date_default_timezone_set('America/Sao_Paulo');

    	$email = Request::get('email');

    	$usr = new User();
    	$user = $usr->getUserByEmail($con, $email);

    	$emailToHash = $user['email'].rand(rand(), rand());
    	
        $token = md5($emailToHash);

    	$today = date("Y-m-d h:i:s");
    	$tomorrow = strtotime("+1 day");
		$tomorrow = date("Y-m-d h:i:s", $tomorrow);

    	$sql = new sql();

    	$columns = array("token", "token_start_date", "token_end_date");
    	$values = array($token, $today, $tomorrow);

    	$set = $sql->setUpdate($columns, $values);
    	$where = "WHERE (email = '$email')";
    	$resp = $sql->updateValues($con, 'user', $set, $where);

    	$bool = false;

    	if ($resp['bool']) {
    		$bool = $this->sendEmail($email, $token);
    	}

    	return $bool;
    }    

    public function choosePassword($con){
        
        date_default_timezone_set('America/Sao_Paulo');

        $password = Request::get('password');
        $email = Request::get('email');

        $bool = $this->checkPassword($password);

        if ($bool['bool']) {
            $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 5]);

            $sql = new sql();

            $time = mktime(0, 0, 0, 1, 1, 1970);
            $time = date("Y-m-d h:i:s", $time);

            $columns = array('password', 'token', 'token_start_date', 'token_end_date');
            $values = array($password, 'inicial', $time, $time);

            $set = $sql->setUpdate($columns, $values);
            $where = "WHERE email='$email'";

            $resp = $sql->updateValues($con, 'user', $set, $where);
        }

        return $bool;

    }
}
