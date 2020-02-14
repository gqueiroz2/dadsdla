<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\region;
use App\pRate;

class rankingBrand extends rank{

	//$con, $tableName, $leftName, $type, $brands, $region, $value, $years, $months, $currency, $order_by, $leftName2=null
	public function getAllResults($con, $regionName, $region, $brands, $value, $months, $currency, $years) {


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

				$info = $this->mountValues($con, $regionName, $region, $years[$y]);

				for ($b=0; $b < sizeof($brands); $b++) {
				
					if ($l == 0) {
						if ($b == 1 && $years[$y] < 2020) {
							$table = "fw_digital";
						}else{
							$table = $info['table'];
						}
					}else{
						$table = 'plan_by_brand';
					}
					
					$infoQuery[$b] = $this->getAllValuesUnion($table, $info['leftName'], "brand", $brands[$b], $region, $value, $months, $currency);	
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

				if (is_array($res[$y])) {
					$size = sizeof($res[$y]);
					$sum = 0;
					$check = false;

					for ($r=0; $r < $size; $r++) { 

						if ($res[$y][$r]['brand'] == 'ONL-SM') {
							$check = true;
							$sum += $res[$y][$r]['total'];
							unset($res[$y][$r]);
						}elseif ($res[$y][$r]['brand'] == 'ONL') {
							$check = true;
							$sum += $res[$y][$r]['total'];
							unset($res[$y][$r]);
						}elseif ($res[$y][$r]['brand'] == 'ONL-DSS') {
							$check = true;
							$sum += $res[$y][$r]['total'];
							unset($res[$y][$r]);
						}elseif ($res[$y][$r]['brand'] == 'ONL-G9') {
							$check = true;
							$sum += $res[$y][$r]['total'];
							unset($res[$y][$r]);
						}elseif ($res[$y][$r]['brand'] == 'VOD') {
							$check = true;
							$sum += $res[$y][$r]['total'];
							unset($res[$y][$r]);
						}
					}

					if ($check) {
						$res[$y] = array_values($res[$y]);
						$aux = array("brandID" => "9" ,"brand" => "ONL", "total" => $sum);
						array_push($res[$y], $aux);
					}	
				}
				
				for ($b=0; $b < sizeof($infoQuery); $b++) {
					if ($infoQuery[$b]['table'] == "cmaps a") {
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

					if (is_array($res[$y])) {
						for ($i=0; $i < sizeof($res[$y]); $i++) {
							if ($infoQuery[$b]['table'] == "cmaps a") {
								
                                if ($res[$y][$i]['brand'] != 'ONL' && $res[$y][$i]['brand'] != 'VIX') {
                                	
                                    $res[$y][$i]['total'] /= $pRate;
                                }else{
                                	
                                    $res[$y][$i]['total'] /= 1.0;
                                }
                            }elseif ($infoQuery[$b]['table'] == "ytd a") {
                            	
                                if ($res[$y][$i]['brand'] != 'ONL' && $res[$y][$i]['brand'] != 'VIX') {
                                    $res[$y][$i]['total'] *= $pRate;
                                }else{
                                	
                                    $res[$y][$i]['total'] *= 1.0;
                                }
                            }elseif ($infoQuery[$b]['table'] == "fw_digital a") {
                            	
                            	if ($res[$y][$i]['brand'] != 'ONL' && $res[$y][$i]['brand'] != 'VIX') {
                                    $res[$y][$i]['total'] *= 1.0;
                                    
                                }else{
                                    $res[$y][$i]['total'] *= $pRate;
                                }
                            }else{
                            	//como a infoquery é dividida em 2 partes, por causa que são canais normais e digital
                            	//mas o plan só precisa ser executado na primeira iteração
                            	if ($b == 1) {
	                            	$res[$y][$i]['total'] *= $pRate;
                            	}
                            }
						}
					}
				}
			}

			$line[$l] = $res;

			if ($l != 0) {
				array_pop($line[$l]);
			}
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

	public function sortDigitalBrands($brands){
        
        $ids = array();

        for ($i=0; $i < sizeof($brands); $i++) { 
            $ids[$i] = $brands[$i]['id'];
        }

        $ids = array_unique($ids);

        sort($ids);

        $rtr = array();

        $c = 0;

        while ($c < sizeof($brands)) {
            for ($i=0; $i < sizeof($brands); $i++) { 
                if ($brands[$i]['id'] == $ids[$c]) {
                    array_push($rtr, $brands[$i]['brand']);
                    $c++;
                    break;
                }
            }   
        }

        return $rtr;
    }

	public function getValueColumn($values, $brand, $column, $year){
		
		if (is_array($values[$column][$year])) {
			for ($b=0; $b < sizeof($values[$column][$year]); $b++) { 
				if ($brand == $values[$column][$year][$b]['brand']) {
					return $values[$column][$year][$b]['total'];
				}
			}
		}

		return "-";
	}

	public function checkBrandColumn($brand, $mtx, $m, $years, $values){
		
		if ($mtx[$m][0] == "Booking ".$years[0] || $mtx[$m][0] == "Share Booking ".$years[0]) {
			$res = $this->getValueColumn($values, $brand, 0, 0);
		}elseif ($mtx[$m][0] == "Target ".$years[0] || $mtx[$m][0] == "Share Target") {
			$res = $this->getValueColumn($values, $brand, 1, 0);
		}elseif ($mtx[$m][0] == "Booking ".$years[1] || $mtx[$m][0] == "Share Booking ".$years[1]) {
			$res = $this->getValueColumn($values, $brand, 0, 1);
		}elseif ($mtx[$m][0] == "%(Booking / Target)") {
			$val1 = $this->getValueColumn($values, $brand, 0, 0);
			$val2 = $this->getValueColumn($values, $brand, 1, 0);

			if ($val1 != "-" && $val2 != "-") {
				$res = ($val1 / $val2)*100;
			}else{
				$res = "-";
			}
		}elseif ($mtx[$m][0] == "(Booking - Target)") {
			$val1 = $this->getValueColumn($values, $brand, 0, 0);
			$val2 = $this->getValueColumn($values, $brand, 1, 0);

			if ($val1 != "-" && $val2 != "-") {
				$res = $val1 - $val2;
			}else{
				$res = "-";
			}
		}else{
			$res = $brand;
		}

		return $res;
	}

	public function assembler($values, $years, $brands){
		
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

		$closedP = 0;
		$targetP = 0;
		$pClosedP = 0;
		for ($b=0; $b < sizeof($brands); $b++) {
			for ($m=0; $m < 4; $m++) {
				$res = $this->checkBrandColumn($brands[$b][1], $mtx, $m, $years, $values);

				if ($mtx[$m][0] == "Booking ".$years[0]) {
					if ($res != "-") {
						$closed += $res;	
					}
				}elseif ($mtx[$m][0] == "Booking ".$years[1]) {
					if ($res != "-") {
						$pClosed += $res;	
					}
				}elseif ($mtx[$m][0] == "Target ".$years[0]) {
					if ($res != "-") {
						$target += $res;	
					}
				}

				array_push($mtx[$m], $res);
			}
			
		}


		for ($b=0; $b < sizeof($brands); $b++) {
			for ($m=4; $m < 7; $m++) { 
				$val = $this->checkBrandColumn($brands[$b][1], $mtx, $m, $years, $values);

				if ($mtx[$m][0] == "Share Booking ".$years[0]) {
					if ($val != "-") {
						$res = ($val / $closed)*100;
						$closedP += $res;
					}else{
						$res = $val;
					}
				}elseif ($mtx[$m][0] == "Share Target") {
					if ($val != "-") {
						$res = ($val / $target)*100;
						$targetP += $res;
					}else{
						$res = $val;
					}
				}elseif ($mtx[$m][0] == "Share Booking ".$years[1]) {
					if ($val != "-") {
						$res = ($val / $pClosed)*100;
						$pClosedP += $res;
					}else{
						$res = $val;
					}
				}

				array_push($mtx[$m], $res);
			}
		}


		for ($b=0; $b < sizeof($brands); $b++) { 
			for ($m=7; $m < sizeof($mtx); $m++) {
				$res = $this->checkBrandColumn($brands[$b][1], $mtx, $m, $years, $values);

				array_push($mtx[$m], $res);
			}
		}



		if (sizeof($brands) > 1) {
			array_push($mtx[0], "DN");
			array_push($mtx[1], $closed);
			array_push($mtx[2], $target);
			array_push($mtx[3], $pClosed);
			array_push($mtx[4], $closedP);
			array_push($mtx[5], $targetP);
			array_push($mtx[6], $pClosedP);

			if ($closed == 0 || $target == 0) {
				$val = 0;
			}else{
				$val = ($closed / $target)*100;
			}
			array_push($mtx[7], $val);

			$val = ($closed - $target);
			array_push($mtx[8], $val);
		}

		return $mtx;
	}

}