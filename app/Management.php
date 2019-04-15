<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Management extends Model{

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

	public function get($con,$parameters,$something,$table,$where,$order){

		$sql = "SELECT $something FROM $table ";

		if($where){
			$sql .= "WHERE $where ";
		}  

		if($order){
			$sql .= "ORDER BY $order";
		}

		$result = $con->query($sql);

		if($result && $result->num_rows > 0){
			$count = 0;
			while ($row = $result->fetch_assoc()) {
				for ($p=0; $p < sizeof($parameters); $p++) { 
					$array[$count][$parameters[$p]] = $row[$parameters[$p]];
				}
				
				$count++;
			}
		}else{
			$array = FALSE;
		}

		return $array;

	}

	public function getID($con,$parameter){
		
	}
}
