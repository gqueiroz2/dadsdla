<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\salesRep;
class dataBase extends Model{
    
    public function __construct(){
        
        if($_SERVER['SERVER_ADDR'] == '10.36.12.151'){
            $this->ip = "dads-dev-mysql.c7wizdvhr2cq.us-east-1.rds.amazonaws.com";    
            $this->pass = "DT3WDDhmcx63D7HF";
            $this->user = "rdsroot";
        }elseif($_SERVER['SERVER_ADDR'] == '10.34.69.207'){
	        $this->ip = "dads-prod-mysql.csrl1r5zexxy.us-east-1.rds.amazonaws.com";
            $this->pass = "DgpbFnJKeSEzTe8e";
            $this->user = "rdsroot";
	}else{
            $this->ip = "127.0.0.1";    
            $this->user = "root";
            $this->pass = "";
        }
    }

    public function defaultConnection(){
        return "dla";
    }

    public function matchesConnection($something){

        if($something = "first"){
            return "firstmatch";
        }elseif($something = "second"){
            return "secondmatch";
        }elseif($something = "third"){
            return "thirdmatch";
        }else{
            return "dla";
        }

    }

    public function openConnection($base){
        $this->con = new \Mysqli($this->ip,$this->user,$this->pass,$base,3306);
        $this->con->set_charset("utf8");
        if($this->con->connect_error){
            return FALSE;
        }

        return $this->con;
    }

    public function openDevConnection($base){
        $dev_ip = "dads-dev-mysql.c7wizdvhr2cq.us-east-1.rds.amazonaws.com";    
        $dev_pass = "DT3WDDhmcx63D7HF";
        $dev_user = "rdsroot";

        $dev_con = new \Mysqli($dev_ip,$dev_user,$dev_pass,$base,3306);
        $dev_con->set_charset("utf8");
        if($dev_con->connect_error){
            return FALSE;
        }

        return $dev_con;
    }

    public function getLastUpdate($myBase,$table){       

        $fc = new functions();
        $con = mysqli_connect($this->ip,$this->user,$this->pass,$myBase);
        $sql = " SHOW TABLE STATUS
        FROM $myBase
        LIKE '$table' ";
        $tableStatus = mysqli_query($con,$sql);
        while ($array = mysqli_fetch_array($tableStatus,MYSQLI_ASSOC)) {
            $temp = $array['Update_time'];
        }

        $tpp = explode(" ", $temp);
        $update = $fc->formatD($tpp[0]);
        return $update;

    }


    public function closeConnection(){
        $con->close();
    }


    protected $ip = null;
    protected $pass = null;
    protected $user = null;

    protected $con;
  
}
