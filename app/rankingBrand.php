<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\region;
use App\pRate;

class rankingBrand extends rank{

	//$con, $tableName, $leftName, $type, $brands, $region, $value, $years, $months, $currency, $order_by, $leftName2=null
	public function getAllResults($con, $r, $region, $brands, $value, $months, $currency, $years) {

		$order_by = " (
							CASE brandID

							WHEN '1'
							THEN 1

							WHEN '2'
							THEN 2

							WHEN '3'
							THEN 3

							WHEN '4'
							THEN 4

							WHEN '5'
							THEN 5

							WHEN '6'
							THEN 6

							WHEN '7'
							THEN 7

							WHEN '8'
							THEN 8

							WHEN '9'
							THEN 9

							WHEN '10'
							THEN 10

							WHEN '11'
							THEN 11	

							WHEN '12'
							THEN 12

							END
						)";

		$sql = new sql();
		$p = new pRate();

		for ($l=0; $l < 2; $l++) {
			
			for ($y=0; $y < sizeof($years); $y++) {

				$info = $this->mountValues($con, $r, $region, $years[$y]);

				for ($b=0; $b < sizeof($brands); $b++) {
				
					if ($l == 0) {
						if ($b == 1) {
							$table = "fw_digital";
						}else{
							$table = $info['table'];
						}
					}else{
						$table = 'plan_by_brand';
					}
					//var_dump($years[$y]);
					//var_dump($table);
					$infoQuery[$b] = $this->getAllValuesUnion($table, $info['leftName'], "brand", $brands[$b], $region, $value, $months, $currency);

					//var_dump("infoQuery", $infoQuery[$b]);
				}

				if (sizeof($brands) > 1) {
					for ($b=0; $b < sizeof($brands); $b++) {
						array_push($infoQuery[$b]['colsValue'], $years[$y]);
						$where[$b] = $sql->where($infoQuery[$b]['columns'], $infoQuery[$b]['colsValue']);
					}

					$values[$y] = $sql->selectWithUnion($con, $where, $infoQuery, $infoQuery[0]['name'], $order_by, "ASC");

					for ($b=0; $b < sizeof($brands); $b++) { 
						array_pop($infoQuery[$b]['colsValue']);
					}
				}else{
					array_push($infoQuery[0]['colsValue'], $years[$y]);
					$where = $sql->where($infoQuery[0]['columns'], $infoQuery[0]['colsValue']);
					$values[$y] = $sql->selectGroupBy($con, $infoQuery[0]['columns'], $infoQuery[0]['table'], $infoQuery[0]['join'], $where, $order_by, $infoQuery[0]['name'], "ASC");
					array_pop($infoQuery[0]['colsValue']);
				}

				$from = $infoQuery[0]['names'];
				$res[$y] = $sql->fetch($values[$y], $from, $from);
					var_dump($table);
				
				if ($infoQuery[0]['table'] == "cmaps a") {
					if ($currency[0]['name'] == "USD") {
			            $pRate = $p->getPRateByRegionAndYear($con, array($region), array($years[0]));
			        }else{
			            $pRate = 1.0;
			        }
					
				}else{
					if ($currency[0]['name'] == "USD") {
			            $pRate = 1.0;
			        }else{
			            $pRate = $p->getPRateByRegionAndYear($con, array($region), array($years[0]));
			        }	
				}
			    
				if ($currency[0]['name'] == "USD") {
		            $pRateDigital = 1.0;
		        }else{
		            $pRateDigital = $p->getPRateByRegionAndYear($con, array($region), array($years[0]));
		        }



				/*if ($y == 0) {
					var_dump("antes da transformação",$res[$y]);
				}*/

				if (is_array($res[$y])) {
					/*var_dump($y);
					var_dump($info['table']);
					var_dump($pRate);*/
					for ($i=0; $i < sizeof($res[$y]); $i++) { 
						if($res[$y][$i]['brand'] == 'ONL' || $res[$y][$i]['brand'] == 'VIX'){
							$res[$y][$i]['total'] *= $pRateDigital;
						}
						elseif ($infoQuery[$y]['table'] == "cmaps a") {
							$res[$y][$i]['total'] /= $pRate;	
						}else{
							$res[$y][$i]['total'] *= $pRate;
						}
					}	
				}

				/*if ($y == 0) {
					var_dump("depois da transformação",$res[$y]);
				}*/
			}

			//var_dump($res);
			$line[$l] = $res;

			if ($l != 0) {
				array_pop($line[$l]);
			}

			//var_dump($line[$l]);
		}
		
