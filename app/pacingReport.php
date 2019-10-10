<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\pAndR;
use App\brand;
use App\salesRep;
use App\base;
use App\sql;

class pacingReport extends Model
{

	public function base($con,$region,$year,$currency,$value,$brands,$pr){

		$sql = new sql();
        $base = new base();
        $currency = $pr->getCurrency($con,array($currency),array($year))[0];

        $currentMonth = intval(date('m'));
        $date = date('Y-m-d');
        $week = $this->weekOfMonth($date);

        $fcstInfo = $this->getForecast($con,$sql,$region,$currentMonth,$week);

        if (!$fcstInfo) {
        	return false;
        }else{
        	$listOfClients = $this->listFCSTClients($con,$sql,$base,$fcstInfo,$region);
            $save = $fcstInfo;
            $temp = $base->adaptCurrency($con,$pr,$save,$currency['id'],$year,true);
            $currencyCheck = $temp["currencyCheck"][0];
            $newCurrency = $temp["newCurrency"][0];
            $oldCurrency = $temp["oldCurrency"][0];
            $temp2 = $base->adaptValue($value,$save,$region,$listOfClients,true);
            $valueCheck = $temp2["valueCheck"][0];
            $multValue = $temp2["multValue"][0];
        }

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

        var_dump($fcstValue);
	}

	public function getFcst($con,$sql,$pr,$brands,$valueCheck,$multValue,$currencyCheck,$newCurrency,$oldCurrency,$fcstInfo,$listOfClients,$lastYearBrand){

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

				if ($resp[$c][$m] != null) {
					
					if ($resp[$c][$m]['brand'] == "") {
						break;
					}

					if ($currencyCheck) {
						$resp[$c][$m]['value'] = ($resp[$c][$m]['value']/$oldCurrency)*$newCurrency;
					}

					if ($valueCheck) {
						$resp[$c][$m]['value'] = $resp[$c][$m]['value']*$multValue[$c];
					}
					
					$resp[$c][$m]['brands'] = explode(";", $resp[$c][$m]['brand']);

					for ($b=0; $b <sizeof($resp[$c][$m]['brands']); $b++) { 
						if ($resp[$c][$m]['brands'][$b] == 'ONL-G9') {
							$resp[$c][$m]['brands'][$b] = 'ONL';
						}
					}

					if (sizeof($resp[$c][$m]['brands']) == 1) {
						for ($b=0; $b <sizeof($brands) ; $b++) { 
							if ($resp[$c][$m]['brand'] == $brands[$b]['name']) {
								$saida[$b][$m] += $resp[$c][$m]['value'];
							}
						}
					}else{
						$total = 0;

						for ($b1=0; $b1 <sizeof($resp[$c][$m]['brands']) ; $b1++) { 
							for ($b2=0; $b2 <sizeof($brands); $b2++) { 
								if ($resp[$c][$m]['brands'][$b1] == $brands[$b2]['name']) {
									$total+= $lastYearBrand[$b2][$m];
								}
							}
						}

						for ($b1=0; $b1 <sizeof($resp[$c][$m]['brands']) ; $b1++) { 
							for ($b2=0; $b2 <sizeof($brands); $b2++) { 
								if ($resp[$c][$m]['brands'][$b1] == $brands[$b2]['name']) {
									if ($total == 0) {
										$prc = 0;
									}else{
										$prc = $lastYearBrand[$b2][$m]/$total;
									}
									$saida[$b2][$m] += $resp[$c][$m]['value']*$prc;
								}
							}
						}
					}

				}
			}
		}

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
				if ($m<$date) {
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
