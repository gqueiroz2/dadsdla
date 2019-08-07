<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\region;

class rankingBrand extends rank{

	//$con, $tableName, $leftName, $type, $brands, $region, $value, $years, $months, $currency, $order_by, $leftName2=null
	public function getAllResults($con, $info, $region, $brands, $value, $months, $currency) {

		$sql = new sql();

		//var_dump($brands);
		for ($b=0; $b < sizeof($brands); $b++) { 
			
			if ($b == 1) {
				$table = "digital";
			}else{
				$table = $info['table'];
			}

			$infoQuery[$b] = $this->getAllValuesUnion($table, $info['leftName'], "brand", $brands[$b], $region, $value, $months, $currency);

			//var_dump("infoQuery", $infoQuery[$b]);
		}

		for ($y=0; $y < sizeof($info['years']); $y++) { 
			
			array_push($infoQuery['colsValue'], $info['years'][$y]);
			$where = $sql->where($infoQuery['columns'], $infoQuery['colsValue']);
		}
		/*$firstClosed[$b] = $this->getAllValues($con, $table, $info['leftName'], "brand", $brands[$b], $region, $value, array($info['years'][0]), $months, $currency, "DESC");
		$secondClosed[$b] = $this->getAllValues($con, $table, $info['leftName'], "brand", $brands[$b], $region, $value, array($info['years'][1]), $months, $currency, "DESC");
		$plan[$b] = $this->getAllValues($con, "plan_by_brand", $info['leftName'], "brand", $brands[$b], $region, $value, array($info['years'][0]), $months, $currency, "DESC");
		var_dump("first", $firstClosed[$b]);*/

	}

	public function mountBrands($brands){
		
		$brandsTV = array();
		$brandsDigital = array();

		for ($b=0; $b < sizeof($brands); $b++) {
			if ($brands[$b][1] == "DC" || $brands[$b][1] == "HH" || $brands[$b][1] == "DK" || $brands[$b][1] == "AP" 
				|| $brands[$b][1] == "TLC"|| $brands[$b][1] == "ID" || $brands[$b][1] == "DT" || $brands[$b][1] == "FN" 
				|| $brands[$b][1] == "OTH" || $brands[$b][1] == "HGTV") {
				array_push($brandsTV, $brands[$b]);
			}else{
				array_push($brandsDigital, $brands[$b]);
			}
		}

		return array($brandsTV, $brandsDigital);
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
