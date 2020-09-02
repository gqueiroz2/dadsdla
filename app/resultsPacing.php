<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class resultsPacing extends Model{
    
	public function construct($con,$currency,$months,$brands,$region,$value){

		$form = "bts";
		
		for ($b=0; $b < sizeof($brands); $b++) { 
            for ($m=0; $m < sizeof($months); $m++) { 
                if ($brands[$b][1] != 'ONL' && $brands[$b][1] != 'VIX') {
                    if ($form == "cmaps") {
                        $where[$b][$m] = $this->defineValues($con, "cmaps", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                    }else{
                        $where[$b][$m] = $this->defineValues($con, "ytd", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                    }
                }else{
                    if($year < 2020){
                        $where[$b][$m] = $this->defineValues($con, "digital", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);       
                    }else{
                        if ($form == "cmaps") {
                            $where[$b][$m] = $this->defineValues($con, "cmaps", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                        }else{
                            $where[$b][$m] = $this->defineValues($con, "ytd", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                        }   
                    }
                }                
            }
        }
	}

}
