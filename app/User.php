<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;
use App\password;

class User extends Management{

	public function getUserType($con){
		$sql = new sql();
		$table = "user_types";
		$columns = "ID,name,level";
		$from = array('ID','name','level');
		$to = array('id','name','level');
		$result = $sql->select($con,$columns,$table);
        $userType = $sql->fetch($result,$from,$to);
		return $userType;
	}

    public function addUserType($con){
        $sql = new sql();
        $userType = Request::get('name');
        $level = Request::get('level');
        $table = 'user_types';
        $columns = 'name,level';
        $values = "'$userType','$level'";


        $bool = $sql->insert($con,$table,$columns,$values);
		

        return $bool;
    }

    public function editUserType($con){
        $sql = new sql();
        $size = Request::get("size");
        $table = "user_types";
        $columns = array('name','level');

        for ($i=0; $i <$size ; $i++) { 
            $oldUser[$i] = Request::get("oldUserType-$i");
            $oldLevel[$i] = Request::get("oldLevel-$i");
            $newUser[$i] = Request::get("newUserType-$i");
            $newLevel[$i] = Request::get("newLevel-$i");
            
            $arrayWhere[$i] = array($oldUser[$i],$oldLevel[$i]);
            $arraySet[$i] = array($newUser[$i],$newLevel[$i]);

            $where[$i] = $sql->where($columns,$arrayWhere[$i]);
            $set[$i] = $sql->setUpdate($columns,$arraySet[$i]);
        }

        $bool = false;

        for ($i=0; $i <$size; $i++) {
            if($oldUser[$i] != $newUser[$i] || $oldLevel[$i] != $newLevel[$i]){
                $bool = $sql->updateValues($con,$table,$set[$i],$where[$i]);

                if ($bool == false) {
                    break;
                }
            }
        }


        return $bool;

    }

    public function getUser($con,$region){		
        $sql = new sql();
        $table = "user u";
        $columns = "u.ID AS 'id',
                    u.name AS 'name',
                    u.email AS 'email',
                    u.password AS 'password',
                    u.status AS 'status',
                    u.sub_level_bool AS 'subLevelBool',
                    r.name AS 'region',
                    ut.name AS 'userType',
                    ut.level AS 'level',
                    srg.name AS 'salesRepGroup'
                    u.token AS 'token'
                    u.token_start_date AS 'token_start_date'
                    u.token_end_date AS 'token_end_date'
                   ";
        $join = "LEFT JOIN region r ON r.ID = u.region_id
                 LEFT JOIN user_types ut ON ut.ID = u.user_type_id
                 LEFT JOIN sales_rep_group srg ON srg.ID = u.sub_level_group 
                ";
<<<<<<< HEAD
        $result = $sql->select($con,$columns,$table,$join);
=======

        $where = "";
        if ($region) {
            $ids = implode(",", $region);
            $where .= "WHERE u.region_id IN ('$ids')";
        }
        $result = $sql->select($con,$columns,$table,$join,$where);
>>>>>>> 1d73c4be6a9953f481648e9dd6a713facbadf1f4

        $from = array('id','name','email','password','status','subLevelBool','region','userType','level','salesRepGroup');
        $to = $from;
        $user = $sql->fetch($result,$from,$to);
        return $user;

	}

    public function getUserByEmail($con, $email){
        
        $sql = new sql();
        $table = "user u";

        $columns = "u.ID AS 'id',
                    u.name AS 'name',
                    u.email AS 'email',
                    u.password AS 'password',
                    u.status AS 'status',
                    u.sub_level_bool AS 'subLevelBool',
                    r.name AS 'region',
                    ut.name AS 'userType',
                    ut.level AS 'level',
                    srg.name AS 'salesRepGroup'
                    u.token AS 'token'
                    u.token_start_date AS 'token_start_date'
                    u.token_end_date AS 'token_end_date'
                   ";

        $join = "LEFT JOIN region r ON r.ID = u.region_id
                 LEFT JOIN user_types ut ON ut.ID = u.user_type_id
                 LEFT JOIN sales_rep_group srg ON srg.ID = u.sub_level_group 
                ";

        $where = "email=$email";

        $result = $sql->select($con,$columns,$table,$join, $where);

        $from = array('id','name','email','password','status','subLevelBool','region','userType','level','salesRepGroup','token','token_start_date','token_end_date');
        $to = $from;

        $user = $sql->fetch($result,$from,$to);

        return $user;

    }

