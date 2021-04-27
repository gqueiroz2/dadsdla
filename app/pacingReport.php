<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\pAndR;
use App\brand;
use App\salesRep;
use App\base;
use App\sql;
use App\region;

class pacingReport extends Model
{

	public function base($con,$region,$year,$currency,$value,$brands,$pr){

		$sql = new sql();
        $base = new base();
        $currency = $pr->getCurrency($con,array($currency),array($year))[0];

        $currentMonth = intval(date('m'));
        $date = date('Y-m-d');
        $week = $this->weekOfMonth($date);

        if ($value == "gross") {
        	$valueView = "Gross";
        }else{
        	$valueView = "Net";
        }


        $fcstInfo = $this->getForecast($con,$sql,$region,$currentMonth,$week);

        if($fcstInfo){
        	$listOfClients = $this->listFCSTClients($con,$sql,$base,$fcstInfo,$region);
            $save = $fcstInfo;
            $temp = $base->adaptCurrency($con,$pr,$save,$currency['id'],$year,true);
            $currencyCheck = $temp["currencyCheck"][0];
            $newCurrency = $temp["newCurrency"][0];
            $oldCurrency = $temp["oldCurrency"][0];
            $temp2 = $base->adaptValue($value,$save,$region,$listOfClients,true);
            $valueCheck = $temp2["valueCheck"][0];
            $multValue = $temp2["multValue"][0];


            //booking ano atual para o fcst
	        $brandValue = $this->getBookingPerBrand($con,$sql,$pr,$brands,$year,$value,$currency,$region);

	        //booking do ano passando para calculo de porcentagem
	        $brandsValueLastYear = $this->lastYearBrand($con,$sql,$pr,$brands,($year-1),$value,$currency,$region);

	        //fcst dividido por canal e mes
	        $fcstValue = $this->getFcst($con,$sql,$pr,$brands,$valueCheck,$multValue,$currencyCheck,$newCurrency,$oldCurrency,$fcstInfo,$listOfClients,$brandsValueLastYear);

	        //juntando booking e fcst por mes e canal
	        for ($b=0; $b <sizeof($brandValue); $b++) { 
	        	for ($m=0; $m <sizeof($brandValue[$b]); $m++) { 
	        		$fcstValue[$b][$m] += $brandValue[$b][$m];
	        	}
	        }

	        //colocando quarter e total no fcst
	        $fcstValue = $this->makeQuarterAndTotal($fcstValue);

        }else{


        	$fcstValue = false;
        	$totalFcstValue = false;

        	$prc1 = false;
        	$prc2 = false;

        	$totalPrc1 = false;
        	$totalPrc2 = false;
        }


 		//pegando SAP ano atual e anterior
        $actualCYear = $this->getPlan($con,$pr,$sql,$brands,$value,$currency,$region,$year,"ACTUAL");
        $actualPYear = $this->getPlan($con,$pr,$sql,$brands,$value,$currency,$region,($year-1),"ACTUAL");

        //colocando quarter e total nos SAP's
        $actualCYear = $this->makeQuarterAndTotal($actualCYear);
        $actualPYear = $this->makeQuarterAndTotal($actualPYear);

        //pegando Target e Corporate FCST
        $corporate = $this->getPlan($con,$pr,$sql,$brands,$value,$currency,$region,$year,"CORPORATE");
        $target = $this->getPlan($con,$pr,$sql,$brands,$value,$currency,$region,$year,"TARGET");

        // adicionando quarter e total ao target e o fcst Corporate
        $corporate = $this->makeQuarterAndTotal($corporate);
        $target = $this->makeQuarterAndTotal($target);

        //pegando booking do ano atual e ano passado
        $bookingCYear = $this->getBooking($con,$sql,$pr,$brands,$year,$value,$currency,$region);
        $bookingPYear = $this->getBooking($con,$sql,$pr,$brands,($year-1),$value,$currency,$region);

        //adicionando quarter e total ...
        $bookingCYear = $this->makeQuarterAndTotal($bookingCYear);
        $bookingPYear = $this->makeQuarterAndTotal($bookingPYear);

        //fazendo porcentagem conta é (x-y)/y
        if ($fcstInfo) {
        	$prc1 = $this->makePrc($fcstValue,$bookingPYear);
       		$prc2 = $this->makePrc($fcstValue,$target);
			
			//total FcstValue
	        $totalFcstValue = $this->makeTotal($fcstValue);
        }

        //somando todos os brands para sumarizar no total
        $totalActualCYear = $this->makeTotal($actualCYear);
        $totalActualPYear = $this->makeTotal($actualPYear);
        $totalCorporate = $this->makeTotal($corporate);
        $totalTarget = $this->makeTotal($target);
        $totalBookingCYear = $this->makeTotal($bookingCYear);
        $totalBookingPYear = $this->makeTotal($bookingPYear);

        if ($fcstInfo) {
        	$totalPrc1 = $this->makePrcTotal($totalFcstValue,$totalBookingPYear);
        	$totalPrc2 = $this->makePrcTotal($totalFcstValue,$totalTarget);
        }


        $forRender = array("fcst" => $fcstValue,
    						"SAPCYear" => $actualCYear,
    						"SAPPYear" => $actualPYear,
    						"corporate" => $corporate,
    						"bookingCYear" => $bookingCYear,
    						"bookingPYear" => $bookingPYear,
    						"target" => $target,
    						"prc1" => $prc1,
    						"prc2" => $prc2,

    						"totalFcstValue" => $totalFcstValue,
    						"totalActualCYear" => $totalActualCYear,
    						"totalActualPYear" => $totalActualPYear,
    						"totalCorporate" => $totalCorporate,
    						"totalTarget" => $totalTarget,
    						"totalBookingCYear" => $totalBookingCYear,
    						"totalBookingPYear" => $totalBookingPYear,
    						"totalPrc1" => $totalPrc1,
    						"totalPrc2" => $totalPrc2,

    						"cYear" => $year,
    						"pYear" => ($year-1),
    						"currency" => $currency['name'],
    						"value" => $valueView

    						);
	
        return $forRender;
	}

