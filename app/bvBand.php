<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bvBand extends Model{

	public $column = array("agency_group","from_value","to_value","percentage","year","company","platform");

	public $columnPayTv = array("station","percentage","year");

	public $columnCurrentTarget = array("agency_group_id","dsc_target","spt_target","year","company");	


	public function fixColumnWorkSheet($col,$mtx){
		for ($m=0; $m < sizeof($mtx); $m++){ 
			for ($n=0; $n < sizeof($mtx[$m]); $n++) { 
				if($this->column[$n] == "percentage"){
					$mtx[$m][$this->column[$n]] = (str_replace("%","",$mtx[$m][$this->column[$n]]) )/100;
				}

				if($this->column[$n] == "to_value" && $mtx[$m][$this->column[$n]] == ""){
					$mtx[$m][$this->column[$n]] = 0;
				}

				if($this->column[$n] == "company"){
					if ($mtx[$m][$this->column[$n]] == "DSC") {
						$mtx[$m][$this->column[$n]] = 1;
					}elseif ($mtx[$m][$this->column[$n]] == "WM") {
						$mtx[$m][$this->column[$n]] = 3;
					}else{
						$mtx[$m][$this->column[$n]] = 2;
					}
					
				}

			}
		}

		return $mtx;
	}

	public function workOnSheet($sheet){

		$column = $this->column;

		for ($s=0; $s < sizeof($sheet); $s++) { 
			for ($t=0; $t < 7; $t++) { 
				//var_dump($sheet[$s][$t]);
				$sh[$s][$column[$t]] = $sheet[$s][$t];
			}
		}
		return $sh;

	}

}
