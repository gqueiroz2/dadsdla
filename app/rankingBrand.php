<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\region;

class rankingBrand extends rank{

	//$con, $tableName, $leftName, $type, $brands, $region, $value, $years, $months, $currency, $order_by, $leftName2=null
	public function getAllResults($con, $info, $region, $brands, $value, $months, $currency) {
		
		for ($b=0; $b < sizeof($brands); $b++) { 
			
			if ($brands[$b][1] == "ONL" || $brands[$b][1] == "VIX") {
				$table = "digital";
			}else{
				$table = $info['table'];
			}

			$firstClosed[$b] = $this->getAllValues($con, $table, $info['leftName'], "brand", array($brands[$b]), $region, $value, array($info['years'][0]), $months, $currency, "DESC");
			$secondClosed[$b] = $this->getAllValues($con, $table, $info['leftName'], "brand", array($brands[$b]), $region, $value, array($info['years'][1]), $months, $currency, "DESC");
			$plan[$b] = $this->getAllValues($con, "plan_by_brand", $info['leftName'], "brand", array($brands[$b]), $region, $value, array($info['years'][0]), $months, $currency, "DESC");
			/*var_dump("first", $firstClosed[$b]);
			var_dump("second", $secondClosed[$b]);
			var_dump("plan", $plan[$b]);*/
		}

	}
    
	public function mountValues($con, $r, $regionID){

		$tmp = $r->getRegion($con,array($regionID));

		if(is_array($tmp)){
            $rtr['region'] = $tmp[0]['name'];
        }else{
            $rtr['region'] = $tmp['name'];
        }

        $cYear = intval(date('Y'));
        $pYear = $cYear - 1;

		$rtr['years'] = array($cYear, $pYear);

		if ($rtr['region'] == "Brazil") {
			$rtr['table'] = "cmaps";
		}else{
			$rtr['table'] = "ytd";
		}

		$rtr['leftName'] = "brand";

		return $rtr;

	}

}
