<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

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
        $userType = Request::get('name');
        $level = Request::get('level');
        $table = 'user_types';
        $columns = 'name,level';
        $values = "'$userType','$level'";
		$bool = $this->insert($con,$table,$columns,$values);
		return $bool;
    }

    public function getUser($con){		
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
                   ";
        $join = "LEFT JOIN region r ON r.ID = u.region_id
                 LEFT JOIN user_types ut ON ut.ID = u.user_type_id
                 LEFT JOIN sales_rep_group srg ON srg.ID = u.sub_level_group 
                ";
        $result = $sql->select($con,$columns,$table,$join);
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

                   ";

        $join = "LEFT JOIN region r ON r.ID = u.region_id
                 LEFT JOIN user_types ut ON ut.ID = u.user_type_id
                 LEFT JOIN sales_rep_group srg ON srg.ID = u.sub_level_group 
                ";

        $where = "email=$email";

        $result = $sql->select($con,$columns,$table,$join, $where);

        $from = array('id','name','email','password','status','subLevelBool','region','userType','level','salesRepGroup');
        $to = $from;

        $user = $sql->fetch($result,$from,$to);

        return $user;

    }

    public function addUser($con){
    	var_dump(Request::all());

        date_default_timezone_set('America/Sao_Paulo');

    	$name = Request::get('name');
    	$email = Request::get('email');
    	$password = Request::get('password');
        $status = Request::get('status');
        $regionID = Request::get('region');
    	$userTypeID = Request::get('userType');
    	$subLevelBool = Request::get('subLevelBool');
        $subLevelGroup = Request::get('subLevelGroup');
        
        $creation_date = date("Y-m-d h:i:s");
        $modification_date = $creation_date;
    	
        $token = 'inicial';
        $tokenStartDate = mktime(0, 0, 0, 1, 1, 1970);
        $tokenStartDate = date("Y-m-d h:i:s", $tokenStartDate);
        $tokenEndDate = $tokenStartDate;

    	$table = 'user';
        $columns = ' region_id , 
                     user_type_id , 
                     sub_level_group ,
                     name,
                     email,
                     password,
                     status,
                     sub_level_bool
                   ';

        $values = " '$regionID',
                    '$userTypeID',
                    'subLevelGroup',
                    '$name',
                    '$email',
                    '$password',
                    '$status',
                    '$subLevelBool'
                  ";

        $bool = $this->insert($con,$table,$columns,$values);

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
        var_dump("creation_date");
        var_dump($creation_date);
        var_dump("modification_date");
        var_dump($modification_date);
        var_dump("token");
        var_dump($token);
        var_dump("tokenStartDate");
        var_dump($tokenStartDate);
        var_dump("tokenEndDate");
        var_dump($tokenEndDate);

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

}
