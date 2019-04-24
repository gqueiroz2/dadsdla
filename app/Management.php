<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Management extends Model{

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

	public function removeDuplicates($array,$col){

		for ($a=0; $a < sizeof($array); $a++) { 
			for ($c=0; $c < sizeof($col); $c++) { 				
				$tmp[$a][$col[$c]] = $array[$a][$col[$c]];
			}
		}

		$rtr = 	array_values( array_map( "unserialize", array_unique( 
								array_map( "serialize", 
									                 $tmp ) 
							    )
							));

		return($rtr);
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

	public function filter($select,$table,$columns,$filter,$con){

		$select1 = "";

		for ($s=0; $s <sizeof($select) ; $s++) { 
			if ($s == sizeof($select)-1) {
				$select1 .= "$select[$s]";
			}else{
				$select1 .= "$select[$s],";
			}
		}


		//return $result;
	}
}