	public function makePrcTotal($array1,$array2){
		$out = array();
		for ($m=0; $m <sizeof($array1); $m++) { 
			if ($array2[$m] != 0) {
				$out[$m] = (($array1[$m]-$array2[$m])/$array2[$m])*100;
			}else{
				$out[$m] = 0;
			}
		}

		return $out;
	}


	public function makeTotal($array){
		$out = array();

		for ($m=0; $m <sizeof($array[0]); $m++) { 
			$out[$m]=0;
		}

		for ($b=0; $b <sizeof($array); $b++) { 
			for ($m=0; $m <sizeof($array[$b]); $m++) { 
				$out[$m] += $array[$b][$m];
			}
		}
		return $out;
	}

	public function makePrc($array1,$array2){
		$out = array();
		for ($b=0; $b <sizeof($array1); $b++) { 
			for ($m=0; $m <sizeof($array1[$b]); $m++) { 
				if ($array2[$b][$m] != 0) {
					$out[$b][$m] = (($array1[$b][$m]-$array2[$b][$m])/$array2[$b][$m])*100;
				}else{
					$out[$b][$m] = 0;
				}
			}
		}

		return $out;
	}

	public function makeQuarterAndTotal($array){
		$out = array();

		for ($b=0; $b <sizeof($array); $b++) { 
			for ($m=0; $m <17; $m++) { 
				$out[$b][$m] = 0;
			}
		}

		for ($b=0; $b <sizeof($array); $b++) { 
			$out[$b][0] = $array[$b][0];
			$out[$b][1] = $array[$b][1];
			$out[$b][2] = $array[$b][2];
			$out[$b][3] = $array[$b][0] + $array[$b][1] + $array[$b][2];
			
			$out[$b][4] = $array[$b][3];
			$out[$b][5] = $array[$b][4];
			$out[$b][6] = $array[$b][5];
			$out[$b][7] = $array[$b][3] + $array[$b][4] + $array[$b][5];

			$out[$b][8] = $array[$b][6];
			$out[$b][9] = $array[$b][7];
			$out[$b][10] = $array[$b][8];
			$out[$b][11] = $array[$b][6] + $array[$b][7] + $array[$b][8];
			
			$out[$b][12] = $array[$b][9];
			$out[$b][13] = $array[$b][10];
			$out[$b][14] = $array[$b][11];
			$out[$b][15] = $array[$b][9] + $array[$b][10] + $array[$b][11];
			
			$out[$b][16] = $out[$b][3] + $out[$b][7] + $out[$b][11] + $out[$b][15];
		}


		return $out;

	}


