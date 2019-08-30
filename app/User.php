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

    public function autenticate($con,$as){
        $attributes = $as->getAttributes();
        $tmp = $attributes["Email"][0];
        $email = strtolower(explode("@",$tmp)[0]);
        $user = $this->getUserByEmail($con,$email);

        if(!is_null($user)){
            Request::session()->put('userName',$user['name']);
            Request::session()->put('userRegion',$user['region']);
            Request::session()->put('userRegionID',$user['regionID']);
            Request::session()->put('userEmail',$user['email']);
            Request::session()->put('userLevel',$user['level']);
            Request::session()->put('performanceName',$user['performance_name']);
            Request::session()->put('special',$user['special']);

            if($user['subLevelBool']){
                Request::session()->put('userSalesRepGroup',$user['salesRepGroup']);
                Request::session()->put('userSalesRepGroupID',$user['salesRepGroupID']);
            }else{
                Request::session()->put('userSalesRepGroup',false);
                Request::session()->put('userSalesRepGroupID',false);
            }
            return true;
        }else{
            return false;
        }

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

    public function getUser($con,$region = false){		
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
                    srg.name AS 'salesRepGroup',
                    u.token AS 'token',
                    u.token_start_date AS 'token_start_date',
                    u.token_end_date AS 'token_end_date'
                   ";

        $join = "LEFT JOIN region r ON r.ID = u.region_id
                 LEFT JOIN user_types ut ON ut.ID = u.user_type_id
                 LEFT JOIN sales_rep_group srg ON srg.ID = u.sub_level_group 
                ";

        $where = "";
        if ($region) {
            $ids = implode(",", $region);
            $where .= "WHERE u.region_id IN ('$ids')";
        }
        $result = $sql->select($con,$columns,$table,$join,$where);


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
                    r.id AS 'regionID',
                    ut.name AS 'userType',
                    ut.level AS 'level',
                    srg.name AS 'salesRepGroup',
                    srg.ID AS 'salesRepGroupID',
                    u.token AS 'token',
                    u.token_start_date AS 'token_start_date',
                    u.token_end_date AS 'token_end_date',
                    u.performance_name AS 'performance_name',
                    u.specialCases AS 'special'
                   ";

        $join = "LEFT JOIN region r ON r.ID = u.region_id
                 LEFT JOIN user_types ut ON ut.ID = u.user_type_id
                 LEFT JOIN sales_rep_group srg ON srg.ID = u.sub_level_group 
                ";

        $where = "WHERE email like '%$email%'";

        $result = $sql->select($con,$columns,$table,$join, $where);

        $from = array('id','name','email','password','status','subLevelBool','region','regionID','userType','level','salesRepGroup','salesRepGroupID','token','token_start_date','token_end_date','performance_name','special');
        $to = $from;

        $user = $sql->fetch($result,$from,$to)[0];

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

        return $bool;

    }
/*
    public function autenticate($con,$as){
        $attributes = $as->getAttributes();
        $tmp = $attributes["Email"][0];
        $email = strtolower(explode("@",$tmp)[0]);
	$user = $this->getUserByEmail($con,$email);
        
	if(!is_null($user)){
            Request::session()->put('userName',$user['name']);
            Request::session()->put('userRegion',$user['region']);
            Request::session()->put('userRegionID',$user['regionID']);
            Request::session()->put('userEmail',$user['email']);
            Request::session()->put('userLevel',$user['level']);

            if($user['subLevelBool']){
                Request::session()->put('userSalesRepGroup',$user['salesRepGroup']);
                Request::session()->put('userSalesRepGroupID',$user['salesRepGroupID']);
            }else{
                Request::session()->put('userSalesRepGroup',false);
                Request::session()->put('userSalesRepGroupID',false);
            }
	    return true;
        }else{
	    return false;
	}

    }
*/
    public function login($con){
	
        $email = Request::get('email');
        $password = Request::get('password');

        $usr = $this->getUserByEmail($con, $email);

       if (password_verify($password, $usr['password'])) {
            $resp['name'] = $usr['name'];
            $resp['bool'] = true;
            $resp['region'] = $usr['region'];
            $resp['regionID'] = $usr['regionID'];
            $resp['email'] = $usr['email'];
            $resp['level'] = $usr['level'];
            $resp['salesRepGroup'] = $usr['salesRepGroup'];
            $resp['salesRepGroupID'] = $usr['salesRepGroupID'];
            $resp['subLevelBool'] = $usr['subLevelBool'];
            $resp['status'] = $usr['status'];
            $resp['performance_name'] = $usr['performance_name'];
            $resp['special'] = $usr['special'];
            $resp['msg'] = "Login Successfull";

            if ($resp['status'] == 0) {
                $resp['bool'] = false;
                $resp['msg'] = "Your user isn't active";   
            }

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
