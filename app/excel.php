<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\documentsHead;

class excel extends Model{
    
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

	public function fixExcelNumberWithComma($number){
		//var_dump($number);
		$number = str_replace('$', '', $number);
		$number = str_replace('.', '', $number);
		$number = str_replace(',', '.', $number);
		$number = doubleval($number);

		return $number;
	}

	public function fixExcelNumber($number){
		$number = str_replace('$', '', $number);
		$number = str_replace(',', '', $number);
		$number = doubleval($number);

		return $number;
	}

	public function putIndexes($matrix,$type){

		$DH = new documentsHead();

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
