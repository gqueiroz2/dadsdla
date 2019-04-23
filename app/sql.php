<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sql extends Model{
    
    public function select($con, $columns, $table, $join = null, $where = null, $order_by = 1, $limit = false){    	
        $sql = "SELECT $columns FROM $table $join $where ORDER BY $order_by $limit";    
    	$res = $con->query($sql);
    	return $res;
    }

    public function insert($con,$table,$columns,$values){
        $insert = "INSERT INTO $table ($columns) VALUES ($values)"; 

        if($con->query($insert) === true){
            $rtr["bool"] = true;
            $rtr["msg"] = "A New record on the table $table was successfully created!";
        }else{
            $rtr["bool"] = false;
            $rtr["msg"] = "Error: ".$insert."<br>".$con->error;
        }

        return $rtr;
    }

    public function fetch($result,$from,$to){

    	if($result && $result->num_rows > 0){
    		$count = 0;
    		while ($row = $result->fetch_assoc()){
    			for ($i=0; $i < sizeof($from); $i++) { 
    				$info[$count][$to[$i]] = $row[$from[$i]];  				
    			}
    			$count++;
    		}
    	}else{
    		$info = false;
    	}

    	return $info;

    }
}
