<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bvBand extends Model{

	public $column = array("agency_group","from_value","to_value","percentage","year");

	public function fixColumnWorkSheet($col,$mtx){
		for ($m=0; $m < sizeof($mtx); $m++){ 
			for ($n=0; $n < sizeof($mtx[$m]); $n++) { 
				if($this->column[$n] == "percentage"){
					$mtx[$m][$this->column[$n]] = (str_replace("%","",$mtx[$m][$this->column[$n]]) )/100;
				}

				if($this->column[$n] == "to_value" && $mtx[$m][$this->column[$n]] == ""){
					$mtx[$m][$this->column[$n]] = 0;
				}
			}
		}

		return $mtx;
	}

	public function workOnSheet($sheet){

		$column = $this->column;

		for ($s=0; $s < sizeof($sheet); $s++) { 
			for ($t=0; $t < 5; $t++) { 
				//var_dump($sheet[$s][$t]);
				$sh[$s][$column[$t]] = $sheet[$s][$t];
			}
		}
		return $sh;

	}

}
