<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rollOutBase;

class rollOut extends rollOutBase{
    
	public function handleExcel($pattern,$mtx){
		var_dump($pattern);
		/*
		$fl = $this->checkFirstLine($mtx[0]);

		for ($m=0; $m < sizeof($mtx); $m++) { 
			for ($n = sizeof($mtx[$m]); $n >= $fl; $n--) { 
				unset($mtx[$m][$n]);
			}
		}
		*/

		$temp = $mtx[7][1];

		$temp2 = str_replace("[","",$temp);

		$temp3 = str_replace("]","",$temp2);
		var_dump($temp3);


	}



}
