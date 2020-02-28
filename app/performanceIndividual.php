<?php

namespace App;

use App\performance;
use App\region;
use App\results;
use App\sql;
use App\base;
use App\brand;
use App\salesRep;
use App\pRate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class performanceIndividual extends performance{


	public function matrix($con,$region,$year,$tier,$salesRep,$salesRepGroup,$currency,$month,$value){
		$p = new pRate();
		$sql = new sql();
		$base = new base();

		if ($region == '6' || $region == '7') {
			array_push($salesRep, '15');
		}elseif ($region == '9') {
			array_push($salesRep, '102');
		}elseif ($region = '10') {
			array_push($salesRep, '103');
		}elseif ($region == '11') {
			array_push($salesRep,'45');	
		}elseif ($region == '12') {
			array_push($salesRep, '104');
		}elseif ($region == '13') {
			array_push($salesRep, '105');
		}

		for ($b=0; $b < sizeof($brand); $b++) {
            for ($m=0; $m < sizeof($month); $m++) {
                if (  ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") && $year < 2020 ) {
                    $table[$b][$m] = "fw_digital";
                }else{
                    $table[$b][$m] = "ytd";
                }
                //pega colunas
                $sum[$b][$m] = $this->generateColumns($value,$table[$b][$m]);
            }
        }


	}
}