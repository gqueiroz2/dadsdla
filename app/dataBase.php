<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dataBase extends Model{
    public function openConnection($base){
        $this->con = new \Mysqli($this->ip,$this->user,$this->pass,$base,3306);
        $this->con->set_charset("utf8");
        if($this->con->connect_error){
            return FALSE;
        }

        return $this->con;
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

    protected $ip = "dads-prod-mysql.csrl1r5zexxy.us-east-1.rds.amazonaws.com";    
    protected $pass = "DgpbFnJKeSEzTe8e";
    protected $user = "rdsroot";    
    protected $con;
    
}