	public function getPlan($con,$pr,$sql,$brands,$value,$currency,$region,$year,$type){
		$r = new region();

		$base = new base();
		
		$from = array("revenue");

		if ($value == "gross") {
			$tmp = $base->getAgencyComm($con,array($region))/100;
			$mult = 1/(1-$tmp);
		}else{
			$mult = 1;
		}

		if($currency['name'] == "USD"){
            $div = 1.0;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($currency['id']),array(date('Y')));
        }

		for ($b=0; $b <sizeof($brands); $b++) { 
			for ($m=0; $m <12; $m++) { 
				$select[$b][$m] = "SELECT SUM(revenue) AS revenue FROM plan_by_brand WHERE (source = \"".$type."\") AND (year = \"".$year."\") AND (sales_office_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (type_of_revenue = \"NET\") AND (currency_id = \"4\")";

				$res[$b][$m] = $con->query($select[$b][$m]);
				$resp[$b][$m] = floatval($sql->fetch($res[$b][$m],$from,$from)[0]['revenue'])*$div*$mult;
			}
		}


		return $resp;
	}


	public function getFcst($con,$sql,$pr,$brands,$valueCheck,$multValue,$currencyCheck,$newCurrency,$oldCurrency,$fcstInfo,$listOfClients,$lastYearBrand){

		$checkNochannel=0;

		$from = array("value","brand");

		$saida = array();

		for ($m=0; $m <12 ; $m++) { 
			for ($b=0; $b <sizeof($brands); $b++) { 
				$saida[$b][$m] = 0;
			}
		}

		for ($c=0; $c <sizeof($listOfClients); $c++) { 
			for ($m=0; $m <12; $m++) { 
				$select[$c][$m] = "SELECT value,brand FROM forecast_client WHERE (forecast_id = \"".$fcstInfo[0]['ID']."\") AND (client_id = \"".$listOfClients[$c]['clientID']."\") AND (month = \"".($m+1)."\")";

				$res[$c][$m] = $con->query($select[$c][$m]);

				$resp[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0];


				$totalPrc[$c][$m] = 0;
				$total[$c][$m] = 0;
				$prcTemp[$c][$m] = array();
				$totalMonth[$m] = 0;

				if ($resp[$c][$m] != null) {
					
					if ($currencyCheck) {
						$resp[$c][$m]['value'] = ($resp[$c][$m]['value']/$oldCurrency)*$newCurrency;
					}

					if ($valueCheck) {
						$resp[$c][$m]['value'] = $resp[$c][$m]['value']*$multValue[$c];
					}
					
					$resp[$c][$m]['brands'] = explode(";", $resp[$c][$m]['brand']);

					if ($resp[$c][$m]['brand'] == 'NOCHANNELS' || $resp[$c][$m]['brand'] == '') {
						$checkNochannel += $resp[$c][$m]['value'];
					}

					for ($b=0; $b <sizeof($resp[$c][$m]['brands']); $b++) { 
						if ($resp[$c][$m]['brands'][$b] == 'ONL-G9' || $resp[$c][$m]['brands'][$b] == 'ONL-DSS') {
							$resp[$c][$m]['brands'][$b] = 'ONL';
						}elseif($resp[$c][$m]['brands'][$b] == 'NOCHANNELS' || $resp[$c][$m]['brands'][$b] == ''){
							unset($resp[$c][$m]['brands'][$b]);
						}
					}

					$resp[$c][$m]['brands'] = array_unique($resp[$c][$m]['brands']);
					$resp[$c][$m]['brands'] = array_values($resp[$c][$m]['brands']);

					if (sizeof($resp[$c][$m]['brands']) == 1) {
						for ($b=0; $b <sizeof($brands); $b++) { 
							if ($resp[$c][$m]['brands'][0] == $brands[$b]['name']) {
								$saida[$b][$m] += $resp[$c][$m]['value'];
							}
						}
					}elseif(sizeof($resp[$c][$m]['brands']) > 1){
						for ($b1=0; $b1 <sizeof($resp[$c][$m]['brands']) ; $b1++) { 
							for ($b2=0; $b2 <sizeof($brands); $b2++) { 
								if ($resp[$c][$m]['brands'][$b1] == $brands[$b2]['name']) {
									$total[$c][$m]+= $lastYearBrand[$b2][$m];
								}
							}
						}
						for ($b1=0; $b1 <sizeof($resp[$c][$m]['brands']) ; $b1++) { 
							for ($b2=0; $b2 <sizeof($brands); $b2++) { 
								if ($resp[$c][$m]['brands'][$b1] == $brands[$b2]['name']) {
									if ($total[$c][$m] == 0) {
										$prc = 0;
									}else{
										$prc = $lastYearBrand[$b2][$m]/$total[$c][$m];
									}
									$totalPrc[$c][$m] += $prc;
									$prcTemp[$c][$m][$b1] = $prc;
									//var_dump("Canal:".$resp[$c][$m]['brands'][$b1]);
									//var_dump("Mes:".($m+1));
									//var_dump("Total:".$total[$c][$m]);
									//var_dump("Porcentagem:".$prc);
									//var_dump("Resp:".$resp[$c][$m]["value"]);
									$saida[$b2][$m] += $resp[$c][$m]['value']*$prc;
									//var_dump("Saida:".$saida[$b2][$m]);
									//var_dump("----------------------------------");
								}
							}
						}
					}else{
						if ($resp[$c][$m]['value'] != 0) {
							for ($b=0; $b <sizeof($lastYearBrand); $b++) { 
								$totalMonth[$m] += $lastYearBrand[$b][$m];
							}
							for ($b=0; $b <sizeof($lastYearBrand); $b++) { 
								$porcentagem[$b][$m] = $lastYearBrand[$b][$m]/$totalMonth[$m];
								$saida[$b][$m] += $resp[$c][$m]['value']*$porcentagem[$b][$m];
							}

						}
					}
				}
			}
		}

		$temp = 0;

		/*for ($c=0; $c <sizeof($resp); $c++) { 
			for ($m=0; $m <sizeof($resp[$c]); $m++) { 
				var_dump($resp[$c][$m]);
				var_dump($prcTemp[$c][$m]);
				var_dump("------------------------------------------------");
			}
		}*/

		for ($b=0; $b <sizeof($saida); $b++) { 
			for ($m=0; $m <sizeof($saida[$b]); $m++) { 
				$temp += $saida[$b][$m];
			}
		}
		$temp += $checkNochannel;

		var_dump($saida);
		return $saida;
	}

	public function lastYearBrand($con,$sql,$pr,$brands,$year,$value,$currency,$region){
		if ($value == "gross") {
			$col = "gross_revenue_prate";
			$colFW = "gross_revenue";
		}else{
			$col = "net_revenue_prate"; 
			$colFW = "net_revenue";
		}

		$date = date('n')-1;

		if($currency['name'] == "USD"){
            $div = 1.0;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($currency['id']),array($year));
        }

        for ($b=0; $b <sizeof($brands); $b++) { 
			for ($m=0; $m <12; $m++){
				if ($m>=$date) {
					if ($brands[$b]['name'] == 'ONL') {
						//pegar ONL do FW
						$select[$b][$m] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id != \"10\") AND (year = \"".$year."\")";
					}elseif($brands[$b]['name'] == 'VIX'){
						//pegar Vix do FW (diferente do ONL pq onl é tudo menos Vix)
						$select[$b][$m] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (year = \"".$year."\")";
					}else{
						$select[$b][$m] = "SELECT SUM($col) AS value FROM ytd WHERE (sales_representant_office_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (year = \"".$year."\")";
					}

					$res[$b][$m] = $con->query($select[$b][$m]);
					$resp[$b][$m] = $sql->fetchSum($res[$b][$m], "value")['value']*$div;
				}else{
					$resp[$b][$m] = 0;
				}
			}
		}

		return $resp;
	}

	public function getBooking($con,$sql,$pr,$brands,$year,$value,$currency,$region){
		
		if ($value == "gross") {
			$col = "gross_revenue_prate";
			$colFW = "gross_revenue";
		}else{
			$col = "net_revenue_prate"; 
			$colFW = "net_revenue";
		}

		if($currency['name'] == "USD"){
            $div = 1.0;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($currency['id']),array(date('Y')));
        }

		for ($b=0; $b <sizeof($brands); $b++) { 
			for ($m=0; $m <12; $m++){
				if ($brands[$b]['name'] == 'ONL') {
					//pegar ONL do FW
					$select[$b][$m] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id != \"10\") AND (year = \"".$year."\")";
				}elseif($brands[$b]['name'] == 'VIX'){
					//pegar Vix do FW (diferente do ONL pq onl é tudo menos Vix)
					$select[$b][$m] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (year = \"".$year."\")";
				}else{
					$select[$b][$m] = "SELECT SUM($col) AS value FROM ytd WHERE (sales_representant_office_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (year = \"".$year."\")";
				}

				$res[$b][$m] = $con->query($select[$b][$m]);
				$resp[$b][$m] = $sql->fetchSum($res[$b][$m], "value")['value']*$div;
			}
		}

		return $resp;		
	}


	public function getBookingPerBrand($con,$sql,$pr,$brands,$year,$value,$currency,$region){

		if ($value == "gross") {
			$col = "gross_revenue_prate";
			$colFW = "gross_revenue";
		}else{
			$col = "net_revenue_prate"; 
			$colFW = "net_revenue";
		}

		$date = date('n')-1;

		if($currency['name'] == "USD"){
            $div = 1.0;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($currency['id']),array($year));
        }

		for ($b=0; $b <sizeof($brands); $b++) { 
			for ($m=0; $m <12; $m++){
				if ($brands[$b]['name'] == 'ONL') {
					//pegar ONL do FW
					$select[$b][$m] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id != \"10\") AND (year = \"".$year."\")";
				}elseif($brands[$b]['name'] == 'VIX'){
					//pegar Vix do FW (diferente do ONL pq onl é tudo menos Vix)
					$select[$b][$m] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (year = \"".$year."\")";
				}else{
					$select[$b][$m] = "SELECT SUM($col) AS value FROM ytd WHERE (sales_representant_office_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (year = \"".$year."\")";
				}

				$res[$b][$m] = $con->query($select[$b][$m]);
				$resp[$b][$m] = $sql->fetchSum($res[$b][$m], "value")['value']*$div;
			}
		}

		return $resp;
	}

	public function getForecast($con,$sql,$regionID,$month,$week){
        $select = " SELECT f.ID AS 'ID',
                           f.oppid AS 'oppid',
                           f.region_id AS 'region_id',
                           f.sales_rep_id AS 'sales_rep_id',
                           f.currency_id AS 'currency_id',
                           f.type_of_value AS 'type_of_value',
                           f.read_q AS 'read_q',
                           f.year AS 'year',
                           f.date_m AS 'date_m',
                           f.last_modify_by AS 'last_modify_by',
                           f.last_modify_date AS 'last_modify_date',
                           f.last_modify_time AS 'last_modify_time',
                           f.month AS 'month',
                           f.submitted AS 'submitted',
                           f.type_of_forecast AS 'type_of_forecast',
                           sr.name AS 'name'
                           FROM forecast f
                           LEFT JOIN sales_rep sr ON f.sales_rep_id = sr.ID
                           WHERE(region_id = \"".$regionID."\") 
                           AND (submitted = '1')
                           AND (type_of_forecast = 'V2')
                           AND (month = \"".$month."\")
                           ";
        if ($regionID == "1") {
        	$select .= "AND read_q =\"".$week."\"";
        }
        $select .= " ORDER BY ID DESC";
        //echo "<pre>".($select)."</pre>";
        $res = $con->query($select);
        //var_dump($res);
        $from = array('ID','oppid','region_id','sales_rep_id','currency_id',
                      'type_of_value','read_q','year',
                      'date_m','last_modify_by','last_modify_date','last_modify_time','month','submitted','type_of_forecast','name');
        $to = array('ID','oppid','regionID','salesRepID','currencyID',
                    'typeOfValue','readQ','year',
                    'dateM','lastModifyBy','lastModifyDate','lastModifyTime','month','submitted','type_of_value','name');
        $fcstInfo = $sql->fetch($res,$from,$to);
        
        return $fcstInfo;
    }

    public function weekOfMonth($date) {
        $date = strtotime($date);
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        //Apply above formula.
        if ((intval(date("W", $date)) - intval(date("W", $firstOfMonth))) == 0) {
            return intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
        }else{
            return intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
        }
    }

    public function listFCSTClients($con,$sql,$base,$fcstInfo,$regionID){
        $from = array("clientID","client");
        for ($f=0; $f < sizeof($fcstInfo); $f++) { 
            $select[$f] = "SELECT DISTINCT c.ID AS 'clientID', 
                                  c.name AS 'client'
                                FROM forecast_client fc
                                LEFT JOIN client c ON c.ID = fc.client_id
                                WHERE(forecast_id = \"".$fcstInfo[$f]['ID']."\")                                
                                ORDER BY client
                          ";
            $res[$f] = $con->query($select[$f]);
            $listC[$f] = $sql->fetch($res[$f],$from,$from);
        }
        $selectYTD = "SELECT DISTINCT c.name AS 'client',
                          c.ID AS 'clientID'
                    FROM ytd y
                    LEFT JOIN client c ON c.ID = y.client_id
                    WHERE (sales_representant_office_id = \"".$regionID."\")
                    AND (sales_representant_office_id = \"".$regionID."\")
                  ";
        
        $resYTD = $con->query($selectYTD);
        $listCYTD = $sql->fetch($resYTD,$from,$from);
        $selectFW = "SELECT DISTINCT c.name AS 'client',
                          c.ID AS 'clientID'
                    FROM fw_digital y
                    LEFT JOIN client c ON c.ID = y.client_id
                    WHERE (region_id = \"".$regionID."\")
                  ";
        
        $resFW = $con->query($selectFW);
        $listCFW = $sql->fetch($resFW,$from,$from);
        $cc = 0;
        if($listC){
            for ($c=0; $c < sizeof($listC); $c++) { 
                if($listC[$c]){
                    for ($d=0; $d < sizeof($listC[$c]); $d++) { 
                        $list[$cc] = $listC[$c][$d];
                        $cc++; 
                    }
                }
            }
        }
        for ($d=0; $d < sizeof($listCYTD); $d++) { 
            $list[$cc] = $listCYTD[$d];
            $cc++;
        }
        
	$list = $base->superUnique($list,'clientID');
        usort($list, array($this,'orderClient'));
        return $list;
    }
    private static function orderClient($a, $b){
        if ($a == $b)
            return 0;
        
        return ($a['client'] < $b['client']) ? -1 : 1;
    }
}