    public function addUser($con){
        $sql = new sql();

        date_default_timezone_set('America/Sao_Paulo');

        $sql = new sql();

    	$name = Request::get('name');
    	$email = Request::get('email');

    	$password = Request::get('password');

        //tirar daqui
        $pwd = new password();
        $bool = $pwd->checkPassword($password);

        if (!$bool['bool']) {
            return $bool;
        }

        $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 5]);
        //até aqui

        $status = Request::get('status');
        $regionID = Request::get('region');
    	$userTypeID = Request::get('userType');
    	$subLevelBool = Request::get('subLevelBool');
        $subLevelGroup = Request::get('subLevelGroup');
    	
        $token = 'inicial';
        $tokenStartDate = mktime(23, 59, 59, 1, 1, 1970);
        $tokenStartDate = date("Y-m-d H:i:s", $tokenStartDate);
        $tokenEndDate = $tokenStartDate;

    	$table = 'user';
        $columns = ' region_id, 
                     user_type_id, 
                     sub_level_group,
                     name,
                     email,
                     password,
                     status,
                     sub_level_bool,
                     token,
                     token_start_date,
                     token_end_date
                   ';

        $values = " '$regionID',
                    '$userTypeID',
                    '$subLevelGroup',
                    '$name',
                    '$email',
                    '$password',
                    '$status',
                    '$subLevelBool',
                    '$token',
                    '$tokenStartDate',
                    '$tokenEndDate'
                  ";

        $bool = $sql->insert($con,$table,$columns,$values);

        var_dump("name");
    	var_dump($name);
        var_dump("email");
    	var_dump($email);
        var_dump("password");
        var_dump($password);
        var_dump("status");
        var_dump($status);
        var_dump("region");
    	var_dump($regionID);
        var_dump("userTypeID");
    	var_dump($userTypeID);
        var_dump("subLevelBool");
    	var_dump($subLevelBool);
        var_dump("subLevelGroup");
        var_dump($subLevelGroup);
        var_dump("token");
        var_dump($token);
        var_dump("tokenStartDate");
        var_dump($tokenStartDate);
        var_dump("tokenEndDate");
        var_dump($tokenEndDate);

        return $bool;

    }

    public function login($con){

        $email = Request::get('email');
        $password = Request::get('password');

        $usr = $this->getUserByEmail($con, $email);

        if (password_verify($password, $usr[0]['password'])) {
            $resp['name'] = $usr[0]['name'];
            $resp['bool'] = true;
            $resp['msg'] = "Login Successfull";
        }else{
            $resp['bool'] = false;
            $resp['msg'] = "Your E-Mail Address or Password is incorrect";
        }

        return $resp;
    }


    public function editUser($con){
        $sql = new sql();
        $size = Request::get('size');
        $table = "user";

        $columns = array('region_id','user_type_id','name','status','sub_level_group','sub_level_bool');

        for ($i=0; $i <$size; $i++) { 
            $oldName[$i] = Request::get("oldName-$i");
            $newName[$i] = Request::get("newName-$i");

            $oldRegion[$i] = Request::get("oldRegion-$i");
            $newRegion[$i] = Request::get("newRegion-$i");

            $oldStatus[$i] = Request::get("oldStatus-$i");
            $newStatus[$i] = Request::get("newStatus-$i");

            $oldSubLevelBool[$i] = Request::get("oldSubLevelBool-$i");
            $newSubLevelBool[$i] = Request::get("newSubLevelBool-$i");

            $oldUserType[$i] = Request::get("oldUserType-$i");
            $newUserType[$i] = Request::get("newUserType-$i");

            $oldSalesGroup[$i] = Request::get("oldSalesGroup-$i");
            $newSalesGroup[$i] = Request::get("newSalesGroup-$i");

            $arrayWhere[$i] = array($oldRegion[$i],$oldUserType[$i],$oldName[$i],$oldStatus[$i],$oldSalesGroup[$i],$oldSubLevelBool[$i]);
            
            $arraySet[$i] = array($newRegion[$i],$newUserType[$i],$newName[$i],$newStatus[$i],$newSalesGroup[$i],$newSubLevelBool[$i]);

            $where[$i] = $sql->where($columns,$arrayWhere[$i]); 

            $set[$i] = $sql->setUpdate($columns,$arraySet[$i]);
        }

        $bool = false;

        for ($i=0; $i <$size ; $i++) { 
            if ($oldName[$i] != $newName[$i] || $oldRegion[$i] != $newRegion[$i] || $oldStatus[$i] != $newStatus[$i] || $oldSubLevelBool[$i] != $newSubLevelBool[$i] || $oldUserType[$i] != $newUserType[$i] ||  $oldSalesGroup[$i] != $newSalesGroup[$i]) {
                $bool = $sql->updateValues($con,$table,$set[$i],$where[$i]);
                if ($bool == false) {
                    break;
                }
            }
        }

        return $bool;
    }

}
