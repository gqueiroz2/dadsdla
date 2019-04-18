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

	public function getID($con,$parameter,$seek){
		$sql = "SELECT id FROM $parameter WHERE name = '$seek'";

		$result = $con->query($sql);

		if($result && $result->num_rows > 0){
			$row = $result->fetch_assoc();
			$id = $row['id'];
		}else{
			$id = false;
		}

		return $id;

	}

	public function where($columns,$variables){

		$where = "WHERE ";


		for ($i=0; $i <sizeof($columns) ; $i++) { 
			if ($i == sizeof($columns)-1) {
				$where .= "($columns[$i] = \"$variables[$i]\")";
			}else{
				$where .= "($columns[$i] = \"$variables[$i]\") AND ";
			}
		}

		return $where;

	}

	public function setUpdate($columns, $values){


		$set = "SET ";
		for ($i=0; $i <sizeof($columns) ; $i++) { 
			if ($i == sizeof($columns)-1) {
				$set .= "$columns[$i] = \"$values[$i]\"";
			}else{
				$set .= "$columns[$i] = \"$values[$i]\", ";
			}
		}

		return $set;
	}

	public function updateValues($con,$tableName,$set,$where){
		$sql = "UPDATE $tableName $set $where";

		if($con->query($sql) === true){
			$rtr["bool"] = true;
			$rtr["msg"] = "Successfully updated!";
		}else{
			$rtr["bool"] = false;
			$rtr["msg"] = "Error: ".$sql."<br>".$con->error;
		}

		return $rtr;
	}
}
