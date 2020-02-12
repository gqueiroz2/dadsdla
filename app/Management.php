<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\documentsHead;
use App\client;
use App\agency;

class Management extends Model{

	public function checkForMissMatches($con,$classe,$type,$array){

		$sizeA = sizeof($array);
		$toCreate = array();
		switch ($type) {
			case 'client':
				for ($a=0; $a < $sizeA; $a++) { 
					$something = $classe->checkClientUnit($con,$array[$a]);			
					if(!$something){
						$toCreate[] = $array[$a];
					}
				}
				break;
			case 'agency':
				for ($a=0; $a < $sizeA; $a++) { 
					$something = $classe->checkAgencyUnit($con,$array[$a]);			
					if(!$something){
						$toCreate[] = $array[$a];
					}
				}
				break;
		}

		return $toCreate;
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

	public function filterMonthYear($matrix){

		$year = date("Y");
		$month = date("F");

		$mtx=array();

		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			if ($matrix[$i]["Calendar Year"] == $year && $matrix[$i]["Calendar Month"] == $month) {
				array_push($mtx, $matrix[$i]);
			}
		}

		return $mtx;
	}

	public function clearSpreadSheet($matrix){

		$verifier = (sizeof($matrix[0]))/2;

		for ($i=0; $i <sizeof($matrix) ; $i++) {
			$count = 0;

			for ($j=0; $j <sizeof($matrix[$i]) ; $j++) { 
				if($matrix[$i][$j] == null){
					$count++;
				}
			}
			if ($count >= $verifier) {
				unset($matrix[$i]);
			}

		}

		$matrix = array_values($matrix);

		return $matrix;
	}

	public function fixExcelNumber($number){
		$number = str_replace(',', '', $number);
		$number = doubleval($number);

		return $number;
	}

	public function fixExcelNumber2($number){
		$number = str_replace('$', '', $number);
		$number = str_replace(',', '', $number);
		$number = doubleval($number);

		return $number;
	}

	public function putIndex($matrix,$type){
		$mtx = array();

		for ($i=1; $i <sizeof($matrix) ; $i++) { 
		   	for ($j=0; $j <sizeof($matrix[$i]) ; $j++) { 
		   		$mtx[$i-1][$matrix[0][$j]] = $matrix[$i][$j];
		   	}
		}

		return $mtx;
	}

	public function findIndex($matrix,$head){

		$index = -1;

		for($i=0;$i<sizeof($matrix);$i++){
			$verifier = true;
			for($j=0;$j<sizeof($matrix[$i]);$j++){
				if ($head[$j] != $matrix[$i][$j]) {
					$verifier = false;
					break;
				}
			}
			if($verifier){
				$index = $i;
			}
		}

		return $index;

	}
}
