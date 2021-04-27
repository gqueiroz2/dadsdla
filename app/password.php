<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\User;
use App\sql;

require __DIR__.'/../vendor/autoload.php';

class password extends Model{
    public function checkPassword($password){
        
        $pattern = "#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#";

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

    /*public function sendEmail($email, $token){
		
		$url = route('requestToChangePassword');
		
		$message = $this->createEmail($url, $email, $token);

		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = 'From: TesteChangePassword <d_ads@discovery.com>';
		
		mail($email, "Request to change password", $message, implode("\r\n", $headers));

		return true;
	}*/

    /*public function sendEmail($email, $token){
        
        $mail = new PHPMailer;

        $mail->isSMTP();

        $mail->setFrom('d_ads@discovery.com', 'D|ADS DLA Portal');
        $mail->addAddress($email);

        $mail->Username = 'lucior.jr@gmail.com';
        $mail->Password = '@Scudetto2809';

        $mail->Host = 'smtp.gmail.com';

        $mail->Subject = 'Request to change password';

        $url = route('requestToChangePassword');
        $mail->Body = $this->createEmail($url, $email, $token);

        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->isHTML(true);

        if(!$mail->send()) {
             echo "Email not sent. " , $mail->ErrorInfo , PHP_EOL;
            return false;
        } else {
            echo "FOI";
            return true;
        }
    }*/

    public function sendEmail($email, $token){
        /*$mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 2;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            //$mail->Host       = 'dctmail.discovery.com';  // Specify main and backup SMTP servers
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'lucio_cruz@discoverybrasil.com';                     // SMTP username
            //$mail->Username   = 'lucior.jr@gmail.com';                     // SMTP username
            $mail->Password   = '@Scudetto2809';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('lucio_cruz@discoverybrasil.com', 'D|ADS DLA Portal');
            $mail->addAddress($email);
            //$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            //$mail->addAddress('ellen@example.com');               // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'No-Reply Request to Change Password';
            $url = route('requestToChangePassword');
            $mail->Body    = $this->createEmail($url, $email, $token);
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }*/


        
        $mail = new PHPMailer(true);

        $mail->isSMTP();

        $mail->setFrom('d_ads@discovery.com', 'D|ADS DLA Portal');
        $mail->addAddress($email);

        $mail->Username = 'lucior_cruz@discoverybrasil.com';

        $mail->Password = '#082016Disc';

        $mail->Host = 'smtp.office365.com';

        $mail->Subject = 'Request to change password';

        $url = route('requestToChangePassword');
        $mail->Body = $this->createEmail($url, $email, $token);

        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->isHTML(true);

        var_dump($mail->send());

        if(!$mail->send()) {
            echo "Email not sent. " , $mail->ErrorInfo , PHP_EOL;
            return false;
        } else {
            echo "FOI";
            return true;
        }
    }

    public function requestToEmail($con, $email){
    	
    	date_default_timezone_set('America/Sao_Paulo');

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

    public function choosePassword($con, $email){
        $sql = new sql();
        date_default_timezone_set('America/Sao_Paulo');

        $password = Request::get('password');
        $password_confirmation = Request::get('password_confirmation');
        $token = Request::get('token');

        $select = "SELECT token FROM user WHERE(email ='$email')";

        $from = array("token");

        $res = $con->query($select);

        $tokenDataBase = $sql->fetch($res,$from,$from)[0]['token'];
       
        if($password != $password_confirmation){
            $bool = array('bool' => false, 'msg' => 'Password does not match !','type' => 'password');
            return $bool;
        }

        if($token != $tokenDataBase){
            $bool = array('bool' => false, 'msg' => 'Token incorrect !','type' => 'token');
            return $bool;
        }
        
        $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 5]);

        $sql = new sql();

        $time = mktime(0, 0, 0, 1, 1, 1970);
        $time = date("Y-m-d h:i:s", $time);

        $columns = array('password', 'token', 'token_start_date', 'token_end_date', 'status');
        $values = array($password, 'inicial', $time, $time, 1);


        $set = $sql->setUpdate($columns, $values);
        $where = "WHERE email=\"$email\"";

        $resp = $sql->updateValues($con, 'user', $set, $where);

        $bool = array('bool' => true, 'msg' => 'Password succesfully updated','type' => 'msg');

        return $bool;
        
    }
}