		return $line;

	}

	public function mountValues($con, $r, $regionID, $year){

        $rtr['region'] = $r;

		if ($rtr['region'] == "Brazil") {
			$rtr['table'] = "cmaps";
		}else{
			$rtr['table'] = "ytd";
		}

		$rtr['leftName'] = "brand";

		return $rtr;

	}

	public function assembler($values, $years, $brands){

		//var_dump($brands);

		$mtx[0][0] = "Brand";
		$mtx[1][0] = "Booking ".$years[0];
		$mtx[2][0] = "Target ".$years[0];
		$mtx[3][0] = "Booking ".$years[1];
		$mtx[4][0] = "Share Booking ".$years[0];
		$mtx[5][0] = "Share Target";
		$mtx[6][0] = "Share Booking ".$years[1];
		$mtx[7][0] = "%(Booking / Target)";
		$mtx[8][0] = "(Booking - Target)";

		$closed = 0;
		$target = 0;
		$pClosed = 0;

		for ($b=0; $b < sizeof($brands); $b++) { 
			$mtx[0][$b+1] = $brands[$b][1];

			if ($b < sizeof($values[0][0])) {
				$val = $values[0][0][$b]['total'];
			}else{
				$val = "-";
			}

			$mtx[1][$b+1] = $val;
			
			if ($val != "-") {
				$closed += $val;	
			}

			if ($b < sizeof($values[1][0])) {
				$val = $values[1][0][$b]['total'];
			}else{
				$val = "-";
			}

			$mtx[2][$b+1] = $val;

			if ($val != "-") {
				$target += $val;	
			}

			if ($b < sizeof($values[0][1])) {
				$val = $values[0][1][$b]['total'];
			}else{
				$val = "-";
			}

			$mtx[3][$b+1] = $val;

			if ($val != "-") {
				$pClosed += $val;	
			}
		}

		$closedP = 0;
		$targetP = 0;
		$pClosedP = 0;

		if (sizeof($brands) > 1) {
			array_push($mtx[0], "DN");
			array_push($mtx[1], $closed);
			array_push($mtx[2], $target);
			array_push($mtx[3], $pClosed);

			for ($b=0; $b < sizeof($brands); $b++) {
				
				if ($mtx[1][$b+1] != "-") {
					$val = ($mtx[1][$b+1] / $closed)*100;
					$closedP += $val;
				}else{
					$val = "-";
				}

				$mtx[4][$b+1] = $val;

				if ($mtx[2][$b+1] != "-") {
					$val = ($mtx[2][$b+1] / $target)*100;
					$targetP += $val;
				}else{
					$val = "-";
				}

				$mtx[5][$b+1] = $val;

				if ($mtx[3][$b+1] != "-") {
					$val = ($mtx[3][$b+1] / $pClosed)*100;
					$pClosedP += $val;
				}else{
					$val = "-";
				}

				$mtx[6][$b+1] = $val;

				if ($mtx[1][$b+1] != "-" && $mtx[2][$b+1] != "-") {
					$val = ($mtx[1][$b+1] / $mtx[2][$b+1])*100;
					$val2 = ($mtx[1][$b+1] - $mtx[2][$b+1]);
				}else{
					$val = "-";
					$val2 = "-";
				}

				$mtx[7][$b+1] = $val;
				$mtx[8][$b+1] = $val2;
			}

			array_push($mtx[4], $closedP);
			array_push($mtx[5], $targetP);
			array_push($mtx[6], $pClosedP);

			$size = sizeof($mtx[0]);

			$val = ($closed / $target)*100;
			array_push($mtx[7], $val);

			$val = ($closed - $target);
			array_push($mtx[8], $val);
		}

		return $mtx;
	}

}
