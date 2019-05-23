<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\sql;

class quarterPerformance extends performance {
    
	public function matchBrandMonth($con, $regionID, $year,  $brands, $currencyID,$value, $months){
		
		for ($b=0; $b < sizeof($brands); $b++) { 
			for ($m=0; $m < sizeof($months); $m++) { 
				if ($brands[$b][1] != 'ONL' || $brands[$b][1] != 'VIX') {
					
				}
			}
		}
	}

    public function lines($con, $regionID, $year, $brands, $salesRepGroupsID, $salesRepID, $currencyID, $value){

    	
    	
    }
}
